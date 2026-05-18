<?php
// Done by: 22-49926-3
// Task 4: Admin AJAX Controller for deleting reviews/customers

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once('../config/db.php');

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

$con = getConnection();

if (!$con) 
{
    echo json_encode([
        'success' => false,
        'error' => 'Database connection failed'
    ]);
    exit();
}

$action = isset($_POST['action']) ? $_POST['action'] : '';


// Done by: 22-49926-3
// Task 4: Admin delete any review using AJAX
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

    $stmt = $con->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $review_id);
    $result = $stmt->execute();

    if ($result) 
    {
        echo json_encode([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
        exit();
    } 
    else 
    {
        echo json_encode([
            'success' => false,
            'error' => 'Review delete failed'
        ]);
        exit();
    }
}


// Done by: 22-49926-3
// Task 4: Admin delete customer with related records using AJAX
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

    mysqli_begin_transaction($con);

    try 
    {
        $checkStmt = $con->prepare("SELECT id FROM users WHERE id = ? AND role = 'customer'");
        $checkStmt->bind_param("i", $customer_id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows == 0) 
        {
            mysqli_rollback($con);

            echo json_encode([
                'success' => false,
                'error' => 'Customer not found'
            ]);
            exit();
        }

        $stmtOrderItems = $con->prepare("
            DELETE oi FROM order_items oi
            INNER JOIN orders o ON oi.order_id = o.id
            WHERE o.user_id = ?
        ");
        $stmtOrderItems->bind_param("i", $customer_id);
        $stmtOrderItems->execute();

        $stmtOrders = $con->prepare("DELETE FROM orders WHERE user_id = ?");
        $stmtOrders->bind_param("i", $customer_id);
        $stmtOrders->execute();

        $stmtCart = $con->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmtCart->bind_param("i", $customer_id);
        $stmtCart->execute();

        $stmtReviews = $con->prepare("DELETE FROM reviews WHERE user_id = ?");
        $stmtReviews->bind_param("i", $customer_id);
        $stmtReviews->execute();

        $stmtUser = $con->prepare("DELETE FROM users WHERE id = ? AND role = 'customer'");
        $stmtUser->bind_param("i", $customer_id);
        $stmtUser->execute();

        if ($stmtUser->affected_rows > 0) 
        {
            mysqli_commit($con);

            echo json_encode([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);
            exit();
        } 
        else 
        {
            mysqli_rollback($con);

            echo json_encode([
                'success' => false,
                'error' => 'Customer delete failed'
            ]);
            exit();
        }
    } 
    catch (Exception $e) 
    {
        mysqli_rollback($con);

        echo json_encode([
            'success' => false,
            'error' => 'Customer delete failed: ' . $e->getMessage()
        ]);
        exit();
    }
}

echo json_encode([
    'success' => false,
    'error' => 'Invalid action'
]);
exit();
?>