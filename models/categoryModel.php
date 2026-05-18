<?php
// models/categoryModel.php
require_once __DIR__ . '/../config/db.php';

function getTopCategories() {
    $con = getConnection();
    // GROUP BY name eliminates duplicate hardware elements on the home dashboard view
    $sql = "SELECT MIN(id) as id, name FROM categories WHERE parent_id IS NULL GROUP BY name ORDER BY id ASC";
    $result = mysqli_query($con, $sql);
    
    $categories = [];
    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $categories;
}

function getCategoryById($id) {
    $con = getConnection();
    $sql = "SELECT id, name, parent_id FROM categories WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $category = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $category;
    }
    return null;
}
?>