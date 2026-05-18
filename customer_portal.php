<?php
require 'config.php';
requireRole(ROLE_CUSTOMER, 'login.php');

// Customers are linked to a CustomerID via their username match
// We look up by UserName = tblusers.UserName → tblcustomers.Email or CustomerName
$userId    = (int)$_SESSION['user_id'];
$compId    = companyId();
$userName  = $_SESSION['fullname'] ?? '';

// Find customer record matching this portal user (by UserID stored in tblcustomers if set, or by name)
// We use a convention: when admin creates a Customer user, the customer record's Email = username
$customerStmt = $pdo->prepare("
    SELECT c.CustomerID, c.CustomerName, c.Email, c.Phone, c.Address
    FROM tblcustomers c
    JOIN tblusers u ON u.UserName = c.Email
    WHERE u.UserID = ? AND c.CompanyID = ?
    LIMIT 1
");
$customerStmt->execute([$userId, $compId]);
$customer = $customerStmt->fetch();

$orders = [];
$products = [];
$message = '';
$error = '';

if ($customer) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'place_order') {
        verify_csrf();
        
        $productIds = $_POST['product_id'] ?? [];
        $qtys = $_POST['qty'] ?? [];
        
        $items = [];
        for ($i = 0; $i < count($productIds); $i++) {
            $pid = (int)$productIds[$i];
            $q = (int)$qtys[$i];
            if ($pid > 0 && $q > 0) {
                $items[] = ['product_id' => $pid, 'qty' => $q];
            }
        }
        
        if (empty($items)) {
            $error = 'Please add at least one item with a quantity greater than 0.';
        } else {
            try {
                $pdo->beginTransaction();
                $orderDate = date('Y-m-d H:i:s');
                $status = 'Pending';
                
                $insertOrder = $pdo->prepare("INSERT INTO tblsalesorders (UserID, CompanyID, CustomerID, OrderDate, Status, TotalAmount) VALUES (?, ?, ?, ?, ?, 0)");
                $insertOrder->execute([$userId, $compId, $customer['CustomerID'], $orderDate, $status]);
                $salesOrderId = (int)$pdo->lastInsertId();
                
                $total = 0.0;
                $insertItem = $pdo->prepare("INSERT INTO tblsalesorderitems (SalesOrderID, ProductID, Quantity, UnitPrice, LineTotal) VALUES (?, ?, ?, ?, ?)");
                
                foreach ($items as $it) {
                    $prodStmt = $pdo->prepare("SELECT Price FROM tblproducts WHERE ProductID = ? AND CompanyID = ?");
                    $prodStmt->execute([$it['product_id'], $compId]);
                    $pRow = $prodStmt->fetch();
                    if (!$pRow) continue;
                    
                    $unitPrice = (float)$pRow['Price'];
                    $line = round($it['qty'] * $unitPrice, 2);
                    $total += $line;
                    
                    $insertItem->execute([$salesOrderId, $it['product_id'], $it['qty'], $unitPrice, $line]);
                }
                
                $updTotal = $pdo->prepare("UPDATE tblsalesorders SET TotalAmount = ? WHERE SalesOrderID = ?");
                $updTotal->execute([$total, $salesOrderId]);
                
                $pdo->commit();
                $message = 'Order placed successfully! It is now pending review.';
            } catch (Throwable $t) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                $error = 'Could not place order: ' . $t->getMessage();
            }
        }
    }

    $ordersStmt = $pdo->prepare("
        SELECT so.SalesOrderID, so.OrderDate, so.Status, so.TotalAmount, so.Notes
        FROM tblsalesorders so
        WHERE so.CustomerID = ? AND so.CompanyID = ?
        ORDER BY so.SalesOrderID DESC
    ");
    $ordersStmt->execute([$customer['CustomerID'], $compId]);
    $orders = $ordersStmt->fetchAll();
    
    // Fetch products for the catalog
    $prodStmt = $pdo->prepare("
        SELECT ProductID, ProductName, StockQuantity, Unit, Price, ImageURL 
        FROM tblproducts 
        WHERE CompanyID = ? 
        ORDER BY ProductName
    ");
    $prodStmt->execute([$compId]);
    $products = $prodStmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockMaster – My Orders</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
    /* ── Customer Portal Styles ─── */
    body { background: #f0f2f6; }

    .cp-wrapper {
        max-width: 860px;
        margin: 0 auto;
        padding: 32px 20px 60px;
    }

    .cp-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--navy);
        padding: 16px 28px;
        border-radius: var(--radius-lg);
        margin-bottom: 28px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }

    .cp-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
        font-weight: 800;
        color: white;
    }

    .cp-brand i {
        width: 32px; height: 32px;
        background: var(--teal);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: var(--navy);
        font-size: 14px;
    }

    .cp-user {
        display: flex;
        align-items: center;
        gap: 10px;
        color: rgba(255,255,255,0.7);
        font-size: 14px;
    }

    .cp-logout {
        color: rgba(255,100,100,0.8);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-left: 16px;
        padding: 5px 12px;
        border-radius: 20px;
        border: 1px solid rgba(239,68,68,0.3);
        transition: all 0.18s;
    }

    .cp-logout:hover { background: rgba(239,68,68,0.15); color: #fca5a5; }

    .cp-hero {
        text-align: center;
        margin-bottom: 32px;
    }

    .cp-hero h1 {
        font-size: 26px;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 6px;
    }

    .cp-hero p { font-size: 14px; color: var(--text-muted); }

    .cp-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .cp-stat {
        background: white;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 20px;
        text-align: center;
        box-shadow: var(--shadow-sm);
    }

    .cp-stat .cs-num {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
    }

    .cp-stat .cs-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: var(--text-muted);
        margin-top: 6px;
    }

    .cp-orders-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .cp-orders-table thead tr { background: var(--navy); }

    .cp-orders-table th {
        padding: 12px 18px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: rgba(255,255,255,0.75);
        text-align: left;
    }

    .cp-orders-table th:first-child { border-radius: 8px 0 0 8px; }
    .cp-orders-table th:last-child  { border-radius: 0 8px 8px 0; }

    .cp-orders-table td {
        padding: 13px 18px;
        border-bottom: 1px solid var(--border);
        color: var(--text-secondary);
        vertical-align: middle;
    }

    .cp-orders-table tbody tr:last-child td { border-bottom: none; }
    .cp-orders-table tbody tr:hover { background: #f8fafc; }

    .cp-no-customer {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .cp-no-customer i { font-size: 48px; opacity: 0.3; margin-bottom: 16px; display: block; }
    .cp-no-customer h2 { font-size: 18px; color: var(--text-primary); margin-bottom: 8px; }
    
    .cp-catalog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .cp-product-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 16px;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
    }
    .cp-product-img {
        width: 100%;
        height: 120px;
        object-fit: contain;
        margin-bottom: 12px;
        border-radius: var(--radius-sm);
        background: var(--surface-2);
    }
    .cp-product-title {
        font-weight: 700;
        font-size: 14px;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    .cp-product-price {
        font-weight: 800;
        color: var(--teal-dark);
        margin-bottom: 8px;
    }
    .cp-product-stock {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 12px;
        flex-grow: 1;
    }
    .cp-qty-input {
        width: 100%;
        padding: 8px;
        border: 1px solid var(--border-dark);
        border-radius: 6px;
        text-align: center;
        margin-top: auto;
    }
    .cp-order-bar {
        background: var(--navy);
        padding: 16px 20px;
        border-radius: var(--radius-lg);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        color: white;
        position: sticky;
        bottom: 20px;
        z-index: 100;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    </style>
</head>
<body>
<div class="cp-wrapper">

    <!-- Top bar -->
    <div class="cp-topbar">
        <div class="cp-brand">
            <i class="fa-solid fa-boxes-stacked"></i>
            StockMaster
        </div>
        <div class="cp-user">
            <i class="fa-solid fa-user-circle"></i>
            <?= e($userName) ?>
            <a href="logout.php" class="cp-logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="notify"><?= e($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="login-error"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if (!$customer): ?>
    <!-- No customer record linked -->
    <div class="card cp-no-customer">
        <i class="fa-solid fa-link-slash"></i>
        <h2>No account linked yet</h2>
        <p>Your portal account isn't connected to a customer record yet.<br>
           Please contact the store administrator to link your account.</p>
    </div>

    <?php else: ?>

    <!-- Hero -->
    <div class="cp-hero">
        <h1>Hello, <?= e(explode(' ', $customer['CustomerName'])[0]) ?>!</h1>
        <p>Here's your order history with us.</p>
    </div>

    <!-- Stats -->
    <?php
        $totalOrders   = count($orders);
        $totalSpent    = array_sum(array_column($orders, 'TotalAmount'));
        $completedOrds = count(array_filter($orders, fn($o) => strtolower($o['Status']) === 'completed'));
    ?>
    <div class="cp-stats">
        <div class="cp-stat">
            <div class="cs-num"><?= $totalOrders ?></div>
            <div class="cs-label">Total Orders</div>
        </div>
        <div class="cp-stat">
            <div class="cs-num"><?= $completedOrds ?></div>
            <div class="cs-label">Completed</div>
        </div>
        <div class="cp-stat">
            <div class="cs-num">$<?= number_format($totalSpent, 2) ?></div>
            <div class="cs-label">Total Spent</div>
        </div>
    </div>

    <!-- Catalog -->
    <div class="card" style="padding:0; overflow:hidden; margin-bottom: 24px;">
        <div style="padding:18px 24px 14px; border-bottom:1px solid var(--border);">
            <h3 style="margin:0; font-size:16px; font-weight:700;">
                <i class="fa-solid fa-store" style="color:var(--teal);"></i> Product Catalog
            </h3>
        </div>
        
        <div style="padding: 24px;">
            <form method="POST" id="catalogForm">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="place_order">
                
                <div class="cp-catalog-grid">
                    <?php foreach ($products as $p): ?>
                    <div class="cp-product-card">
                        <?php if ($p['ImageURL']): ?>
                            <img src="<?= e($p['ImageURL']) ?>" alt="<?= e($p['ProductName']) ?>" class="cp-product-img">
                        <?php else: ?>
                            <div class="cp-product-img" style="display:flex; align-items:center; justify-content:center; font-size:32px; color:var(--text-muted);">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        <?php endif; ?>
                        <div class="cp-product-title"><?= e($p['ProductName']) ?></div>
                        <div class="cp-product-price">$<?= number_format((float)$p['Price'], 2) ?></div>
                        <div class="cp-product-stock">Available: <?= $p['StockQuantity'] ?> <?= e($p['Unit'] ?: 'pcs') ?></div>
                        
                        <input type="hidden" name="product_id[]" value="<?= $p['ProductID'] ?>">
                        <input type="number" name="qty[]" class="cp-qty-input" min="0" placeholder="Qty">
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="cp-order-bar">
                    <div>
                        <div style="font-weight:700; font-size:16px;">Ready to order?</div>
                        <div style="font-size:12px; color:rgba(255,255,255,0.7);">Enter quantities above and click place order.</div>
                    </div>
                    <button type="submit" class="btn-primary" style="box-shadow: none; padding: 12px 24px;">
                        <i class="fa-solid fa-paper-plane"></i> Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders table -->
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding:18px 24px 14px; border-bottom:1px solid var(--border);">
            <h3 style="margin:0; font-size:16px; font-weight:700;">
                <i class="fa-solid fa-receipt" style="color:var(--teal);"></i> My Orders
            </h3>
        </div>

        <?php if (empty($orders)): ?>
            <div class="empty-state" style="padding:40px;">
                <i class="fa-solid fa-box-open"></i>
                No orders yet.
            </div>
        <?php else: ?>
        <table class="cp-orders-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o):
                    $statusClass = match(strtolower($o['Status'])) {
                        'completed' => 'order-status-completed',
                        'pending'   => 'order-status-pending',
                        default     => 'order-status-draft',
                    };
                ?>
                <tr>
                    <td style="font-weight:700; color:var(--text-primary);">#<?= (int)$o['SalesOrderID'] ?></td>
                    <td><?= date('M d, Y', strtotime($o['OrderDate'])) ?></td>
                    <td><span class="order-status <?= $statusClass ?>"><?= e($o['Status']) ?></span></td>
                    <td style="font-weight:700;">$<?= number_format((float)$o['TotalAmount'], 2) ?></td>
                    <td><?= e($o['Notes'] ?: '—') ?></td>
                    <td>
                        <a href="generate_invoice.php?type=sales&id=<?= (int)$o['SalesOrderID'] ?>" target="_blank" class="btn-secondary btn-gap-sm" style="font-size:11px; padding:4px 8px;">
                            <i class="fa-solid fa-print"></i> Print
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- Contact info -->
    <div class="card" style="margin-top:20px; display:flex; gap:20px; flex-wrap:wrap;">
        <?php if ($customer['Email']): ?>
        <div style="display:flex; align-items:center; gap:8px; font-size:14px; color:var(--text-secondary);">
            <i class="fa-solid fa-envelope" style="color:var(--teal);"></i>
            <?= e($customer['Email']) ?>
        </div>
        <?php endif; ?>
        <?php if ($customer['Phone']): ?>
        <div style="display:flex; align-items:center; gap:8px; font-size:14px; color:var(--text-secondary);">
            <i class="fa-solid fa-phone" style="color:var(--teal);"></i>
            <?= e($customer['Phone']) ?>
        </div>
        <?php endif; ?>
        <?php if ($customer['Address']): ?>
        <div style="display:flex; align-items:center; gap:8px; font-size:14px; color:var(--text-secondary);">
            <i class="fa-solid fa-location-dot" style="color:var(--teal);"></i>
            <?= e($customer['Address']) ?>
        </div>
        <?php endif; ?>
    </div>

    <?php endif; ?>

</div>
</body>
</html>
