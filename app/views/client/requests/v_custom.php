<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">

<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Package CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/requests/package_style.css">

<style>
    .custom-package-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .custom-package-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .custom-package-header {
        background: linear-gradient(135deg, #a40000 0%, #ff6b6b 100%);
        padding: 30px;
        color: white;
        text-align: center;
    }

    .custom-package-header h2 {
        font-size: 32px;
        font-weight: 600;
        margin: 0 0 10px 0;
    }

    .custom-package-header p {
        font-size: 16px;
        margin: 0;
        opacity: 0.9;
    }

    .custom-form-section {
        padding: 30px;
    }

    .price-display {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 25px;
    }

    .price-display h3 {
        font-size: 14px;
        color: #666;
        margin: 0 0 8px 0;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .price-amount {
        font-size: 36px;
        font-weight: 700;
        color: #a40000;
        margin: 0;
    }

    .price-note {
        font-size: 13px;
        color: #888;
        margin: 5px 0 0 0;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

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

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="main-content">
    <!-- Back Button -->
    <button class="tertiary-btn back-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/requests'">
        <span class="material-icons">arrow_back</span>
        Back to Packages
    </button>

    <div class="custom-package-container">
        <div class="custom-package-card">
            <div class="custom-package-header">
                <h2>Customize Your Own Package</h2>
                <p>Build a security package tailored to your specific needs</p>
            </div>
            
            <div class="custom-form-section">
                <!-- Price Display -->
                <div class="price-display">
                    <h3>Estimated Monthly Cost</h3>
                    <p class="price-amount" id="totalPrice">LKR 0/=</p>
                    <p class="price-note" id="priceBreakdown">Select number of guards to calculate price</p>
                </div>

                <form method="POST" action="<?php echo URL_ROOT; ?>/client/submitPackageRequest" id="customPackageForm" enctype="multipart/form-data">
                    <input type="hidden" name="package_name" value="Custom Package">
                    <input type="hidden" name="number_of_guards" id="totalGuards" value="0">
                    <input type="hidden" name="package_price" id="packagePrice" value="0">
                    
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

                    <!-- Site Location -->
                    <div class="form-group">
                        <label for="site_address">Site Location</label>
                        <input type="text" id="site_address" name="site_address" placeholder="Enter address" required />
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

                    <!-- Date Range -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="startDate">Start Date</label>
                            <input type="date" id="startDate" name="start_date" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="endDate">End Date</label>
                            <input type="date" id="endDate" name="end_date" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <!-- Number of Guards -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="dayGuards">Number of Guards (Day Shift)</label>
                            <select id="dayGuards" name="day_guards" required>
                                <option value="0">0 Guards</option>
                                <option value="1">1 Guard</option>
                                <option value="2">2 Guards</option>
                                <option value="3">3 Guards</option>
                                <option value="4">4 Guards</option>
                                <option value="5">5 Guards</option>
                                <option value="6">6 Guards</option>
                                <option value="7">7 Guards</option>
                                <option value="8">8 Guards</option>
                                <option value="9">9 Guards</option>
                                <option value="10">10 Guards</option>
                                <option value="11">11 Guards</option>
                                <option value="12">12 Guards</option>
                                <option value="13">13 Guards</option>
                                <option value="14">14 Guards</option>
                                <option value="15">15 Guards</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nightGuards">Number of Guards (Night Shift)</label>
                            <select id="nightGuards" name="night_guards" required>
                                <option value="0">0 Guards</option>
                                <option value="1">1 Guard</option>
                                <option value="2">2 Guards</option>
                                <option value="3">3 Guards</option>
                                <option value="4">4 Guards</option>
                                <option value="5">5 Guards</option>
                                <option value="6">6 Guards</option>
                                <option value="7">7 Guards</option>
                                <option value="8">8 Guards</option>
                                <option value="9">9 Guards</option>
                                <option value="10">10 Guards</option>
                                <option value="11">11 Guards</option>
                                <option value="12">12 Guards</option>
                                <option value="13">13 Guards</option>
                                <option value="14">14 Guards</option>
                                <option value="15">15 Guards</option>
                            </select>
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
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const PRICE_PER_GUARD = 15000;
    
    const dayGuardsSelect = document.getElementById('dayGuards');
    const nightGuardsSelect = document.getElementById('nightGuards');
    const totalPriceElement = document.getElementById('totalPrice');
    const priceBreakdownElement = document.getElementById('priceBreakdown');
    const totalGuardsInput = document.getElementById('totalGuards');
    const packagePriceInput = document.getElementById('packagePrice');

    function calculatePrice() {
        const dayGuards = parseInt(dayGuardsSelect.value);
        const nightGuards = parseInt(nightGuardsSelect.value);
        const totalGuards = dayGuards + nightGuards;
        const totalPrice = totalGuards * PRICE_PER_GUARD;

        // Update display
        totalPriceElement.textContent = `LKR ${totalPrice.toLocaleString()}/=`;
        
        // Update breakdown
        if (totalGuards === 0) {
            priceBreakdownElement.textContent = 'Select number of guards to calculate price';
        } else {
            priceBreakdownElement.textContent = `${totalGuards} guard${totalGuards !== 1 ? 's' : ''} × LKR 15,000 = LKR ${totalPrice.toLocaleString()}/monthly`;
        }

        // Update hidden inputs
        totalGuardsInput.value = totalGuards;
        packagePriceInput.value = totalPrice;
    }

    // Add event listeners
    dayGuardsSelect.addEventListener('change', calculatePrice);
    nightGuardsSelect.addEventListener('change', calculatePrice);

    // Form validation
    document.getElementById('customPackageForm').addEventListener('submit', function(e) {
        const totalGuards = parseInt(totalGuardsInput.value);
        if (totalGuards === 0) {
            e.preventDefault();
            alert('Please select at least one guard for either day shift or night shift.');
            return false;
        }
    });

    // Initialize
    calculatePrice();
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

<!-- Load map.js first, then Google Maps API -->
<script src="<?php echo URL_ROOT; ?>/js/map.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGwijY64zQTmizwDN6omOoI9nzxb1MQog&libraries=places&callback=initSiteMap" async defer></script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/components/select_district_city.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
