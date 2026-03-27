<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ── EMPTY CHECK ──────────────────────────────────────────
    if (empty($name) || empty($email) || empty($password)) {
        header("Location: ../public/registre.php?error=All fields are required");
        exit();
    }

    // ── EMAIL: must contain @ and a dot ──────────────────────
    if (strpos($email, '@') === false || strpos($email, '.') === false) {
        header("Location: ../public/registre.php?error=Email must contain @ and a dot (e.g. user@domain.com)");
        exit();
    }

    // ── PASSWORD: minimum length ──────────────────────────────
    if (strlen($password) < 6) {
        header("Location: ../public/registre.php?error=Password must be at least 6 characters long");
        exit();
    }

    // ── PASSWORD: uppercase letter ────────────────────────────
    if (!preg_match('/[A-Z]/', $password)) {
        header("Location: ../public/registre.php?error=Password must contain at least one uppercase letter (A-Z)");
        exit();
    }

    // ── PASSWORD: lowercase letter ────────────────────────────
    if (!preg_match('/[a-z]/', $password)) {
        header("Location: ../public/registre.php?error=Password must contain at least one lowercase letter (a-z)");
        exit();
    }

    // ── PASSWORD: digit ───────────────────────────────────────
    if (!preg_match('/[0-9]/', $password)) {
        header("Location: ../public/registre.php?error=Password must contain at least one number (0-9)");
        exit();
    }

    // ── PASSWORD: special symbol ──────────────────────────────
    if (!preg_match('/[@!#$%^&*()\-_=+\[\]{};:\'",.<>?\/\\\\|`~]/', $password)) {
        header("Location: ../public/registre.php?error=Password must contain at least one symbol (@, !, #, \$, etc.)");
        exit();
    }

    // ── HASH & INSERT ─────────────────────────────────────────
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare(
            "INSERT INTO users (name, email, password, role, created_at)
             VALUES (:name, :email, :password, 'user', NOW())"
        );
        $stmt->execute([
            'name'     => $name,
            'email'    => $email,
            'password' => $hashedPassword
        ]);

        header("Location: ../public/index.php?success=Account created! You can now login.");
        exit();

    } catch (PDOException $e) {
        header("Location: ../public/registre.php?error=This email is already registered");
        exit();
    }
}
?>