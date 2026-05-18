<?php
session_start();
$passedCatId = isset($_GET['category_id']) ? intval($_GET['category_id']) : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>SEARCH & FILTER</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; display: flex; background: #f5f5f5; }
        .sidebar { width: 300px; background: white; padding: 25px; box-shadow: 2px 0 5px rgba(0,0,0,0.05); height: 100vh; position: fixed; box-sizing: border-box; overflow-y: auto; z-index: 10; }
        .main-content { margin-left: 330px; padding: 40px; flex-grow: 1; }
        .search-box { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 20px; box-sizing: border-box; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
        .card { background: white; border: 1px solid #e0e0e0; padding: 20px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); text-align: center; }
        
        
        .img-box { 
            width: 100%; 
            height: 150px; 
            background: #f0f0f0; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-bottom: 15px; 
            border-radius: 4px; 
            overflow: hidden; 
            border: 1px solid #ddd; 
        }
        .img-box img { 
            width: 100%;
            height: 100%;
            object-fit: contain; 
            display: block;
        }
        .price { font-weight: bold; color: #2e7d32; margin: 10px 0; font-size: 18px; text-align: left; }
        .view-link { display: inline-block; background: #007bff; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold; width: 100%; text-align: center; box-sizing: border-box; }
        .brand-container { background: #f9f9f9; padding: 10px; border-radius: 4px; margin-top: 10px; border-left: 3px solid #007bff; display: none; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h3>Catalog Filters</h3>
        <input type="text" id="searchKey" class="search-box" placeholder="Search by model name..." onkeyup="executeAjaxFilter()">
        
        <div style="margin-bottom:20px;">
            <h4>Price Threshold</h4>
            <input type="number" id="minPrice" placeholder="Min ৳" style="width:100px; padding:6px;" oninput="executeAjaxFilter()"> - 
            <input type="number" id="maxPrice" placeholder="Max ৳" style="width:100px; padding:6px;" oninput="executeAjaxFilter()">
        </div>

        <div style="margin-bottom:20px;">
            <h4>Category Check</h4>
            <label><input type="radio" name="cat" value="1" <?php echo $passedCatId === 1 ? 'checked' : ''; ?> onclick="handleCategoryChange(1)"> Processor</label><br>
            <label><input type="radio" name="cat" value="2" <?php echo $passedCatId === 2 ? 'checked' : ''; ?> onclick="handleCategoryChange(2)"> Graphics Card</label><br>
            <label><input type="radio" name="cat" value="3" <?php echo $passedCatId === 3 ? 'checked' : ''; ?> onclick="handleCategoryChange(3)"> Memory</label><br>
            <label><input type="radio" name="cat" value="4" <?php echo $passedCatId === 4 ? 'checked' : ''; ?> onclick="handleCategoryChange(4)"> Storage</label><br>
            <label><input type="radio" name="cat" value="5" <?php echo $passedCatId === 5 ? 'checked' : ''; ?> onclick="handleCategoryChange(5)"> Monitor</label>
        </div>

        <div id="brandSection" class="brand-container">
            <h4 style="margin-top:0;">Available Brands</h4>
            <div id="brandRadioList"></div>
        </div>

        <br><br>
        <button onclick="window.location.href='home.php'" style="width:100%; padding:10px; background:#6c757d; color:white; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">🏠 Return to Home</button>
    </div>

    <div class="main-content" style="width: 100%;">
        <?php include('partials/navbar.php'); ?>
        <h2 style="margin-top: 20px;">Filtered Component Results</h2>
        <div class="grid" id="productGrid"></div>
    </div>

    <script>
    const brandMap = {
        1: [{id: 1, name: 'Intel'}, {id: 2, name: 'AMD'}],
        2: [{id: 3, name: 'NVIDIA'}],
        3: [{id: 4, name: 'Corsair'}],
        4: [{id: 5, name: 'Samsung'}],
        5: [{id: 6, name: 'LG'}]
    };

    function handleCategoryChange(catId) {
        let brandSection = document.getElementById('brandSection');
        let brandRadioList = document.getElementById('brandRadioList');
        
        brandRadioList.innerHTML = '<label><input type="radio" name="brand" value="" checked onclick="executeAjaxFilter()"> <strong>All Brands</strong></label><br>';
        
        if (brandMap[catId]) {
            brandMap[catId].forEach(b => {
                brandRadioList.innerHTML += `<label><input type="radio" name="brand" value="${b.id}" onclick="executeAjaxFilter()"> ${b.name}</label><br>`;
            });
            brandSection.style.display = 'block';
        } else {
            brandSection.style.display = 'none';
        }
        executeAjaxFilter();
    }

    function executeAjaxFilter() {
        let q = document.getElementById('searchKey').value;
        let min = document.getElementById('minPrice').value;
        let max = document.getElementById('maxPrice').value;
        
        let catChecked = document.querySelector('input[name="cat"]:checked');
        let catId = catChecked ? catChecked.value : '';

        let brandChecked = document.querySelector('input[name="brand"]:checked');
        let brandId = brandChecked ? brandChecked.value : '';

        let queryUrl = `../api/products/search.php?q=${encodeURIComponent(q)}&min=${min}&max=${max}&category_id=${catId}&brand_id=${brandId}`;

        fetch(queryUrl)
        .then(res => res.json())
        .then(data => {
            let grid = document.getElementById('productGrid');
            grid.innerHTML = '';
            if(!data || data.length === 0) {
                grid.innerHTML = '<p style="color:#666; padding: 20px;">No matching components found for this selection.</p>';
                return;
            }
            data.forEach(p => {
                let rawImg = p.image ? p.image.trim() : (p.product_image ? p.product_image.trim() : '');
                
                // Prefixes "../" before the database string "images/filename.jpg"
                let imgSrc = rawImg !== '' ? "../" + rawImg : "../images/no-image.png";

                grid.innerHTML += `
                    <div class="card">
                        <div class="img-box">
                            <img src="${imgSrc}" width="200" height="150" alt="${p.name}" onerror="this.src='../images/no-image.png';">
                        </div>
                        <h3 style="text-align: left; font-size: 16px; margin: 5px 0;">${p.name}</h3>
                        <div class="price">৳${parseFloat(p.price).toLocaleString('en-US', {minimumFractionDigits:2})}</div>
                        <p style="font-size:12px; color: ${p.stock > 0 ? '#2e7d32' : '#dc3545'}; font-weight:bold; text-align: left;">
                            In-Stock: ${p.stock} units
                        </p>
                        <a href="product_detail.php?id=${p.id}" class="view-link">View Specs & Buy</a>
                    </div>
                `;
            });
        });
    }

    window.onload = function() {
        <?php if($passedCatId !== ''): ?>
            handleCategoryChange(<?php echo $passedCatId; ?>);
        <?php else: ?>
            executeAjaxFilter();
        <?php endif; ?>
    };
    </script>
</body>
</html>