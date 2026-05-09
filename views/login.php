<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <p style="color:green;"><?php if(isset($_SESSION['msg'])){echo $_SESSION['msg']; unset($_SESSION['msg']);} ?></p>
    <a href="signup.php">Go to Signup</a>
</body>
</html>
