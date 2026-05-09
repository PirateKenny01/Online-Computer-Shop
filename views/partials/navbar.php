<?php
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
?>
<div>
    <?php if ($role == 'admin') { ?>
        <a href="home.php">Home</a> |
        <a href="profile.php">Profile</a> |
        <a href="admin_create_user.php">Admin Create User</a> |
        <a href="../controllers/logout.php">Logout</a>
    <?php } else if ($role == 'customer') { ?>
        <a href="home.php">Home</a> |
        <a href="profile.php">Profile</a> |
        <a href="../controllers/logout.php">Logout</a>
    <?php } else { ?>
        <a href="home.php">Home</a> |
        <a href="login.php">Login</a> |
        <a href="signup.php">Signup</a>
    <?php } ?>
</div>
<hr>
