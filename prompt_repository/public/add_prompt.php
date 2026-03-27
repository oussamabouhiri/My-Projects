<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
require_once '../includes/header.php';

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="container">

    <div class="page-header-row">
        <div>
            <h2 class="page-title">➕ Add New Prompt</h2>
            <p class="page-subtitle">Save a tested &amp; approved prompt to the shared library</p>
        </div>
        <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <div class="card card--form">
        <form action="../actions/add_prompt.php" method="POST" class="main-form">

            <div class="form-group">
                <label for="title">📝 Prompt Title</label>
                <input type="text" id="title" name="title"
                       placeholder="e.g. Generate a REST API with authentication"
                       required>
            </div>

            <div class="form-group">
                <label for="category_id">🏷️ Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">— Select a category —</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="content">💬 Prompt Content</label>
                <textarea id="content" name="content"
                          placeholder="Write your full prompt here…"
                          required></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">💾 Save Prompt</button>
                <a href="dashboard.php" class="btn-cancel">Cancel</a>
            </div>

        </form>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>