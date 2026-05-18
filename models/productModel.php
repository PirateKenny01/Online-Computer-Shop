<?php
// models/productModel.php
require_once __DIR__ . '/../config/db.php';

function searchAndFilterProducts($q, $minPrice, $maxPrice, $catId, $brandId) {
    $con = getConnection();
    
    // Base query
    $sql = "SELECT p.*, b.name as brand_name FROM products p 
            LEFT JOIN brands b ON p.brand_id = b.id WHERE 1=1";
    $types = "";
    $params = [];

    // Search query keyword check
    if (!empty($q)) {
        $sql .= " AND p.name LIKE ?";
        $params[] = "%" . $q . "%";
        $types .= "s";
    }
    
    // Explicit numeric sanitation bounds to completely fix condition 5
    $finalMin = ($minPrice !== '' && $minPrice !== null) ? floatval($minPrice) : 0.0;
    $finalMax = ($maxPrice !== '' && $maxPrice !== null && floatval($maxPrice) > 0) ? floatval($maxPrice) : 99999999.0;

    $sql .= " AND p.price >= ? AND p.price <= ?";
    $params[] = $finalMin;
    $params[] = $finalMax;
    $types .= "dd";

    if (!empty($catId)) {
        $sql .= " AND p.category_id = ?";
        $params[] = intval($catId);
        $types .= "i";
    }
    if (!empty($brandId)) {
        $sql .= " AND p.brand_id = ?";
        $params[] = intval($brandId);
        $types .= "i";
    }

    $stmt = mysqli_prepare($con, $sql);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $data;
}

function getFeaturedProducts() {
    $con = getConnection();
    $query = "SELECT * FROM products ORDER BY id ASC";
    $result = mysqli_query($con, $query);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $products;
}

function getProductById($id) {
    $con = getConnection();
    $query = "SELECT p.*, c.name as category_name, b.name as brand_name 
              FROM products p
              LEFT JOIN categories c ON p.category_id = c.id
              LEFT JOIN brands b ON p.brand_id = b.id
              WHERE p.id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $product;
}
?>