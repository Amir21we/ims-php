<?php
require 'config.php';
requireRole([ROLE_ADMIN, ROLE_MANAGER]);

include 'includes/header.php';
include 'includes/navbar.php';
echo '<link rel="stylesheet" href="assets/css/customers.css">';

$currentUserId = (int)$_SESSION['user_id'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if ($name === '') {
            $error = 'Customer name is required.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO tblcustomers (UserID, CustomerName, Phone, Email, Address) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$currentUserId, $name, ($phone ?: null), ($email ?: null), ($address ?: null)]);
            $message = 'Customer added.';
        }
    } elseif ($action === 'edit') {
        $id = (int)($_POST['customer_id'] ?? 0);
        $name = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if ($id <= 0 || $name === '') {
            $error = 'Invalid customer.';
        } else {
            $stmt = $pdo->prepare("UPDATE tblcustomers SET CustomerName=?, Phone=?, Email=?, Address=? WHERE CustomerID=? AND UserID=?");
            $stmt->execute([$name, ($phone ?: null), ($email ?: null), ($address ?: null), $id, $currentUserId]);
            $message = 'Customer updated.';
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['customer_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM tblcustomers WHERE CustomerID=? AND UserID=?");
            $stmt->execute([$id, $currentUserId]);
            $message = 'Customer deleted.';
        }
    }
}

$customersStmt = $pdo->prepare("SELECT * FROM tblcustomers WHERE UserID = ? ORDER BY CustomerName");
$customersStmt->execute([$currentUserId]);
$customers = $customersStmt->fetchAll();
?>

<main class="customers-page">
    <div class="header-title page-title">
        <i class="fa-solid fa-user-group"></i> Customers
    </div>
    <div class="subtitle">Manage customers for your account</div>

    <?php if ($message): ?>
        <div class="notify"><?= e($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="login-error"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="card" style="margin-top:18px;">
        <h3>Add / Edit Customer</h3>
        <form method="POST" id="customerForm">
            <?= csrf_field() ?>
            <input type="hidden" name="action" id="customerAction" value="add">
            <input type="hidden" name="customer_id" id="customerId" value="">

            <div class="form-grid">
                <div>
                    <label class="product-label">Customer Name</label>
                    <input class="product-input" type="text" name="customer_name" id="customerName" required>
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
                    <i class="fa-solid fa-save"></i> Save Customer
                </button>
                <button class="btn-secondary btn-icon" type="button" onclick="resetCustomerForm()">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </button>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top:18px;">
        <h3>Customers</h3>
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
                <?php foreach ($customers as $c): ?>
                    <tr>
                        <td><?= e($c['CustomerName']) ?></td>
                        <td><?= e($c['Phone']) ?></td>
                        <td><?= e($c['Email']) ?></td>
                        <td>
                            <div class="table-actions">
                                <button class="btn-secondary btn-gap-sm" type="button"
                                    onclick="editCustomer(
                                        <?= (int)$c['CustomerID'] ?>,
                                        '<?= addslashes(e($c['CustomerName'])) ?>',
                                        '<?= addslashes(e($c['Phone'])) ?>',
                                        '<?= addslashes(e($c['Email'])) ?>',
                                        '<?= addslashes(e($c['Address'])) ?>'
                                    )">
                                    <i class="fa-solid fa-edit"></i> Edit
                                </button>
                                <form method="POST" class="inline-form" onsubmit="return confirm('Delete this customer?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="customer_id" value="<?= (int)$c['CustomerID'] ?>">
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
function editCustomer(id, name, phone, email, address) {
    document.getElementById('customerAction').value = 'edit';
    document.getElementById('customerId').value = id;
    document.getElementById('customerName').value = name;
    document.getElementById('phone').value = phone;
    document.getElementById('email').value = email;
    document.getElementById('address').value = address;
    document.getElementById('customerForm').scrollIntoView({behavior: 'smooth'});
}
function resetCustomerForm() {
    document.getElementById('customerAction').value = 'add';
    document.getElementById('customerId').value = '';
    document.getElementById('customerName').value = '';
    document.getElementById('phone').value = '';
    document.getElementById('email').value = '';
    document.getElementById('address').value = '';
}
</script>

<?php include 'includes/footer.php'; ?>

