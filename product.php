<?php
require 'config.php';
requireRole([ROLE_ADMIN, ROLE_MANAGER]);


include 'includes/header.php';
include 'includes/navbar.php';
echo '<link rel="stylesheet" href="assets/css/product.css">';
$currentUserId = (int)$_SESSION['user_id'];
$error = '';

// Handle add/edit/delete product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

    if ($action === 'add' && canDo('product_add')) {
            $name       = trim($_POST['product_name'] ?? '');
            $stock      = (int)($_POST['stock_quantity'] ?? 0);
            $unit       = trim($_POST['unit'] ?? 'pcs');
            $unit       = $unit === '' ? 'pcs' : $unit;
            $price      = (float)($_POST['price'] ?? 0);
            $categoryId = (int)($_POST['category_id'] ?? 0) ?: null;

            if ($name === '') {
                $error = 'Product name is required.';
            } elseif ($stock < 0) {
                $error = 'Stock quantity cannot be negative.';
            } else {
                if ($categoryId !== null) {
                    $catChk = $pdo->prepare("SELECT CategoryID FROM tblcategories WHERE CategoryID=? AND UserID=?");
                    $catChk->execute([$categoryId, $currentUserId]);
                    if (!$catChk->fetch()) $categoryId = null;
                }
                $imageURL = null;
                if (!empty($_FILES['product_image']['name'])) {
                    $targetDir = "assets/img/products/";
                    $fileName = time() . '_' . basename($_FILES["product_image"]["name"]);
                    $targetFilePath = $targetDir . $fileName;
                    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFilePath)) {
                        $imageURL = $targetFilePath;
                    }
                }

                $stmt = $pdo->prepare("INSERT INTO tblproducts (ProductName, StockQuantity, Unit, Price, ImageURL, UserID, CategoryID, CompanyID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$name, $stock, $unit, $price, $imageURL, $currentUserId, $categoryId, companyId()])) {
                    $newId = (int)$pdo->lastInsertId();
                    if ($stock > 0) logStockMovement($newId, companyId(), $currentUserId, 'SET', $stock, 'Initial Stock');
                    echo "<script>showSuccess('Product added successfully!');</script>";
                }
            }

        } elseif ($action === 'edit' && canDo('product_edit')) {
            $id         = (int)$_POST['product_id'];
            $name       = trim($_POST['product_name'] ?? '');
            $stock      = (int)($_POST['stock_quantity'] ?? 0);
            $unit       = trim($_POST['unit'] ?? 'pcs');
            $unit       = $unit === '' ? 'pcs' : $unit;
            $price      = (float)($_POST['price'] ?? 0);
            $categoryId = (int)($_POST['category_id'] ?? 0) ?: null;

            if ($id <= 0 || $name === '') {
                $error = 'Invalid product.';
            } elseif ($stock < 0) {
                $error = 'Stock quantity cannot be negative.';
            } else {
                if ($categoryId !== null) {
                    $catChk = $pdo->prepare("SELECT CategoryID FROM tblcategories WHERE CategoryID=? AND UserID=?");
                    $catChk->execute([$categoryId, $currentUserId]);
                    if (!$catChk->fetch()) $categoryId = null;
                }
                $prev = $pdo->prepare("SELECT StockQuantity, ImageURL FROM tblproducts WHERE ProductID=? AND CompanyID=?");
                $prev->execute([$id, companyId()]);
                $prevData = $prev->fetch();
                $oldStock = (int)($prevData['StockQuantity'] ?? 0);
                $imageURL = $prevData['ImageURL'] ?? null;

                if (!empty($_FILES['product_image']['name'])) {
                    $targetDir = "assets/img/products/";
                    $fileName = time() . '_' . basename($_FILES["product_image"]["name"]);
                    $targetFilePath = $targetDir . $fileName;
                    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFilePath)) {
                        $imageURL = $targetFilePath;
                    }
                }

                $stmt = $pdo->prepare("UPDATE tblproducts SET ProductName=?, StockQuantity=?, Unit=?, Price=?, ImageURL=?, CategoryID=? WHERE ProductID=? AND CompanyID=?");
                if ($stmt->execute([$name, $stock, $unit, $price, $imageURL, $categoryId, $id, companyId()])) {
                    if ($oldStock !== $stock) {
                        $diff = $stock - $oldStock;
                        $type = $diff > 0 ? 'IN' : 'OUT';
                        logStockMovement($id, companyId(), $currentUserId, $type, abs($diff), 'Product Edit');
                    }
                    echo "<script>showSuccess('Product updated successfully!');</script>";
                }
            }

        } elseif ($action === 'delete' && canDo('product_delete')) {
            $id = (int)$_POST['product_id'];
            $stmt = $pdo->prepare("DELETE FROM tblproducts WHERE ProductID = ? AND CompanyID = ?");
            if ($stmt->execute([$id, companyId()])) {
                echo "<script>showSuccess('Product deleted successfully!');</script>";
            }
        }
    }
}

$productsStmt = $pdo->prepare("
    SELECT p.*, c.CategoryName, c.CategoryIcon, c.CategoryColor
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

if ($error) {
    echo "<script>showError(" . json_encode($error) . ");</script>";
}
?>

<main class="product-page">
    <div class="dashboard-stats stats-wrap">
        <div class="stat-card">
            <h3>Total Products</h3>
            <p class="number"><?= count($products) ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Stock</h3>
            <p class="number">
                <?php
                $totalStock = array_sum(array_column($products, 'StockQuantity'));
                echo $totalStock;
                ?>
            </p>
        </div>
        <div class="stat-card">
            <h3>Out of Stock</h3>
            <p class="number">
                <?php
                $outOfStock = count(array_filter($products, fn($p) => $p['StockQuantity'] == 0));
                echo $outOfStock;
                ?>
            </p>
        </div>
        <div class="stat-card">
            <h3>Categories</h3>
            <p class="number"><?= count($categories) ?></p>
        </div>
    </div>

    <!-- Add/Edit Form -->
    <div class="card product-form" id="productForm">
        <h3 id="formTitle">Add Product</h3>
        <form method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="product_id" id="productId">

            <div class="product-field">
                <label class="product-label">Product Image (Optional)</label>
                <input type="file" name="product_image" id="productImage" class="product-input" accept="image/*">
            </div>

            <div class="product-field">
                <label class="product-label">Product Name</label>
                <input type="text" name="product_name" id="productName" class="product-input" required>
            </div>

            <div class="product-field">
                <label class="product-label">Item Category</label>
                <div class="category-select-wrap">
                    <select name="category_id" id="categoryId" class="product-input" onchange="updateCategoryBadge()">
                        <option value="0">— No Category —</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int)$cat['CategoryID'] ?>"
                                data-icon="<?= e($cat['CategoryIcon']) ?>"
                                data-color="<?= e($cat['CategoryColor']) ?>">
                                <?= e($cat['CategoryName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($categories)): ?>
                        <a href="categories.php" class="cat-hint">
                            <i class="fa-solid fa-plus"></i> Create categories first
                        </a>
                    <?php else: ?>
                        <a href="categories.php" class="cat-hint">
                            <i class="fa-solid fa-layer-group"></i> Manage
                        </a>
                    <?php endif; ?>
                </div>
                <div id="selectedCatBadge" class="selected-cat-badge" style="display:none;"></div>
            </div>

            <div class="product-field">
                <label class="product-label">Stock Quantity</label>
                <input type="number" name="stock_quantity" id="stockQuantity" min="0" class="product-input" required>
            </div>

            <div class="product-field">
                <label class="product-label">Price</label>
                <input type="number" step="0.01" name="price" id="price" min="0" class="product-input" required>
            </div>

            <div class="product-field">
                <label class="product-label">Measuring Unit</label>
                <select name="unit" id="unit" class="product-input" required>
                    <option value="pcs">pcs</option>
                    <option value="kg">kg</option>
                    <option value="g">g</option>
                    <option value="ltr">ltr</option>
                    <option value="ml">ml</option>
                    <option value="box">box</option>
                    <option value="pack">pack</option>
                </select>
            </div>

            <div class="product-actions">
                <button type="submit" class="btn-primary btn-icon">
                    <i class="fa-solid fa-save"></i> Save Product
                </button>
                <button type="button" class="btn-secondary btn-icon" onclick="hideForm()">
                    <i class="fa-solid fa-times"></i> Cancel
                </button>
            </div>
        </form>
    </div>

    <div class="header-title product-title">
        <i class="fa-solid fa-cubes product-title-icon"></i> Product Management
    </div>
    <div class="product-toolbar">
        <div class="left">
            <div class="subtitle">Add, edit, and manage your products</div>
            <div class="search-wrap" style="margin:0;">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" id="searchInput" placeholder="Search products..."
                           onkeyup="filterProducts()">
                </div>
            </div>
        </div>
        <div class="right" style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="categories.php" class="btn-secondary btn-icon">
                <i class="fa-solid fa-layer-group"></i> Categories
            </a>
            <?php if (canDo('product_add')): ?>
            <button type="button" class="btn-primary btn-icon" onclick="showAddForm()">
                <i class="fa-solid fa-plus"></i> Add Product
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter by Category -->
    <?php if (!empty($categories)): ?>
    <div class="cat-filter-bar">
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

    <!-- Products Table -->
    <div class="card">
        <h3>Products</h3>
        <table id="productsTable">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $p): ?>
                    <tr class="product-row"
                        data-name="<?= strtolower(e($p['ProductName'])) ?>"
                        data-category="<?= $p['CategoryID'] ? (int)$p['CategoryID'] : 'none' ?>">
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <?php if ($p['ImageURL']): ?>
                                    <img src="<?= e($p['ImageURL']) ?>" alt="" style="width:36px; height:36px; object-fit:cover; border-radius:6px; border:1px solid #e2e8f0;">
                                <?php else: ?>
                                    <div style="width:36px; height:36px; border-radius:6px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#94a3b8;"><i class="fa-solid fa-box"></i></div>
                                <?php endif; ?>
                                <?= e($p['ProductName']) ?>
                            </div>
                        </td>
                        <td>
                            <?php if ($p['CategoryName']): ?>
                                <span class="cat-pill" style="background: <?= e($p['CategoryColor']) ?>20; color: <?= e($p['CategoryColor']) ?>; border: 1px solid <?= e($p['CategoryColor']) ?>40;">
                                    <i class="fa-solid <?= e($p['CategoryIcon']) ?>"></i>
                                    <?= e($p['CategoryName']) ?>
                                </span>
                            <?php else: ?>
                                <span class="cat-none">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $p['StockQuantity'] ?></td>
                        <td><?= e($p['Unit'] ?? 'pcs') ?></td>
                        <td>$<?= number_format((float)$p['Price'], 2) ?></td>
                        <td>
                            <?php if (canDo('product_edit')): ?>
                            <button type="button" class="btn-secondary btn-gap-sm"
                                onclick="editProduct(
                                    <?= $p['ProductID'] ?>,
                                    '<?= addslashes(e($p['ProductName'])) ?>',
                                    <?= (int)$p['StockQuantity'] ?>,
                                    '<?= addslashes(e($p['Unit'] ?? 'pcs')) ?>',
                                    <?= (float)$p['Price'] ?>,
                                    <?= $p['CategoryID'] ? (int)$p['CategoryID'] : 'null' ?>
                                )">
                                <i class="fa-solid fa-edit"></i> Edit
                            </button>
                            <?php endif; ?>
                            <?php if (canDo('product_delete')): ?>
                            <form method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="product_id" value="<?= $p['ProductID'] ?>">
                                <button type="submit" class="btn-secondary btn-delete btn-gap-sm">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </form>
                            <?php endif; ?>
                            <?php if (!canDo('product_edit') && !canDo('product_delete')): ?>
                                <span style="color:var(--text-muted);font-size:12px;">View only</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<style>
.category-select-wrap { display: flex; align-items: center; gap: 10px; }
.cat-hint { font-size: 0.82rem; color: #6366f1; text-decoration: none; white-space: nowrap; display: flex; align-items: center; gap: 4px; }
.cat-hint:hover { text-decoration: underline; }
.selected-cat-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #fff;
}
.cat-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}
.cat-none { color: #cbd5e1; }

/* Filter bar */
.cat-filter-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin: 14px 0;
}
.cat-filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    border: 2px solid #e2e8f0;
    background: #fff;
    color: #555;
    font-size: 0.83rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}
.cat-filter-btn:hover,
.cat-filter-btn.active {
    border-color: var(--fc, #6366f1);
    background: var(--fc, #6366f1);
    color: #fff;
}
.cat-filter-btn.active { background: var(--fc, #6366f1); }
.cat-filter-btn:first-child.active { background: #1e293b; border-color: #1e293b; }
.cat-filter-btn.uncategorized.active { background: #94a3b8; border-color: #94a3b8; }
</style>

<script>
function showAddForm() {
    document.getElementById('formTitle').textContent = 'Add Product';
    document.getElementById('formAction').value = 'add';
    document.getElementById('productId').value = '';
    document.getElementById('productName').value = '';
    document.getElementById('stockQuantity').value = '';
    document.getElementById('unit').value = 'pcs';
    document.getElementById('price').value = '';
    document.getElementById('categoryId').value = '0';
    updateCategoryBadge();
    document.getElementById('productForm').style.display = 'block';
    document.getElementById('productForm').scrollIntoView({behavior: 'smooth'});
}

function editProduct(id, name, stock, unit, price, categoryId) {
    document.getElementById('formTitle').textContent = 'Edit Product';
    document.getElementById('formAction').value = 'edit';
    document.getElementById('productId').value = id;
    document.getElementById('productName').value = name;
    document.getElementById('stockQuantity').value = stock;
    document.getElementById('unit').value = unit || 'pcs';
    document.getElementById('price').value = price;
    document.getElementById('categoryId').value = categoryId || 0;
    updateCategoryBadge();
    document.getElementById('productForm').style.display = 'block';
    document.getElementById('productForm').scrollIntoView({behavior: 'smooth'});
}

function hideForm() {
    document.getElementById('productForm').style.display = 'none';
}

function updateCategoryBadge() {
    const sel = document.getElementById('categoryId');
    const badge = document.getElementById('selectedCatBadge');
    const opt = sel.options[sel.selectedIndex];
    if (sel.value === '0') {
        badge.style.display = 'none';
    } else {
        const icon = opt.dataset.icon || 'fa-tag';
        const color = opt.dataset.color || '#6366f1';
        badge.style.display = 'inline-flex';
        badge.style.background = color;
        badge.innerHTML = `<i class="fa-solid ${icon}"></i> ${opt.text}`;
    }
}

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
        if (activeCategoryFilter === 'none') {
            catMatch = row.dataset.category === 'none';
        } else if (activeCategoryFilter !== null) {
            catMatch = row.dataset.category == activeCategoryFilter;
        }
        row.style.display = (nameMatch && catMatch) ? '' : 'none';
    });
}
</script>

<?php include 'includes/footer.php'; ?>
