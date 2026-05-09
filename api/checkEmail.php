<?php
header('Content-Type: application/json');
require_once('../models/userModel.php');

if (!isset($_GET['email'])) {
    echo json_encode(['status' => false, 'message' => 'email required']);
    exit();
}

$email = trim($_GET['email']);

if ($email == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => false, 'message' => 'invalid email']);
    exit();
}

if (emailExists($email)) {
    echo json_encode(['status' => true, 'available' => false, 'message' => 'email already exists']);
} else {
    echo json_encode(['status' => true, 'available' => true, 'message' => 'email available']);
}
?>
