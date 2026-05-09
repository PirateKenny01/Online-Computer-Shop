<?php
session_start();
require_once('../models/userModel.php');

if (isset($_SESSION['user_id'])) {
    clearRememberToken($_SESSION['user_id']);
} else if (isset($_COOKIE['remember_token'])) {
    $token_hash = hash('sha256', $_COOKIE['remember_token']);
    $u = getUserByRememberToken($token_hash);
    if ($u) {
        clearRememberToken($u['id']);
    }
}

$_SESSION = [];

setcookie('remember_token', '', time() - 10, '/');
setcookie(session_name(), '', time() - 10, '/');

session_destroy();

header('location: ../views/login.php');
exit();
?>
