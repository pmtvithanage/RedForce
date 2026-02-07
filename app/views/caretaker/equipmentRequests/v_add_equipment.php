<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/caretaker/equipment_style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<main class="main-content">
    <div class="equipment-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><span class="material-symbols-outlined">inventory</span></h1>
                <div class="live-datetime" id="liveDateTime"></div>
            </div>
            <a href="<?php echo URL_ROOT; ?>/caretaker/equipmentRequests" class="btn-secondary">
                <span class="material-symbols-outlined">arrow_back</span> Back to List
            </a>
        </div>

        <!-- Flash Messages -->
        <?php flash('equipment_message'); ?>
        <?php flash('equipment_error'); ?>

        <!-- Request Form Card -->
        <div class="form-card">
            <form method="POST" action="<?php echo URL_ROOT; ?>/caretaker/addEquipmentRequest" id="equipmentForm">
                <div class="form-grid">
                    
                    <!-- Equipment Name -->
                    <div class="form-group">
                        <label>
                            Equipment Name <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="equipment_name" 
                            placeholder="e.g., Flashlight, Uniform, Radio"
                            list="equipment-suggestions"
                            required
                            maxlength="255"
                        >
                        <!-- Pre-defined suggestions -->
                        <datalist id="equipment-suggestions">
                            <option value="Flashlight">
                            <option value="Uniform">
                            <option value="Radio">
                            <option value="Baton">
                            <option value="Whistle">
                            <option value="First Aid Kit">
                            <option value="Raincoat">
                            <option value="Boots">
                            <option value="Helmet">
                            <option value="Gloves">
                        </datalist>
                        <small class="form-hint">Enter the specific equipment you need</small>
                    </div>

                    <!-- Quantity -->
                    <div class="form-group">
                        <label>
                            Quantity <span class="required">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="quantity" 
                            min="1" 
                            max="100"
                            value="1"
                            id="quantity"
                            required
                        >
                        <small class="form-hint">How many units do you need?</small>
                    </div>

                    <!-- Estimated Cost per Unit -->
                    <div class="form-group">
                        <label>
                            Estimated Cost (per unit) <span class="required">*</span>
                        </label>
                        <div class="input-with-prefix">
                            <span class="prefix">Rs.</span>
                            <input 
                                type="number" 
                                name="estimated_cost" 
                                min="0" 
                                step="0.01"
                                placeholder="0.00"
                                id="estimated_cost"
                                required
                            >
                        </div>
                        <small class="form-hint">Approximate price per item</small>
                    </div>

                    <!-- Total Estimated Cost (Auto-calculated, Read-only) -->
                    <div class="form-group">
                        <label>Total Estimated Cost</label>
                        <div class="cost-display">
                            <span class="currency">Rs.</span>
                            <span id="total_estimated" class="amount">0.00</span>
                        </div>
                        <small class="form-hint">Quantity × Cost per unit</small>
                    </div>

                    <!-- Priority -->
                    <div class="form-group">
                        <label>
                            Priority Level <span class="required">*</span>
                        </label>
                        <select name="priority" required>
                            <option value="Low">Low - Can wait a few days</option>
                            <option value="Medium" selected>Medium - Needed soon</option>
                            <option value="High">High - Urgent need</option>
                        </select>
                        <small class="form-hint">How urgently do you need this?</small>
                    </div>

                    <!-- Requested Date (Hidden, auto-filled) -->
                    <input type="hidden" name="requested_date" value="<?php echo date('Y-m-d'); ?>">

                    <!-- Reason (Full Width) -->
                    <div class="form-group full-width">
                        <label>
                            Reason for Request <span class="required">*</span>
                        </label>
                        <textarea 
                            name="reason" 
                            rows="5"
                            placeholder="Explain why you need this equipment...&#10;&#10;Example:&#10;- Previous flashlight stopped working&#10;- Battery died and cannot be replaced&#10;- Need for night patrol duty"
                            required
                            minlength="10"
                            maxlength="1000"
                        ></textarea>
                        <small class="form-hint">Provide detailed explanation (minimum 10 characters)</small>
                    </div>

                </div>

                <!-- Form Action Buttons -->
                <div class="form-actions">
                    <a href="<?php echo URL_ROOT; ?>/caretaker/equipmentRequests" class="btn-cancel">
                        <span class="material-symbols-outlined">close</span>
                        Cancel
                    </a>
                    <button type="submit" class="btn-submit">
                        <span class="material-symbols-outlined">send</span>
                        Submit Request
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Section -->
        <div class="info-box">
            <span class="material-symbols-outlined">info</span>
            <div>
                <strong>Guidelines:</strong>
                <ul>
                    <li>Provide accurate cost estimates for faster processing</li>
                    <li>High priority requests are reviewed first</li>
                    <li>Include specific details in your reason (model, condition, etc.)</li>
                    <li>Your supervisor will review and approve/reject your request</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<script>
// Auto-calculate total cost
const quantityInput = document.getElementById('quantity');
const costInput = document.getElementById('estimated_cost');
const totalDisplay = document.getElementById('total_estimated');

function calculateTotal() {
    const quantity = parseFloat(quantityInput.value) || 0;
    const cost = parseFloat(costInput.value) || 0;
    const total = (quantity * cost).toFixed(2);
    totalDisplay.textContent = total;
}

quantityInput.addEventListener('input', calculateTotal);
costInput.addEventListener('input', calculateTotal);

// Live Date and Time
function updateDateTime() {
    const now = new Date();
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    };
    const dateTimeString = now.toLocaleDateString('en-US', options);
    document.getElementById('liveDateTime').textContent = dateTimeString;
}

// Update immediately and then every second
updateDateTime();
setInterval(updateDateTime, 1000);
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
