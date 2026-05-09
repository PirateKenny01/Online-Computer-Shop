<?php
session_start();
require_once('../models/userModel.php');

if (isset($_POST['login_submit'])) {
    $email = trim($_REQUEST['email']);
    $password = $_REQUEST['password'];
    $remember = isset($_REQUEST['remember']) ? $_REQUEST['remember'] : "";

    if ($email == "" || $password == "") {
        $_SESSION['login_error'] = "null email/password!";
        $_SESSION['login_old_email'] = $email;
        header('location: ../views/login.php');
        exit();
    } else {
        $user = getUserByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($remember == "yes") {
                $token = bin2hex(random_bytes(32));
                $token_hash = hash('sha256', $token);
                updateRememberToken($user['id'], $token_hash);
                setcookie('remember_token', $token, time() + 60 * 60 * 24 * 30, '/');
            } else {
                clearRememberToken($user['id']);
                setcookie('remember_token', '', time() - 10, '/');
            }

            header('location: ../views/home.php');
            exit();
        } else {
            $_SESSION['login_error'] = "invalid email/password!";
            $_SESSION['login_old_email'] = $email;
            header('location: ../views/login.php');
            exit();
        }
    }
} else {
    echo "invalid request!";
}
?>
