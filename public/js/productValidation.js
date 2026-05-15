let priceInput = document.getElementById('price');
let imageInput = document.getElementById('product_image');
let productForm = document.getElementById('productForm');


function validatePriceClientSide() 
{
    let price = priceInput.value.trim();
    
    // Check if empty
    if (price === '') 
    {
        return { valid: false, message: "Price is required" };
    }
    
    // Check if numeric
    if (isNaN(price)) 
    {
        return { valid: false, message: "Price must be a valid number" };
    }
    
    // Check if positive
    if (parseFloat(price) <= 0) 
    {
        return { valid: false, message: "Price must be greater than 0" };
    }
    
    // Check decimal places (max 2)
    if (!/^\d+(\.\d{1,2})?$/.test(price)) 
    {
        return { valid: false, message: "Price can have maximum 2 decimal places" };
    }
    
    return { valid: true, message: "Price is valid" };
}

function validateImageClientSide() 
{
    // Image validation specific variables
    let maxImageSize = 2 * 1024 * 1024; // 2MB
    let allowedExtensions = ['jpg', 'jpeg', 'png'];
    
    if (!imageInput.files || imageInput.files.length === 0) 
    {
        return { valid: false, message: "Please select an image" };
    }
    
    let file = imageInput.files[0];
    let fileName = file.name;
    let fileSize = file.size;
    
    // Get file extension
    let fileExtension = fileName.split('.').pop().toLowerCase();
    
    // Check file format
    if (!allowedExtensions.includes(fileExtension)) 
    {
        return { valid: false, message: "Only JPEG, JPG, and PNG files are allowed" };
    }
    
    // Check file size
    if (fileSize > maxImageSize) 
    {
        let sizeMB = (fileSize / 1024 / 1024).toFixed(2);
        return { valid: false, message: `Image size must be less than 2MB. Current: ${sizeMB}MB` };
    }
    
    return { valid: true, message: "Image is valid" };
}


function ajax() 
{
    // Get price
    let price = priceInput.value;
    
    // Create object for validation data
    let validationData = 
    {
        action: 'validate_all',
        price: price
    };
    
    let data = JSON.stringify(validationData);
    
    // Create FormData to include both JSON and file
    let formData = new FormData();
    formData.append('validation', data);
    
    // Add image file if exists
    if (imageInput.files && imageInput.files.length > 0) 
    {
        formData.append('product_image', imageInput.files[0]);
    }
    
    // Send AJAX request
    let xhttp = new XMLHttpRequest();
    xhttp.open('post', '../controllers/productValidation.php', true);
    
    xhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            let response = JSON.parse(this.responseText);
            
            if (response.success) 
            {
                console.log('All validations passed!');
                return true;
            } 
            else 
            {
                // Show errors
                let errorMessages = [];
                if (response.errors.price) 
                {
                    errorMessages.push('Price: ' + response.errors.price);
                }
                if (response.errors.image) 
                {
                    errorMessages.push('Image: ' + response.errors.image);
                }
                alert('Validation Errors:\n\n' + errorMessages.join('\n'));
                return false;
            }
        }
    };
    
    xhttp.send(formData);
}

// =====================================================
// 4. HANDLE FORM SUBMISSION WITH VALIDATION
// =====================================================
function handleProductFormSubmit(e) 
{
    e.preventDefault();
    
    // Client-side validations first
    let hasError = false;
    let errors = {};
    
    // Validate price
    let priceValidation = validatePriceClientSide();
    if (!priceValidation.valid) 
    {
        errors.price = priceValidation.message;
        hasError = true;
    }
    
    // Validate image if provided
    if (imageInput.files && imageInput.files.length > 0) 
    {
        let imageValidation = validateImageClientSide();
        if (!imageValidation.valid) 
        {
            errors.image = imageValidation.message;
            hasError = true;
        }
    }
    
    // If client-side validation fails, show errors
    if (hasError) 
    {
        alert('Validation Errors:\n\n' + Object.values(errors).join('\n'));
        return false;
    }
    
    // If client-side passes, call AJAX for server-side validation
    ajax();
    
    // Allow form submission after validation
    setTimeout(function() {
        productForm.submit();
    }, 500);
}

// =====================================================
// 5. ATTACH EVENT LISTENERS GLOBALLY
// =====================================================
productForm.addEventListener('submit', handleProductFormSubmit);

