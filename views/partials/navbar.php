<?php
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
?>
<div>
    <?php if ($role == 'admin') { ?>
        <a href="home.php">Home</a> |
        <a href="profile.php">Profile</a> |
        <a href="admin_create_user.php">Admin Create User</a> |

        <a href="Admin_Dashboard.php">Dashboard</a> | 
        <a href="categories.php">Categories</a> | 
        <a href="brands.php">Brands</a> | 
        <a href="products.php">Products</a> |
        
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
