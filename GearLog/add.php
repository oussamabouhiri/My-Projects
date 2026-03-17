<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        INSERT INTO assets (serial_number, device_name, price, status, category_id)
        VALUES (:serial, :name, :price, :status, :category)
    ");

    $stmt->execute([
        'serial' => $_POST['serial_number'],
        'name' => $_POST['device_name'],
        'price' => $_POST['price'],
        'status' => $_POST['status'],
        'category' => $_POST['category_id']
    ]);

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="En">
<head>
    <title>CREATE DATA GREALOG</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form class="add-form" method="POST">
        <input name="serial_number" placeholder="Serial"><br>
        <input name="device_name" placeholder="Device"><br>
        <input name="price" placeholder="Price"><br>
        
        <select name="status">
        <option value="in_use">In Use</option>
        <option value="available">Available</option>
        <option value="maintenance">Maintenance</option>
    </select><br>

    <input name="category_id" placeholder="Category ID"><br>

    <button type="submit">Add</button>
</form>
</body>
</html>