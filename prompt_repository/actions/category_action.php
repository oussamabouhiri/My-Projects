<?php
session_start();
require_once '../config/db.php';

// Admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/dashboard.php");
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── ADD ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {

    $name = trim($_POST['name']);

    if (empty($name)) {
        header("Location: ../public/manage_categories.php?error=Category name is required");
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
    $stmt->execute(['name' => $name]);

    header("Location: ../public/manage_categories.php?success=Category added successfully");
    exit();
}

// ── EDIT ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit') {

    $id   = (int) $_POST['id'];
    $name = trim($_POST['name']);

    if (empty($name) || $id <= 0) {
        header("Location: ../public/manage_categories.php?error=Invalid data");
        exit();
    }

    $stmt = $pdo->prepare("UPDATE categories SET name = :name WHERE id = :id");
    $stmt->execute(['name' => $name, 'id' => $id]);

    header("Location: ../public/manage_categories.php?success=Category updated successfully");
    exit();
}

// ── DELETE ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'delete') {

    $id = (int) $_GET['id'];

    if ($id <= 0) {
        header("Location: ../public/manage_categories.php?error=Invalid category");
        exit();
    }

    // Check if category has prompts
    $check = $pdo->prepare("SELECT COUNT(*) FROM prompts WHERE category_id = :id");
    $check->execute(['id' => $id]);
    $count = $check->fetchColumn();

    if ($count > 0) {
        header("Location: ../public/manage_categories.php?error=Cannot delete: category has {$count} prompt(s) linked to it");
        exit();
    }

    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->execute(['id' => $id]);

    header("Location: ../public/manage_categories.php?success=Category deleted successfully");
    exit();
}

header("Location: ../public/manage_categories.php");
exit();
?>