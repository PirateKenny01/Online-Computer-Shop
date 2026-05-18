<?php
// views/partials/navbar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Safely identify the logged-in user's role (lowercase check for consistency)
$userRole = isset($_SESSION['role']) ? strtolower(trim($_SESSION['role'])) : 'guest';
?>
<div style="background: #222; padding: 15px 30px; color: white; display: flex; justify-content: space-between; align-items: center; font-family: Arial, sans-serif; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    
    <div>
        <?php if ($userRole === 'admin'): ?>
            <a href="admin_dashboard.php" style="color: #ffc107; font-weight: bold; text-decoration: none; font-size: 18px; margin-right: 30px;">🛡️ Admin Panel</a>
            <a href="home.php" style="color: #bbb; text-decoration: none; margin-right: 20px;">Home</a>
            <a href="profile.php" style="color: #bbb; text-decoration: none; margin-right: 20px;">Profile</a>
            <a href="admin_create_user.php" style="color: #bbb; text-decoration: none; margin-right: 20px;">Admin Create User</a>
            <a href="admin_dashboard.php" style="color: #bbb; text-decoration: none; margin-right: 20px;">Dashboard</a>
            <a href="categories.php" style="color: #bbb; text-decoration: none; margin-right: 20px;">Categories</a>
            <a href="brands.php" style="color: #bbb; text-decoration: none; margin-right: 20px;">Brands</a>
            <a href="products.php" style="color: #bbb; text-decoration: none; margin-right: 20px;">Products</a>
        <?php else: ?>
            <a href="home.php" style="color: white; font-weight: bold; text-decoration: none; font-size: 18px; margin-right: 30px;">💻 OCS System</a>
            <a href="home.php" style="color: #bbb; text-decoration: none; margin-right: 20px;">Home</a>
            <a href="browse.php" style="color: #bbb; text-decoration: none;">Browse Catalog</a>
        <?php endif; ?>
    </div>
    
    <div style="display: flex; gap: 12px; align-items: center;">
        <?php if ($userRole === 'admin'): ?>
            <a href="logout.php" style="background: #dc3545; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold;">Logout</a>
        <?php else: ?>
            <a href="login.php" style="background: #007bff; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold;">Login</a>
            <a href="signup.php" style="background: #28a745; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold;">Sign Up</a>
            <a href="profile.php" style="background: #17a2b8; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold;">Profile View</a>
            <a href="cart.php" style="background: #ffc107; color: #222; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold;">My Cart 🛒</a>
        <?php endif; ?>
    </div>
</div>