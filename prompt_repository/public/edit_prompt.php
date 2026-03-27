<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
require_once '../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: dashboard.php");
    exit();
}

// Fetch the prompt
$stmt = $pdo->prepare(
    "SELECT prompts.*, categories.name AS category_name
     FROM prompts
     INNER JOIN categories ON prompts.category_id = categories.id
     WHERE prompts.id = :id"
);
$stmt->execute(['id' => $id]);
$prompt = $stmt->fetch();

if (!$prompt) {
    header("Location: dashboard.php?error=Prompt not found");
    exit();
}

// Only owner or admin can edit
if ($_SESSION['role'] !== 'admin' && $prompt['user_id'] != $_SESSION['user_id']) {
    header("Location: dashboard.php?error=You can only edit your own prompts");
    exit();
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="container">

    <div class="page-header-row">
        <div>
            <h2 class="page-title">✏️ Edit Prompt</h2>
            <p class="page-subtitle">Update the title, category or content of your prompt</p>
        </div>
        <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <div class="card card--form">
        <form action="../actions/edit_prompt.php" method="POST" class="main-form">
            <input type="hidden" name="id" value="<?= $prompt['id'] ?>">

            <div class="form-group">
                <label for="title">📝 Prompt Title</label>
                <input type="text" id="title" name="title"
                       value="<?= htmlspecialchars($prompt['title']) ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="category_id">🏷️ Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">— Select a category —</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= $cat['id'] == $prompt['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="content">💬 Prompt Content</label>
                <textarea id="content" name="content"
                          required><?= htmlspecialchars($prompt['content']) ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">💾 Update Prompt</button>
                <a href="dashboard.php" class="btn-cancel">Cancel</a>
            </div>

        </form>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>