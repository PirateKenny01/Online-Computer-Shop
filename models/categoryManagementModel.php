<?php

require_once('../config/db.php');

function createCategory(array $category): bool
{
    $con = getConnection();
    $name = mysqli_real_escape_string($con, $category['name']);
    $parent_id_val = isset($category['parent_id']) && $category['parent_id'] != '' ? (int)$category['parent_id'] : NULL;
    $parent_id = $parent_id_val === NULL ? "NULL" : $parent_id_val;
    
    $sql = "INSERT INTO categories (name, parent_id) VALUES ('$name', $parent_id)";
    
    if (mysqli_query($con, $sql))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function editCategory(array $category): bool
{
    $con = getConnection();
    $id = (int)$category['id'];
    $name = mysqli_real_escape_string($con, $category['name']);
    $parent_id_val = isset($category['parent_id']) && $category['parent_id'] != '' ? (int)$category['parent_id'] : NULL;
    $parent_id = $parent_id_val === NULL ? "NULL" : $parent_id_val;
    
    $sql = "UPDATE categories SET name = '$name', parent_id = $parent_id WHERE id = $id";
    
    if (mysqli_query($con, $sql))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function deleteCategory(int $id): bool
{
    $con = getConnection();
    $id = (int)$id;
    
    // Check if category has child categories
    $checkChild = "SELECT COUNT(*) as child_count FROM categories WHERE parent_id = $id";
    $childResult = mysqli_query($con, $checkChild);
    if($childResult) 
   {
        $childRow = mysqli_fetch_assoc($childResult);
        if ($childRow['child_count'] > 0)
        {
            return false;
        }
    }
    
    // Check if category has products
    $checkProduct = "SELECT COUNT(*) as product_count FROM products WHERE category_id = $id";
    $productResult = mysqli_query($con, $checkProduct);
    if($productResult) 
    {
        $productRow = mysqli_fetch_assoc($productResult);
        if ($productRow['product_count'] > 0)
        {
            return false;
        }
    }
    
    // Delete category
    $sql = "DELETE FROM categories WHERE id = $id";
    
    if (mysqli_query($con, $sql))
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>
