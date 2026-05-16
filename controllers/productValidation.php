<?php
header('Content-Type: application/json');

// Get data from both POST and XMLHttpRequest formats
$inputData = [];

// Format 1: XMLHttpRequest with application/x-www-form-urlencoded (validation=JSON)
if (isset($_POST['validation'])) 
{
    $inputData = json_decode($_POST['validation'], true);
}
// Format 2: JSON from php://input
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !$_FILES) 
{
    $inputData = json_decode(file_get_contents("php://input"), true);
}

// Response array
$response = [
    'success' => false,
    'errors' => []
];

// ==========================================
// 1. PRODUCT IMAGE VALIDATION FUNCTION
// ==========================================
function validateProductImage() 
{
    global $inputData, $response;
    
    if (!isset($_FILES['product_image'])) 
    {
        $response['errors']['image'] = "No image file provided";
        return false;
    }
    
    $file = $_FILES['product_image'];
    $file_name = $file['name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    
    // Rule A: Check for upload errors
    if ($file_error !== UPLOAD_ERR_OK) 
    {
        $response['errors']['image'] = "File upload error. Please try again.";
        return false;
    }
    
    // Rule B: Check format (jpeg, jpg, png)
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpeg', 'jpg', 'png'];
    
    if (!in_array($file_ext, $allowed_ext)) 
    {
        $response['errors']['image'] = "Only JPEG, JPG, and PNG files are allowed.";
        return false;
    }
    
    // Rule C: Check size (2MB = 2 * 1024 * 1024 bytes)
    $max_size = 2 * 1024 * 1024;
    
    if ($file_size > $max_size) 
    {
        $response['errors']['image'] = "Image size must be less than 2MB. Current size: " . round($file_size / 1024 / 1024, 2) . "MB";
        return false;
    }
    
    return true;
}

// ==========================================
// 2. PRODUCT PRICE VALIDATION FUNCTION
// ==========================================
function validateProductPrice() 
{
    global $inputData, $response;
    
    if (!isset($inputData['price'])) 
    {
        $response['errors']['price'] = "Price is required";
        return false;
    }
    
    $price = $inputData['price'];
    
    // Rule A: Check if price is numeric
    if (!is_numeric($price)) 
    {
        $response['errors']['price'] = "Price must be a valid number.";
        return false;
    }
    
    // Rule B: Check if price is positive
    if ($price <= 0) 
    {
        $response['errors']['price'] = "Price must be a positive number greater than 0.";
        return false;
    }
    
    // Rule C: Check for reasonable decimal places (max 2)
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $price)) 
    {
        $response['errors']['price'] = "Price can have maximum 2 decimal places.";
        return false;
    }
    
    return true;
}

// ==========================================
// PROCESS REQUEST
// ==========================================

// Check if this is a validation request
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    // Determine which validation to run
    if (isset($inputData['action']) || isset($_POST['action'])) 
    {
        $action = isset($inputData['action']) ? $inputData['action'] : $_POST['action'];
        
        if ($action === 'validate_all') 
        {
            // Validate both price and image
            $isPriceValid = validateProductPrice();
            $isImageValid = validateProductImage();
            
            if ($isPriceValid && $isImageValid) 
            {
                $response['success'] = true;
            }
        }
        elseif ($action === 'validate_price') 
        {
            if (validateProductPrice()) 
            {
                $response['success'] = true;
            }
        }
        elseif ($action === 'validate_image')
        {
            if (validateProductImage()) 
            {
                $response['success'] = true;
            }
        }
    }
}

// Send response as JSON
echo json_encode($response);

?>
