<?php
require 'config.php';
requireRole([ROLE_ADMIN, ROLE_MANAGER, ROLE_STAFF, ROLE_CUSTOMER]);

include 'includes/header.php';
include 'includes/navbar.php';

$q = trim($_GET['q'] ?? '');
$cid = companyId();
$role = (int)$_SESSION['role_id'];

$products = [];
$customers = [];
$suppliers = [];

if ($q !== '') {
    $searchQ = "%$q%";

    // Products
    if (hasRole([ROLE_ADMIN, ROLE_MANAGER, ROLE_STAFF])) {
        $stmt = $pdo->prepare("SELECT ProductID, ProductName, StockQuantity FROM tblproducts WHERE CompanyID = ? AND ProductName LIKE ? LIMIT 10");
        $stmt->execute([$cid, $searchQ]);
        $products = $stmt->fetchAll();
    }

    // Customers
    if (hasRole([ROLE_ADMIN, ROLE_MANAGER])) {
        $stmt = $pdo->prepare("SELECT CustomerID, CustomerName, Email, Phone FROM tblcustomers WHERE CompanyID = ? AND (CustomerName LIKE ? OR Email LIKE ? OR Phone LIKE ?) LIMIT 10");
        $stmt->execute([$cid, $searchQ, $searchQ, $searchQ]);
        $customers = $stmt->fetchAll();
    }

    // Suppliers
    if (hasRole([ROLE_ADMIN, ROLE_MANAGER])) {
        $stmt = $pdo->prepare("SELECT SupplierID, SupplierName, ContactPerson as ContactName, Phone FROM tblsuppliers WHERE CompanyID = ? AND (SupplierName LIKE ? OR ContactPerson LIKE ? OR Phone LIKE ?) LIMIT 10");
        $stmt->execute([$cid, $searchQ, $searchQ, $searchQ]);
        $suppliers = $stmt->fetchAll();
    }
}
?>
<style>
.search-page { max-width: 1000px; margin: 0 auto; padding: 24px; }
.search-card { background: white; border-radius: var(--radius); padding: 20px; margin-bottom: 20px; box-shadow: var(--shadow-sm); }
.search-card h3 { margin-top: 0; margin-bottom: 14px; font-size: 16px; color: var(--navy); border-bottom: 1px solid var(--border); padding-bottom: 10px; }
.result-item { padding: 12px; border: 1px solid var(--border); border-radius: 6px; margin-bottom: 10px; transition: 0.2s; text-decoration: none; display: block; color: inherit; }
.result-item:hover { border-color: var(--teal); box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
.result-title { font-weight: 700; color: var(--text-primary); font-size: 15px; margin-bottom: 4px; }
.result-desc { font-size: 13px; color: var(--text-secondary); }
</style>

<main class="search-page">
    <div class="header-title page-title">
        <i class="fa-solid fa-magnifying-glass"></i> Global Search
    </div>
    <div class="subtitle" style="margin-bottom: 24px;">Search results for: <strong><?= e($q) ?></strong></div>

    <?php if ($q === ''): ?>
        <div class="notify">Please enter a search term.</div>
    <?php elseif (empty($products) && empty($customers) && empty($suppliers)): ?>
        <div class="search-card">
            <div style="text-align:center; padding:40px; color:var(--text-muted);">
                <i class="fa-solid fa-folder-open" style="font-size:32px; margin-bottom:12px;"></i>
                <div>No results found for "<?= e($q) ?>"</div>
            </div>
        </div>
    <?php else: ?>

        <?php if (!empty($products)): ?>
        <div class="search-card">
            <h3>Products</h3>
            <?php foreach ($products as $p): ?>
                <a href="product.php?search=<?= urlencode($p['ProductName']) ?>" class="result-item">
                    <div class="result-title"><i class="fa-solid fa-box" style="color:var(--teal); margin-right:6px;"></i> <?= e($p['ProductName']) ?></div>
                    <div class="result-desc">Current Stock: <?= $p['StockQuantity'] ?></div>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($customers)): ?>
        <div class="search-card">
            <h3>Customers</h3>
            <?php foreach ($customers as $c): ?>
                <a href="customers.php" class="result-item">
                    <div class="result-title"><i class="fa-solid fa-user" style="color:var(--blue); margin-right:6px;"></i> <?= e($c['CustomerName']) ?></div>
                    <div class="result-desc"><?= e($c['Email']) ?> • <?= e($c['Phone']) ?></div>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($suppliers)): ?>
        <div class="search-card">
            <h3>Suppliers</h3>
            <?php foreach ($suppliers as $s): ?>
                <a href="suppliers.php" class="result-item">
                    <div class="result-title"><i class="fa-solid fa-truck" style="color:var(--amber); margin-right:6px;"></i> <?= e($s['SupplierName']) ?></div>
                    <div class="result-desc">Contact: <?= e($s['ContactName']) ?> • <?= e($s['Phone']) ?></div>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
