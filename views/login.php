<?php
session_start();
require_once('../models/userModel.php');

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token_hash = hash('sha256', $_COOKIE['remember_token']);
    $user = getUserByRememberToken($token_hash);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        header('location: home.php');
        exit();
    } else {
        setcookie('remember_token', '', time() - 10, '/');
    }
}

if (isset($_SESSION['user_id'])) {
    header('location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
</head>

<body>
    <?php include('partials/navbar.php'); ?>

    <h1>Login</h1>
    <p style="color:green;"><?php if (isset($_SESSION['msg'])) {
                                echo $_SESSION['msg'];
                                unset($_SESSION['msg']);
                            } ?></p>
    <p style="color:red;"><?php if (isset($_SESSION['login_error'])) {
                                echo $_SESSION['login_error'];
                                unset($_SESSION['login_error']);
                            } ?></p>

    <form method="post" action="../controllers/loginCheck.php">
        Email: <input type="text" name="email" value="<?php if (isset($_SESSION['login_old_email'])) {
                                                            echo htmlspecialchars($_SESSION['login_old_email']);
                                                            unset($_SESSION['login_old_email']);
                                                        } ?>" /> <br>
        Password: <input type="password" name="password" value="" /> <br>
        Remember Me: <input type="checkbox" name="remember" value="yes" /> <br>
        <input type="submit" name="login_submit" value="Login" />
        <a href="signup.php">Signup</a>
    </form>
</body>

</html>
