<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prompt Repository</title>
    <link rel="stylesheet" href="/Prompt_Repository/assets/style.css">
</head>
<body>

<div class="navbar">
    <div class="logo">💡 PromptRepo</div>

    <?php if (isset($_SESSION['user_name'])): ?>
        <div class="nav-right">
            <span class="nav-user">👤 <?= htmlspecialchars($_SESSION['user_name']) ?></span>

            <a href="dashboard.php">Dashboard</a>
            <a href="add_prompt.php" class="nav-add-btn">➕ Add Prompt</a>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="manage_categories.php">⚙️ Categories</a>
            <?php endif; ?>

            <a href="logout.php" class="logout">Logout</a>
        </div>
    <?php endif; ?>
</div>

<div class="main-content"></div>