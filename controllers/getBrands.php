<?php
require_once('../config/db.php');

// Tell the browser to interpret this file's output as clean JSON data, not HTML
header('Content-Type: application/json');

if (isset($_GET['category_id'])) 
{
    $con = getConnection();
    $category_id = (int)$_GET['category_id'];
    $sql = "SELECT * FROM brands WHERE category_id = $category_id ORDER BY name";
    $result = mysqli_query($con, $sql);
    
    $brands = [];
    while ($row = mysqli_fetch_assoc($result)) 
    {
        $brands[] = 
        [
            'id' => $row['id'],
            'name' => $row['name']
        ];
    }
    
    // Output only the clean array data
    echo json_encode($brands);
} 
else 
{
    echo json_encode([]);
}
?>