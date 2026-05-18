<?php
// views/login.php
session_start();
require_once __DIR__ . '/../models/userModel.php';

$errorMsg = "";
// Detect if user was redirected from clicking checkout in the cart
$isCheckoutIntercept = isset($_GET['checkout_intercept']) || (isset($_POST['intercept_flag']) && $_POST['intercept_flag'] === '1');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $formRedirectToOrder = isset($_POST['intercept_flag']) && $_POST['intercept_flag'] === '1';

    if (empty($email) || empty($password)) {
        $errorMsg = "Please populate both login credential fields.";
    } else {
        $con = getConnection();
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if ($user) {
            // FIXED: Using your exact database column name 'password_hash'
            $dbStoredPassword = $user['password_hash'];
            
            // Checks plain-text comparison or standard bcrypt hash verification automatically
            $isPasswordCorrect = ($password === $dbStoredPassword) || password_verify($password, $dbStoredPassword);

            if ($isPasswordCorrect) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'] ?? 'User';
                $_SESSION['role'] = isset($user['role']) ? $user['role'] : 'customer';

                // Condition 1 Rule Redirect Logic:
                if ($isCheckoutIntercept) {
                    header("Location: order.php");
                } else {
                    // Check explicit roles to route accounts to their specific dashboards
                    if (strtolower($_SESSION['role']) === 'admin') {
                        header("Location: admin_dashboard.php"); 
                    } else {
                        header("Location: home.php");
                    }
                }
                exit;
            } else {
                $errorMsg = "Invalid username email address or incorrect password.";
            }
        } else {
            $errorMsg = "Invalid username email address or incorrect password.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Account Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #fafafa; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); width: 360px; }
        .input-field { width: 100%; padding: 10px; margin: 10px 0 20px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .submit-btn { background: #007bff; color: white; border: none; width: 100%; padding: 12px; font-weight: bold; border-radius: 4px; cursor: pointer; font-size: 15px; }
        .submit-btn:hover { background: #0056b3; }
        .notice-banner { background: #fff3cd; color: #856404; padding: 10px; border: 1px solid #ffeeba; border-radius: 4px; margin-bottom: 15px; font-size: 13px; font-weight: bold; text-align: center; }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Sign-In Checkpoint</h2>
        
        <?php if($isCheckoutIntercept): ?>
            <div class="notice-banner">🔑 Please login first to complete your payment order!</div>
        <?php endif; ?>

        <?php if(!empty($errorMsg)): ?>
            <p style="color: #dc3545; font-weight: bold; font-size: 14px;"><?php echo $errorMsg; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="hidden" name="intercept_flag" value="<?php echo $isCheckoutIntercept ? '1' : '0'; ?>">

            <label>Email Address / Username</label>
            <input type="text" name="email" class="input-field" required placeholder="user@example.com">

            <label>Password Secret Key</label>
            <input type="password" name="password" class="input-field" required placeholder="••••••••">

            <button type="submit" class="submit-btn">Verify and Continue</button>
        </form>
        <br>
        <a href="home.php" style="text-decoration:none; color:#666; font-size:14px; display:block; text-align:center;">Cancel & Go Back</a>
    </div>

</body>
</html>