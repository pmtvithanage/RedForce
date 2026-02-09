<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>

<!-- Import global stylesheet -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">

<!-- Import Google Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<style>
    /* ---------- Form Section ---------- */
    .application-form {
        background-color: #fff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
    }

    .field {
        margin-bottom: 15px;
    }

    .field-label {
        margin-bottom: 6px;
        font-weight: 500;
        color: var(--text-color);
    }

    .field-input, .field-select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 15px;
    }

    .field-input:focus, .field-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(164, 0, 0, 0.2);
    }

    .field-textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 15px;
        min-height: 120px;
        resize: vertical;
        font-family: inherit;
    }

    .field-textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(164, 0, 0, 0.2);
    }

    /* ---------- Buttons ---------- */
    .btn {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: var(--secondary-color);
        box-shadow: 0 4px 10px rgba(164, 0, 0, 0.3);
    }

    .btn-primary:hover {
        background-color: #b50000;
    }

    .btn-primary:active {
        background-color: #800000;
        transform: scale(0.97);
    }

    .btn-light {
        background-color: #f1f1f1;
        color: #333;
    }

    .btn-light:hover {
        background-color: #e0e0e0;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .form-input-error {
        color: red;
        font-size: 13px;
        margin-top: 4px;
    }

    /* ---------- Page Layout ---------- */
    .page {
        max-width: 1200px;
        margin-right: 15vw;
        margin-left: 15vw;
        padding: 0 20px;
    }

    .back-btn-container {
        margin: 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .input-with-prefix {
        display: flex;
        align-items: center;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
    }

    .input-with-prefix .prefix {
        background-color: #f5f5f5;
        padding: 10px 12px;
        font-weight: 500;
        border-right: 1px solid var(--border-color);
        color: #666;
    }

    .input-with-prefix input {
        border: none;
        flex: 1;
        padding: 10px 12px;
        font-size: 15px;
    }

    .input-with-prefix input:focus {
        outline: none;
    }

    .input-with-prefix:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(164, 0, 0, 0.2);
    }

    .cost-display {
        background-color: #f5f5f5;
        padding: 10px 12px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 18px;
        font-weight: 600;
        color: var(--primary-color);
    }

    .cost-display .currency {
        color: #666;
        font-size: 14px;
    }

    .info-box {
        background: #e8f4fd;
        border-left: 4px solid #2196f3;
        padding: 15px;
        border-radius: 6px;
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .info-box .material-symbols-outlined {
        color: #2196f3;
        font-size: 24px;
    }

    .info-box ul {
        margin: 8px 0 0 0;
        padding-left: 20px;
    }

    .info-box li {
        margin-bottom: 4px;
        font-size: 14px;
        color: #333;
    }

    .required {
        color: red;
    }

    small.form-hint {
        color: #666;
        font-size: 13px;
        display: block;
        margin-top: 4px;
    }
</style>

<div class="back-btn-container">
    <button class="tertiary-btn" style="display:flex; width:100px; align-items:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/caretaker/equipmentRequests'"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>
</div>

<main class="page">
    <!-- Flash Messages -->
    <?php flash('equipment_message'); ?>
    <?php flash('equipment_error'); ?>

    <div class="application-form" style="flex: 1 1 100%; max-width: 800px; margin: 0 auto;">
        <h3 style="color: var(--primary-color); margin-top: 0;">New Equipment Request</h3>
        
        <form method="POST" action="<?php echo URL_ROOT; ?>/caretaker/addEquipmentRequest" id="equipmentForm">
            <div class="form-grid">
                
                <!-- Equipment Name -->
                <div class="field">
                    <div class="field-label">Equipment Name <span class="required">*</span></div>
                    <input 
                        class="field-input"
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
                <div class="field">
                    <div class="field-label">Quantity <span class="required">*</span></div>
                    <input 
                        class="field-input"
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
                <div class="field">
                    <div class="field-label">Estimated Cost (per unit) <span class="required">*</span></div>
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
                <div class="field">
                    <div class="field-label">Total Estimated Cost</div>
                    <div class="cost-display">
                        <span class="currency">Rs.</span>
                        <span id="total_estimated" class="amount">0.00</span>
                    </div>
                    <small class="form-hint">Quantity × Cost per unit</small>
                </div>

                <!-- Priority -->
                <div class="field">
                    <div class="field-label">Priority Level <span class="required">*</span></div>
                    <select class="field-select" name="priority" required>
                        <option value="Low">Low - Can wait a few days</option>
                        <option value="Medium" selected>Medium - Needed soon</option>
                        <option value="High">High - Urgent need</option>
                    </select>
                    <small class="form-hint">How urgently do you need this?</small>
                </div>

                <!-- Requested Date (Hidden, auto-filled) -->
                <input type="hidden" name="requested_date" value="<?php echo date('Y-m-d'); ?>">

                <!-- Reason (Full Width) -->
                <div class="field full-width">
                    <div class="field-label">Reason for Request <span class="required">*</span></div>
                    <textarea 
                        class="field-textarea"
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
                <button type="button" class="btn btn-light" onclick="window.location.href='<?php echo URL_ROOT; ?>/caretaker/equipmentRequests'">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Request</button>
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
</script>

<?php flash('msg')?>

<script>
  // Flash message auto-remove
  document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.getElementById('msg-flash');
    
    if (flashMessage) {
      setTimeout(function() {
        flashMessage.classList.add('fade-out');
        
        setTimeout(function() {
          if (flashMessage.parentNode) {
            flashMessage.parentNode.removeChild(flashMessage);
          }
        }, 300);
      }, 5000);
    }
  });
</script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
