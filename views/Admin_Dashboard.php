<?php
// Mock data to prevent "undefined variable" errors during design
$totalProducts = 0;   // Will be replaced by Task 2 Model logic later [cite: 78]
$totalCategories = 0; // Will be replaced by Task 2 Model logic later [cite: 78]
$totalBrands = 0;     // Will be replaced by Task 2 Model logic later [cite: 78]
$lowStockItems = [];  // Array for products with stock < 5 [cite: 78]
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Online Computer Shop</title>
    <link rel="stylesheet" href="../public/css/style.css"> </head>
<body>
    <nav>
        <a href="Admin_Dashboard.php">Dashboard</a> | 
        <a href="categories.php">Categories</a> | 
        <a href="brands.php">Brands</a> | 
        <a href="products.php">Products</a>
    </nav>

    <h1>Dashboard Summary</h1>
    <div style="display: flex; gap: 20px;">
        <div style="padding: 20px; border: 1px solid #ccc;">
            <h3>Total Products</h3>
            <p><?php echo $totalProducts; // From Controller ?></p>
        </div>
        <div style="padding: 20px; border: 1px solid #ccc;">
            <h3>Total Categories</h3>
            <p><?php echo $totalCategories; // From Controller ?></p>
        </div>
        <div style="padding: 20px; border: 1px solid #ccc;">
            <h3>Total Brands</h3>
            <p><?php echo $totalBrands; // From Controller ?></p>
        </div>
    </div>

    <h2>Low Stock Alerts (Stock < 5)</h2>
    <table border="1" width="100%">
        <tr>
            <th>Product Name</th>
            <th>Current Stock</th>
            <th>Action</th>
        </tr>
        <?php foreach($lowStockItems as $item): ?>
        <tr>
            <td><?= $item['name'] ?></td>
            <td style="color: red; font-weight: bold;"><?= $item['stock'] ?></td>
            <td><a href="products.php?action=edit&id=<?= $item['id'] ?>">Restock</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>