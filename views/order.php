<?php
// views/orders.php
session_start();
require_once __DIR__ . '/../models/OrderModel.php';

// Safe development session injection
$_SESSION['user_id'] = 1; 

$userId = $_SESSION['user_id'];
$myOrders = getUserOrders($userId);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #fafafa; }
        .order-card { background: white; border: 1px solid #ddd; padding: 20px; margin-bottom: 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .order-header { display: flex; justify-content: space-between; font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px; }
        .status-badge { background-color: #f0ad4e; color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px; text-transform: uppercase; }
        .total-amt { color: #27ae60; font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Your Order History</h2>
    <a href="home.php">Back to Components Shop</a>
    <br><br>

    <?php if (!empty($myOrders)): ?>
        <?php foreach ($myOrders as $order): ?>
            <div class="order-card">
                <div class="order-header">
                    <span>Order ID: #<?php echo $order['id']; ?></span>
                    <span>Date: <?php echo $order['order_date']; ?></span>
                </div>
                <p>Payment Method: <strong><?php echo ucwords($order['payment_method']); ?></strong></p>
                <p>Status: <span class="status-badge"><?php echo htmlspecialchars($order['status']); ?></span></p>
                <p class="total-amt">Grand Total: ৳<?php echo number_format($order['total_amount'], 2); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You haven't placed any orders yet.</p>
    <?php endif; ?>

</body>
</html>