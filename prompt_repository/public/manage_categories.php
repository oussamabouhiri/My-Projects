<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

// Count prompts per category
$counts = [];
$rows   = $pdo->query(
    "SELECT category_id, COUNT(*) AS total FROM prompts GROUP BY category_id"
)->fetchAll();
foreach ($rows as $row) {
    $counts[$row['category_id']] = $row['total'];
}

// Edit mode?
$editCat = null;
if (isset($_GET['edit'])) {
    $id   = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $editCat = $stmt->fetch();
}
?>

<div class="container">

    <div class="page-header-row">
        <div>
            <h2 class="page-title">⚙️ Manage Categories</h2>
            <p class="page-subtitle">Add, edit or delete prompt categories</p>
        </div>
        <a href="dashboard.php" class="btn-back">← Dashboard</a>
    </div>

    <!-- ALERTS -->
    <?php if (isset($_GET['error'])): ?>
        <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['success'])): ?>
        <div class="success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <!-- ADD / EDIT FORM -->
    <div class="card card--form">
        <?php if ($editCat): ?>
            <h3>✏️ Edit Category</h3>
            <form action="../actions/category_action.php" method="POST" class="main-form">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?= $editCat['id'] ?>">
                <div class="input-row">
                    <input type="text" name="name"
                           value="<?= htmlspecialchars($editCat['name']) ?>" required>
                    <button type="submit">Update</button>
                    <a href="manage_categories.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        <?php else: ?>
            <h3>➕ Add New Category</h3>
            <form action="../actions/category_action.php" method="POST" class="main-form">
                <input type="hidden" name="action" value="add">
                <div class="input-row">
                    <input type="text" name="name"
                           placeholder="Category name (e.g. SQL, Marketing)" required>
                    <button type="submit">Add Category</button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <!-- CATEGORY LIST -->
    <div class="card">
        <div class="card-header-row">
            <h3>📂 All Categories</h3>
            <span class="result-count"><?= count($categories) ?> categor(ies)</span>
        </div>

        <?php if (empty($categories)): ?>
            <p class="empty">No categories yet. Add your first one above!</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Prompts</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $i => $cat): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><strong><?= htmlspecialchars($cat['name']) ?></strong></td>
                            <td>
                                <span class="badge"><?= $counts[$cat['id']] ?? 0 ?></span>
                            </td>
                            <td class="action-btns">
                                <a href="manage_categories.php?edit=<?= $cat['id'] ?>"
                                   class="btn-edit">✏️ Edit</a>
                                <a href="../actions/category_action.php?action=delete&id=<?= $cat['id'] ?>"
                                   class="btn-delete"
                                   onclick="return confirm('Delete this category?')">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>