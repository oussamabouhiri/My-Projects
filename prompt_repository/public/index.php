<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Prompt Repository</title>
    <link rel="stylesheet" href="/Prompt_Repository/assets/style.css">
</head>
<body class="auth-body">

<div class="auth-card">
    <div class="auth-logo">💡</div>
    <h2>Welcome Back 👋</h2>
    <p>Login to access your Prompt Library</p>

    <?php if (isset($_GET['error'])): ?>
        <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <form action="../actions/login_action.php" method="POST">
        <div class="input-group">
            <label for="email">Email Address</label>
            <input type="text" id="email" name="email"
                   placeholder="you@example.com" required>
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="Your password" required>
        </div>
        <button type="submit">Login →</button>
    </form>

    <p class="link">
        Don't have an account?
        <a href="registre.php">Create one here</a>
    </p>
</div>

</body>
</html>