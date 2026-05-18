<?php
require 'config.php';
requireRole([ROLE_ADMIN, ROLE_MANAGER]);

include 'includes/header.php';
include 'includes/navbar.php';

$cid = companyId();

$movementsStmt = $pdo->prepare("
    SELECT m.MovementID, m.Type, m.Quantity, m.Reason, m.CreatedAt,
           p.ProductName, p.Unit,
           u.FullName as UserName
    FROM tblstockmovements m
    JOIN tblproducts p ON p.ProductID = m.ProductID
    JOIN tblusers u ON u.UserID = m.UserID
    WHERE m.CompanyID = ?
    ORDER BY m.CreatedAt DESC
    LIMIT 100
");
$movementsStmt->execute([$cid]);
$movements = $movementsStmt->fetchAll();
?>

<style>
.sm-page {
    max-width: 1000px;
    margin: 0 auto;
    padding: 24px;
}
.sm-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    background: white;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.sm-table thead tr { background: var(--navy); }
.sm-table th {
    padding: 12px 18px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: rgba(255,255,255,0.75);
    text-align: left;
}
.sm-table td {
    padding: 12px 18px;
    border-bottom: 1px solid var(--border);
    color: var(--text-secondary);
    vertical-align: middle;
}
.sm-table tbody tr:hover { background: var(--surface-2); }
.sm-type-in { color: #00a884; font-weight: 700; }
.sm-type-out { color: #dc2626; font-weight: 700; }
.sm-type-set { color: #2563eb; font-weight: 700; }
</style>

<main class="sm-page">
    <div class="header-title page-title">
        <i class="fa-solid fa-clock-rotate-left"></i> Stock Movement History
    </div>
    <div class="subtitle" style="margin-bottom: 20px;">Audit trail of all inventory changes</div>

    <table class="sm-table">
        <thead>
            <tr>
                <th>Date / Time</th>
                <th>Product</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>User</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movements as $m): 
                $typeClass = strtolower($m['Type']);
                $sign = $m['Type'] === 'OUT' ? '-' : ($m['Type'] === 'IN' ? '+' : '');
            ?>
            <tr>
                <td><?= date('M d, Y H:i', strtotime($m['CreatedAt'])) ?></td>
                <td style="font-weight:600; color:var(--text-primary);"><?= e($m['ProductName']) ?></td>
                <td class="sm-type-<?= $typeClass ?>"><?= e($m['Type']) ?></td>
                <td class="sm-type-<?= $typeClass ?>"><?= $sign . $m['Quantity'] ?> <?= e($m['Unit']) ?></td>
                <td><i class="fa-solid fa-user-circle" style="color:var(--text-muted);"></i> <?= e($m['UserName']) ?></td>
                <td><?= e($m['Reason'] ?: '—') ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($movements)): ?>
            <tr><td colspan="6" class="empty-state">No stock movements found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include 'includes/footer.php'; ?>
