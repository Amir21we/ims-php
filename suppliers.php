<?php
require 'config.php';
requireRole([ROLE_ADMIN, ROLE_MANAGER]);

include 'includes/header.php';
include 'includes/navbar.php';
echo '<link rel="stylesheet" href="assets/suppliers.css">';

$currentUserId = (int)$_SESSION['user_id'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name = trim($_POST['supplier_name'] ?? '');
        $contact = trim($_POST['contact_person'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if ($name === '') {
            $error = 'Supplier name is required.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO tblsuppliers (UserID, SupplierName, ContactPerson, Phone, Email, Address) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$currentUserId, $name, ($contact ?: null), ($phone ?: null), ($email ?: null), ($address ?: null)]);
            $message = 'Supplier added.';
        }
    } elseif ($action === 'edit') {
        $id = (int)($_POST['supplier_id'] ?? 0);
        $name = trim($_POST['supplier_name'] ?? '');
        $contact = trim($_POST['contact_person'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if ($id <= 0 || $name === '') {
            $error = 'Invalid supplier.';
        } else {
            $stmt = $pdo->prepare("UPDATE tblsuppliers SET SupplierName=?, ContactPerson=?, Phone=?, Email=?, Address=? WHERE SupplierID=? AND UserID=?");
            $stmt->execute([$name, ($contact ?: null), ($phone ?: null), ($email ?: null), ($address ?: null), $id, $currentUserId]);
            $message = 'Supplier updated.';
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['supplier_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM tblsuppliers WHERE SupplierID=? AND UserID=?");
            $stmt->execute([$id, $currentUserId]);
            $message = 'Supplier deleted.';
        }
    }
}

$suppliersStmt = $pdo->prepare("SELECT * FROM tblsuppliers WHERE UserID = ? ORDER BY SupplierName");
$suppliersStmt->execute([$currentUserId]);
$suppliers = $suppliersStmt->fetchAll();
?>

<main class="suppliers-page">
    <div class="header-title page-title">
        <i class="fa-solid fa-truck-field"></i> Suppliers
    </div>
    <div class="subtitle">Manage suppliers for your account</div>

    <?php if ($message): ?>
        <div class="notify"><?= e($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="login-error"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="card" style="margin-top:18px;">
        <h3>Add / Edit Supplier</h3>
        <form method="POST" id="supplierForm">
            <?= csrf_field() ?>
            <input type="hidden" name="action" id="supplierAction" value="add">
            <input type="hidden" name="supplier_id" id="supplierId" value="">

            <div class="form-grid">
                <div>
                    <label class="product-label">Supplier Name</label>
                    <input class="product-input" type="text" name="supplier_name" id="supplierName" required>
                </div>

                <div>
                    <label class="product-label">Phone</label>
                    <input class="product-input" type="text" name="phone" id="phone">
                </div>
                <div>
                    <label class="product-label">Email</label>
                    <input class="product-input" type="email" name="email" id="email">
                </div>
                <div class="span-2">
                    <label class="product-label">Address</label>
                    <input class="product-input" type="text" name="address" id="address">
                </div>
            </div>

            <div class="inline-actions">
                <button class="btn-primary btn-icon" type="submit">
                    <i class="fa-solid fa-save"></i> Save Supplier
                </button>
                <button class="btn-secondary btn-icon" type="button" onclick="resetSupplierForm()">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </button>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top:18px;">
        <h3>Suppliers</h3>
        <table id="productsTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suppliers as $s): ?>
                    <tr>
                        <td><?= e($s['SupplierName']) ?></td>
                        <td><?= e($s['Phone']) ?></td>
                        <td><?= e($s['Email']) ?></td>
                        <td>
                            <div class="table-actions">
                                <button class="btn-secondary btn-gap-sm" type="button"
                                    onclick="editSupplier(
                                        <?= (int)$s['SupplierID'] ?>,
                                        '<?= addslashes(e($s['SupplierName'])) ?>',
                                        '<?= addslashes(e($s['Phone'])) ?>',
                                        '<?= addslashes(e($s['Email'])) ?>',
                                        '<?= addslashes(e($s['Address'])) ?>'
                                    )">
                                    <i class="fa-solid fa-edit"></i> Edit
                                </button>
                                <form method="POST" class="inline-form" onsubmit="return confirm('Delete this supplier?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="supplier_id" value="<?= (int)$s['SupplierID'] ?>">
                                    <button type="submit" class="btn-secondary btn-danger btn-gap-sm">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
function editSupplier(id, name, phone, email, address) {
    document.getElementById('supplierAction').value = 'edit';
    document.getElementById('supplierId').value = id;
    document.getElementById('supplierName').value = name;
    document.getElementById('phone').value = phone;
    document.getElementById('email').value = email;
    document.getElementById('address').value = address;
    document.getElementById('supplierForm').scrollIntoView({behavior: 'smooth'});
}
function resetSupplierForm() {
    document.getElementById('supplierAction').value = 'add';
    document.getElementById('supplierId').value = '';
    document.getElementById('supplierName').value = '';
    document.getElementById('phone').value = '';
    document.getElementById('email').value = '';
    document.getElementById('address').value = '';
}
</script>

<?php include 'includes/footer.php'; ?>

