<?php
// Start the session to check for user roles
session_start();

/**
 * Logic Check: 
 * If the user is logged in as Admin, send them to the Dashboard.
 * Otherwise, send them to the Login page (Task 1's responsibility).
 * * For now, since you are designing, I have commented out the strict check 
 * so you can navigate freely.
 */

/*
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // If not admin, redirect to login (usually in view/login.php)
    header('Location: view/login.php');
    exit();
}
*/

// Redirect to your Task 2 Main Page
header('Location: views/Admin_Dashboard.php');
exit();
?>