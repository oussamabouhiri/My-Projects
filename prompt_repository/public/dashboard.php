<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
require_once '../includes/header.php';

$isAdmin = ($_SESSION['role'] === 'admin');

// ── CATEGORIES ────────────────────────────────────────────────
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

// ── FILTERS ───────────────────────────────────────────────────
$filter_cat   = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$filter_user  = isset($_GET['user_id'])     ? (int)$_GET['user_id']     : 0;
$search       = isset($_GET['search'])      ? trim($_GET['search'])      : '';

// ── USERS LIST (admin filter) ─────────────────────────────────
$allUsers = [];
if ($isAdmin) {
    $allUsers = $pdo->query(
        "SELECT id, name FROM users WHERE role = 'user' ORDER BY name ASC"
    )->fetchAll();
}

// ── PROMPTS QUERY (all users see all prompts) ─────────────────
$sql = "SELECT prompts.*, users.name AS author, users.id AS author_id,
               categories.name AS category
        FROM prompts
        INNER JOIN users      ON prompts.user_id      = users.id
        INNER JOIN categories ON prompts.category_id  = categories.id
        WHERE 1=1";

$params = [];

if ($filter_cat > 0) {
    $sql .= " AND prompts.category_id = :cat";
    $params['cat'] = $filter_cat;
}

if ($isAdmin && $filter_user > 0) {
    $sql .= " AND prompts.user_id = :uid";
    $params['uid'] = $filter_user;
}

if (!empty($search)) {
    $sql .= " AND (prompts.title LIKE :s1 OR prompts.content LIKE :s2)";
    $params['s1'] = '%' . $search . '%';
    $params['s2'] = '%' . $search . '%';
}

$sql .= " ORDER BY prompts.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$prompts = $stmt->fetchAll();

// ── STATS ─────────────────────────────────────────────────────
$totalPrompts = $pdo->query("SELECT COUNT(*) FROM prompts")->fetchColumn();
$totalCats    = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

if ($isAdmin) {
    $totalUsers = $pdo->query(
        "SELECT COUNT(*) FROM users WHERE role = 'user'"
    )->fetchColumn();

    $contributors = $pdo->query(
        "SELECT users.name, users.email, COUNT(prompts.id) AS prompt_count
         FROM users
         LEFT JOIN prompts ON users.id = prompts.user_id
         WHERE users.role = 'user'
         GROUP BY users.id, users.name, users.email
         ORDER BY prompt_count DESC"
    )->fetchAll();
} else {
    $myCount = $pdo->prepare("SELECT COUNT(*) FROM prompts WHERE user_id = :uid");
    $myCount->execute(['uid' => $_SESSION['user_id']]);
    $myTotal = $myCount->fetchColumn();
}
?>

<div class="container">

<?php if ($isAdmin): ?>
<!-- ═══════════════════════════ ADMIN VIEW ═══════════════════════════ -->

    <div class="page-header-row">
        <div>
            <h2 class="page-title">🛡️ Admin Dashboard</h2>
            <p class="page-subtitle">Full overview of the Prompt Repository</p>
        </div>
        <a href="add_prompt.php" class="btn-add-large">➕ Add Prompt</a>
    </div>

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-number"><?= $totalPrompts ?></div>
            <div class="stat-label">Total Prompts</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-number"><?= $totalUsers ?></div>
            <div class="stat-label">Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏷️</div>
            <div class="stat-number"><?= $totalCats ?></div>
            <div class="stat-label">Categories</div>
        </div>
    </div>

    <!-- TOP CONTRIBUTORS -->
    <div class="card">
        <h3>👑 Top Contributors</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Prompts</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contributors)): ?>
                    <tr><td colspan="4" class="empty">No users yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($contributors as $i => $u): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <?php if ($i === 0): ?>
                                    🥇
                                <?php elseif ($i === 1): ?>
                                    🥈
                                <?php elseif ($i === 2): ?>
                                    🥉
                                <?php endif; ?>
                                <?= htmlspecialchars($u['name']) ?>
                            </td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><span class="badge"><?= $u['prompt_count'] ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ADVANCED FILTER BAR -->
    <div class="card">
        <div class="card-header-row">
            <h3>📚 All Prompts
                <span class="result-count"><?= count($prompts) ?> result(s)</span>
            </h3>
        </div>

        <form action="dashboard.php" method="GET" class="filter-bar">
            <div class="filter-item">
                <input type="text" name="search" class="filter-input"
                       placeholder="🔍 Search title or content…"
                       value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="filter-item">
                <select name="category_id" class="filter-select">
                    <option value="0">🏷️ All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= $filter_cat == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item">
                <select name="user_id" class="filter-select">
                    <option value="0">👤 All Authors</option>
                    <?php foreach ($allUsers as $u): ?>
                        <option value="<?= $u['id'] ?>"
                            <?= $filter_user == $u['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="filter-btn">Filter</button>
            <?php if ($filter_cat || $filter_user || $search): ?>
                <a href="dashboard.php" class="filter-reset">✕ Clear</a>
            <?php endif; ?>
        </form>

        <?php if (empty($prompts)): ?>
            <p class="empty">No prompts match your filters.</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prompts as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <strong><?= htmlspecialchars($p['title']) ?></strong>
                                <div class="content-preview">
                                    <?= htmlspecialchars(substr($p['content'], 0, 90)) ?>…
                                </div>
                            </td>
                            <td><span class="tag"><?= htmlspecialchars($p['category']) ?></span></td>
                            <td>👤 <?= htmlspecialchars($p['author']) ?></td>
                            <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                            <td class="action-btns">
                                <a href="edit_prompt.php?id=<?= $p['id'] ?>"
                                   class="btn-edit">✏️ Edit</a>
                                <a href="../actions/delete_prompt.php?id=<?= $p['id'] ?>"
                                   class="btn-delete"
                                   onclick="return confirm('Delete this prompt?')">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?php else: ?>
<!-- ═══════════════════════════ USER VIEW ════════════════════════════ -->

    <div class="page-header-row">
        <div>
            <h2 class="page-title">👋 Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></h2>
            <p class="page-subtitle">Browse the team's prompt library</p>
        </div>
        <a href="add_prompt.php" class="btn-add-large">➕ Add Prompt</a>
    </div>

    <!-- MINI STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📚</div>
            <div class="stat-number"><?= $totalPrompts ?></div>
            <div class="stat-label">Library Prompts</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✍️</div>
            <div class="stat-number"><?= $myTotal ?></div>
            <div class="stat-label">My Contributions</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏷️</div>
            <div class="stat-number"><?= $totalCats ?></div>
            <div class="stat-label">Categories</div>
        </div>
    </div>

    <!-- ALERTS -->
    <?php if (isset($_GET['error'])): ?>
        <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['success'])): ?>
        <div class="success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <!-- FILTER BAR -->
    <div class="card">
        <div class="card-header-row">
            <h3>📋 All Prompts
                <span class="result-count"><?= count($prompts) ?> result(s)</span>
            </h3>
        </div>

        <form action="dashboard.php" method="GET" class="filter-bar">
            <div class="filter-item">
                <input type="text" name="search" class="filter-input"
                       placeholder="🔍 Search title or content…"
                       value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="filter-item">
                <select name="category_id" class="filter-select">
                    <option value="0">🏷️ All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= $filter_cat == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="filter-btn">Filter</button>
            <?php if ($filter_cat || $search): ?>
                <a href="dashboard.php" class="filter-reset">✕ Clear</a>
            <?php endif; ?>
        </form>

        <!-- PROMPTS LIST -->
        <?php if (empty($prompts)): ?>
            <p class="empty">No prompts match your search. Be the first to add one! 🚀</p>
        <?php else: ?>
            <div class="prompt-list">
                <?php foreach ($prompts as $p): ?>
                    <?php $isOwner = ($p['author_id'] == $_SESSION['user_id']); ?>
                    <div class="prompt-card <?= $isOwner ? 'prompt-card--own' : '' ?>">
                        <div class="prompt-card-header">
                            <div class="prompt-card-title-wrap">
                                <h4 class="prompt-card-title">
                                    <?= htmlspecialchars($p['title']) ?>
                                </h4>
                                <span class="tag"><?= htmlspecialchars($p['category']) ?></span>
                                <?php if ($isOwner): ?>
                                    <span class="own-badge">✨ Mine</span>
                                <?php endif; ?>
                            </div>
                            <?php if ($isOwner): ?>
                                <div class="action-btns">
                                    <a href="edit_prompt.php?id=<?= $p['id'] ?>"
                                       class="btn-edit">✏️ Edit</a>
                                    <a href="../actions/delete_prompt.php?id=<?= $p['id'] ?>"
                                       class="btn-delete"
                                       onclick="return confirm('Delete this prompt?')">🗑️ Delete</a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <p class="prompt-card-content">
                            <?= htmlspecialchars($p['content']) ?>
                        </p>
                        <div class="prompt-card-footer">
                            <span>👤 <?= htmlspecialchars($p['author']) ?></span>
                            <span>📅 <?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php endif; ?>

</div><!-- /container -->

<?php require_once '../includes/footer.php'; ?>