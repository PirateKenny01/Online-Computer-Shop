<?php
// Done by: 22-49926-3
// Task 4: Admin AJAX Controller for deleting customers and reviews

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once('../models/AdminTask4Model.php');

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') 
{
    echo json_encode([
        'success' => false,
        'error' => 'Admin access required'
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
{
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method'
    ]);
    exit();
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action == 'delete_review') 
{
    $review_id = isset($_POST['review_id']) ? (int)$_POST['review_id'] : 0;

    if ($review_id <= 0) 
    {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid review ID'
        ]);
        exit();
    }

    $result = deleteReviewByAdmin($review_id);

    if ($result) 
    {
        echo json_encode([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
        exit();
    }

    echo json_encode([
        'success' => false,
        'error' => 'Review delete failed'
    ]);
    exit();
}

if ($action == 'delete_customer') 
{
    $customer_id = isset($_POST['customer_id']) ? (int)$_POST['customer_id'] : 0;

    if ($customer_id <= 0) 
    {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid customer ID'
        ]);
        exit();
    }

    $result = deleteCustomerByAdmin($customer_id);

    if ($result) 
    {
        echo json_encode([
            'success' => true,
            'message' => 'Customer deleted successfully'
        ]);
        exit();
    }

    echo json_encode([
        'success' => false,
        'error' => 'Customer delete failed'
    ]);
    exit();
}

echo json_encode([
    'success' => false,
    'error' => 'Invalid action'
]);
exit();
?>