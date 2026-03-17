<?php
require 'db.php';

$id = $_GET['id'];

// Get existing data
$stmt = $pdo->prepare("SELECT * FROM assets WHERE id = ?");
$stmt->execute([$id]);
$asset = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        UPDATE assets 
        SET serial_number=?, device_name=?, price=?, status=?, category_id=?
        WHERE id=?
    ");

    $stmt->execute([
        $_POST['serial_number'],
        $_POST['device_name'],
        $_POST['price'],
        $_POST['status'],
        $_POST['category_id'],
        $id
    ]);

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>UPDATE DATA GEARLOG</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form class="edit-form" method="POST">
        <label for="serial_number">Enter serial number</label>
        <input name="serial_number" placeholder="serial number"value="<?= $asset['serial_number'] ?>"><br>
        <label for="device_name">Enter name of the device</label>
        <input name="device_name" placeholder="decice_name" value="<?= $asset['device_name'] ?>"><br>
    <label for="price">Enter price</label>
    <input name="price" placeholder="price" value="<?= $asset['price'] ?>"><br>
    <label for="status">Select status</label>
    <select name="status">
        <option value="in_use">In Use</option>
        <option value="available">Available</option>
        <option value="maintenance">Maintenance</option>
    </select><br>
    <label for="category_id">Enter the category id number</label>
    <input name="category_id" placeholder="category id " value="<?= $asset['category_id'] ?>"><br>
    
    <button type="submit">Update</button>
</form>
</body>
</html>