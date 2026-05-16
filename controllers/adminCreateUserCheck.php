<?php
    session_start();
    require_once('../models/userModel.php');

    if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
        header('location: ../views/login.php');
        exit();
    }

    if(isset($_POST['admin_create_submit'])){
        $name       = trim($_REQUEST['name']);
        $email      = trim($_REQUEST['email']);
        $password   = $_REQUEST['password'];
        $confirm    = $_REQUEST['confirm_password'];
        $role       = $_REQUEST['role'];

        $errors = [];

        if($name == "" || $email == "" || $password == "" || $confirm == "" || $role == ""){
            $errors['form'] = "null input found!";
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors['email'] = "invalid email format!";
        }

        if(strlen($password) < 8){
            $errors['password'] = "password must be at least 8 characters!";
        }

        if($password != $confirm){
            $errors['confirm_password'] = "password and confirm password mismatch!";
        }

        if($role != "admin" && $role != "customer"){
            $errors['role'] = "invalid role selected!";
        }

        if(emailExists($email)){
            $errors['email_exists'] = "email already exists!";
        }

        if(count($errors) > 0){
            $_SESSION['admin_create_errors'] = $errors;
            $_SESSION['admin_create_old'] = ['name'=>$name, 'email'=>$email, 'role'=>$role];
            header('location: ../views/admin_create_user.php');
            exit();
        }else{
            $user = [
                'name' => $name,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role
            ];

            if(addUser($user)){
                $_SESSION['msg'] = "user created successfully.";
            }else{
                $_SESSION['msg'] = "user create failed.";
            }

            header('location: ../views/admin_create_user.php');
            exit();
        }
    }else{
        echo "invalid request!";
    }
?>
