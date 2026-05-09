<?php
    session_start();
    require_once('../models/userModel.php');

    if(!isset($_SESSION['user_id'])){
        header('location: ../views/login.php');
        exit();
    }

    if(isset($_POST['password_change_submit'])){
        $id = $_SESSION['user_id'];
        $current_password = $_REQUEST['current_password'];
        $new_password = $_REQUEST['new_password'];
        $confirm_password = $_REQUEST['confirm_password'];

        $errors = [];

        if($current_password == "" || $new_password == "" || $confirm_password == ""){
            $errors['form'] = "null password input!";
        }

        if(strlen($new_password) < 8){
            $errors['new_password'] = "new password must be at least 8 characters!";
        }

        if($new_password != $confirm_password){
            $errors['confirm_password'] = "new and confirm password mismatch!";
        }

        $user = getUserById($id);
        if($user && !password_verify($current_password, $user['password_hash'])){
            $errors['current_password'] = "current password not matched!";
        }

        if(count($errors) > 0){
            $_SESSION['password_errors'] = $errors;
            header('location: ../views/profile.php');
            exit();
        }else{
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);

            if(updateUserPassword($id, $new_hash)){
                $_SESSION['profile_success'] = "password updated successfully.";
            }else{
                $_SESSION['password_errors'] = ['db' => 'password update failed!'];
            }

            header('location: ../views/profile.php');
            exit();
        }
    }else{
        echo "invalid request!";
    }
?>
