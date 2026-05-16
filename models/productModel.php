<?php
require_once('../config/db.php');

function getFeaturedProducts()
{
    $con = getConnection();
    $sql = "select id, name, manufacturer_review, price, category_id from products order by created_at desc limit 6";
    $result = mysqli_query($con, $sql);

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    mysqli_close($con);
    return $products;
}

function getProductsByTopCategory($category_id)
{
    $con = getConnection();
    $sql = "select p.id, p.name, p.manufacturer_review, p.price, c.name as category_name
                from products p
                inner join categories c on c.id = p.category_id
                where c.id=? or c.parent_id=?
                order by p.created_at desc";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $category_id, $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
    return $products;
}
?>
