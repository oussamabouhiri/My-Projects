<?php
$server    = "localhost";
$port      = 3307;
$user_name = "tester";
$password  = "123";
$db_name   = "prompt_repository";

try {
    $pdo = new PDO(
        "mysql:host=$server;port=$port;dbname=$db_name;charset=utf8",
        $user_name,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE,        PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('<span style="color:red;">Connection failed: ' . $e->getMessage() . '</span>');
}
?>