<?php
session_start();
require_once('../models/userModel.php');

if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['remember_token'])) {
        $token_hash = hash('sha256', $_COOKIE['remember_token']);
        $rememberUser = getUserByRememberToken($token_hash);

        if ($rememberUser) {
            $_SESSION['user_id'] = $rememberUser['id'];
            $_SESSION['name'] = $rememberUser['name'];
            $_SESSION['role'] = $rememberUser['role'];
        } else {
            setcookie('remember_token', '', time() - 10, '/');
            header('location: login.php');
            exit();
        }
    } else {
        header('location: login.php');
        exit();
    }
}

$user = getUserById($_SESSION['user_id']);
if (!$user) {
    session_destroy();
    setcookie('remember_token', '', time() - 10, '/');
    header('location: login.php');
    exit();
}

$profileErrors = isset($_SESSION['profile_errors']) ? $_SESSION['profile_errors'] : [];
$passwordErrors = isset($_SESSION['password_errors']) ? $_SESSION['password_errors'] : [];
$old = isset($_SESSION['profile_old']) ? $_SESSION['profile_old'] : [];

unset($_SESSION['profile_errors']);
unset($_SESSION['password_errors']);
unset($_SESSION['profile_old']);

$nameValue = isset($old['name']) ? $old['name'] : $user['name'];
$emailValue = isset($old['email']) ? $old['email'] : $user['email'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile</title>
</head>

<body>
    <?php include('partials/navbar.php'); ?>

    <h1>Profile</h1>

    <p style="color:green;"><?php if (isset($_SESSION['profile_success'])) {
                                echo $_SESSION['profile_success'];
                                unset($_SESSION['profile_success']);
                            } ?></p>

    <h3>Update Profile</h3>
    <p id="profile_msg" style="color:red;"><?php if (isset($profileErrors['form'])) {
                                                echo $profileErrors['form'];
                                            } ?></p>
    <p style="color:red;"><?php if (isset($profileErrors['email'])) {
                                echo $profileErrors['email'];
                            } ?></p>
    <p style="color:red;"><?php if (isset($profileErrors['email_exists'])) {
                                echo $profileErrors['email_exists'];
                            } ?></p>
    <p style="color:red;"><?php if (isset($profileErrors['picture'])) {
                                echo $profileErrors['picture'];
                            } ?></p>
    <p style="color:red;"><?php if (isset($profileErrors['db'])) {
                                echo $profileErrors['db'];
                            } ?></p>

    <?php if ($user['profile_picture'] != "") { ?>
        <img src="../<?php echo htmlspecialchars($user['profile_picture']); ?>" width="120" height="120" /> <br><br>
    <?php } ?>

    <form method="post" action="../controllers/profileUpdateCheck.php" enctype="multipart/form-data" onsubmit="return validateProfileUpdate()">
        Name: <input type="text" id="profile_name" name="name" value="<?php echo htmlspecialchars($nameValue); ?>" /> <br>
        Email: <input type="text" id="profile_email" name="email" value="<?php echo htmlspecialchars($emailValue); ?>" /> <br>
        Profile Picture: <input type="file" name="profile_picture" /> <br>
        <input type="submit" name="profile_update_submit" value="Update Profile" />
    </form>

    <hr>

    <h3>Change Password</h3>
    <p id="password_msg" style="color:red;"><?php if (isset($passwordErrors['form'])) {
                                                echo $passwordErrors['form'];
                                            } ?></p>
    <p style="color:red;"><?php if (isset($passwordErrors['current_password'])) {
                                echo $passwordErrors['current_password'];
                            } ?></p>
    <p style="color:red;"><?php if (isset($passwordErrors['new_password'])) {
                                echo $passwordErrors['new_password'];
                            } ?></p>
    <p style="color:red;"><?php if (isset($passwordErrors['confirm_password'])) {
                                echo $passwordErrors['confirm_password'];
                            } ?></p>
    <p style="color:red;"><?php if (isset($passwordErrors['db'])) {
                                echo $passwordErrors['db'];
                            } ?></p>

    <form method="post" action="../controllers/passwordChangeCheck.php" onsubmit="return validatePasswordChange()">
        Current Password: <input type="password" id="current_password" name="current_password" value="" /> <br>
        New Password: <input type="password" id="new_password" name="new_password" value="" /> <br>
        Confirm Password: <input type="password" id="confirm_password_change" name="confirm_password" value="" /> <br>
        <input type="submit" name="password_change_submit" value="Change Password" />
    </form>

    <script src="../public/js/main.js"></script>
</body>

</html>
