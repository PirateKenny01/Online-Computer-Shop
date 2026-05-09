<?php
    session_start();
    require_once('../models/userModel.php');

    if(isset($_SESSION['user_id'])){
        clearRememberToken($_SESSION['user_id']);
    }

    session_destroy();
    setcookie('remember_token', '', time()-10, '/');
    header('location: ../views/login.php');
?>
