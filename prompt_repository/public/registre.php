<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Prompt Repository</title>
    <link rel="stylesheet" href="/Prompt_Repository/assets/style.css">
</head>
<body class="auth-body">

<div class="auth-card">
    <div class="auth-logo">💡</div>
    <h2>Create Account</h2>
    <p>Join the PromptRepo community today</p>

    <?php if (isset($_GET['error'])): ?>
        <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <form action="../actions/registre_action.php" method="POST">
        <div class="input-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name"
                   placeholder="John Doe" required>
        </div>
        <div class="input-group">
            <label for="email">Email Address</label>
            <input type="text" id="email" name="email"
                   placeholder="you@example.com" required>
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="Create a strong password" required>
        </div>

        <div class="password-hints">
            <span>✓ Min. 6 chars</span>
            <span>✓ Uppercase</span>
            <span>✓ Lowercase</span>
            <span>✓ Number</span>
            <span>✓ Symbol (@!#…)</span>
        </div>

        <button type="submit">Create Account →</button>
    </form>

    <p class="link">
        Already have an account?
        <a href="index.php">Login here</a>
    </p>
</div>

</body>
</html>