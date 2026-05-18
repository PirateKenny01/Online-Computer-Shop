<?php
// api/cart/update.php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../models/cartModel.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Session expired. Please log in again.']);
    exit;
}

$cartId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';
$currentQty = isset($_GET['current_qty']) ? intval($_GET['current_qty']) : 1;

$newQty = ($action === 'add') ? $currentQty + 1 : $currentQty - 1;

$success = updateCartQuantity($cartId, $newQty);

if ($success) {
    $cartItems = getCartItems($_SESSION['user_id']);
    $totalPrice = 0;
    foreach ($cartItems as $item) {
        $totalPrice += ($item['price'] * $item['quantity']);
    }
    echo json_encode([
        'success' => true, 
        'new_qty' => $newQty, 
        'total_price' => number_format($totalPrice, 2)
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: Requested quantity violates available stock levels!']);
}
exit;
?>