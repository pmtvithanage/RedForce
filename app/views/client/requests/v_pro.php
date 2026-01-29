<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">

<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Package CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/requests/package_style.css">

<style>
/* Image Upload Styles */
.image-upload-section {
    margin-bottom: 20px;
}

.imagePlaceholder {
    width: 100%;
    max-width: 300px;
    height: 200px;
    border: 2px solid #ddd;
    border-radius: 12px;
    margin: 0 auto 10px;
    display: block;
    object-fit: cover;
    background-color: #f8f9fa;
}

.btn-upload {
    display: inline-block;
    padding: 10px 20px;
    background-color: #a40000;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    margin: 5px;
}

.btn-upload:hover {
    background-color: #b50000;
}

/* Map Section Styles */
.map-section {
    margin: 20px 0;
}

#map {
    width: 100%;
    height: 400px;
    border-radius: 8px;
    border: 1px solid #ddd;
    margin-bottom: 10px;
}

.map-instructions {
    color: #666;
    font-size: 14px;
    margin-bottom: 10px;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 6px;
}

.location-search-box {
    margin-bottom: 15px;
}

#location-search {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s ease;
}

#location-search:focus {
    outline: none;
    border-color: #a40000;
    box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.1);
}
</style>

<div class="main-content">
    <!-- Back Button -->
    <button class="tertiary-btn back-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/requests'">
        <span class="material-icons">arrow_back</span>
        Back to Packages
    </button>

    <div class="package-detail-container">
        <!-- Package Info Section -->
        <div class="package-info-card">
            <div class="package-header-detail pro-header">
                <h2>Pro Package</h2>
            </div>
            
            <div class="package-details-section">
                <h3>What's Included?</h3>
                <ul class="features-list">
                    <li><span class="material-icons">check_circle</span> Ten trained security guards assigned for continuous coverage</li>
                    <li><span class="material-icons">check_circle</span> Day Shift: 9:00 AM ~ 5:00 PM (5 guards)</li>
                    <li><span class="material-icons">check_circle</span> Night Shift: 5:00 PM ~ 1:00 AM (5 guards)</li>
                    <li><span class="material-icons">check_circle</span> Continuous on-site security presence during operational and after-hours periods</li>
                    <li><span class="material-icons">check_circle</span> Up to 4 hours of overtime per guard, available when extended coverage is required</li>
                    <li><span class="material-icons">check_circle</span> Monitoring and protection of premises, including access control and incident prevention</li>
                    <li><span class="material-icons">check_circle</span> Suitable for large-scale properties, corporate offices, industrial sites, and high-security locations</li>
                    <li><span class="material-icons">check_circle</span> Professional security solution providing maximum coverage and comprehensive protection</li>
                </ul>
                
                <div class="package-note">
                    <strong>Note:</strong> Officers are given only on a monthly basis.
                </div>
                
                <div class="pricing-info">
                    <h3>LKR 138,000/=</h3>
                    <p class="pricing-note">/monthly (Night shift included)</p>
                </div>
            </div>
        </div>

        <!-- Booking Form Section -->
        <div class="booking-form-card">
            <h3>Book This Package</h3>
            
            <form method="POST" action="<?php echo URL_ROOT; ?>/client/submitPackageRequest" enctype="multipart/form-data">
                <input type="hidden" name="package_name" value="Pro Package">
                <input type="hidden" name="number_of_guards" value="10">
                <input type="hidden" name="monthly_price" value="138000">
                
                <!-- Site Name -->
                <div class="form-group">
                    <label for="siteName">Site name</label>
                    <input type="text" id="siteName" name="site_name" required>
                </div>

                <!-- District -->
                <div class="form-group">
                    <label for="district">District</label> 
                    <input class="field-input" type="text" id="district" name="district" placeholder="Select your district" required />
                </div>

                <!-- City -->
                <div class="form-group" id="city-field" style="display: none;">
                    <label for="city">City</label> 
                    <input class="field-input" type="text" id="city" name="city" placeholder="Select your city" required />
                </div>

                <!-- Site Address -->
                <div class="form-group">
                    <label for="siteAddress">Enter site address</label>
                    <textarea id="siteAddress" name="site_address" id="site_address" rows="3" required></textarea>
                </div>

                <!-- Site Image Upload -->
                <div class="form-group image-upload-section">
                    <label>Upload Site Image</label>
                    <img class="imagePlaceholder" src="<?php echo URL_ROOT; ?>/public/img/photo.png" 
                        alt="Site image preview" 
                        id="imagePlaceholder"
                        data-default-src="<?php echo URL_ROOT; ?>/public/img/photo.png" />
                    <div style="text-align: center;">
                        <button type="button" class="btn-upload" id="addImageBtn" onclick="toggleBrowse()">Add Image</button>
                        <button type="button" class="btn-upload" id="removeImageBtn" style="display: none;" onclick="removeImage()">Remove</button>
                    </div>
                    <input type="file" name="image" id="image" accept="image/*" hidden />
                </div>

                <!-- Map Section -->
                <div class="map-section">
                    <label>Select Location on Map:</label>
                    <div class="map-instructions">
                        📍 Search for a location below, click on the map, or type the address above to pin the exact site location.
                    </div>
                    
                    <!-- Location Search Box -->
                    <div class="location-search-box">
                        <input type="text" 
                            id="location-search" 
                            placeholder="🔍 Search for places, addresses, or landmarks..." 
                            autocomplete="off">
                    </div>
                    
                    <div id="map"></div>
                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">
                </div>

                <!-- Start Date -->
                <div class="form-group">
                    <label for="startDate">Start date</label>
                    <input type="date" id="startDate" name="start_date" min="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <!-- Number of Months -->
                <div class="form-group">
                    <label for="numMonths">How many months of service is needed?</label>
                    <input type="number" id="numMonths" name="num_months" min="1" max="60" value="1" required>
                </div>

                <!-- Total Price Display -->
                <div class="form-group">
                    <label>Total Price</label>
                    <div class="price-display">
                        <span class="total-price">LKR <span id="totalPrice">138,000</span>/=</span>
                        <small class="price-breakdown">LKR 138,000 × <span id="monthsDisplay">1</span> month(s)</small>
                    </div>
                </div>

                <!-- Additional Comments -->
                <div class="form-group">
                    <label for="comments">Additional Comments</label>
                    <textarea id="comments" name="comments" rows="2"></textarea>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="tertiary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/requests'">
                        Back
                    </button>
                    <button type="submit" class="primary-btn">
                        <span class="material-icons">send</span>
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Price calculation for Pro Package
const monthlyPrice = 138000;
const numMonthsInput = document.getElementById('numMonths');
const totalPriceDisplay = document.getElementById('totalPrice');
const monthsDisplay = document.getElementById('monthsDisplay');

function updatePrice() {
    const numMonths = parseInt(numMonthsInput.value) || 1;
    const totalPrice = monthlyPrice * numMonths;
    totalPriceDisplay.textContent = totalPrice.toLocaleString();
    monthsDisplay.textContent = numMonths;
}

numMonthsInput.addEventListener('input', updatePrice);
</script>

<!-- Image Upload JavaScript -->
<script>
const addImageBtn = document.getElementById("addImageBtn");
const removeImageBtn = document.getElementById("removeImageBtn");
const imagePlaceholder = document.getElementById("imagePlaceholder");
let inputPath = document.querySelector("#image");
let file;

const defaultImagePath = imagePlaceholder.getAttribute('data-default-src');

function toggleBrowse(){
    inputPath.click();
}

function removeImage(){
    addImageBtn.style.display = "inline-block";
    removeImageBtn.style.display = "none";
    imagePlaceholder.setAttribute('src', defaultImagePath);
    inputPath.value = null;
    file = null;
}

inputPath.addEventListener('change', function(){
    file = this.files[0];
    if (file) {
        addImageBtn.style.display = "none";
        removeImageBtn.style.display = "inline-block";
        showImage();
    } else {
        removeImage();
    }
});

function showImage(){
    let fileType = file.type;
    let validExtensions = ["image/jpeg", "image/jpg", "image/png"];
    
    if(validExtensions.includes(fileType)){
        let fileReader = new FileReader();
        fileReader.onload = () => {
            let fileURL = fileReader.result;
            imagePlaceholder.setAttribute('src', fileURL);
        }
        fileReader.onerror = () => {
            alert('Error reading file');
            removeImage();
        }
        fileReader.readAsDataURL(file);
    } else {
        alert('This is not a valid image file');
        removeImage();
    }
}
</script>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGwijY64zQTmizwDN6omOoI9nzxb1MQog&libraries=places&callback=initSiteMap" async defer></script>
<script src="<?php echo URL_ROOT; ?>/js/map.js"></script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/components/select_district_city.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
