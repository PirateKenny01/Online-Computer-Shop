<?php
session_start();
require_once('../models/userModel.php');

if (isset($_POST['signup_submit'])) {
    $name       = trim($_REQUEST['name']);
    $email      = trim($_REQUEST['email']);
    $password   = $_REQUEST['password'];
    $confirm    = $_REQUEST['confirm_password'];

    $errors = [];

    if ($name == "" || $email == "" || $password == "" || $confirm == "") {
        $errors['form'] = "null input found!";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "invalid email format!";
    }

    if (strlen($password) < 8) {
        $errors['password'] = "password must be at least 8 characters!";
    }

    if ($password != $confirm) {
        $errors['confirm_password'] = "password and confirm password mismatch!";
    }

    if (emailExists($email)) {
        $errors['email_exists'] = "email already exists!";
    }

    if (count($errors) > 0) {
        $_SESSION['signup_errors'] = $errors;
        $_SESSION['signup_old'] = ['name' => $name, 'email' => $email];
        header('location: ../views/signup.php');
        exit();
    } else {
        $user = [
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'customer'
        ];

        if (addUser($user)) {
            $_SESSION['msg'] = "registration successful. please login.";
            header('location: ../views/login.php');
            exit();
        } else {
            $_SESSION['signup_errors'] = ['db' => 'registration failed!'];
            $_SESSION['signup_old'] = ['name' => $name, 'email' => $email];
            header('location: ../views/signup.php');
            exit();
        }
    }
} else {
    echo "invalid request!";
}
?>
