<?php
// api/cart/add.php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../models/cartModel.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to add components to your cart.']);
    exit;
}

$productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($productId <= 0 || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product parameters.']);
    exit;
}

$success = addToCart($_SESSION['user_id'], $productId, $quantity);

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Product successfully added to your cart catalog.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Cannot add item. Check if order exceeds warehouse stock limit bounds!']);
}
exit;
?>