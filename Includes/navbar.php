<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$fullName    = $_SESSION['fullname'] ?? 'User';
$roleId      = (int)($_SESSION['role_id'] ?? ROLE_ADMIN);

$navLowStockCount = 0;
$navLowStockItems = [];
if (isset($pdo) && hasRole([ROLE_ADMIN, ROLE_MANAGER, ROLE_STAFF])) {
    $threshold = (int)($_SESSION['ui_settings']['low_stock_threshold'] ?? 10);
    $lsStmt = $pdo->prepare("SELECT ProductName, StockQuantity, Unit FROM tblproducts WHERE CompanyID = ? AND StockQuantity < ? ORDER BY StockQuantity ASC LIMIT 5");
    $lsStmt->execute([companyId(), $threshold]);
    $navLowStockItems = $lsStmt->fetchAll();
    
    $lsCountStmt = $pdo->prepare("SELECT COUNT(*) FROM tblproducts WHERE CompanyID = ? AND StockQuantity < ?");
    $lsCountStmt->execute([companyId(), $threshold]);
    $navLowStockCount = (int)$lsCountStmt->fetchColumn();
}
?>

<!-- ── Oracle NetSuite Styled Top Brand Header ── -->
<div class="netsuite-header">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="netsuite-logo">
        <span class="oracle-text">Megazen</span>
        <span class="brand-text">Track</span>
    </a>

    <!-- Center Search Bar (Triggers Command Palette on Focus) -->
    <div class="netsuite-search">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" placeholder="Search inventory, pages or actions... (Ctrl+K)" autocomplete="off">
    </div>

    <!-- Right Header Actions -->
    <div class="netsuite-header-actions">
        <!-- Quick Add / Shortcuts Icon -->
        <a href="#" class="netsuite-header-link" title="Quick Add" id="quickAddTrigger">
            <i class="fa-regular fa-file-lines" style="font-size: 15px;"></i>
        </a>

        <!-- Help Link -->
        <a href="#" class="netsuite-header-link" title="Help">
            <i class="fa-regular fa-question-circle" style="font-size: 15px;"></i>
            <span>Help</span>
        </a>

        <!-- Feedback Link -->
        <a href="mailto:support@megazentrack.com?subject=IMS%20Feedback" class="netsuite-header-link" title="Feedback">
            <i class="fa-regular fa-comment-dots" style="font-size: 15px;"></i>
            <span>Feedback</span>
        </a>

        <!-- Notifications Bell (Low stock alerts) -->
        <div class="notification-wrapper" style="position:relative; display: flex; align-items: center; height: 100%;">
            <a href="#" class="netsuite-header-link" title="Notifications" style="text-decoration:none;">
                <i class="fa-regular fa-bell" style="font-size: 15px;"></i>
                <?php if ($navLowStockCount > 0): ?>
                    <span class="netsuite-badge-count"><?= $navLowStockCount ?></span>
                <?php endif; ?>
            </a>
            
            <div class="notification-dropdown">
                <div style="padding:10px 16px; font-weight:bold; border-bottom:1px solid #eaecef; color:#333333;">
                     Notifications (<?= $navLowStockCount ?>)
                </div>
                <?php if (empty($navLowStockItems)): ?>
                    <div style="padding:16px; text-align:center; color:#777777; font-size:13px;">No new notifications.</div>
                <?php else: ?>
                    <?php foreach ($navLowStockItems as $item): ?>
                        <a href="product.php" class="notification-item">
                            <strong style="display:block; color:#333333; font-size: 12px;"><?= e($item['ProductName']) ?></strong>
                            <span style="color:#e05650; font-weight:bold; font-size: 11px;"><?= $item['StockQuantity'] ?> <?= e($item['Unit'] ?? 'pcs') ?></span> remaining
                        </a>
                    <?php endforeach; ?>
                    <?php if ($navLowStockCount > 5): ?>
                        <a href="dashboard.php" class="notification-item" style="text-align:center; color:#104f75; font-weight:bold;">
                            View all <?= $navLowStockCount ?> items
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- User profile block -->
        <div class="netsuite-user-block">
            <div class="netsuite-user-info">
                <span class="netsuite-user-name"><?= e($fullName) ?></span>
                <span class="netsuite-user-role">MegazenTrack – <?= e(roleName($roleId)) ?></span>
            </div>
            <div class="netsuite-user-avatar">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>
    </div>
</div>

<!-- ── Oracle NetSuite Styled Secondary Menu Navigation ── -->
<div class="netsuite-nav">
    <!-- Navigation Quick Icons -->
    <div class="netsuite-nav-icons">
        <a href="dashboard.php" class="netsuite-nav-icon-link" title="History"><i class="fa-solid fa-clock-rotate-left"></i></a>
        <a href="#" class="netsuite-nav-icon-link" title="Favorites" id="favLink"><i class="fa-regular fa-star"></i></a>
        <a href="dashboard.php" class="netsuite-nav-icon-link" title="Home"><i class="fa-solid fa-house"></i></a>
    </div>

    <!-- Navigation Menu Options (Aligned to PHP user permissions) -->
    <div class="netsuite-nav-links">
        <?php if (hasRole([ROLE_ADMIN, ROLE_MANAGER, ROLE_STAFF])): ?>
        <a href="dashboard.php" class="netsuite-nav-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
            Dashboard
        </a>
        <?php endif; ?>

        <?php if (hasRole([ROLE_ADMIN, ROLE_MANAGER, ROLE_STAFF])): ?>
        <a href="inventory.php" class="netsuite-nav-link <?= $currentPage === 'inventory.php' ? 'active' : '' ?>">
            Inventory
        </a>
        <?php endif; ?>

        <?php if (hasRole([ROLE_ADMIN, ROLE_MANAGER])): ?>
        <a href="product.php" class="netsuite-nav-link <?= $currentPage === 'product.php' ? 'active' : '' ?>">
            Products
        </a>
        <?php endif; ?>

        <?php if (hasRole([ROLE_ADMIN, ROLE_MANAGER])): ?>
        <a href="categories.php" class="netsuite-nav-link <?= $currentPage === 'categories.php' ? 'active' : '' ?>">
            Categories
        </a>
        <?php endif; ?>

        <?php if (hasRole([ROLE_ADMIN, ROLE_MANAGER])): ?>
        <a href="suppliers.php" class="netsuite-nav-link <?= $currentPage === 'suppliers.php' ? 'active' : '' ?>">
            Suppliers
        </a>
        <?php endif; ?>

        <?php if (hasRole([ROLE_ADMIN, ROLE_MANAGER])): ?>
        <a href="customers.php" class="netsuite-nav-link <?= $currentPage === 'customers.php' ? 'active' : '' ?>">
            Customers
        </a>
        <?php endif; ?>

        <?php if (hasRole([ROLE_ADMIN, ROLE_MANAGER])): ?>
        <a href="sales_orders.php" class="netsuite-nav-link <?= $currentPage === 'sales_orders.php' ? 'active' : '' ?>">
            Sales Orders
        </a>
        <?php endif; ?>

        <?php if (hasRole([ROLE_ADMIN, ROLE_MANAGER])): ?>
        <a href="purchase_orders.php" class="netsuite-nav-link <?= $currentPage === 'purchase_orders.php' ? 'active' : '' ?>">
            Purchase Orders
        </a>
        <?php endif; ?>

        <?php if (hasRole([ROLE_ADMIN, ROLE_MANAGER])): ?>
        <a href="stock_movements.php" class="netsuite-nav-link <?= $currentPage === 'stock_movements.php' ? 'active' : '' ?>">
            Audit Trail
        </a>
        <?php endif; ?>

        <?php if (hasRole(ROLE_ADMIN)): ?>
        <a href="manage_users.php" class="netsuite-nav-link <?= $currentPage === 'manage_users.php' ? 'active' : '' ?>">
            Manage Users
        </a>
        <?php endif; ?>

        <a href="logout.php" class="netsuite-nav-link" style="color: #fda4af;">
            <i class="fa-solid fa-arrow-right-from-bracket" style="margin-right: 6px;"></i> Logout
        </a>
    </div>
</div>

<!-- ── Spotlight Command Palette Modal ────────────────────── -->
<div class="spotlight-modal" id="spotlightModal">
    <div class="spotlight-content">
        <div class="spotlight-header">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" class="spotlight-input" placeholder="Search commands, pages, or actions... (Ctrl+K)" id="spotlightInput" autocomplete="off">
            <span class="spotlight-shortcut">ESC</span>
        </div>
        <div class="spotlight-results" id="spotlightResults">
            <div class="spotlight-group-title">Navigation</div>
            <a href="dashboard.php" class="spotlight-item selected"><i class="fa-solid fa-chart-pie"></i> Go to Dashboard <span class="spotlight-item-badge">Page</span></a>
            <a href="inventory.php" class="spotlight-item"><i class="fa-solid fa-warehouse"></i> Go to Inventory <span class="spotlight-item-badge">Page</span></a>
            <?php if (hasRole([ROLE_ADMIN, ROLE_MANAGER])): ?>
            <a href="product.php" class="spotlight-item"><i class="fa-solid fa-cube"></i> Go to Products <span class="spotlight-item-badge">Page</span></a>
            <a href="categories.php" class="spotlight-item"><i class="fa-solid fa-tags"></i> Go to Categories <span class="spotlight-item-badge">Page</span></a>
            <a href="suppliers.php" class="spotlight-item"><i class="fa-solid fa-truck"></i> Go to Suppliers <span class="spotlight-item-badge">Page</span></a>
            <a href="customers.php" class="spotlight-item"><i class="fa-solid fa-users"></i> Go to Customers <span class="spotlight-item-badge">Page</span></a>
            <a href="sales_orders.php" class="spotlight-item"><i class="fa-solid fa-receipt"></i> Go to Sales Orders <span class="spotlight-item-badge">Page</span></a>
            <a href="purchase_orders.php" class="spotlight-item"><i class="fa-solid fa-file-invoice-dollar"></i> Go to Purchase Orders <span class="spotlight-item-badge">Page</span></a>
            <a href="stock_movements.php" class="spotlight-item"><i class="fa-solid fa-clock-rotate-left"></i> Go to Audit Trail <span class="spotlight-item-badge">Page</span></a>
            <?php endif; ?>
            <?php if (hasRole(ROLE_ADMIN)): ?>
            <a href="manage_users.php" class="spotlight-item"><i class="fa-solid fa-users-gear"></i> Go to Manage Users <span class="spotlight-item-badge">Page</span></a>
            <?php endif; ?>

            <div class="spotlight-group-title">Quick Actions</div>
            <?php if (hasRole([ROLE_ADMIN, ROLE_MANAGER])): ?>
            <a href="product.php?add=1" class="spotlight-item"><i class="fa-solid fa-plus"></i> Add New Product <span class="spotlight-item-badge">Action</span></a>
            <a href="sales_orders.php?add=1" class="spotlight-item"><i class="fa-solid fa-receipt"></i> Create Sales Order <span class="spotlight-item-badge">Action</span></a>
            <?php endif; ?>
            <a href="dashboard.php#report_type" class="spotlight-item"><i class="fa-solid fa-download"></i> Generate Reports <span class="spotlight-item-badge">Action</span></a>
            <a href="logout.php" class="spotlight-item"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout <span class="spotlight-item-badge">System</span></a>
        </div>
    </div>
</div>

<!-- ── Legacy compatibility elements (hidden but kept) ── -->
<nav style="display:none;"></nav>
<div class="topbar" style="display:none;"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const spotlightModal = document.getElementById('spotlightModal');
    const spotlightInput = document.getElementById('spotlightInput');
    const spotlightItems = document.querySelectorAll('.spotlight-item');
    
    // Toggle Spotlight command palette
    function toggleSpotlight(show) {
        if (show) {
            spotlightModal.classList.add('active');
            spotlightInput.focus();
            spotlightInput.value = '';
            filterSpotlightItems();
        } else {
            spotlightModal.classList.remove('active');
        }
    }
    
    // Ctrl+K key binding
    window.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            toggleSpotlight(true);
        }
        if (e.key === 'Escape') {
            toggleSpotlight(false);
        }
    });

    // Clicks on brand search input
    const netsuiteSearchInput = document.querySelector('.netsuite-search input');
    if (netsuiteSearchInput) {
        netsuiteSearchInput.addEventListener('click', (e) => {
            e.preventDefault();
            toggleSpotlight(true);
        });
        netsuiteSearchInput.addEventListener('focus', (e) => {
            e.preventDefault();
            toggleSpotlight(true);
            netsuiteSearchInput.blur();
        });
    }

    // Modal click out closing
    spotlightModal.addEventListener('click', (e) => {
        if (e.target === spotlightModal) {
            toggleSpotlight(false);
        }
    });

    // Spotlight search filtering
    spotlightInput.addEventListener('input', filterSpotlightItems);

    function filterSpotlightItems() {
        const query = spotlightInput.value.toLowerCase();
        let firstVisible = null;
        
        spotlightItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(query)) {
                item.style.display = 'flex';
                item.classList.remove('selected');
                if (!firstVisible) {
                    firstVisible = item;
                }
            } else {
                item.style.display = 'none';
            }
        });

        if (firstVisible) {
            firstVisible.classList.add('selected');
        }
    }

    // Arrow Key Navigation
    spotlightInput.addEventListener('keydown', (e) => {
        const visibleItems = Array.from(spotlightItems).filter(item => item.style.display !== 'none');
        if (visibleItems.length === 0) return;

        let currentIndex = visibleItems.findIndex(item => item.classList.contains('selected'));

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (currentIndex !== -1) visibleItems[currentIndex].classList.remove('selected');
            const nextIndex = (currentIndex + 1) % visibleItems.length;
            visibleItems[nextIndex].classList.add('selected');
            visibleItems[nextIndex].scrollIntoView({ block: 'nearest' });
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (currentIndex !== -1) visibleItems[currentIndex].classList.remove('selected');
            const prevIndex = (currentIndex - 1 + visibleItems.length) % visibleItems.length;
            visibleItems[prevIndex].classList.add('selected');
            visibleItems[prevIndex].scrollIntoView({ block: 'nearest' });
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentIndex !== -1) {
                visibleItems[currentIndex].click();
            }
        }
    });

    // Favorites & Quick Add UI reactions
    const favLink = document.getElementById('favLink');
    if (favLink) {
        favLink.addEventListener('click', (e) => {
            e.preventDefault();
            const star = favLink.querySelector('i');
            if (star.classList.contains('fa-regular')) {
                star.classList.replace('fa-regular', 'fa-solid');
                star.style.color = '#fbbf24';
            } else {
                star.classList.replace('fa-solid', 'fa-regular');
                star.style.color = '';
            }
        });
    }

    const quickAddTrigger = document.getElementById('quickAddTrigger');
    if (quickAddTrigger) {
        quickAddTrigger.addEventListener('click', (e) => {
            e.preventDefault();
            toggleSpotlight(true);
        });
    }
});
</script>
