<?php
// Done by: 22-49926-3
// Task 4: Admin Remove Customers Page

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') 
{
    header('Location: login.php');
    exit();
}

require_once('../config/db.php');

$con = getConnection();

if (!$con) 
{
    die("Database connection failed!");
}

// Done by: 22-49926-3
// Task 4: Fetch all customers from users table
$sqlCustomers = "SELECT 
                    u.id,
                    u.name,
                    u.email,
                    u.profile_picture,
                    u.created_at,
                    COUNT(DISTINCT r.id) AS review_count,
                    COUNT(DISTINCT c.id) AS cart_count,
                    COUNT(DISTINCT o.id) AS order_count
                FROM users u
                LEFT JOIN reviews r ON u.id = r.user_id
                LEFT JOIN cart c ON u.id = c.user_id
                LEFT JOIN orders o ON u.id = o.user_id
                WHERE u.role = 'customer'
                GROUP BY u.id, u.name, u.email, u.profile_picture, u.created_at
                ORDER BY u.id DESC";

$resCustomers = mysqli_query($con, $sqlCustomers);

if (!$resCustomers) 
{
    die("Error fetching customers: " . mysqli_error($con));
}

$customers = [];

while ($row = mysqli_fetch_assoc($resCustomers)) 
{
    $customers[] = $row;
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Remove Customers</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>

    <?php include('partials/navbar.php'); ?>

    <h1>Admin - Remove Customers</h1>

    <!-- Done by: 22-49926-3 -->
    <!-- Task 4: Admin can remove customers using AJAX -->

    <p id="customerMessage"></p>

    <table border="1" width="100%" cellpadding="8" style="border-collapse: collapse;">
        <tr>
            <th>Customer ID</th>
            <th>Profile Picture</th>
            <th>Name</th>
            <th>Email</th>
            <th>Total Reviews</th>
            <th>Cart Items</th>
            <th>Total Orders</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>

        <?php if (count($customers) > 0) { ?>
            <?php foreach ($customers as $customer) { ?>
                <tr id="customer-row-<?php echo $customer['id']; ?>">
                    <td><?php echo $customer['id']; ?></td>

                    <td>
                        <?php if (!empty($customer['profile_picture'])) { ?>
                            <img src="../<?php echo htmlspecialchars($customer['profile_picture']); ?>" width="50" height="50">
                        <?php } else { ?>
                            No Image
                        <?php } ?>
                    </td>

                    <td><?php echo htmlspecialchars($customer['name']); ?></td>
                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                    <td><?php echo $customer['review_count']; ?></td>
                    <td><?php echo $customer['cart_count']; ?></td>
                    <td><?php echo $customer['order_count']; ?></td>
                    <td><?php echo htmlspecialchars($customer['created_at']); ?></td>

                    <td>
                        <!-- Done by: 22-49926-3 -->
                        <!-- Task 4: AJAX remove customer button -->
                        <button type="button" onclick="deleteCustomerByAdmin(<?php echo $customer['id']; ?>)">
                            Remove Customer
                        </button>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="9" style="text-align:center;">No customers found.</td>
            </tr>
        <?php } ?>
    </table>

    <script src="../public/js/admin_task4.js?v=300"></script>
</body>
</html>