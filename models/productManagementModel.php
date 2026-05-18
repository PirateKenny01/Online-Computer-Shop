<?php

require_once('../config/db.php');

function createProduct(array $product): bool
{
    $con = getConnection();
    
    $name = mysqli_real_escape_string($con, $product['name']);
    $description = mysqli_real_escape_string($con, $product['description']);
    $manufacturer_review = mysqli_real_escape_string($con, $product['manufacturer_review']);
    $price = (float)$product['price'];
    $category_id = (int)$product['category_id'];
    $brand_id = (int)$product['brand_id'];
    $stock = (int)$product['stock'];
    $image_path = isset($product['image_path']) ? $product['image_path'] : NULL;
    
    $sql = "INSERT INTO products (name, description, manufacturer_review, price, category_id, brand_id, image_path, stock) 
            VALUES ('$name', '$description', '$manufacturer_review', $price, $category_id, $brand_id, '$image_path', $stock)";
    
    if (mysqli_query($con, $sql))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function editProduct(array $product): bool
{
    $con = getConnection();
    
    $id = (int)$product['id'];
    $name = mysqli_real_escape_string($con, $product['name']);
    $description = mysqli_real_escape_string($con, $product['description']);
    $manufacturer_review = mysqli_real_escape_string($con, $product['manufacturer_review']);
    $price = (float)$product['price'];
    $category_id = (int)$product['category_id'];
    $brand_id = (int)$product['brand_id'];
    $stock = (int)$product['stock'];
    
    if(isset($product['image_path']) && $product['image_path'] != '') {
        $image_path = $product['image_path'];
        $sql = "UPDATE products SET name = '$name', description = '$description', manufacturer_review = '$manufacturer_review', 
                price = $price, category_id = $category_id, brand_id = $brand_id, image_path = '$image_path', stock = $stock WHERE id = $id";
    } else {
        $sql = "UPDATE products SET name = '$name', description = '$description', manufacturer_review = '$manufacturer_review', 
                price = $price, category_id = $category_id, brand_id = $brand_id, stock = $stock WHERE id = $id";
    }
    
    if (mysqli_query($con, $sql))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function deleteProduct(int $id): bool
{
    $con = getConnection();
    $id = (int)$id;
    
    $sqlGet = "SELECT image_path FROM products WHERE id = $id";
    $resultGet = mysqli_query($con, $sqlGet);
    if($resultGet) 
    {
        $row = mysqli_fetch_assoc($resultGet);
        $image_path = $row['image_path'];
        
        // Delete image file if it exists
        if($image_path && file_exists('../' . $image_path)) 
        {
            unlink('../' . $image_path);
        }
    }
    
    $sql = "DELETE FROM products WHERE id = $id";
    
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
