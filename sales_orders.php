<?php
require 'config.php';
requireRole([ROLE_ADMIN, ROLE_MANAGER]);

include 'includes/header.php';
include 'includes/navbar.php';
echo '<link rel="stylesheet" href="assets/css/sales_orders.css">';

$currentUserId = (int)$_SESSION['user_id'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'create_order') {
        $customerId = (int)($_POST['customer_id'] ?? 0);
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

                // Ensure customer belongs to this user if provided
                $customerIdForInsert = null;
                if ($customerId > 0) {
                    $custStmt = $pdo->prepare("SELECT CustomerID FROM tblcustomers WHERE CustomerID=? AND UserID=?");
                    $custStmt->execute([$customerId, $currentUserId]);
                    if ($custStmt->fetch()) $customerIdForInsert = $customerId;
                }

                $insertOrder = $pdo->prepare("INSERT INTO tblsalesorders (UserID, CustomerID, OrderDate, Status, Notes, TotalAmount) VALUES (?, ?, ?, ?, ?, 0)");
                $insertOrder->execute([$currentUserId, $customerIdForInsert, $orderDate, $status, ($notes ?: null)]);
                $salesOrderId = (int)$pdo->lastInsertId();

                $total = 0.0;
                $insertItem = $pdo->prepare("INSERT INTO tblsalesorderitems (SalesOrderID, ProductID, Quantity, UnitPrice, LineTotal) VALUES (?, ?, ?, ?, ?)");
                $updateStock = $pdo->prepare("UPDATE tblproducts SET StockQuantity = StockQuantity - ? WHERE ProductID = ? AND UserID = ? AND StockQuantity >= ?");

                foreach ($items as $it) {
                    // Verify product belongs to this user
                    $prodStmt = $pdo->prepare("SELECT ProductID FROM tblproducts WHERE ProductID=? AND UserID=?");
                    $prodStmt->execute([$it['product_id'], $currentUserId]);
                    if (!$prodStmt->fetch()) continue;

                    $line = round($it['qty'] * $it['unit_price'], 2);
                    $total += $line;
                    $insertItem->execute([$salesOrderId, $it['product_id'], $it['qty'], $it['unit_price'], $line]);

                    // Deduct stock immediately for non-draft orders
                    if (strtolower($status) !== 'draft') {
                        $updateStock->execute([$it['qty'], $it['product_id'], $currentUserId, $it['qty']]);
                        if ($updateStock->rowCount() === 0) {
                            throw new Exception('Insufficient stock for one of the items.');
                        }
                        logStockMovement($it['product_id'], companyId(), $currentUserId, 'OUT', $it['qty'], 'Sales Order #' . $salesOrderId);
                    }
                }

                $updTotal = $pdo->prepare("UPDATE tblsalesorders SET TotalAmount=? WHERE SalesOrderID=? AND UserID=?");
                $updTotal->execute([round($total, 2), $salesOrderId, $currentUserId]);

                $pdo->commit();
                $message = 'Sales order created.';
            } catch (Throwable $t) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                $error = 'Could not create order: ' . $t->getMessage();
            }
        }
    } elseif ($action === 'delete_order') {
        $id = (int)($_POST['sales_order_id'] ?? 0);
        if ($id > 0) {
            // delete items first then header
            $pdo->prepare("DELETE FROM tblsalesorderitems WHERE SalesOrderID = ?")->execute([$id]);
            $pdo->prepare("DELETE FROM tblsalesorders WHERE SalesOrderID = ? AND UserID = ?")->execute([$id, $currentUserId]);
            $message = 'Sales order deleted.';
        }
    }
}

$customersStmt = $pdo->prepare("SELECT CustomerID, CustomerName FROM tblcustomers WHERE UserID=? ORDER BY CustomerName");
$customersStmt->execute([$currentUserId]);
$customers = $customersStmt->fetchAll();

$productsStmt = $pdo->prepare("SELECT ProductID, ProductName, StockQuantity, Unit, Price FROM tblproducts WHERE UserID=? ORDER BY ProductName");
$productsStmt->execute([$currentUserId]);
$products = $productsStmt->fetchAll();

$ordersStmt = $pdo->prepare("
    SELECT so.SalesOrderID, so.OrderDate, so.Status, so.TotalAmount, c.CustomerName
    FROM tblsalesorders so
    LEFT JOIN tblcustomers c ON c.CustomerID = so.CustomerID
    WHERE so.UserID = ?
    ORDER BY so.SalesOrderID DESC
    LIMIT 50
");
$ordersStmt->execute([$currentUserId]);
$orders = $ordersStmt->fetchAll();
?>

<main class="sales-page">
    <div class="header-title page-title">
        <i class="fa-solid fa-receipt"></i> Sales Orders
    </div>
    <div class="subtitle">Create orders and track sales</div>

    <?php if ($message): ?>
        <div class="notify"><?= e($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="login-error"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="top-grid">
        <div class="card">
            <h3>Create Sales Order</h3>
            <p class="muted">Draft orders do not change stock. Any other status will deduct stock immediately.</p>

            <form method="POST" id="soForm">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="create_order">

                <div class="form-grid">
                    <div>
                        <label class="product-label">Customer</label>
                        <select class="product-input" name="customer_id">
                            <option value="0">Walk-in / None</option>
                            <?php foreach ($customers as $c): ?>
                                <option value="<?= (int)$c['CustomerID'] ?>"><?= e($c['CustomerName']) ?></option>
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
                            <th style="width:18%;">Unit Price</th>
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
            <h3>Recent Orders</h3>
            <table id="productsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><?= (int)$o['SalesOrderID'] ?></td>
                            <td><?= e($o['CustomerName'] ?: 'Walk-in') ?></td>
                            <td><?= e($o['Status']) ?></td>
                            <td><?= $o['OrderDate'] ?></td>
                            <td><?= number_format((float)$o['TotalAmount'], 2) ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="generate_invoice.php?type=sales&id=<?= (int)$o['SalesOrderID'] ?>" target="_blank" class="btn-secondary btn-gap-sm">
                                        <i class="fa-solid fa-print"></i> Print
                                    </a>
                                    <form method="POST" class="inline-form" onsubmit="return confirm('Delete this sales order?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete_order">
                                        <input type="hidden" name="sales_order_id" value="<?= (int)$o['SalesOrderID'] ?>">
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

