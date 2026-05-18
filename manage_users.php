<?php
require 'config.php';
requireRole(ROLE_ADMIN);

include 'includes/header.php';
include 'includes/navbar.php';
echo '<link rel="stylesheet" href="assets/css/manage_users.css">';

$cid     = companyId();
$message = '';
$error   = '';

// ── Handle POST ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'add_user') {
        $fullName = trim($_POST['fullname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $roleId   = (int)($_POST['role_id'] ?? ROLE_STAFF);

        // Only allow creating Manager or Staff from this panel
        if (!in_array($roleId, [ROLE_MANAGER, ROLE_STAFF, ROLE_CUSTOMER], true)) {
            $roleId = ROLE_STAFF;
        }

        if ($fullName === '' || $username === '' || strlen($password) < 6) {
            $error = 'Full name, username, and a password of at least 6 characters are required.';
        } else {
            $chk = $pdo->prepare("SELECT UserID FROM tblusers WHERE UserName = ?");
            $chk->execute([$username]);
            if ($chk->fetch()) {
                $error = 'Username already taken.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $pdo->prepare("INSERT INTO tblusers (FullName, UserName, Password, RoleID, CompanyID) VALUES (?, ?, ?, ?, ?)")
                    ->execute([$fullName, $username, $hash, $roleId, $cid]);
                $message = "User '$fullName' added successfully.";
            }
        }

    } elseif ($action === 'delete_user') {
        $uid = (int)($_POST['user_id'] ?? 0);
        // Safety: can only delete users in same company, not yourself
        if ($uid > 0 && $uid !== (int)$_SESSION['user_id']) {
            $pdo->prepare("DELETE FROM tblusers WHERE UserID = ? AND CompanyID = ? AND RoleID != 1")
                ->execute([$uid, $cid]);
            $message = 'User removed.';
        }

    } elseif ($action === 'change_role') {
        $uid    = (int)($_POST['user_id'] ?? 0);
        $newRole = (int)($_POST['role_id'] ?? ROLE_STAFF);
        if ($uid > 0 && in_array($newRole, [ROLE_MANAGER, ROLE_STAFF, ROLE_CUSTOMER], true)) {
            $pdo->prepare("UPDATE tblusers SET RoleID = ? WHERE UserID = ? AND CompanyID = ? AND RoleID != 1")
                ->execute([$newRole, $uid, $cid]);
            $message = 'Role updated.';
        }
    }
}

// ── Fetch company info ─────────────────────────────────────────
$company = $pdo->prepare("SELECT CompanyName FROM tblcompanies WHERE CompanyID = ?");
$company->execute([$cid]);
$company = $company->fetch();

// ── Fetch all staff users in this company (excluding self) ─────
$users = $pdo->prepare("
    SELECT UserID, FullName, UserName, RoleID
    FROM tblusers
    WHERE CompanyID = ?
    ORDER BY RoleID ASC, FullName ASC
");
$users->execute([$cid]);
$users = $users->fetchAll();
?>

<main class="mu-page">
    <div class="header-title">
        <i class="fa-solid fa-users-gear"></i> Manage Users
    </div>
    <div class="subtitle">Staff accounts under <?= e($company['CompanyName'] ?? 'your company') ?></div>

    <?php if ($message): ?>
        <div class="notify"><i class="fa-solid fa-circle-check"></i> <?= e($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="login-error"><i class="fa-solid fa-circle-exclamation"></i> <?= e($error) ?></div>
    <?php endif; ?>

    <div class="mu-grid">

        <!-- Add User Form -->
        <div class="card">
            <h3 class="mu-section-title">
                <i class="fa-solid fa-user-plus"></i> Add Staff Member
            </h3>
            <style>
                /* Remove browser autofill styling */
                .mu-form input:-webkit-autofill {
                    -webkit-box-shadow: 0 0 0 30px white inset !important;
                }
            </style>
            <form method="POST" class="mu-form" autocomplete="off">
                <!-- Honeypot fields to trap browser autofill -->
                <input type="text" style="display:none">
                <input type="password" style="display:none">
                
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="add_user">

                <div class="mu-field">
                    <label class="product-label">Full Name</label>
                    <input type="text" name="fullname" class="product-input" placeholder="Enter full name" required autocomplete="none">
                </div>
                <div class="mu-field">
                    <label class="product-label">Username</label>
                    <input type="text" name="username" class="product-input" placeholder="Create username" required autocomplete="none">
                </div>
                <div class="mu-field">
                    <label class="product-label">Password</label>
                    <input type="password" name="password" class="product-input" placeholder="min. 6 characters" required autocomplete="new-password">
                </div>
                <div class="mu-field">
                    <label class="product-label">Role</label>
                    <select name="role_id" class="product-input">
                        <option value="<?= ROLE_MANAGER ?>">Manager</option>
                        <option value="<?= ROLE_STAFF ?>" selected>Inventory Staff</option>
                        <option value="<?= ROLE_CUSTOMER ?>">Customer (Portal)</option>
                    </select>
                </div>

                <!-- Role guide -->
                <div class="role-guide">
                    <div class="rg-item rg-manager">
                        <strong><i class="fa-solid fa-briefcase"></i> Manager</strong>
                        <span>Products, orders, customers, reports</span>
                    </div>
                    <div class="rg-item rg-staff">
                        <strong><i class="fa-solid fa-screwdriver-wrench"></i> Inventory Staff</strong>
                        <span>Stock adjustments only</span>
                    </div>
                    <div class="rg-item rg-customer">
                        <strong><i class="fa-solid fa-user"></i> Customer (Portal)</strong>
                        <span>View their own orders online</span>
                    </div>
                </div>

                <button type="submit" class="btn-primary btn-icon" style="margin-top:16px;">
                    <i class="fa-solid fa-plus"></i> Add User
                </button>
            </form>
        </div>

        <!-- Current Users Table -->
        <div class="card" style="padding:0; overflow:hidden;">
            <div style="padding:20px 24px 14px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between;">
                <h3 style="margin:0; font-size:16px; font-weight:700;">Staff Members</h3>
                <span class="mu-count"><?= count($users) ?> users</span>
            </div>

            <table class="mu-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u):
                        $isSelf   = ((int)$u['UserID'] === (int)$_SESSION['user_id']);
                        $isAdmin  = ((int)$u['RoleID'] === ROLE_ADMIN);
                    ?>
                    <tr>
                        <td>
                            <div class="mu-user-cell">
                                <div class="mu-avatar mu-avatar-<?= (int)$u['RoleID'] ?>">
                                    <?= strtoupper(substr($u['FullName'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="mu-name"><?= e($u['FullName']) ?></div>
                                    <?php if ($isSelf): ?>
                                        <span class="mu-self-tag">You</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="mu-username">@<?= e($u['UserName']) ?></td>
                        <td>
                            <?php if ($isAdmin || $isSelf): ?>
                                <span class="mu-role-badge role-admin"><?= e(roleName((int)$u['RoleID'])) ?></span>
                            <?php else: ?>
                                <form method="POST" class="inline-form">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="change_role">
                                    <input type="hidden" name="user_id" value="<?= (int)$u['UserID'] ?>">
                                    <select name="role_id" class="mu-role-select"
                                        onchange="this.form.submit()">
                                        <option value="<?= ROLE_MANAGER ?>"  <?= $u['RoleID'] == ROLE_MANAGER  ? 'selected' : '' ?>>Manager</option>
                                        <option value="<?= ROLE_STAFF ?>"    <?= $u['RoleID'] == ROLE_STAFF    ? 'selected' : '' ?>>Inventory Staff</option>
                                        <option value="<?= ROLE_CUSTOMER ?>" <?= $u['RoleID'] == ROLE_CUSTOMER ? 'selected' : '' ?>>Customer (Portal)</option>
                                    </select>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$isAdmin && !$isSelf): ?>
                                <form method="POST" class="inline-form"
                                      onsubmit="return confirm('Remove <?= e($u['FullName']) ?>?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="delete_user">
                                    <input type="hidden" name="user_id" value="<?= (int)$u['UserID'] ?>">
                                    <button type="submit" class="btn-danger btn-gap-sm">
                                        <i class="fa-solid fa-trash"></i> Remove
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="mu-protected"><i class="fa-solid fa-lock"></i> Protected</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="4" class="empty-state">No staff members yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
