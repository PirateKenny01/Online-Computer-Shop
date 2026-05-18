<?php
require_once('../config/db.php');

function getReviewsByProduct($product_id)
{
    $con = getConnection();

    $stmt = $con->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    return $stmt->get_result();
}

function addReview($product_id, $user_id, $reviewer_name, $comment)
{
    $con = getConnection();

    $stmt = $con->prepare("INSERT INTO reviews (product_id, user_id, reviewer_name, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $product_id, $user_id, $reviewer_name, $comment);

    if ($stmt->execute()) {
        return $con->insert_id;
    }

    return false;
}

function getReviewById($review_id)
{
    $con = getConnection();

    $stmt = $con->prepare("SELECT * FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function updateOwnReview($review_id, $user_id, $comment)
{
    $con = getConnection();

    $stmt = $con->prepare("UPDATE reviews SET comment = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $comment, $review_id, $user_id);

    return $stmt->execute();
}

function deleteOwnReview($review_id, $user_id)
{
    $con = getConnection();

    $stmt = $con->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $review_id, $user_id);

    return $stmt->execute();
}
?>