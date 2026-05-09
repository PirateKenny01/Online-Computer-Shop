<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('location: login.php');
    exit();
}

$errors = isset($_SESSION['admin_create_errors']) ? $_SESSION['admin_create_errors'] : [];
$old = isset($_SESSION['admin_create_old']) ? $_SESSION['admin_create_old'] : [];
unset($_SESSION['admin_create_errors']);
unset($_SESSION['admin_create_old']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Create User</title>
</head>

<body>
    <?php include('partials/navbar.php'); ?>

    <h1>Admin Dashboard - Create User</h1>
    <p style="color:green;"><?php if (isset($_SESSION['msg'])) {
                                echo $_SESSION['msg'];
                                unset($_SESSION['msg']);
                            } ?></p>
    <p id="form_msg" style="color:red;"><?php if (isset($errors['form'])) {
                                            echo $errors['form'];
                                        } ?></p>

    <form method="post" action="../controllers/adminCreateUserCheck.php" onsubmit="return validateSignup()">
        Name: <input type="text" id="name" name="name" value="<?php if (isset($old['name'])) {
                                                                    echo htmlspecialchars($old['name']);
                                                                } ?>" /> <br>
        Email: <input type="text" id="email" name="email" onkeyup="checkEmailAvailability()" value="<?php if (isset($old['email'])) {
                                                                                                        echo htmlspecialchars($old['email']);
                                                                                                    } ?>" /> <br>
        <p id="email_msg" style="color:red;"><?php if (isset($errors['email']) || isset($errors['email_exists'])) {
                                                    echo isset($errors['email']) ? $errors['email'] : $errors['email_exists'];
                                                } ?></p>

        Password: <input type="password" id="password" name="password" value="" /> <br>
        Confirm Password: <input type="password" id="confirm_password" name="confirm_password" value="" /> <br>
        <p style="color:red;"><?php if (isset($errors['password'])) {
                                    echo $errors['password'];
                                } ?></p>
        <p style="color:red;"><?php if (isset($errors['confirm_password'])) {
                                    echo $errors['confirm_password'];
                                } ?></p>

        Role:
        <select name="role">
            <option value="customer" <?php if (isset($old['role']) && $old['role'] == "customer") {
                                            echo "selected";
                                        } ?>>Customer</option>
            <option value="admin" <?php if (isset($old['role']) && $old['role'] == "admin") {
                                        echo "selected";
                                    } ?>>Admin</option>
        </select>
        <p style="color:red;"><?php if (isset($errors['role'])) {
                                    echo $errors['role'];
                                } ?></p>

        <input type="submit" name="admin_create_submit" value="Create User" />
        <a href="home.php">Back</a>
    </form>

    <script src="../public/js/main.js"></script>
</body>

</html>
