<?php
// views/home.php
session_start();
require_once('../models/userModel.php');
require_once('../models/categoryModel.php');
require_once('../models/productModel.php');

$categories = getTopCategories();
$featuredProducts = getFeaturedProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home - Computer Shop</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #fafafa; }
        .container { padding: 30px; }
        .component-link { color: #007bff; font-weight: bold; text-decoration: none; }
        .component-link:hover { text-decoration: underline; }
        .cat-bar { background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 20px; }
        .cat-link { font-weight: bold; text-decoration: none; color: #007bff; margin-right: 15px; font-size: 16px; }
        
        /* CRITICAL FIXED: Strong CSS declarations to permanently kill image column tremors */
        .image-frame {
            width: 60px !important;
            height: 60px !important;
            min-width: 60px !important;
            min-height: 60px !important;
            max-width: 60px !important;
            max-height: 60px !important;
            display: inline-block;
            background: #fdfdfd;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .image-frame img {
            width: 100% !important;
            height: 100% !important;
            object-fit: contain;
            display: block;
        }

        .truncate-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 450px;
            display: block;
        }
    </style>
</head>
<body>
    <?php include('partials/navbar.php'); ?>

    <div class="container">
        <h1>Online Computer Shop</h1>
        
        <div style="margin-bottom: 20px;">
            <a href="browse.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block;">🔍 SEARCH & FILTER</a>
        </div>

        <h3>Category Bar</h3>
        <div class="cat-bar">
            <?php foreach ($categories as $cat): ?>
                <a class="cat-link" href="browse.php?category_id=<?php echo $cat['id']; ?>">
                    📁 <?php echo htmlspecialchars($cat['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <hr style="border:0; border-top: 1px solid #ddd; margin: 30px 0;">

        <h3>Featured Components</h3>
        <?php if (count($featuredProducts) > 0): ?>
            <table border="1" cellpadding="12" cellspacing="0" style="background: white; border-collapse: collapse; border-color: #ddd; width: 100%; table-layout: fixed;">
                <colgroup>
                    <col style="width: 100px;"> <col style="width: 300px;">
                    <col style="width: 500px;">
                    <col style="width: 150px;">
                </colgroup>
                <tr style="background-color: #f4f4f4; text-align: left;">
                    <th>Preview Image</th>
                    <th>Component Name</th>
                    <th>Manufacturer Review Summary</th>
                    <th>Price</th>
                </tr>
                
                <?php foreach ($featuredProducts as $p): ?>
                    <tr style="height: 85px;"> <td style="text-align: center; vertical-align: middle; width: 100px;">
                            <div class="image-frame">
                                <?php 
                                $dbValue = isset($p['image_path']) ? trim($p['image_path']) : '';
                                $dbValue = ltrim($dbValue, '/');

                                if (!empty($dbValue)) {
                                    $imgSrc = "../" . $dbValue;
                                } else {
                                    $imgSrc = "../images/no-image.png";
                                }
                                ?>
                                <img src="<?php echo $imgSrc; ?>" alt="Component Item">
                            </div>
                        </td>
                        <td style="vertical-align: middle;">
                            <div class="truncate-text" style="max-width: 280px;">
                                <a class="component-link" href="product_detail.php?id=<?php echo $p['id']; ?>">
                                    🔹 <?php echo htmlspecialchars($p['name']); ?>
                                </a>
                            </div>
                        </td>
                        <td style="vertical-align: middle; color: #555;">
                            <span class="truncate-text">
                                <?php echo htmlspecialchars($p['manufacturer_review'] ?? 'No reviews listed.'); ?>
                            </span>
                        </td>
                        <td style="vertical-align: middle;">
                            <strong>৳<?php echo number_format((float)$p['price'], 2); ?></strong>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No featured products found.</p>
        <?php endif; ?>
    </div>
</body>
</html>