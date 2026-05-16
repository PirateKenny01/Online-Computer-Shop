<?php
session_start();
require_once('../models/userModel.php');
require_once('../models/categoryModel.php');
require_once('../models/productModel.php');

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token_hash = hash('sha256', $_COOKIE['remember_token']);
    $user = getUserByRememberToken($token_hash);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
    } else {
        setcookie('remember_token', '', time() - 10, '/');
    }
}

$categories = getTopCategories();

$categoryId = 0;
if (isset($_GET['id'])) {
    $categoryId = (int)$_GET['id'];
}

$selectedCategory = null;
$products = [];

if ($categoryId > 0) {
    $selectedCategory = getCategoryById($categoryId);
    if ($selectedCategory) {
        $products = getProductsByTopCategory($categoryId);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Category Products</title>
</head>

<body>
    <?php include('partials/navbar.php'); ?>

    <h2>Category Bar</h2>
    <div>
        <?php if (count($categories) > 0) { ?>
            <?php foreach ($categories as $cat) { ?>
                <a href="category.php?id=<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></a> |
            <?php } ?>
        <?php } else { ?>
            <p>No categories found.</p>
        <?php } ?>
    </div>

    <hr>

    <?php if (!$selectedCategory) { ?>
        <p>Invalid category.</p>
    <?php } else { ?>
        <h3>Products of: <?php echo htmlspecialchars($selectedCategory['name']); ?></h3>

        <?php if (count($products) > 0) { ?>
            <table border="1" cellpadding="8" cellspacing="0">
                <tr>
                    <th>Name</th>
                    <th>Manufacturer Review</th>
                    <th>Price</th>
                </tr>
                <?php foreach ($products as $p) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                        <td>
                            <?php
                            $review = trim((string)$p['manufacturer_review']);
                            if ($review == "") {
                                echo "No review available";
                            } else {
                                $short = substr($review, 0, 100);
                                if (strlen($review) > 100) {
                                    $short .= "...";
                                }
                                echo htmlspecialchars($short);
                            }
                            ?>
                        </td>
                        <td><?php echo number_format((float)$p['price'], 2); ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>No products found in this category.</p>
        <?php } ?>
    <?php } ?>
</body>

</html>
