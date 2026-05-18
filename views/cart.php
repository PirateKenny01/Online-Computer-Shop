<?php
// views/cart.php
session_start();
require_once __DIR__ . '/../models/cartModel.php';

// Hard-coded default baseline fallback to track your current session list
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; 
}

$cartItems = getCartItems($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #fafafa; }
        .wrapper { padding: 40px; max-width: 1100px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; background: white; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        th, td { padding: 14px; border: 1px solid #ddd; text-align: left; }
        th { background: #f4f4f4; }
        .qty-btn { background: #007bff; color: white; border: none; padding: 6px 14px; font-weight: bold; cursor: pointer; border-radius: 3px; }
        .checkout-btn { display: inline-block; background: #28a745; color: white; text-decoration: none; padding: 14px 30px; border-radius: 4px; font-weight: bold; font-size: 16px; margin-top: 15px; border: none; cursor: pointer; text-align: center; }
        .checkout-btn:hover { background: #218838; }
    </style>
</head>
<body>
    <?php include('partials/navbar.php'); ?>

    <div class="wrapper">
        <h2>Your Shopping Cart Management</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Product Component Model</th>
                    <th>Selected Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grandTotal = 0;
                if(!empty($cartItems)):
                    foreach ($cartItems as $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $grandTotal += $subtotal;
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($item['name']); ?></strong></td>
                    <td>
                        <button class="qty-btn" onclick="adjustQuantity(<?php echo $item['cart_id']; ?>, 'subtract', <?php echo $item['quantity']; ?>)">-</button>
                        <span style="margin: 0 15px; font-weight: bold; font-size: 16px;"><?php echo $item['quantity']; ?></span>
                        <button class="qty-btn" onclick="adjustQuantity(<?php echo $item['cart_id']; ?>, 'add', <?php echo $item['quantity']; ?>)">+</button>
                    </td>
                    <td>৳<?php echo number_format($item['price'], 2); ?></td>
                    <td>৳<?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <?php 
                    endforeach; 
                else:
                ?>
                    <tr><td colspan="4" style="text-align: center; color: #666; padding: 30px;">Your cart list is currently empty. Choose components from the catalog to build your setup!</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if(!empty($cartItems)): ?>
            <div style="text-align: right; background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px;">
                <h3>Grand Total: ৳<?php echo number_format($grandTotal, 2); ?></h3>
                <a href="login.php?checkout_intercept=true" class="checkout-btn">💳 Make Payment & Checkout</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function adjustQuantity(cartId, actionStr, currentQty) {
        if(actionStr === 'subtract' && currentQty <= 1) {
            alert("Quantity cannot drop below 1 unit bounds.");
            return;
        }
        fetch(`../api/cart/update.php?id=${cartId}&action=${actionStr}&current_qty=${currentQty}`)
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    }
    </script>
</body>
</html>