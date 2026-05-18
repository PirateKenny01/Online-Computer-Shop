<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('../models/ReviewModel.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'error' => 'Controller is working. Use POST request from review form.'
    ]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Login required'
    ]);
    exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action == 'add') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    if ($product_id <= 0) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid product ID'
        ]);
        exit;
    }

    if ($comment == '') {
        echo json_encode([
            'success' => false,
            'error' => 'Review cannot be empty'
        ]);
        exit;
    }

    if (strlen($comment) > 500) {
        echo json_encode([
            'success' => false,
            'error' => 'Review must be less than 500 characters'
        ]);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $reviewer_name = $_SESSION['name'];

    $review_id = addReview($product_id, $user_id, $reviewer_name, $comment);

    if ($review_id) {
        $review = getReviewById($review_id);

        echo json_encode([
            'success' => true,
            'review' => $review
        ]);
        exit;
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Database insert failed'
        ]);
        exit;
    }
}

if ($action == 'update') {
    $review_id = isset($_POST['review_id']) ? (int)$_POST['review_id'] : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    if ($review_id <= 0) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid review ID'
        ]);
        exit;
    }

    if ($comment == '') {
        echo json_encode([
            'success' => false,
            'error' => 'Review cannot be empty'
        ]);
        exit;
    }

    if (strlen($comment) > 500) {
        echo json_encode([
            'success' => false,
            'error' => 'Review must be less than 500 characters'
        ]);
        exit;
    }

    $result = updateOwnReview($review_id, $_SESSION['user_id'], $comment);

    echo json_encode([
        'success' => $result,
        'comment' => $comment
    ]);
    exit;
}

if ($action == 'delete') {
    $review_id = isset($_POST['review_id']) ? (int)$_POST['review_id'] : 0;

    if ($review_id <= 0) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid review ID'
        ]);
        exit;
    }

    $result = deleteOwnReview($review_id, $_SESSION['user_id']);

    echo json_encode([
        'success' => $result
    ]);
    exit;
}

echo json_encode([
    'success' => false,
    'error' => 'Invalid action'
]);
exit;
?>