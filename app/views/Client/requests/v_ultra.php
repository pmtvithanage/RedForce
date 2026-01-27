<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">

<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Package CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/requests/package_style.css">

<div class="main-content">
    <!-- Back Button -->
    <button class="tertiary-btn back-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/requests'">
        <span class="material-icons">arrow_back</span>
        Back to Packages
    </button>

    <div class="package-detail-container">
        <!-- Package Info Section -->
        <div class="package-info-card">
            <div class="package-header-detail ultra-header">
                <h2>Ultra Package</h2>
            </div>
            
            <div class="package-details-section">
                <h3>What's Included?</h3>
                <ul class="features-list">
                    <li><span class="material-icons">check_circle</span> Twelve trained security guards assigned for continuous coverage</li>
                    <li><span class="material-icons">check_circle</span> Day Shift: 9:00 AM ~ 5:00 PM (6 guards)</li>
                    <li><span class="material-icons">check_circle</span> Night Shift: 5:00 PM ~ 1:00 AM (6 guards)</li>
                    <li><span class="material-icons">check_circle</span> Continuous on-site security presence during operational and after-hours periods</li>
                    <li><span class="material-icons">check_circle</span> Up to 4 hours of overtime per guard, available when extended coverage is required</li>
                    <li><span class="material-icons">check_circle</span> Monitoring and protection of premises, including access control and incident prevention</li>
                    <li><span class="material-icons">check_circle</span> Suitable for very large-scale properties, enterprise facilities, critical infrastructure, and maximum security locations</li>
                    <li><span class="material-icons">check_circle</span> Premium security solution providing ultimate coverage and elite protection</li>
                </ul>
                
                <div class="package-note">
                    <strong>Note:</strong> Officers are given only on a monthly basis.
                </div>
                
                <div class="pricing-info">
                    <h3>LKR 167,000/=</h3>
                    <p class="pricing-note">/monthly (Night shift included)</p>
                </div>
            </div>
        </div>

        <!-- Booking Form Section -->
        <div class="booking-form-card">
            <h3>Book This Package</h3>
            
            <form method="POST" action="<?php echo URL_ROOT; ?>/client/submitPackageRequest">
                <input type="hidden" name="package_name" value="Ultra Package">
                <input type="hidden" name="number_of_guards" value="12">
                <input type="hidden" name="monthly_price" value="167000">
                
                <!-- Site Name -->
                <div class="form-group">
                    <label for="siteName">Site name</label>
                    <input type="text" id="siteName" name="site_name" required>
                </div>

                <!-- District -->
                <div class="form-group">
                    <label for="district">District</label>
                    <input type="text" id="district" name="district" placeholder="Enter your district" required>
                </div>

                <!-- City -->
                <div class="form-group" id="city-field" style="display: none;">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" placeholder="Enter your city" required>
                </div>

                <!-- Site Address -->
                <div class="form-group">
                    <label for="siteAddress">Enter site address</label>
                    <textarea id="siteAddress" name="site_address" rows="3" required></textarea>
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
                        <span class="total-price">LKR <span id="totalPrice">167,000</span>/=</span>
                        <small class="price-breakdown">LKR 167,000 × <span id="monthsDisplay">1</span> month(s)</small>
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
// Price calculation for Ultra Package
const monthlyPrice = 167000;
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

<script src="<?php echo URL_ROOT; ?>/js/components/select_district_city.js"></script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
