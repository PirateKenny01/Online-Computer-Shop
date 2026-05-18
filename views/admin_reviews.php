<?php
// Done by: 22-49926-3
// Task 4: Admin Remove Reviews and Customers Page

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
// Task 4: Fetch all reviews with product and customer details
$sqlReviews = "SELECT 
                    r.id,
                    r.product_id,
                    r.user_id,
                    r.reviewer_name,
                    r.comment,
                    r.created_at,
                    p.name AS product_name,
                    u.name AS customer_name,
                    u.email AS customer_email
                FROM reviews r
                LEFT JOIN products p ON r.product_id = p.id
                LEFT JOIN users u ON r.user_id = u.id
                ORDER BY r.created_at DESC";

$resReviews = mysqli_query($con, $sqlReviews);

if (!$resReviews) 
{
    die("Error fetching reviews: " . mysqli_error($con));
}

$reviews = [];

while ($row = mysqli_fetch_assoc($resReviews)) 
{
    $reviews[] = $row;
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Remove Reviews</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>

    <?php include('partials/navbar.php'); ?>

    <h1>Admin - Remove Reviews</h1>

    <!-- Done by: 22-49926-3 -->
    <!-- Task 4: Admin can remove any review or customer using AJAX -->

    <p id="reviewMessage"></p>

    <table border="1" width="100%" cellpadding="8" style="border-collapse: collapse;">
        <tr>
            <th>Review ID</th>
            <th>Product Name</th>
            <th>Reviewer Name</th>
            <th>Customer Email</th>
            <th>Comment</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>

        <?php if (count($reviews) > 0) { ?>
            <?php foreach ($reviews as $review) { ?>
                <tr 
                    id="review-row-<?php echo $review['id']; ?>" 
                    class="customer-review-row-<?php echo $review['user_id']; ?>"
                >
                    <td><?php echo $review['id']; ?></td>

                    <td>
                        <?php 
                        if ($review['product_name']) {
                            echo htmlspecialchars($review['product_name']);
                        } else {
                            echo "Deleted Product";
                        }
                        ?>
                    </td>

                    <td><?php echo htmlspecialchars($review['reviewer_name']); ?></td>

                    <td>
                        <?php 
                        if ($review['customer_email']) {
                            echo htmlspecialchars($review['customer_email']);
                        } else {
                            echo "Deleted Customer";
                        }
                        ?>
                    </td>

                    <td><?php echo htmlspecialchars($review['comment']); ?></td>
                    <td><?php echo htmlspecialchars($review['created_at']); ?></td>

                    <td>
                        <!-- Done by: 22-49926-3 -->
                        <!-- Task 4: AJAX delete review button -->
                        <button type="button" onclick="deleteReviewByAdmin(<?php echo $review['id']; ?>)">
                            Delete Review
                        </button>

                        <!-- Done by: 22-49926-3 -->
                        <!-- Task 4: AJAX remove customer button -->
                        <?php if (!empty($review['user_id'])) { ?>
                            <button type="button" onclick="deleteCustomerFromReviewPage(<?php echo $review['user_id']; ?>)">
                                Remove Customer
                            </button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="7" style="text-align:center;">No reviews found.</td>
            </tr>
        <?php } ?>
    </table>

    <script src="../public/js/admin_task4.js?v=100"></script>
</body>
</html>