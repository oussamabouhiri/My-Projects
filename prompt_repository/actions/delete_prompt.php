<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/index.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: ../public/dashboard.php?error=Invalid prompt ID");
    exit();
}

// Verify prompt exists and check ownership
$check = $pdo->prepare("SELECT user_id FROM prompts WHERE id = :id");
$check->execute(['id' => $id]);
$prompt = $check->fetch();

if (!$prompt) {
    header("Location: ../public/dashboard.php?error=Prompt not found");
    exit();
}

if ($_SESSION['role'] !== 'admin' && $prompt['user_id'] != $_SESSION['user_id']) {
    header("Location: ../public/dashboard.php?error=You can only delete your own prompts");
    exit();
}

$stmt = $pdo->prepare("DELETE FROM prompts WHERE id = :id");
$stmt->execute(['id' => $id]);

header("Location: ../public/dashboard.php?success=Prompt deleted successfully 🗑️");
exit();
?>