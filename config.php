<?php
session_start();

$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_NAME') ?: 'inventory_db';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbCharset = getenv('DB_CHARSET') ?: 'utf8mb4';

$pdoOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $safeDbName = str_replace('`', '``', $dbName);
    $bootstrap = new PDO(
        "mysql:host={$dbHost};port={$dbPort};charset={$dbCharset}",
        $dbUser,
        $dbPass,
        $pdoOptions
    );

    $bootstrap->exec("CREATE DATABASE IF NOT EXISTS `{$safeDbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    $pdo = new PDO(
        "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset={$dbCharset}",
        $dbUser,
        $dbPass,
        $pdoOptions
    );

    // ── Legacy columns (keep for backward compat) ─────────────
    $pdo->exec("ALTER TABLE tblproducts ADD COLUMN IF NOT EXISTS UserID INT NULL");
    $pdo->exec("ALTER TABLE tblproducts ADD COLUMN IF NOT EXISTS Unit VARCHAR(24) NOT NULL DEFAULT 'pcs'");
    $pdo->exec("ALTER TABLE tblproducts ADD COLUMN IF NOT EXISTS Price DECIMAL(12,2) NOT NULL DEFAULT 0");
    $pdo->exec("ALTER TABLE tblproducts ADD COLUMN IF NOT EXISTS CategoryID INT NULL");
    $pdo->exec("ALTER TABLE tblproducts ADD INDEX IF NOT EXISTS idx_products_category (CategoryID)");

    // ── Companies (multi-tenant) ───────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tblcompanies (
            CompanyID   INT AUTO_INCREMENT PRIMARY KEY,
            CompanyName VARCHAR(120) NOT NULL,
            CreatedAt   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    // ── Users: add CompanyID + RoleID columns ──────────────────
    $pdo->exec("ALTER TABLE tblusers ADD COLUMN IF NOT EXISTS CompanyID INT NULL");
    $pdo->exec("ALTER TABLE tblusers ADD COLUMN IF NOT EXISTS RoleID    INT NOT NULL DEFAULT 1");
    $pdo->exec("ALTER TABLE tblusers ADD INDEX IF NOT EXISTS idx_users_company (CompanyID)");

    // ── Roles Table ──────────────────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tblroles (
            RoleID   INT PRIMARY KEY,
            RoleName VARCHAR(50) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    // Seed roles if empty
    $roleCount = $pdo->query("SELECT COUNT(*) FROM tblroles")->fetchColumn();
    if ($roleCount == 0) {
        $pdo->exec("INSERT INTO tblroles (RoleID, RoleName) VALUES
            (1, 'Admin'),
            (2, 'Manager'),
            (3, 'Staff'),
            (4, 'Customer')");
    } else {
        // Ensure role 3 and 4 exist if they were missing from a previous version
        $pdo->exec("INSERT IGNORE INTO tblroles (RoleID, RoleName) VALUES (2, 'Manager'), (3, 'Staff'), (4, 'Customer')");
        // Also update name of role 2 to Manager if it was Staff
        $pdo->exec("UPDATE tblroles SET RoleName='Manager' WHERE RoleID=2 AND RoleName='Staff'");
    }

    // ── Migrate existing admins: auto-create their company ─────
    //    Any user with CompanyID = NULL gets a company created for them
    $orphans = $pdo->query("SELECT UserID, FullName FROM tblusers WHERE CompanyID IS NULL")->fetchAll();
    foreach ($orphans as $u) {
        $pdo->prepare("INSERT INTO tblcompanies (CompanyName) VALUES (?)")
            ->execute([$u['FullName'] . "'s Company"]);
        $cid = (int)$pdo->lastInsertId();
        $pdo->prepare("UPDATE tblusers SET CompanyID=?, RoleID=1 WHERE UserID=?")
            ->execute([$cid, $u['UserID']]);
    }

    // ── CompanyID on all data tables ───────────────────────────
    $pdo->exec("ALTER TABLE tblproducts    ADD COLUMN IF NOT EXISTS CompanyID INT NULL");
    $pdo->exec("ALTER TABLE tblproducts    ADD INDEX  IF NOT EXISTS idx_prod_company (CompanyID)");

    // Migrate products: copy UserID→CompanyID via user lookup
    $pdo->exec("
        UPDATE tblproducts p
        JOIN tblusers u ON u.UserID = p.UserID
        SET p.CompanyID = u.CompanyID
        WHERE p.CompanyID IS NULL AND p.UserID IS NOT NULL
    ");

    // ── Categories ────────────────────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tblcategories (
            CategoryID    INT AUTO_INCREMENT PRIMARY KEY,
            UserID        INT NOT NULL,
            CompanyID     INT NULL,
            CategoryName  VARCHAR(100) NOT NULL,
            CategoryIcon  VARCHAR(60)  NOT NULL DEFAULT 'fa-tag',
            CategoryColor VARCHAR(20)  NOT NULL DEFAULT '#6366f1',
            Description   VARCHAR(255) NULL,
            CreatedAt     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_categories_user    (UserID),
            INDEX idx_categories_company (CompanyID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $pdo->exec("ALTER TABLE tblcategories ADD COLUMN IF NOT EXISTS CompanyID INT NULL");
    $pdo->exec("ALTER TABLE tblcategories ADD INDEX  IF NOT EXISTS idx_cat_company (CompanyID)");
    $pdo->exec("
        UPDATE tblcategories c
        JOIN tblusers u ON u.UserID = c.UserID
        SET c.CompanyID = u.CompanyID
        WHERE c.CompanyID IS NULL
    ");

    // ── Suppliers ─────────────────────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tblsuppliers (
            SupplierID    INT AUTO_INCREMENT PRIMARY KEY,
            UserID        INT NOT NULL,
            CompanyID     INT NULL,
            SupplierName  VARCHAR(120) NOT NULL,
            ContactPerson VARCHAR(120) NULL,
            Phone         VARCHAR(40)  NULL,
            Email         VARCHAR(120) NULL,
            Address       VARCHAR(255) NULL,
            CreatedAt     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_suppliers_user    (UserID),
            INDEX idx_suppliers_company (CompanyID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $pdo->exec("ALTER TABLE tblsuppliers ADD COLUMN IF NOT EXISTS CompanyID INT NULL");
    $pdo->exec("
        UPDATE tblsuppliers s
        JOIN tblusers u ON u.UserID = s.UserID
        SET s.CompanyID = u.CompanyID
        WHERE s.CompanyID IS NULL
    ");

    // ── Customers ─────────────────────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tblcustomers (
            CustomerID   INT AUTO_INCREMENT PRIMARY KEY,
            UserID       INT NOT NULL,
            CompanyID    INT NULL,
            CustomerName VARCHAR(120) NOT NULL,
            Phone        VARCHAR(40)  NULL,
            Email        VARCHAR(120) NULL,
            Address      VARCHAR(255) NULL,
            CreatedAt    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_customers_user    (UserID),
            INDEX idx_customers_company (CompanyID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $pdo->exec("ALTER TABLE tblcustomers ADD COLUMN IF NOT EXISTS CompanyID INT NULL");
    $pdo->exec("
        UPDATE tblcustomers c
        JOIN tblusers u ON u.UserID = c.UserID
        SET c.CompanyID = u.CompanyID
        WHERE c.CompanyID IS NULL
    ");

    // ── Sales Orders ──────────────────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tblsalesorders (
            SalesOrderID INT AUTO_INCREMENT PRIMARY KEY,
            UserID       INT NOT NULL,
            CompanyID    INT NULL,
            CustomerID   INT NULL,
            OrderDate    DATETIME NOT NULL,
            Status       VARCHAR(30) NOT NULL DEFAULT 'Draft',
            Notes        VARCHAR(255) NULL,
            TotalAmount  DECIMAL(12,2) NOT NULL DEFAULT 0,
            CreatedAt    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_sales_user    (UserID),
            INDEX idx_sales_company (CompanyID),
            INDEX idx_sales_customer(CustomerID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $pdo->exec("ALTER TABLE tblsalesorders ADD COLUMN IF NOT EXISTS CompanyID INT NULL");
    $pdo->exec("
        UPDATE tblsalesorders so
        JOIN tblusers u ON u.UserID = so.UserID
        SET so.CompanyID = u.CompanyID
        WHERE so.CompanyID IS NULL
    ");

    // ── Sales Order Items ─────────────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tblsalesorderitems (
            SalesOrderItemID INT AUTO_INCREMENT PRIMARY KEY,
            SalesOrderID     INT NOT NULL,
            ProductID        INT NOT NULL,
            Quantity         INT NOT NULL,
            UnitPrice        DECIMAL(12,2) NOT NULL DEFAULT 0,
            LineTotal        DECIMAL(12,2) NOT NULL DEFAULT 0,
            INDEX idx_so_items_order   (SalesOrderID),
            INDEX idx_so_items_product (ProductID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    // ── Purchase Orders ───────────────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tblpurchaseorders (
            PurchaseOrderID INT AUTO_INCREMENT PRIMARY KEY,
            UserID       INT NOT NULL,
            CompanyID    INT NULL,
            SupplierID   INT NULL,
            OrderDate    DATETIME NOT NULL,
            Status       VARCHAR(30) NOT NULL DEFAULT 'Draft',
            Notes        VARCHAR(255) NULL,
            TotalAmount  DECIMAL(12,2) NOT NULL DEFAULT 0,
            CreatedAt    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_po_user    (UserID),
            INDEX idx_po_company (CompanyID),
            INDEX idx_po_supplier(SupplierID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tblpurchaseorderitems (
            PurchaseOrderItemID INT AUTO_INCREMENT PRIMARY KEY,
            PurchaseOrderID  INT NOT NULL,
            ProductID        INT NOT NULL,
            Quantity         INT NOT NULL,
            UnitPrice        DECIMAL(12,2) NOT NULL DEFAULT 0,
            LineTotal        DECIMAL(12,2) NOT NULL DEFAULT 0,
            INDEX idx_po_items_order   (PurchaseOrderID),
            INDEX idx_po_items_product (ProductID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    // ── Stock Movements (Audit Trail) ──────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tblstockmovements (
            MovementID  INT AUTO_INCREMENT PRIMARY KEY,
            ProductID   INT NOT NULL,
            CompanyID   INT NOT NULL,
            UserID      INT NOT NULL,
            Type        VARCHAR(20) NOT NULL,
            Quantity    INT NOT NULL,
            Reason      VARCHAR(100) NULL,
            CreatedAt   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_move_product (ProductID),
            INDEX idx_move_company (CompanyID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    // ── Product Images ─────────────────────────────────────────
    $pdo->exec("ALTER TABLE tblproducts ADD COLUMN IF NOT EXISTS ImageURL VARCHAR(255) NULL");

} catch (PDOException $e) {
    die(
        'Database connection failed. Set DB_HOST, DB_NAME, DB_USER and DB_PASS in your environment, or create the database first.<br><br>' .
        htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
    );
}

// ══════════════════════════════════════════════════════════════
//  RBAC Helper Functions
// ══════════════════════════════════════════════════════════════

/**
 * Role constants
 */
define('ROLE_ADMIN',    1);
define('ROLE_MANAGER',  2);
define('ROLE_STAFF',    3);
define('ROLE_CUSTOMER', 4);

/**
 * Returns the current user's RoleID (integer).
 */
function currentRole(): int {
    return (int)($_SESSION['role_id'] ?? 0);
}

/**
 * Returns the current user's CompanyID.
 */
function companyId(): int {
    return (int)($_SESSION['company_id'] ?? 0);
}

/**
 * Check if the current user has one of the given role IDs.
 * @param int|int[] $roles
 */
function hasRole($roles): bool {
    $roles = is_array($roles) ? $roles : [$roles];
    return in_array(currentRole(), $roles, true);
}

/**
 * Redirect to dashboard (or login) if the user does NOT have one of the given roles.
 * Call at top of any restricted page.
 * @param int|int[] $roles
 * @param string $redirect  Where to send unauthorized users
 */
function requireRole($roles, string $redirect = 'dashboard.php'): void {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php"); exit;
    }
    if (!hasRole($roles)) {
        header("Location: $redirect"); exit;
    }
}

/**
 * Fine-grained permission check.
 * Returns true if the current role can perform $action.
 *
 * Permissions:
 *   product_add    | product_edit   | product_delete
 *   category_manage| supplier_manage| customer_manage
 *   order_create   | report_view
 *   user_manage    (admin only)
 *   portal_view    (customer only)
 */
function canDo(string $action): bool {
    $role = currentRole();
    $map  = [
        ROLE_ADMIN    => ['product_add','product_edit','product_delete',
                          'category_manage','supplier_manage','customer_manage',
                          'order_create','report_view','user_manage',
                          'inventory_adjust'],
        ROLE_MANAGER  => ['product_add','product_edit',
                          'category_manage','supplier_manage','customer_manage',
                          'order_create','report_view','inventory_adjust'],
        ROLE_STAFF    => ['inventory_adjust'],
        ROLE_CUSTOMER => ['portal_view'],
    ];
    return in_array($action, $map[$role] ?? [], true);
}

/**
 * HTML-escape helper.
 */
function e($str): string {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Role label for display.
 */
function roleName(int $roleId): string {
    switch ($roleId) {
        case ROLE_ADMIN:
            return 'Admin';
        case ROLE_MANAGER:
            return 'Manager';
        case ROLE_STAFF:
            return 'Inventory Staff';
        case ROLE_CUSTOMER:
            return 'Customer';
        default:
            return 'Unknown';
    }
}

/**
 * Role badge CSS class.
 */
function roleBadgeClass(int $roleId): string {
    switch ($roleId) {
        case ROLE_ADMIN:
            return 'role-admin';
        case ROLE_MANAGER:
            return 'role-manager';
        case ROLE_STAFF:
            return 'role-staff';
        case ROLE_CUSTOMER:
            return 'role-customer';
        default:
            return '';
    }
}

/**
 * CSRF Protection
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
            die("CSRF token validation failed.");
        }
    }
}

/**
 * Log Stock Movement
 */
function logStockMovement(int $productId, int $companyId, int $userId, string $type, int $quantity, string $reason): void {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO tblstockmovements (ProductID, CompanyID, UserID, Type, Quantity, Reason) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$productId, $companyId, $userId, $type, $quantity, $reason]);
}
?>
