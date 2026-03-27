<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Empty check
    if (empty($email) || empty($password)) {
        header("Location: ../public/index.php?error=All fields are required");
        exit();
    }

    // Basic email format: must contain @ and a dot
    if (strpos($email, '@') === false || strpos($email, '.') === false) {
        header("Location: ../public/index.php?error=Invalid email format (must contain @ and a dot)");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role']      = $user['role'];

        header("Location: ../public/dashboard.php");
        exit();

    } else {
        header("Location: ../public/index.php?error=Invalid email or password");
        exit();
    }
}
?>