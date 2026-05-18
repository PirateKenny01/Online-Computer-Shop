<?php
header('Content-Type: application/json'); 
$inputData = [];

if (isset($_POST['validation'])) 
{
    $inputData = json_decode($_POST['validation'], true);
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !$_FILES) 
{
    $inputData = json_decode(file_get_contents("php://input"), true);
}
 
$response = 
[
    'success' => false,
    'errors' => []
];

function validateProductImage() 
{
    global $inputData, $response;
    
    if (!isset($_FILES['product_image'])) 
    {
        $response['errors']['image'] = "No image field detected.";
        return false;
    }
    
    $file = $_FILES['product_image'];
    $file_error = $file['error'];
    
    if ($file_error === UPLOAD_ERR_NO_FILE) 
    {
        return true; 
    }
    
    if ($file_error !== UPLOAD_ERR_OK) 
    {
        $response['errors']['image'] = "File upload error. Please try again.";
        return false;
    }
    
    $file_name = $file['name'];
    $file_size = $file['size'];
    
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpeg',  'png'];
    
    if (!in_array($file_ext, $allowed_ext)) 
    {
        $response['errors']['image'] = "Only JPEG and PNG files are allowed.";
        return false;
    }
    
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
    
    if (!is_numeric($price)) 
    {
        $response['errors']['price'] = "Price must be a valid number.";
        return false;
    }
     
    if ($price <= 0) 
    {
        $response['errors']['price'] = "Price must be a positive number greater than 0.";
        return false;
    }
    
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $price)) 
    {
        $response['errors']['price'] = "Price can have maximum 2 decimal places.";
        return false;
    }
    
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if (isset($inputData['action'])) 
    {
        $action = $inputData['action'];
        
        if ($action === 'validate_all') 
        {
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

echo json_encode($response);

?>