<?php
require_once('../config/db.php');
require_once('../models/productManagementModel.php');

$con = getConnection();

// Get all categories
$sqlCat = "SELECT * FROM categories ORDER BY name";
$resCat = mysqli_query($con, $sqlCat);
$allCategories = [];
while($row = mysqli_fetch_assoc($resCat)) 
{
    $allCategories[] = $row;
}

// Get all brands (initially all brands)
$sqlBrand = "SELECT * FROM brands ORDER BY name";
$resBrand = mysqli_query($con, $sqlBrand);
$allBrands = [];
while($row = mysqli_fetch_assoc($resBrand)) 
{
    $allBrands[] = $row;
}

// UPGRADE: Join tables so you display Category & Brand Names instead of raw IDs in your table
$sqlProducts = "SELECT p.*, c.name AS category_name, b.name AS brand_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                ORDER BY p.id DESC";
$resProducts = mysqli_query($con, $sqlProducts);
$allProducts = [];
while($row = mysqli_fetch_assoc($resProducts)) 
{
    $allProducts[] = $row;
}

// Handle create
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') 
{
    $product_image = '';
    
    // Handle image upload
    if(isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) 
    {
        $uploadDir = '../public/uploads/products/';
        if(!is_dir($uploadDir)) 
        {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = basename($_FILES['product_image']['name']);
        $uniqueName = time() . '_' . $fileName;
        $uploadPath = $uploadDir . $uniqueName;
        
        if(move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) 
        {
            $product_image = 'public/uploads/products/' . $uniqueName;
        }
    }
    
    $product = array(
        'name' => $_POST['product_name'],
        'description' => $_POST['description'],
        'manufacturer_review' => $_POST['manufacturer_review'],
        'price' => $_POST['price'],
        'category_id' => $_POST['category_id'],
        'brand_id' => $_POST['brand_id'],
        'stock' => $_POST['stock'],
        'image_path' => $product_image
    );
    
    $result = createProduct($product);
    if($result) 
    {
        header("Location: products.php");
        exit;
    }
}

// Handle edit
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') 
{
    // FIX: Grab the existing image path from hidden state or DB fallback first
    $product_image = $_POST['existing_image_path']; 
    
    // Handle new image upload if provided
    if(isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) 
    {
        $uploadDir = '../public/uploads/products/';
        if(!is_dir($uploadDir)) 
        {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = basename($_FILES['product_image']['name']);
        $uniqueName = time() . '_' . $fileName;
        $uploadPath = $uploadDir . $uniqueName;
        
        if(move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) 
        {
            $product_image = 'public/uploads/products/' . $uniqueName;
        }
    }
    
    $product = array(
        'id' => $_POST['product_id'],
        'name' => $_POST['product_name'],
        'description' => $_POST['description'],
        'manufacturer_review' => $_POST['manufacturer_review'],
        'price' => $_POST['price'],
        'category_id' => $_POST['category_id'],
        'brand_id' => $_POST['brand_id'],
        'stock' => $_POST['stock'],
        'image_path' => $product_image // Keeps old string if no new file uploaded
    );
    
    $result = editProduct($product);
    if($result) 
    {
        header("Location: products.php");
        exit;
    }
}

// Handle delete
if(isset($_GET['delete'])) 
{
    $result = deleteProduct($_GET['delete']);
    if($result) 
    {
        header("Location: products.php");
        exit;
    }
}

// Get edit data if edit is requested
$editData = null;
if(isset($_GET['edit'])) {
    $sqlEdit = "SELECT * FROM products WHERE id = " . (int)$_GET['edit'];
    $resultEdit = mysqli_query($con, $sqlEdit);
    $editData = mysqli_fetch_assoc($resultEdit);
}

?>
<?php include('partials/navbar.php'); ?>

<h1>Product Management</h1>

<form method="POST" action="" enctype="multipart/form-data" id="productForm">
    <input type="hidden" name="action" value="<?= $editData ? 'edit' : 'create' ?>">
    <?php if($editData): ?>
        <input type="hidden" name="product_id" value="<?= $editData['id'] ?>">
        <input type="hidden" name="existing_image_path" value="<?= $editData['image_path'] ?>">
    <?php endif; ?>
    
    <input type="text" name="product_name" placeholder="Product Name" value="<?= $editData ? htmlspecialchars($editData['name']) : '' ?>" required>
    <textarea name="description" placeholder="Description"><?= $editData ? htmlspecialchars($editData['description']) : '' ?></textarea>
    <textarea name="manufacturer_review" placeholder="Manufacturer Review"><?= $editData ? htmlspecialchars($editData['manufacturer_review']) : '' ?></textarea>
    <input type="number" name="price" id="price" step="0.01" min="0.01" placeholder="Price" value="<?= $editData ? $editData['price'] : '' ?>" required>
    
    <select name="category_id" id="cat_select" onchange="fetchBrands(this.value)" required>
        <option value="">Select Category</option>
        <?php foreach($allCategories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($editData && $editData['category_id'] == $cat['id']) ? 'selected' : '' ?>><?= $cat['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <select name="brand_id" id="brand_select" required>
        <option value="">Select Brand</option>
        <?php foreach($allBrands as $brand): ?>
            <option value="<?= $brand['id'] ?>" <?= ($editData && $editData['brand_id'] == $brand['id']) ? 'selected' : '' ?>><?= $brand['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <input type="file" name="product_image" id="product_image" accept="image/png, image/jpeg" <?= $editData ? '' : 'required' ?>>
    <small>JPEG/PNG, max 2MB <?= $editData ? '(Optional - leave empty to keep current image)' : '' ?></small>

    <input type="number" name="stock" id="stock" placeholder="Stock Quantity" value="<?= $editData ? $editData['stock'] : '' ?>" required>
    <button type="submit"><?= $editData ? 'Update Product' : 'Create Product' ?></button>
    <?php if($editData): ?>
        <a href="products.php"><button type="button">Cancel</button></a>
    <?php endif; ?>
</form>

<script src="../public/js/productValidation.js"></script>

<hr>
<h2>All Products</h2>

<table border="1" width="100%" cellpadding="8" style="border-collapse: collapse; text-align: center;">
    <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Category</th>
        <th>Brand</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Actions</th>
    </tr>
    <?php foreach($allProducts as $prod): ?>
    <tr>
        <td><?= $prod['id'] ?></td>
        <td>
            <?php if(!empty($prod['image_path'])): ?>
                <img src="../<?= $prod['image_path'] ?>" alt="Image" width="60" height="60" style="object-fit: cover; border-radius: 4px;">
            <?php else: ?>
                <span>No Image</span>
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($prod['name']) ?></td>
        <td><?= htmlspecialchars($prod['category_name'] ?? 'Uncategorized') ?></td>
        <td><?= htmlspecialchars($prod['brand_name'] ?? 'Generic') ?></td>
        <td>$<?= number_format($prod['price'], 2) ?></td>
        <td><?= $prod['stock'] ?></td>
        <td>
            <a href="?edit=<?= $prod['id'] ?>">Edit</a> | 
            <a href="?delete=<?= $prod['id'] ?>" onclick="return confirm('Delete this product? Image will also be removed.')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>