<?php
header('Content-Type: application/json'); // 

// Get data from our FormData object payload
$inputData = [];

// Format 1: FormData payload containing our stringified JSON validation package
if (isset($_POST['validation'])) 
{
    $inputData = json_decode($_POST['validation'], true);
}
// Format 2: Fallback for raw JSON streams
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !$_FILES) 
{
    $inputData = json_decode(file_get_contents("php://input"), true);
}

// Response array 
$response = 
[
    'success' => false,
    'errors' => []
];

function validateProductImage() 
{
    global $inputData, $response;
    
    // Check if the file block wasn't transmitted at all
    if (!isset($_FILES['product_image'])) 
    {
        $response['errors']['image'] = "No image field detected.";
        return false;
    }
    
    $file = $_FILES['product_image'];
    $file_error = $file['error'];
    
    // FIX: If no file was uploaded, check if it's optional (like during an Edit)
    if ($file_error === UPLOAD_ERR_NO_FILE) 
    {
        // If it's an update scenario, an empty file upload is perfectly valid! 
        return true; 
    }
    
    // Rule A: Check for other system upload errors 
    if ($file_error !== UPLOAD_ERR_OK) 
    {
        $response['errors']['image'] = "File upload error. Please try again.";
        return false;
    }
    
    $file_name = $file['name'];
    $file_size = $file['size'];
    
    // Rule B: Check format (jpeg, png) 
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpeg',  'png'];
    
    if (!in_array($file_ext, $allowed_ext)) 
    {
        $response['errors']['image'] = "Only JPEG and PNG files are allowed.";
        return false;
    }
    
    // Rule C: Check size (2MB max) 
    $max_size = 2 * 1024 * 1024;
    
    if ($file_size > $max_size) 
    {
        $response['errors']['image'] = "Image size must be less than 2MB. Current size: " . round($file_size / 1024 / 1024, 2) . "MB";
        return false;
    }
    
    return true;
}

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

// Check if this is a validation request
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    // FIX: Look inside $inputData natively since your JS places "action" inside the JSON packet
    if (isset($inputData['action'])) 
    {
        $action = $inputData['action'];
        
        if ($action === 'validate_all') 
        {
            // Run validations [cite: 79]
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