<?php
require 'config.php';
requireRole([ROLE_ADMIN, ROLE_MANAGER]);

include 'includes/header.php';
include 'includes/navbar.php';
// We can reuse the sales_orders CSS because the layout is identical
echo '<link rel="stylesheet" href="assets/css/sales_orders.css">';

$currentUserId = (int)$_SESSION['user_id'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'create_order') {
        $supplierId = (int)($_POST['supplier_id'] ?? 0);
        $status = trim($_POST['status'] ?? 'Draft');
        $notes = trim($_POST['notes'] ?? '');

        $productIds = $_POST['product_id'] ?? [];
        $qtys = $_POST['qty'] ?? [];
        $prices = $_POST['unit_price'] ?? [];

        $items = [];
        for ($i = 0; $i < count($productIds); $i++) {
            $pid = (int)$productIds[$i];
            $q = (int)$qtys[$i];
            $p = (float)$prices[$i];
            if ($pid > 0 && $q > 0) {
                $items[] = ['product_id' => $pid, 'qty' => $q, 'unit_price' => max(0, $p)];
            }
        }

        if (count($items) === 0) {
            $error = 'Add at least one item.';
        } else {
            try {
                $pdo->beginTransaction();

                $postedDate = trim($_POST['order_date'] ?? '');
                $orderDate = $postedDate ? $postedDate . ' ' . date('H:i:s') : date('Y-m-d H:i:s');

                // Ensure supplier belongs to this company if provided
                $supplierIdForInsert = null;
                if ($supplierId > 0) {
                    $supStmt = $pdo->prepare("SELECT SupplierID FROM tblsuppliers WHERE SupplierID=? AND CompanyID=?");
                    $supStmt->execute([$supplierId, companyId()]);
                    if ($supStmt->fetch()) $supplierIdForInsert = $supplierId;
                }

                $insertOrder = $pdo->prepare("INSERT INTO tblpurchaseorders (UserID, CompanyID, SupplierID, OrderDate, Status, Notes, TotalAmount) VALUES (?, ?, ?, ?, ?, ?, 0)");
                $insertOrder->execute([$currentUserId, companyId(), $supplierIdForInsert, $orderDate, $status, ($notes ?: null)]);
                $purchaseOrderId = (int)$pdo->lastInsertId();

                $total = 0.0;
                $insertItem = $pdo->prepare("INSERT INTO tblpurchaseorderitems (PurchaseOrderID, ProductID, Quantity, UnitPrice, LineTotal) VALUES (?, ?, ?, ?, ?)");
                
                // Purchase orders INCREASE stock
                $updateStock = $pdo->prepare("UPDATE tblproducts SET StockQuantity = StockQuantity + ? WHERE ProductID = ? AND CompanyID = ?");

                foreach ($items as $it) {
                    // Verify product belongs to this company
                    $prodStmt = $pdo->prepare("SELECT ProductID FROM tblproducts WHERE ProductID=? AND CompanyID=?");
                    $prodStmt->execute([$it['product_id'], companyId()]);
                    if (!$prodStmt->fetch()) continue;

                    $line = round($it['qty'] * $it['unit_price'], 2);
                    $total += $line;
                    $insertItem->execute([$purchaseOrderId, $it['product_id'], $it['qty'], $it['unit_price'], $line]);

                    // Increase stock immediately for non-draft orders
                    if (strtolower($status) !== 'draft') {
                        $updateStock->execute([$it['qty'], $it['product_id'], companyId()]);
                        logStockMovement($it['product_id'], companyId(), $currentUserId, 'IN', $it['qty'], 'Purchase Order #' . $purchaseOrderId);
                    }
                }

                $updTotal = $pdo->prepare("UPDATE tblpurchaseorders SET TotalAmount=? WHERE PurchaseOrderID=? AND CompanyID=?");
                $updTotal->execute([round($total, 2), $purchaseOrderId, companyId()]);

                $pdo->commit();
                $message = 'Purchase order created.';
            } catch (Throwable $t) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                $error = 'Could not create order: ' . $t->getMessage();
            }
        }
    } elseif ($action === 'delete_order') {
        $id = (int)($_POST['purchase_order_id'] ?? 0);
        if ($id > 0) {
            $pdo->prepare("DELETE FROM tblpurchaseorderitems WHERE PurchaseOrderID = ?")->execute([$id]);
            $pdo->prepare("DELETE FROM tblpurchaseorders WHERE PurchaseOrderID = ? AND CompanyID = ?")->execute([$id, companyId()]);
            $message = 'Purchase order deleted.';
        }
    }
}

$suppliersStmt = $pdo->prepare("SELECT SupplierID, SupplierName FROM tblsuppliers WHERE CompanyID=? ORDER BY SupplierName");
$suppliersStmt->execute([companyId()]);
$suppliers = $suppliersStmt->fetchAll();

$productsStmt = $pdo->prepare("SELECT ProductID, ProductName, StockQuantity, Unit, Price FROM tblproducts WHERE CompanyID=? ORDER BY ProductName");
$productsStmt->execute([companyId()]);
$products = $productsStmt->fetchAll();

$ordersStmt = $pdo->prepare("
    SELECT po.PurchaseOrderID, po.OrderDate, po.Status, po.TotalAmount, s.SupplierName
    FROM tblpurchaseorders po
    LEFT JOIN tblsuppliers s ON s.SupplierID = po.SupplierID
    WHERE po.CompanyID = ?
    ORDER BY po.PurchaseOrderID DESC
    LIMIT 50
");
$ordersStmt->execute([companyId()]);
$orders = $ordersStmt->fetchAll();
?>

<main class="sales-page">
    <div class="header-title page-title">
        <i class="fa-solid fa-file-invoice-dollar"></i> Purchase Orders
    </div>
    <div class="subtitle">Create restock orders from suppliers</div>

    <?php if ($message): ?>
        <div class="notify"><?= e($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="login-error"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="top-grid">
        <div class="card">
            <h3>Create Purchase Order</h3>
            <p class="muted">Draft orders do not change stock. Any other status will increase stock immediately.</p>

            <form method="POST" id="poForm">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="create_order">

                <div class="form-grid">
                    <div>
                        <label class="product-label">Supplier</label>
                        <select class="product-input" name="supplier_id">
                            <option value="0">Walk-in / None</option>
                            <?php foreach ($suppliers as $s): ?>
                                <option value="<?= (int)$s['SupplierID'] ?>"><?= e($s['SupplierName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="product-label">Status</label>
                        <select class="product-input" name="status">
                            <option>Draft</option>
                            <option>Pending</option>
                            <option>Completed</option>
                        </select>
                    </div>
                    <div class="span-2">
                        <label class="product-label">Notes</label>
                        <input class="product-input" type="text" name="notes" placeholder="Optional notes">
                    </div>
                    <div>
                        <label class="product-label">Date</label>
                        <div class="date-wrapper">
                            <i class="fa-solid fa-calendar-days date-icon"></i>
                            <input type="date" name="order_date">
                        </div>
                    </div>
                </div>
                <script>
                    document.querySelector('input[name="order_date"]').value = new Date().toISOString().split('T')[0];
                </script>

                <h3 style="margin-top:16px;">Items</h3>
                <table class="items-table" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="width:46%;">Product</th>
                            <th style="width:16%;">Qty</th>
                            <th style="width:18%;">Unit Cost</th>
                            <th style="width:20%;">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <div class="items-actions">
                    <button type="button" class="btn-secondary btn-icon" onclick="addItemRow()">
                        <i class="fa-solid fa-plus"></i> Add Item
                    </button>
                    <button type="submit" class="btn-primary btn-icon">
                        <i class="fa-solid fa-check"></i> Create Order
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <h3>Recent Purchase Orders</h3>
            <table id="productsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><?= (int)$o['PurchaseOrderID'] ?></td>
                            <td><?= e($o['SupplierName'] ?: 'Walk-in') ?></td>
                            <td><?= e($o['Status']) ?></td>
                            <td><?= $o['OrderDate'] ?></td>
                            <td><?= number_format((float)$o['TotalAmount'], 2) ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="generate_invoice.php?type=purchase&id=<?= (int)$o['PurchaseOrderID'] ?>" target="_blank" class="btn-secondary btn-gap-sm">
                                        <i class="fa-solid fa-print"></i> Print
                                    </a>
                                    <form method="POST" class="inline-form" onsubmit="return confirm('Delete this purchase order?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete_order">
                                        <input type="hidden" name="purchase_order_id" value="<?= (int)$o['PurchaseOrderID'] ?>">
                                        <button class="btn-secondary btn-danger btn-gap-sm" type="submit">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
const products = <?php echo json_encode(array_map(function($p){
    return [
        'id' => (int)$p['ProductID'],
        'name' => (string)$p['ProductName'],
        'stock' => (int)$p['StockQuantity'],
        'unit' => (string)($p['Unit'] ?? 'pcs'),
        'price' => (float)($p['Price'] ?? 0)
    ];
}, $products)); ?>;

function addItemRow() {
    const tbody = document.querySelector('#itemsTable tbody');
    const tr = document.createElement('tr');

    const productOptions = products.map(p => `<option value="${p.id}" data-price="${p.price}">${escapeHtml(p.name)} (Stock: ${p.stock} ${escapeHtml(p.unit || 'pcs')})</option>`).join('');

    tr.innerHTML = `
        <td style="padding: 8px 4px;">
            <select class="product-input" name="product_id[]" onchange="updatePrice(this)" style="min-width:120px;">
                <option value="0" data-price="0">Select product</option>
                ${productOptions}
            </select>
        </td>
        <td style="padding: 8px 4px;"><input class="product-input" type="number" name="qty[]" min="1" value="1" required style="min-width:60px;"></td>
        <td style="padding: 8px 4px;"><input class="product-input price-input" type="number" name="unit_price[]" min="0" step="0.01" value="0" required style="min-width:80px;"></td>
        <td style="padding: 8px 4px; text-align: right;"><button type="button" class="btn-secondary btn-gap-sm" onclick="this.closest('tr').remove()"><i class="fa-solid fa-xmark"></i> Remove</button></td>
    `;
    tbody.appendChild(tr);
}

function updatePrice(select) {
    const option = select.options[select.selectedIndex];
    const price = option.getAttribute('data-price') || 0;
    const tr = select.closest('tr');
    const priceInput = tr.querySelector('.price-input');
    if (priceInput) {
        priceInput.value = price;
    }
}

function escapeHtml(str) {
    return String(str)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

// add first row by default
addItemRow();
</script>

<?php include 'includes/footer.php'; ?>
