<?php
require_once('../config/db.php');
require_once('../models/categoryManagementModel.php');

$con = getConnection();

$sql = "SELECT * FROM categories ORDER BY parent_id, name";
$result = mysqli_query($con, $sql);
$allCategories = [];
while($row = mysqli_fetch_assoc($result)) 
{
    $allCategories[] = $row;
}

$sqlMain = "SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name";
$resultMain = mysqli_query($con, $sqlMain);
$mainCategories = [];
while($row = mysqli_fetch_assoc($resultMain)) 
{
    $mainCategories[] = $row;
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') 
{
    $category = array(
        'name' => $_POST['category_name'],
        'parent_id' => $_POST['parent_id'] == 0 ? NULL : $_POST['parent_id']
    );
    $result = createCategory($category);
    if($result) 
    {
        header("Location: categories.php");
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') 
{
    $category = array(
        'id' => $_POST['cat_id'],
        'name' => $_POST['category_name'],
        'parent_id' => $_POST['parent_id'] == 0 ? NULL : $_POST['parent_id']
    );
    $result = editCategory($category);
    if($result) 
    {
        header("Location: categories.php");
    }
}

if(isset($_GET['delete'])) 
{
    $result = deleteCategory($_GET['delete']);
    if(!$result) 
    {
        $deleteError = "Cannot delete! Category has child categories or products assigned to it.";
    } 
    else 
    {
        header("Location: categories.php");
    }
}

$editData = null;
if(isset($_GET['edit'])) 
{
    $sqlEdit = "SELECT * FROM categories WHERE id = " . (int)$_GET['edit'];
    $resultEdit = mysqli_query($con, $sqlEdit);
    $editData = mysqli_fetch_assoc($resultEdit);
}

?>

<!DOCTYPE html>
  <html lang="en">
  <head>

    <meta charset="UTF-8">
    <title>Category Management - Online Computer Shop</title>
    <link rel="stylesheet" href="../public/css/style.css">

  </head>

  <body>

     <?php include('partials/navbar.php'); ?>

     <h1>Category Management</h1>

     <?php 
         if(isset($deleteError)): ?>
         <p style="color: red;"><?= $deleteError ?></p>
         <?php endif; 
     ?>

<form method="POST">

    <input type="hidden" name="action" value="<?= $editData ? 'edit' : 'create' ?>">
    <?php if($editData): ?>
        <input type="hidden" name="cat_id" value="<?= $editData['id'] ?>">
    <?php endif; ?>
    
    <input type="text" name="category_name" placeholder="Category Name" value="<?= $editData ? $editData['name'] : '' ?>" required>
    
    <select name="parent_id">
        <option value="0">None (Main Category)</option>
        <?php foreach($mainCategories as $cat): ?>
            <?php if(!$editData || $editData['id'] != $cat['id']): ?>
                <option value="<?= $cat['id'] ?>" <?= ($editData && $editData['parent_id'] == $cat['id']) ? 'selected' : '' ?>><?= $cat['name'] ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
    
    <button type="submit"><?= $editData ? 'Update Category' : 'Create Category' ?></button>
    <?php if($editData): ?>
        <a href="categories.php"><button type="button">Cancel</button></a>
    <?php endif; ?>

</form>

<table border="1" width="100%">

    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Actions</th>
    </tr>

    <?php foreach($allCategories as $cat): ?>

    <tr>
        <td><?= $cat['id'] ?></td>
        <td><?= $cat['name'] ?></td>
        <td><?= ($cat['parent_id'] == NULL) ? "Main" : "Sub-category" ?></td>
        <td>
            <a href="?edit=<?= $cat['id'] ?>">Edit</a> | 
            <a href="?delete=<?= $cat['id'] ?>" onclick="return confirm('Delete this category? Cannot delete if it has child categories or products.')">Delete</a>
        </td>
    </tr>

    <?php endforeach; ?>

</table>

     </body>
</html>