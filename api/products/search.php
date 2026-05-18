<?php
// api/products/search.php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../models/productModel.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$minPrice = (isset($_GET['min']) && $_GET['min'] !== '') ? floatval($_GET['min']) : 0.0;
$maxPrice = (isset($_GET['max']) && $_GET['max'] !== '') ? floatval($_GET['max']) : 99999999.0;

$catId = (isset($_GET['category_id']) && $_GET['category_id'] !== '') ? intval($_GET['category_id']) : 0;
$brandId = (isset($_GET['brand_id']) && $_GET['brand_id'] !== '') ? intval($_GET['brand_id']) : 0;

$products = searchAndFilterProducts($q, $minPrice, $maxPrice, $catId, $brandId);

foreach ($products as &$p) {
    $imgKey = isset($p['image']) ? 'image' : (isset($p['product_image']) ? 'product_image' : null);
    if ($imgKey && !empty($p[$imgKey])) {
        // Only base64 encode if the field contains actual binary data instead of a text path string
        if (preg_match('~[^\x20-\x7E\t\r\n]~', $p[$imgKey])) {
            $p[$imgKey] = base64_encode($p[$imgKey]);
        }
    }
}

echo json_encode($products);
exit;
?>