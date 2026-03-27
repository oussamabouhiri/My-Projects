<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id          = (int)($_POST['id'] ?? 0);
    $title       = trim($_POST['title'] ?? '');
    $content     = trim($_POST['content'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);

    // Validate inputs
    if ($id <= 0 || empty($title) || empty($content) || $category_id <= 0) {
        header("Location: ../public/edit_prompt.php?id=$id&error=All fields are required");
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
        header("Location: ../public/dashboard.php?error=You are not authorized to edit this prompt");
        exit();
    }

    // Perform update
    $stmt = $pdo->prepare(
        "UPDATE prompts
         SET title = :title, content = :content, category_id = :cat
         WHERE id = :id"
    );
    $stmt->execute([
        'title'   => $title,
        'content' => $content,
        'cat'     => $category_id,
        'id'      => $id
    ]);

    header("Location: ../public/dashboard.php?success=Prompt updated successfully ✅");
    exit();
}

header("Location: ../public/dashboard.php");
exit();
?>