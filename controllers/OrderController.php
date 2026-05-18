<?php
session_start();
require_once '../models/OrderModel.php';
require_once '../models/CartModel.php';

$user_id = $_SESSION['user_id'] ?? null;
$cart_items = CartModel::getCartItems($user_id);
$payment_method = $_POST['payment_method'] ?? 'cash_on_delivery';

if(!$cart_items) die("Cart is empty");

$order_id = OrderModel::placeOrder($user_id, $cart_items, $payment_method);

header("Location: /views/order_confirmation.php?order_id=$order_id");
exit();
?>