let priceInput = document.getElementById('price');
let imageInput = document.getElementById('product_image');
let productForm = document.getElementById('productForm');
let brandSelect = document.getElementById('brand_select');

// =====================================================
// 2. ATTACH THE EVENT LISTENER TO THE FORM SUBMIT
// =====================================================
if (productForm) {
    productForm.addEventListener('submit', handleProductFormSubmit);
}

// =====================================================
// 3. SINGLE SUBMISSION AND VALIDATION WORKFLOW
// =====================================================
function handleProductFormSubmit(event) 
{
    // -------------------------------------------------
    // PRICE VALIDATION LOGIC
    // -------------------------------------------------
    let price = priceInput.value.trim();
    
    // Check if empty
    if (price === '') 
    {
        event.preventDefault(); // Stop form submission
        alert("Price is required");
        return;
    }
    
    // Check if numeric
    if (isNaN(price)) 
    {
        event.preventDefault(); 
        alert("Price must be a valid number");
        return;
    }
    
    // Check if positive
    if (parseFloat(price) <= 0) 
    {
        event.preventDefault();
        alert("Price must be greater than 0");
        return;
    }
    
    // Check decimal places (max 2)
    if (!/^\d+(\.\d{1,2})?$/.test(price)) 
    {
        event.preventDefault();
        alert("Price can have maximum 2 decimal places");
        return;
    }
    
    // -------------------------------------------------
    // IMAGE VALIDATION LOGIC
    // -------------------------------------------------
    let maxImageSize = 2 * 1024 * 1024; // 2MB
    let allowedExtensions = ['jpg', 'jpeg', 'png'];
    
    if (imageInput.files && imageInput.files.length > 0) 
    {
        let file = imageInput.files[0];
        let fileName = file.name;
        let fileSize = file.size;
        
        let fileExtension = fileName.split('.').pop().toLowerCase();
        
        // Check file format
        if (allowedExtensions.includes(fileExtension) === false) 
        {
            event.preventDefault();
            alert("Only JPEG, JPG, and PNG files are allowed");
            return;
        }
        
        // Check file size
        if (fileSize > maxImageSize) 
        {
            event.preventDefault();
            let sizeMB = (fileSize / 1024 / 1024).toFixed(2);
            alert("Image size must be less than 2MB. Current: " + sizeMB + "MB");
            return;
        }
    }
    else if (!imageInput.hasAttribute('required'))
    {
        // If no file was uploaded and it's an "Edit" form, it's optional!
    }
    else
    {
        // If it's a "Create" form and no image is picked
        event.preventDefault();
        alert("Please select an image");
        return;
    }
    
    // -------------------------------------------------
    // SERVER-SIDE VALIDATION GATEWAY (AJAX)
    // -------------------------------------------------
    // Stop the immediate HTML submission so AJAX can verify with PHP first.
    event.preventDefault(); 
    ajax();
}

// =====================================================
// 4. SERVER-SIDE VALIDATION (AJAX)
// =====================================================
function ajax() 
{
    let price = priceInput.value;

    let validationData = 
    {
        action: 'validate_all',
        price: price
    };
    
    let data = JSON.stringify(validationData);
    let formData = new FormData();
    formData.append('validation', data);
    
    if (imageInput.files && imageInput.files.length > 0) {
        formData.append('product_image', imageInput.files[0]);
    }
    
    let xhttp = new XMLHttpRequest();
    xhttp.open('post', '../controllers/productValidation.php', true);
    
    xhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            let response = JSON.parse(this.responseText);
            
            if (response.success) 
            {
                console.log('All validations passed! Submitting form to database...');
                productForm.submit(); // Submit bypasses the listener
            } 
            else 
            {
                let errorMessages = [];
                if (response.errors.price) errorMessages.push('Price: ' + response.errors.price);
                if (response.errors.image) errorMessages.push('Image: ' + response.errors.image);
                
                alert('Validation Errors:\n\n' + errorMessages.join('\n'));
            }
        }
    };
    
    xhttp.send(formData);
}

// =====================================================
// 5. DYNAMIC BRAND LOADING (AJAX GET FOR ONCHANGE)
// =====================================================
function fetchBrands(categoryId) 
{
    // If no category is selected, reset the dropdown option and step away
    if (!categoryId) 
    {
        brandSelect.innerHTML = '<option value="">Select Brand</option>';
        return;
    }
    
    let xhttp = new XMLHttpRequest();
    xhttp.open('GET', '../controllers/getBrands.php?category_id=' + categoryId, true);
    
    xhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            try {
                let brands = JSON.parse(this.responseText);
                
                // Clear the old brand options
                brandSelect.innerHTML = '<option value="">Select Brand</option>';
                
                // Construct and drop in new brand items
                brands.forEach(function(brand) {
                    let option = document.createElement('option');
                    option.value = brand.id;
                    option.textContent = brand.name;
                    brandSelect.appendChild(option);
                });
            } 
            catch (e) 
            {
                console.error("Error formatting brand items stream:", e);
            }
        }
    };
    
    xhttp.send();
}