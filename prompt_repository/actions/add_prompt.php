<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../public/index.php");
        exit();
    }

    $title       = trim($_POST['title']);
    $content     = trim($_POST['content']);
    $category_id = (int)($_POST['category_id'] ?? 0);

    if (empty($title) || empty($content) || $category_id <= 0) {
        header("Location: ../public/add_prompt.php?error=All fields are required");
        exit();
    }

    $stmt = $pdo->prepare(
        "INSERT INTO prompts (title, content, user_id, category_id, created_at)
         VALUES (:title, :content, :user_id, :category_id, NOW())"
    );
    $stmt->execute([
        'title'       => $title,
        'content'     => $content,
        'user_id'     => $_SESSION['user_id'],
        'category_id' => $category_id
    ]);

    header("Location: ../public/dashboard.php?success=Prompt saved successfully! 🎉");
    exit();
}
?>