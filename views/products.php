<?php
// Mock data for Product form dropdowns
$allCategories = [['id' => 1, 'name' => 'Storage'], ['id' => 2, 'name' => 'Monitor']];
$allBrands = [['id' => 1, 'name' => 'ASUS'], ['id' => 2, 'name' => 'LG']];
?>

<h1>Product Management</h1>
<form action="product_action.php" method="POST" enctype="multipart/form-data" id="productForm">
    <input type="text" name="name" placeholder="Product Name" required>
    <textarea name="description" placeholder="Description"></textarea>
    <textarea name="manufacturer_review" placeholder="Manufacturer Review"></textarea>
    <input type="number" name="price" step="0.01" min="0.01" placeholder="Price" required>
    
    <select name="category_id" id="cat_select" onchange="fetchBrands(this.value)">
        <option value="">Select Category</option>
        <?php foreach($allCategories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <select name="brand_id" id="brand_select">
        <option value="">Select Brand</option>
    </select>

    <input type="file" name="product_image" id="imageInput" accept="image/png, image/jpeg" required>
    <small>JPEG/PNG, max 2MB</small>

    <input type="number" name="stock" placeholder="Stock Quantity" required>
    <button type="submit">Save Product</button>
</form>

<script>
    // JS Validation for image size (Task 2 Requirement)
    document.getElementById('productForm').onsubmit = function(e) {
        const file = document.getElementById('imageInput').files[0];
        if (file && file.size > 2 * 1024 * 1024) {
            alert("Image size must be less than 2MB!");
            e.preventDefault();
        }
    };

    // Placeholder for AJAX dynamic brand loading
    function fetchBrands(catId) {
        // You will implement the AJAX call to load brands based on category here
    }
</script>