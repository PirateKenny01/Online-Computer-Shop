let priceInput = document.getElementById('price');
let imageInput = document.getElementById('product_image');
let productForm = document.getElementById('productForm');
let brandSelect = document.getElementById('brand_select');

if (productForm) 
{
    productForm.addEventListener('submit', handleProductFormSubmit);
}

function handleProductFormSubmit(event) 
{
    let price = priceInput.value.trim();
    
    if (price === '') 
    {
        event.preventDefault(); 
        alert("Price is required");
        return;
    }
    
    if (isNaN(price)) 
    {
        event.preventDefault(); 
        alert("Price must be a valid number");
        return;
    }
    
    if (parseFloat(price) <= 0) 
    {
        event.preventDefault();
        alert("Price must be greater than 0");
        return;
    }
    
    if (!/^\d+(\.\d{1,2})?$/.test(price)) 
    {
        event.preventDefault();
        alert("Price can have maximum 2 decimal places");
        return;
    }
    
    let maxImageSize = 2 * 1024 * 1024; // 2MB
    let allowedExtensions = ['jpg', 'jpeg', 'png'];
    
    if (imageInput.files && imageInput.files.length > 0) 
    {
        let file = imageInput.files[0];
        let fileName = file.name;
        let fileSize = file.size;
        
        let fileExtension = fileName.split('.').pop().toLowerCase();
        
        if (allowedExtensions.includes(fileExtension) === false) 
        {
            event.preventDefault();
            alert("Only JPEG, JPG, and PNG files are allowed");
            return;
        }
        
        if (fileSize > maxImageSize) 
        {
            event.preventDefault();
            let sizeMB = (fileSize / 1024 / 1024).toFixed(2);
            alert("Image size must be less than 2MB. Current: " + sizeMB + "MB");
            return;
        }
    }
    else
    {
        event.preventDefault();
        alert("Please select an image");
        return;
    }
    
    event.preventDefault(); 
    ajax();
}

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
    
    if (imageInput.files && imageInput.files.length > 0) 
    {
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
                productForm.submit(); 
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

function fetchBrands(categoryId) 
{
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
            try 
            {
                let brands = JSON.parse(this.responseText);
                
                brandSelect.innerHTML = '<option value="">Select Brand</option>';
                
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