<?php
// views/product_detail.php
session_start();
require_once __DIR__ . '/../models/productModel.php';

// Safe temporary assignment fallback to ensure fluid testing
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; 
}

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = getProductById($productId);

if (!$product) {
    die("<h2>Component error: Product not found.</h2><a href='home.php'>Return Home</a>");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #fafafa; }
        .details-container { max-width: 700px; background: white; border: 1px solid #ddd; padding: 30px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .price { font-size: 22px; color: #2e7d32; font-weight: bold; margin: 15px 0; }
        .cart-btn { background: #28a745; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

    <a href="home.php" style="text-decoration:none; color:#007bff; display:inline-block; margin-bottom:20px;">← Back to Home Catalog</a>

    <div class="details-container">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <p style="color: #666;">Category Reference: <strong><?php echo htmlspecialchars($product['category_name'] ?? 'General'); ?></strong></p>
        <hr style="border: 0; border-top: 1px solid #eee;">
        
        <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
        <p style="background: #f9f9f9; padding: 10px; border-left: 3px solid #007bff; font-style: italic;">
            "<?php echo htmlspecialchars($product['manufacturer_review']); ?>"
        </p>
        
        <div class="price">Fulfillment Price: ৳<?php echo number_format($product['price'], 2); ?></div>
        <p>Available System Warehouse Stock: <strong><span id="stock-count"><?php echo $product['stock']; ?></span> units left</strong></p>

        <br>
        <button class="cart-btn" onclick="executeAddToCart(<?php echo $product['id']; ?>)">🛒 Add to Cart Basket via AJAX</button>
    </div>

    <script>
    function executeAddToCart(prodId) {
        let formData = new FormData();
        formData.append('product_id', prodId);
        formData.append('quantity', 1);

        fetch('../api/cart/add.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = "cart.php"; // Redirects smoothly into active list validation view
            } else {
                alert(data.message);
            }
        })
        .catch(err => console.error("Error executing AJAX insertion:", err));
    }
    </script>
</body>
</html>