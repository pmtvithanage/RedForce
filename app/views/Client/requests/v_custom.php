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

                <form method="POST" action="<?php echo URL_ROOT; ?>/client/submitPackageRequest" id="customPackageForm">
                    <input type="hidden" name="package_name" value="Custom Package">
                    <input type="hidden" name="number_of_guards" id="totalGuards" value="0">
                    <input type="hidden" name="package_price" id="packagePrice" value="0">
                    
                    <!-- Site Location -->
                    <div class="form-group">
                        <label for="siteAddress">Site Location</label>
                        <textarea id="siteAddress" name="site_address" rows="3" required></textarea>
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

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
