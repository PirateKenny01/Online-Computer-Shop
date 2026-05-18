<?php
require_once 'config/db.php';

class OrderModel {
    public static function placeOrder($user_id, $cart_items, $payment_method) {
        $conn = getConnection();
        $conn->begin_transaction();
        try {
            $total_amount = array_sum(array_map(fn($i) => $i['price']*$i['quantity'], $cart_items));
            $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method, status, order_date) VALUES (?, ?, ?, 'pending', NOW())");
            $stmt->bind_param("ids", $user_id, $total_amount, $payment_method);
            $stmt->execute();
            $order_id = $conn->insert_id;
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
            foreach($cart_items as $item){
                $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                $stmt->execute();
            }
            $stmt->close();

            // Clear cart
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            $conn->commit();
            return $order_id;
        } catch(Exception $e) {
            $conn->rollback();
            throw $e;
        }
    }
}
?>
