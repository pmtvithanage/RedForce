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
                <input type="hidden" name="package_price" value="167000">
                
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

                <!-- End Date -->
                <div class="form-group">
                    <label for="endDate">End date</label>
                    <input type="date" id="endDate" name="end_date" min="<?php echo date('Y-m-d'); ?>" required>
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

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
