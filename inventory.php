<?php 
require 'config.php';
requireRole([ROLE_ADMIN, ROLE_MANAGER, ROLE_STAFF]);


include 'includes/header.php';
include 'includes/navbar.php';
echo '<link rel="stylesheet" href="assets/css/inventory.css">';
$currentUserId = (int)$_SESSION['user_id'];

// Handle stock adjustment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'adjust') {
    verify_csrf();
    $product_id = (int)$_POST['product_id'];
    $qty = (int)$_POST['qty'];
    $type = $_POST['movement_type'];

    $change = ($type === 'in') ? $qty : -$qty;

    $stmt = $pdo->prepare("UPDATE tblproducts SET StockQuantity = StockQuantity + ? WHERE ProductID = ? AND CompanyID = ?");
    if ($stmt->execute([$change, $product_id, companyId()])) {
        $reason = isset($_POST['reason']) ? trim($_POST['reason']) : 'Manual Adjustment';
        logStockMovement($product_id, companyId(), $currentUserId, strtoupper($type), $qty, $reason);
        echo "<script>showSuccess('Stock updated successfully!');</script>";
    }
}

$productsStmt = $pdo->prepare("
    SELECT p.ProductID, p.ProductName, p.StockQuantity, p.Unit, p.CategoryID,
           c.CategoryName, c.CategoryIcon, c.CategoryColor
    FROM tblproducts p
    LEFT JOIN tblcategories c ON c.CategoryID = p.CategoryID
    WHERE p.CompanyID = ?
    ORDER BY p.ProductName
");
$productsStmt->execute([companyId()]);
$products = $productsStmt->fetchAll();

$catsStmt = $pdo->prepare("SELECT CategoryID, CategoryName, CategoryIcon, CategoryColor FROM tblcategories WHERE CompanyID=? ORDER BY CategoryName");
$catsStmt->execute([companyId()]);
$categories = $catsStmt->fetchAll();
?>

<main>
    <div class="header-title">Stock Adjustment</div>
    <div class="subtitle">Real-time inventory control</div>

    <!-- Search -->
    <div class="inventory-search-wrap search-wrap">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" id="searchInput" placeholder="Search products..." 
                   onkeyup="filterProducts()">
        </div>
    </div>

    <!-- Category filter -->
    <?php if (!empty($categories)): ?>
    <div class="cat-filter-bar" style="margin:10px 0 0;">
        <button class="cat-filter-btn active" onclick="filterByCategory(null, this)">All</button>
        <button class="cat-filter-btn uncategorized" onclick="filterByCategory('none', this)">Uncategorized</button>
        <?php foreach ($categories as $cat): ?>
            <button class="cat-filter-btn"
                style="--fc: <?= e($cat['CategoryColor']) ?>;"
                onclick="filterByCategory(<?= (int)$cat['CategoryID'] ?>, this)">
                <i class="fa-solid <?= e($cat['CategoryIcon']) ?>"></i>
                <?= e($cat['CategoryName']) ?>
            </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="inventory-layout">
        <!-- Form -->
        <div class="card">
            <form method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="adjust">

                <div class="inventory-field">
                    <label class="inventory-label">Select Product</label>
                    <select name="product_id" id="productSelect" class="inventory-select" required onchange="updatePreview()">
                        <?php foreach($products as $p): ?>
                            <option value="<?= $p['ProductID'] ?>" data-stock="<?= (int)$p['StockQuantity'] ?>">
                                <?= e($p['ProductName']) ?>
                                <?php if ($p['CategoryName']): ?>[<?= e($p['CategoryName']) ?>]<?php endif; ?>
                                (Stock: <?= $p['StockQuantity'] ?> <?= e($p['Unit'] ?? 'pcs') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="movement-toggle">
                    <button type="button" id="btn_in" class="movement-btn active" onclick="setMovementType('in')">Stock In</button>
                    <button type="button" id="btn_out" class="movement-btn" onclick="setMovementType('out')">Stock Out</button>
                </div>
                <input type="hidden" name="movement_type" id="movement_type" value="in">

                <div class="qty-wrap">
                    <label class="inventory-label">Quantity</label>
                    <div class="quantity-control">
                        <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">−</button>
                        <input type="number" id="qty" name="qty" value="10" min="1" required oninput="updatePreview()">
                        <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
                    </div>
                </div>

                <div class="inventory-field">
                    <label class="inventory-label">Reason (Optional)</label>
                    <input type="text" name="reason" class="inventory-select" placeholder="e.g. Audit, Damage, Restock" style="height:42px; background:#fff;">
                </div>

                <button type="submit" class="btn-primary">EXECUTE ADJUSTMENT</button>
            </form>
        </div>

        <!-- Preview -->
        <div class="preview-card">
            <h3 class="preview-title">Live Preview</h3>
            <p class="preview-subtitle">Projected stock</p>
            <div id="projected_stock" class="projected-stock">—</div>
        </div>
    </div>

    <!-- Products List with Search -->
    <div class="card products-card">
        <h3 class="products-title">Products</h3>
        <table id="productsTable">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $p): ?>
                    <tr class="product-row"
                        data-name="<?= strtolower(e($p['ProductName'])) ?>"
                        data-category="<?= $p['CategoryID'] ? (int)$p['CategoryID'] : 'none' ?>">
                        <td><?= e($p['ProductName']) ?></td>
                        <td>
                            <?php if ($p['CategoryName']): ?>
                                <span class="cat-pill" style="background: <?= e($p['CategoryColor']) ?>20; color: <?= e($p['CategoryColor']) ?>; border: 1px solid <?= e($p['CategoryColor']) ?>40;">
                                    <i class="fa-solid <?= e($p['CategoryIcon']) ?>"></i>
                                    <?= e($p['CategoryName']) ?>
                                </span>
                            <?php else: ?>
                                <span style="color:#cbd5e1;">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $p['StockQuantity'] ?> <?= e($p['Unit'] ?? 'pcs') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<style>
.cat-filter-bar { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
.cat-filter-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 13px; border-radius: 20px;
    border: 2px solid #e2e8f0; background: #fff; color: #555;
    font-size: 0.82rem; font-weight: 600; cursor: pointer; transition: all 0.15s;
}
.cat-filter-btn:hover, .cat-filter-btn.active { border-color: var(--fc, #6366f1); background: var(--fc, #6366f1); color: #fff; }
.cat-filter-btn:first-child.active { background: #1e293b; border-color: #1e293b; }
.cat-filter-btn.uncategorized.active { background: #94a3b8; border-color: #94a3b8; }
.cat-pill { display: inline-flex; align-items: center; gap: 5px; padding: 2px 9px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
</style>

<script>
let activeCategoryFilter = null;

function filterByCategory(catId, btn) {
    activeCategoryFilter = catId;
    document.querySelectorAll('.cat-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    applyFilters();
}

function filterProducts() { applyFilters(); }

function applyFilters() {
    const search = (document.getElementById('searchInput')?.value || '').toLowerCase();
    document.querySelectorAll('.product-row').forEach(row => {
        const nameMatch = !search || row.dataset.name.includes(search);
        let catMatch = true;
        if (activeCategoryFilter === 'none') catMatch = row.dataset.category === 'none';
        else if (activeCategoryFilter !== null) catMatch = row.dataset.category == activeCategoryFilter;
        row.style.display = (nameMatch && catMatch) ? '' : 'none';
    });
}

function updatePreview() {
    const sel = document.getElementById('productSelect');
    const opt = sel.options[sel.selectedIndex];
    const currentStock = parseInt(opt?.dataset.stock ?? 0);
    const qty = parseInt(document.getElementById('qty').value) || 0;
    const type = document.getElementById('movement_type').value;
    const projected = type === 'in' ? currentStock + qty : currentStock - qty;
    const el = document.getElementById('projected_stock');
    el.textContent = projected;
    el.style.color = projected < 0 ? '#dc2626' : projected < 10 ? '#f59e0b' : '#10b981';
}

document.getElementById('productSelect')?.addEventListener('change', updatePreview);
updatePreview();
</script>

<?php include 'includes/footer.php'; ?>
