<?php
require_once('../config/db.php');
require_once('../models/brandManagementModel.php');

$con = getConnection();

// Get all categories
$sqlCat = "SELECT * FROM categories ORDER BY name";
$resCat = mysqli_query($con, $sqlCat);
$allCategories = [];
while($row = mysqli_fetch_assoc($resCat)) 
{
    $allCategories[] = $row;
}

// Get all brands
$sqlBrands = "SELECT b.*, c.name as category_name FROM brands b JOIN categories c ON b.category_id = c.id ORDER BY c.name, b.name";
$resBrands = mysqli_query($con, $sqlBrands);
$brands = [];
while($row = mysqli_fetch_assoc($resBrands)) 
{
    $brands[] = $row;
}

// Handle create
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') 
{
    $brand = array(
        'name' => $_POST['brand_name'],
        'category_id' => $_POST['category_id']
    );
    $result = createBrand($brand);
    if($result) 
    {
        header("Location: brands.php");
    }
}

// Handle edit
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') 
{
    $brand = array(
        'id' => $_POST['brand_id'],
        'name' => $_POST['brand_name'],
        'category_id' => $_POST['category_id']
    );
    $result = editBrand($brand);
    if($result) 
    {
        header("Location: brands.php");
    }
}

// Handle delete
if(isset($_GET['delete'])) 
{
    $result = deleteBrand($_GET['delete']);
    if(!$result) 
    {
        $deleteError = "Cannot delete! Brand has products assigned to it.";
    } 
    else 
    {
        header("Location: brands.php");
    }
}

// Get edit data if edit is requested
$editData = null;
if(isset($_GET['edit'])) {
    $sqlEdit = "SELECT * FROM brands WHERE id = " . (int)$_GET['edit'];
    $resultEdit = mysqli_query($con, $sqlEdit);
    $editData = mysqli_fetch_assoc($resultEdit);
}

?>

<h1>Brand Management</h1>

<?php if(isset($deleteError)): ?>
    <p style="color: red;"><?= $deleteError ?></p>
<?php endif; ?>

<form method="POST" action="">
    <input type="hidden" name="action" value="<?= $editData ? 'edit' : 'create' ?>">
    <?php if($editData): ?>
        <input type="hidden" name="brand_id" value="<?= $editData['id'] ?>">
    <?php endif; ?>
    
    <input type="text" name="brand_name" placeholder="Brand Name" value="<?= $editData ? $editData['name'] : '' ?>" required>
    
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php foreach($allCategories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($editData && $editData['category_id'] == $cat['id']) ? 'selected' : '' ?>><?= $cat['name'] ?></option>
        <?php endforeach; ?>
    </select>
    
    <button type="submit"><?= $editData ? 'Update Brand' : 'Add Brand' ?></button>
    <?php if($editData): ?>
        <a href="brands.php"><button type="button">Cancel</button></a>
    <?php endif; ?>
</form>

<h2>All Brands</h2>

<table border="1" width="100%">
    <tr>
        <th>ID</th>
        <th>Brand Name</th>
        <th>Category</th>
        <th>Actions</th>
    </tr>
    <?php foreach($brands as $brand): ?>
    <tr>
        <td><?= $brand['id'] ?></td>
        <td><?= $brand['name'] ?></td>
        <td><?= $brand['category_name'] ?></td>
        <td>
            <a href="?edit=<?= $brand['id'] ?>">Edit</a> | 
            <a href="?delete=<?= $brand['id'] ?>" onclick="return confirm('Delete this brand? Cannot delete if products are assigned.')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>