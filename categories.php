<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) header("Location: login.php");

include 'includes/header.php';
include 'includes/navbar.php';

$currentUserId = (int)$_SESSION['user_id'];
$message = '';
$error = '';

// Preset category suggestions with icons and colors
$presets = [
    ['name' => 'Electronics',    'icon' => 'fa-microchip',        'color' => '#3b82f6'],
    ['name' => 'Building',       'icon' => 'fa-helmet-safety',    'color' => '#f59e0b'],
    ['name' => 'Food & Beverage','icon' => 'fa-utensils',         'color' => '#10b981'],
    ['name' => 'Clothing',       'icon' => 'fa-shirt',            'color' => '#ec4899'],
    ['name' => 'Furniture',      'icon' => 'fa-couch',            'color' => '#8b5cf6'],
    ['name' => 'Tools',          'icon' => 'fa-wrench',           'color' => '#64748b'],
    ['name' => 'Medical',        'icon' => 'fa-kit-medical',      'color' => '#ef4444'],
    ['name' => 'Books',          'icon' => 'fa-book',             'color' => '#06b6d4'],
    ['name' => 'Automotive',     'icon' => 'fa-car',              'color' => '#f97316'],
    ['name' => 'Sports',         'icon' => 'fa-dumbbell',         'color' => '#84cc16'],
    ['name' => 'Stationery',     'icon' => 'fa-pen-ruler',        'color' => '#a78bfa'],
    ['name' => 'Chemicals',      'icon' => 'fa-flask',            'color' => '#14b8a6'],
];

$availableIcons = [
    'fa-tag','fa-microchip','fa-helmet-safety','fa-utensils','fa-shirt',
    'fa-couch','fa-wrench','fa-kit-medical','fa-book','fa-car','fa-dumbbell',
    'fa-pen-ruler','fa-flask','fa-box','fa-warehouse','fa-seedling',
    'fa-laptop','fa-tv','fa-mobile','fa-camera','fa-headphones',
    'fa-plug','fa-lightbulb','fa-paint-roller','fa-hammer','fa-screwdriver',
    'fa-truck','fa-plane','fa-ship','fa-bicycle','fa-baby',
    'fa-paw','fa-leaf','fa-gem','fa-ring','fa-gift',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'add_preset') {
        $name  = trim($_POST['category_name'] ?? '');
        $icon  = trim($_POST['category_icon'] ?? 'fa-tag');
        $color = trim($_POST['category_color'] ?? '#6366f1');
        $desc  = trim($_POST['description'] ?? '');

        if ($name === '') {
            $error = 'Category name is required.';
        } else {
            // Check duplicate for this user
            $dupStmt = $pdo->prepare("SELECT CategoryID FROM tblcategories WHERE UserID=? AND CategoryName=?");
            $dupStmt->execute([$currentUserId, $name]);
            if ($dupStmt->fetch()) {
                $error = "Category \"$name\" already exists.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO tblcategories (UserID, CategoryName, CategoryIcon, CategoryColor, Description) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$currentUserId, $name, $icon, $color, ($desc ?: null)]);
                $message = "Category \"$name\" added.";
            }
        }

    } elseif ($action === 'edit') {
        $id    = (int)($_POST['category_id'] ?? 0);
        $name  = trim($_POST['category_name'] ?? '');
        $icon  = trim($_POST['category_icon'] ?? 'fa-tag');
        $color = trim($_POST['category_color'] ?? '#6366f1');
        $desc  = trim($_POST['description'] ?? '');

        if ($id <= 0 || $name === '') {
            $error = 'Invalid category.';
        } else {
            $stmt = $pdo->prepare("UPDATE tblcategories SET CategoryName=?, CategoryIcon=?, CategoryColor=?, Description=? WHERE CategoryID=? AND UserID=?");
            $stmt->execute([$name, $icon, $color, ($desc ?: null), $id, $currentUserId]);
            $message = 'Category updated.';
        }

    } elseif ($action === 'delete') {
        $id = (int)($_POST['category_id'] ?? 0);
        if ($id > 0) {
            // Unlink products first
            $pdo->prepare("UPDATE tblproducts SET CategoryID=NULL WHERE CategoryID=? AND UserID=?")->execute([$id, $currentUserId]);
            $pdo->prepare("DELETE FROM tblcategories WHERE CategoryID=? AND UserID=?")->execute([$id, $currentUserId]);
            $message = 'Category deleted (products uncategorized).';
        }
    }
}

// Fetch categories with product counts
$catsStmt = $pdo->prepare("
    SELECT c.*, COUNT(p.ProductID) AS ProductCount
    FROM tblcategories c
    LEFT JOIN tblproducts p ON p.CategoryID = c.CategoryID AND p.UserID = c.UserID
    WHERE c.UserID = ?
    GROUP BY c.CategoryID
    ORDER BY c.CategoryName
");
$catsStmt->execute([$currentUserId]);
$categories = $catsStmt->fetchAll();
?>

<link rel="stylesheet" href="assets/categories.css">

<main class="categories-page">
    <div class="header-title page-title">
        <i class="fa-solid fa-layer-group"></i> Item Categories
    </div>
    <div class="subtitle">Define item types like Electronics, Building Materials, Food &amp; more</div>

    <?php if ($message): ?>
        <div class="notify"><?= e($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="login-error"><?= e($error) ?></div>
    <?php endif; ?>

    <!-- Quick-add Presets -->
    <div class="card preset-card" style="margin-top:18px;">
        <h3><i class="fa-solid fa-bolt"></i> Quick Add Preset Categories</h3>
        <p class="muted">Click a preset to instantly add it to your categories.</p>
        <div class="preset-grid">
            <?php foreach ($presets as $preset): ?>
                <form method="POST" class="preset-form">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="add_preset">
                    <input type="hidden" name="category_name" value="<?= e($preset['name']) ?>">
                    <input type="hidden" name="category_icon" value="<?= e($preset['icon']) ?>">
                    <input type="hidden" name="category_color" value="<?= e($preset['color']) ?>">
                    <input type="hidden" name="description" value="">
                    <button type="submit" class="preset-btn" style="--preset-color: <?= e($preset['color']) ?>">
                        <i class="fa-solid <?= e($preset['icon']) ?> preset-icon"></i>
                        <span><?= e($preset['name']) ?></span>
                    </button>
                </form>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add / Edit Form -->
    <div class="card" style="margin-top:18px;" id="categoryForm">
        <h3 id="formTitle"><i class="fa-solid fa-plus-circle"></i> Add Custom Category</h3>
        <form method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="action" id="catAction" value="add">
            <input type="hidden" name="category_id" id="catId" value="">

            <div class="form-grid">
                <div>
                    <label class="product-label">Category Name *</label>
                    <input class="product-input" type="text" name="category_name" id="catName" placeholder="e.g. Electronics" required>
                </div>
                <div>
                    <label class="product-label">Color</label>
                    <div class="color-pick-wrap">
                        <input type="color" name="category_color" id="catColor" value="#6366f1" class="color-input">
                        <span id="colorLabel" class="color-label">#6366f1</span>
                    </div>
                </div>
                <div class="span-2">
                    <label class="product-label">Description (optional)</label>
                    <input class="product-input" type="text" name="description" id="catDesc" placeholder="Short description of this category">
                </div>
            </div>

            <!-- Icon Picker -->
            <div style="margin-top:14px;">
                <label class="product-label">Icon</label>
                <input type="hidden" name="category_icon" id="catIcon" value="fa-tag">
                <div class="icon-picker" id="iconPicker">
                    <?php foreach ($availableIcons as $ico): ?>
                        <button type="button" class="icon-btn <?= $ico === 'fa-tag' ? 'selected' : '' ?>"
                            data-icon="<?= e($ico) ?>"
                            onclick="selectIcon('<?= e($ico) ?>', this)"
                            title="<?= e($ico) ?>">
                            <i class="fa-solid <?= e($ico) ?>"></i>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Live Preview -->
            <div class="preview-wrap">
                <div class="cat-badge-preview" id="badgePreview" style="background: #6366f1;">
                    <i class="fa-solid fa-tag" id="previewIcon"></i>
                    <span id="previewName">Category Name</span>
                </div>
            </div>

            <div class="inline-actions" style="margin-top:16px;">
                <button class="btn-primary btn-icon" type="submit">
                    <i class="fa-solid fa-save"></i> Save Category
                </button>
                <button class="btn-secondary btn-icon" type="button" onclick="resetForm()">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Categories Grid -->
    <div style="margin-top:24px;">
        <h3 class="section-title"><i class="fa-solid fa-list"></i> Your Categories (<?= count($categories) ?>)</h3>
        <?php if (empty($categories)): ?>
            <div class="card empty-state-card">
                <i class="fa-solid fa-layer-group empty-icon"></i>
                <p>No categories yet. Add presets above or create a custom one.</p>
            </div>
        <?php else: ?>
            <div class="categories-grid">
                <?php foreach ($categories as $cat): ?>
                    <div class="cat-card" style="--cat-color: <?= e($cat['CategoryColor']) ?>;">
                        <div class="cat-card-top">
                            <div class="cat-icon-wrap">
                                <i class="fa-solid <?= e($cat['CategoryIcon']) ?>"></i>
                            </div>
                            <div class="cat-info">
                                <div class="cat-name"><?= e($cat['CategoryName']) ?></div>
                                <?php if ($cat['Description']): ?>
                                    <div class="cat-desc"><?= e($cat['Description']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="cat-count">
                            <i class="fa-solid fa-box"></i>
                            <?= (int)$cat['ProductCount'] ?> product<?= $cat['ProductCount'] != 1 ? 's' : '' ?>
                        </div>
                        <div class="cat-actions">
                            <button class="btn-secondary btn-gap-sm" type="button"
                                onclick="editCategory(
                                    <?= (int)$cat['CategoryID'] ?>,
                                    '<?= addslashes(e($cat['CategoryName'])) ?>',
                                    '<?= addslashes(e($cat['CategoryIcon'])) ?>',
                                    '<?= addslashes(e($cat['CategoryColor'])) ?>',
                                    '<?= addslashes(e($cat['Description'])) ?>'
                                )">
                                <i class="fa-solid fa-edit"></i> Edit
                            </button>
                            <form method="POST" class="inline-form" onsubmit="return confirm('Delete this category? Products in it will be uncategorized.')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="category_id" value="<?= (int)$cat['CategoryID'] ?>">
                                <button type="submit" class="btn-secondary btn-danger btn-gap-sm">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
function selectIcon(icon, btn) {
    document.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('catIcon').value = icon;
    document.getElementById('previewIcon').className = 'fa-solid ' + icon;
}

document.getElementById('catColor').addEventListener('input', function() {
    const val = this.value;
    document.getElementById('colorLabel').textContent = val;
    document.getElementById('badgePreview').style.background = val;
});

document.getElementById('catName').addEventListener('input', function() {
    const v = this.value.trim();
    document.getElementById('previewName').textContent = v || 'Category Name';
});

function editCategory(id, name, icon, color, desc) {
    document.getElementById('catAction').value = 'edit';
    document.getElementById('catId').value = id;
    document.getElementById('catName').value = name;
    document.getElementById('catColor').value = color;
    document.getElementById('colorLabel').textContent = color;
    document.getElementById('catDesc').value = desc;
    document.getElementById('catIcon').value = icon;
    document.getElementById('badgePreview').style.background = color;
    document.getElementById('previewIcon').className = 'fa-solid ' + icon;
    document.getElementById('previewName').textContent = name;
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-edit"></i> Edit Category';

    // Highlight selected icon
    document.querySelectorAll('.icon-btn').forEach(b => {
        b.classList.toggle('selected', b.dataset.icon === icon);
    });
    document.getElementById('categoryForm').scrollIntoView({behavior: 'smooth'});
}

function resetForm() {
    document.getElementById('catAction').value = 'add';
    document.getElementById('catId').value = '';
    document.getElementById('catName').value = '';
    document.getElementById('catColor').value = '#6366f1';
    document.getElementById('colorLabel').textContent = '#6366f1';
    document.getElementById('catDesc').value = '';
    document.getElementById('catIcon').value = 'fa-tag';
    document.getElementById('badgePreview').style.background = '#6366f1';
    document.getElementById('previewIcon').className = 'fa-solid fa-tag';
    document.getElementById('previewName').textContent = 'Category Name';
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-plus-circle"></i> Add Custom Category';
    document.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('selected'));
    document.querySelector('[data-icon="fa-tag"]')?.classList.add('selected');
}
</script>

<?php include 'includes/footer.php'; ?>