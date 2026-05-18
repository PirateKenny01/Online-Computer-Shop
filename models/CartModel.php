<?php
// models/cartModel.php
require_once __DIR__ . '/../config/db.php';

function getCartItems($userId) {
    $con = getConnection();
    $query = "SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price, p.stock 
              FROM cart c 
              JOIN products p ON c.product_id = p.id 
              WHERE c.user_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $data;
}

function addToCart($userId, $productId, $quantity) {
    $con = getConnection();
    
    
    $stockQuery = "SELECT stock FROM products WHERE id = ?";
    $stmt = mysqli_prepare($con, $stockQuery);
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    
    if (!$product) return false;
    
    
    $checkQuery = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = mysqli_prepare($con, $checkQuery);
    mysqli_stmt_bind_param($stmt, "ii", $userId, $productId);
    mysqli_stmt_execute($stmt);
    $checkRes = mysqli_stmt_get_result($stmt);
    $existing = mysqli_fetch_assoc($checkRes);
    mysqli_stmt_close($stmt);
    
    if ($existing) {
        $newQty = $existing['quantity'] + $quantity;
        if ($newQty > $product['stock']) return false; // Block execution if limit is exceeded
        
        $updateQuery = "UPDATE cart SET quantity = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ii", $newQty, $existing['id']);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    } else {
        if ($quantity > $product['stock']) return false;
        $insertQuery = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $insertQuery);
        mysqli_stmt_bind_param($stmt, "iii", $userId, $productId, $quantity);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }
}

function updateCartQuantity($cartId, $newQty) {
    $con = getConnection();
    
    $query = "SELECT p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $cartId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $item = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    
    if (!$item || $newQty > $item['stock'] || $newQty < 1) {
        return false;
    }
    
    $update = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $update);
    mysqli_stmt_bind_param($stmt, "ii", $newQty, $cartId);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function removeFromCart($cartId) {
    $con = getConnection();
    $query = "DELETE FROM cart WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $cartId);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}
?>