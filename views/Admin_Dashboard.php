<?php
//session_start();

// Check if user is admin
//if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') 
//{
   // header('Location: ../index.php');
    //exit();
//}

require_once('../config/db.php');

$con = getConnection();

// Check connection
if (!$con) 
{
    die("Database connection failed!");
}

// Get total products
$sqlProducts = "SELECT COUNT(*) as total FROM products";
$resProducts = mysqli_query($con, $sqlProducts);
if (!$resProducts) 
{
    die("Error fetching products: " . mysqli_error($con));
}
$rowProducts = mysqli_fetch_assoc($resProducts);
$totalProducts = $rowProducts['total'];

// Get total categories
$sqlCategories = "SELECT COUNT(*) as total FROM categories";
$resCategories = mysqli_query($con, $sqlCategories);
if (!$resCategories) 
{
    die("Error fetching categories: " . mysqli_error($con));
}
$rowCategories = mysqli_fetch_assoc($resCategories);
$totalCategories = $rowCategories['total'];

// Get total brands
$sqlBrands = "SELECT COUNT(*) as total FROM brands";
$resBrands = mysqli_query($con, $sqlBrands);
if (!$resBrands) 
{
    die("Error fetching brands: " . mysqli_error($con));
}
$rowBrands = mysqli_fetch_assoc($resBrands);
$totalBrands = $rowBrands['total'];

// Get ALL products with their stock levels
$sqlAllProducts = "SELECT id, name, stock FROM products ORDER BY stock ASC";
$resAllProducts = mysqli_query($con, $sqlAllProducts);
if (!$resAllProducts) 
{
    die("Error fetching all products: " . mysqli_error($con));
}
$allProductsList = [];
while($row = mysqli_fetch_assoc($resAllProducts)) 
{
    $allProductsList[] = $row;
}

mysqli_close($con);
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
        <?php foreach($allProductsList as $item): ?>
        <tr>
            <td><?= $item['name'] ?></td>
            <td style="<?= ($item['stock'] < 5) ? 'color: red; font-weight: bold;' : 'color: green;' ?>"><?= $item['stock'] ?></td>
            <td>
                <?php if ($item['stock'] < 5): ?>
                    <a href="products.php?action=edit&id=<?= $item['id'] ?>" style="color: red; font-weight: bold;">⚠️ Restock</a>
                <?php else: ?>
                    <span style="color: green;">✅ Sufficient</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>