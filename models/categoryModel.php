<?php
require_once('../config/db.php');

function getTopCategories()
{
    $con = getConnection();
    $sql = "select id, name from categories where parent_id is null order by name asc";
    $result = mysqli_query($con, $sql);

    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }

    mysqli_close($con);
    return $categories;
}

function getCategoryById($id)
{
    $con = getConnection();
    $sql = "select id, name, parent_id from categories where id=? limit 1";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $category = null;
    if (mysqli_num_rows($result) == 1) {
        $category = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
    return $category;
}
?>
