<?php
    session_start();
    require_once('../models/userModel.php');

    if(!isset($_SESSION['user_id'])){
        if(isset($_COOKIE['remember_token'])){
            $token_hash = hash('sha256', $_COOKIE['remember_token']);
            $user = getUserByRememberToken($token_hash);

            if($user){
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
            }else{
                setcookie('remember_token', '', time()-10, '/');
                header('location: login.php');
                exit();
            }
        }else{
            header('location: login.php');
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
</head>
<body>
    <?php include('partials/navbar.php'); ?>

    <h1>Welcome <?php echo $_SESSION['name']; ?></h1>
    <p>Role: <?php echo $_SESSION['role']; ?></p>
</body>
</html>
