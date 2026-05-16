<?php

require_once('../config/db.php');

function createBrand(array $brand): bool
{
    $con = getConnection();
    
    $name = mysqli_real_escape_string($con, $brand['name']);
    $category_id = (int)$brand['category_id'];
    
    $sql = "INSERT INTO brands (name, category_id) VALUES ('$name', $category_id)";
    
    if (mysqli_query($con, $sql))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function editBrand(array $brand): bool
{
    $con = getConnection();
    
    $id = (int)$brand['id'];
    $name = mysqli_real_escape_string($con, $brand['name']);
    $category_id = (int)$brand['category_id'];
    
    $sql = "UPDATE brands SET name = '$name', category_id = $category_id WHERE id = $id";
    
    if (mysqli_query($con, $sql))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function deleteBrand(int $id): bool
{
    $con = getConnection();
    $id = (int)$id;
    
    // Check if brand has products
    $checkProduct = "SELECT COUNT(*) as product_count FROM products WHERE brand_id = $id";
    $productResult = mysqli_query($con, $checkProduct);
    if($productResult) {
        $productRow = mysqli_fetch_assoc($productResult);
        if ($productRow['product_count'] > 0)
        {
            return false;
        }
    }
    
    // Delete brand
    $sql = "DELETE FROM brands WHERE id = $id";
    
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
