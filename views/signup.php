<?php
    session_start();
    $errors = isset($_SESSION['signup_errors']) ? $_SESSION['signup_errors'] : [];
    $old = isset($_SESSION['signup_old']) ? $_SESSION['signup_old'] : [];
    unset($_SESSION['signup_errors']);
    unset($_SESSION['signup_old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signup</title>
</head>
<body>
    <?php include('partials/navbar.php'); ?>

    <h1>Signup</h1>
    <p id="form_msg" style="color:red;"><?php if(isset($errors['form'])){echo $errors['form'];} ?></p>

    <form method="post" action="../controllers/signupCheck.php" onsubmit="return validateSignup()">
        Name: <input type="text" id="name" name="name" value="<?php if(isset($old['name'])){echo htmlspecialchars($old['name']);} ?>"/> <br>
        Email: <input type="text" id="email" name="email" onkeyup="checkEmailAvailability()" value="<?php if(isset($old['email'])){echo htmlspecialchars($old['email']);} ?>"/> <br>
        <p id="email_msg" style="color:red;"><?php if(isset($errors['email']) || isset($errors['email_exists'])){echo isset($errors['email']) ? $errors['email'] : $errors['email_exists'];} ?></p>

        Password: <input type="password" id="password" name="password" value=""/> <br>
        Confirm Password: <input type="password" id="confirm_password" name="confirm_password" value=""/> <br>
        <p style="color:red;"><?php if(isset($errors['password'])){echo $errors['password'];} ?></p>
        <p style="color:red;"><?php if(isset($errors['confirm_password'])){echo $errors['confirm_password'];} ?></p>

        <input type="submit" name="signup_submit" value="Signup"/>
        <a href="login.php">Login</a>
    </form>

    <script src="../public/js/main.js"></script>
</body>
</html>
