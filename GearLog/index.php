<?php
require 'db.php';

// =====================
// GET SEARCH & FILTER
// =====================
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

// =====================
// TOTAL INVENTORY VALUE
// =====================
$totalStmt = $pdo->prepare("SELECT SUM(price) AS Total FROM assets");
$totalStmt->execute();
$Total = $totalStmt->fetch();

// fix null total
$totalValue = $Total['Total'] ?? 0;

// =====================
// FETCH ASSETS WITH JOIN
// =====================
$sql = "
    SELECT assets.*, categories.hardware_type
    FROM assets
    INNER JOIN categories ON assets.category_id = categories.id
    WHERE 1
";

$params = [];

if ($search) {
    $sql .= " AND (device_name LIKE :search OR serial_number LIKE :search)";
    $params['search'] = "%$search%";
}

if ($status) {
    $sql .= " AND status = :status";
    $params['status'] = $status;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$assets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>GearLog Dashboard</title>
    <style>
        .in_use { color: green; }
        .available { color: blue; }
        .maintenance { color: red; }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
        }

        a {
            text-decoration: none;
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Welcome to GearLog Dashboard</h1>

    <!-- ADD BUTTON -->
    <a href="add.php">➕ Add New Asset</a>

    <!-- SEARCH & FILTER -->
    <form class="main-form" method="GET">
        <input type="text" name="search" placeholder="Search by device or serial" 
               value="<?= htmlspecialchars($search) ?>">
        
        <select name="status">
            <option value="">All Status</option>
            <option value="in_use" <?= $status=='in_use' ? 'selected' : '' ?>>In Use</option>
            <option value="available" <?= $status=='available' ? 'selected' : '' ?>>Available</option>
            <option value="maintenance" <?= $status=='maintenance' ? 'selected' : '' ?>>Maintenance</option>
        </select>

        <button type="submit">Filter</button>
    </form>
</header>

<main>
    <h2>Total Inventory Value: <?= htmlspecialchars($totalValue) ?> Dhs</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Device</th>
            <th>Serial</th>
            <th>Category</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th> <!-- NEW -->
        </tr>

        <?php foreach($assets as $asset): ?>
        <tr>
            <td><?= htmlspecialchars($asset['id']) ?></td>
            <td><?= htmlspecialchars($asset['device_name']) ?></td>
            <td><?= htmlspecialchars($asset['serial_number']) ?></td>
            <td><?= htmlspecialchars($asset['hardware_type']) ?></td>
            <td><?= htmlspecialchars($asset['price']) ?></td>

            <td class="<?= htmlspecialchars($asset['status']) ?>">
                <?= htmlspecialchars($asset['status']) ?>
            </td>

            <!-- ACTIONS -->
            <td>
                <a href="edit.php?id=<?= $asset['id'] ?>">✏️ Edit</a> |
                <a href="delete.php?id=<?= $asset['id'] ?>" 
                   onclick="return confirm('Are you sure?')">❌ Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>
</main>

</body>
</html>