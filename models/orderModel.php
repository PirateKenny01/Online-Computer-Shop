<?php
// models/OrderModel.php
require_once __DIR__ . '/../config/db.php';

// Get all orders placed by a specific user
function getUserOrders($userId) {
    $con = getConnection();
    $query = "SELECT id, total_amount, payment_method, status, order_date 
              FROM orders 
              WHERE user_id = ? 
              ORDER BY order_date DESC";
              
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $orders;
}
?>