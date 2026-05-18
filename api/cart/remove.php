<?php
// api/cart/remove.php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../models/CartModel.php';

$cartId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$success = removeFromCart($cartId);

if ($success) {
    $cartItems = getCartItems($_SESSION['user_id']);
    $totalPrice = 0;
    foreach ($cartItems as $item) {
        $totalPrice += ($item['price'] * $item['quantity']);
    }
    echo json_encode([
        'success' => true, 
        'message' => 'Item removed successfully.', 
        'total_price' => number_format($totalPrice, 2)
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove item.']);
}
exit;
?>
