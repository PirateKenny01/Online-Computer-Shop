<?php
session_start();

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') 
{
    header('Location: login.php');
    exit();
}

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


// Done by: 22-49926-3
// Task 4: Get recent orders for Admin Dashboard
$sqlRecentOrders = "SELECT 
                        o.id,
                        o.user_id,
                        o.total_amount,
                        o.payment_method,
                        o.status,
                        o.order_date,
                        u.name AS customer_name
                    FROM orders o
                    LEFT JOIN users u ON o.user_id = u.id
                    ORDER BY o.order_date DESC
                    LIMIT 5";

$resRecentOrders = mysqli_query($con, $sqlRecentOrders);
if (!$resRecentOrders) 
{
    die("Error fetching recent orders: " . mysqli_error($con));
}
$recentOrders = [];
while($row = mysqli_fetch_assoc($resRecentOrders)) 
{
    $recentOrders[] = $row;
}


// Done by: 22-49926-3
// Task 4: Get recent reviews for Admin Dashboard
$sqlRecentReviews = "SELECT 
                        r.id,
                        r.product_id,
                        r.user_id,
                        r.reviewer_name,
                        r.comment,
                        r.created_at,
                        p.name AS product_name
                    FROM reviews r
                    LEFT JOIN products p ON r.product_id = p.id
                    ORDER BY r.created_at DESC
                    LIMIT 5";

$resRecentReviews = mysqli_query($con, $sqlRecentReviews);
if (!$resRecentReviews) 
{
    die("Error fetching recent reviews: " . mysqli_error($con));
}
$recentReviews = [];
while($row = mysqli_fetch_assoc($resRecentReviews)) 
{
    $recentReviews[] = $row;
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Online Computer Shop</title>
    <link rel="stylesheet" href="../public/css/style.css"> 
</head>
<body>

    <?php include 'partials/navbar.php'; ?>

    <h1>Dashboard Summary</h1>
    <div style="display: flex; gap: 20px;">
        <div style="padding: 20px; border: 1px solid #ccc;">
            <h3>Total Products</h3>
            <p><?php echo $totalProducts;?></p>
        </div>
        <div style="padding: 20px; border: 1px solid #ccc;">
            <h3>Total Categories</h3>
            <p><?php echo $totalCategories;?></p>
        </div>
        <div style="padding: 20px; border: 1px solid #ccc;">
            <h3>Total Brands</h3>
            <p><?php echo $totalBrands;?></p>
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


    <!-- Done by: 22-49926-3 -->
    <!-- Task 4: Recent Orders section added in Admin Dashboard -->
    <h2>Recent Orders</h2>
    <table border="1" width="100%" cellpadding="8" style="border-collapse: collapse;">
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Total Amount</th>
            <th>Payment Method</th>
            <th>Status</th>
            <th>Order Date</th>
        </tr>

        <?php if(count($recentOrders) > 0): ?>
            <?php foreach($recentOrders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td>
                        <?php 
                        if($order['customer_name']) {
                            echo htmlspecialchars($order['customer_name']);
                        } else {
                            echo "Deleted Customer";
                        }
                        ?>
                    </td>
                    <td><?= number_format((float)$order['total_amount'], 2) ?></td>
                    <td><?= htmlspecialchars($order['payment_method']) ?></td>
                    <td><?= htmlspecialchars($order['status']) ?></td>
                    <td><?= htmlspecialchars($order['order_date']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align:center;">No recent orders found.</td>
            </tr>
        <?php endif; ?>
    </table>


    <!-- Done by: 22-49926-3 -->
    <!-- Task 4: Recent Reviews section added in Admin Dashboard -->
    <h2>Recent Reviews</h2>
    <table border="1" width="100%" cellpadding="8" style="border-collapse: collapse;">
        <tr>
            <th>Review ID</th>
            <th>Product Name</th>
            <th>Reviewer Name</th>
            <th>Comment</th>
            <th>Review Date</th>
        </tr>

        <?php if(count($recentReviews) > 0): ?>
            <?php foreach($recentReviews as $review): ?>
                <tr>
                    <td><?= $review['id'] ?></td>
                    <td>
                        <?php 
                        if($review['product_name']) {
                            echo htmlspecialchars($review['product_name']);
                        } else {
                            echo "Deleted Product";
                        }
                        ?>
                    </td>
                    <td><?= htmlspecialchars($review['reviewer_name']) ?></td>
                    <td><?= htmlspecialchars($review['comment']) ?></td>
                    <td><?= htmlspecialchars($review['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center;">No recent reviews found.</td>
            </tr>
        <?php endif; ?>
    </table>

</body>
</html>