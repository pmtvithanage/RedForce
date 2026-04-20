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

    .field-input,
    .field-select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 15px;
    }

    .field-input:focus,
    .field-select:focus {
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

    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        gap: 12px;
        align-items: start;
    }

    .alert-warning {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        color: #856404;
    }

    .alert .material-symbols-outlined {
        font-size: 24px;
    }

    .required {
        color: red;
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

    <?php
    // Check if request can be edited
    if ($data['request']->status != 'Pending'):
    ?>
        <!-- Cannot Edit - Show Alert -->
        <div class="alert alert-warning">
            <span class="material-symbols-outlined">lock</span>
            <div>
                <strong>Cannot Edit This Request</strong>
                <p>This request has been <strong><?php echo $data['request']->status; ?></strong> and can no longer be modified.</p>
                <?php if ($data['request']->supervisor_notes): ?>
                    <p><strong>Supervisor Notes:</strong> <?php echo htmlspecialchars($data['request']->supervisor_notes); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Show Read-Only View -->
        <div class="application-form" style="flex: 1 1 100%; max-width: 800px; margin: 0 auto;">
            <h3 style="color: var(--primary-color); margin-top: 0;">View Equipment Request</h3>
            <div class="form-grid">
                <div class="field">
                    <div class="field-label">Equipment Name</div>
                    <input class="field-input" type="text" value="<?php echo htmlspecialchars($data['request']->equipment_name); ?>" disabled>
                </div>
                <div class="field">
                    <div class="field-label">Quantity</div>
                    <input class="field-input" type="number" value="<?php echo $data['request']->quantity; ?>" disabled>
                </div>
                <div class="field">
                    <div class="field-label">Estimated Cost (per unit)</div>
                    <input class="field-input" type="text" value="Rs. <?php echo number_format($data['request']->estimated_cost, 2); ?>" disabled>
                </div>
                <div class="field">
                    <div class="field-label">Total Cost</div>
                    <input class="field-input" type="text" value="Rs. <?php echo number_format($data['request']->total_cost, 2); ?>" disabled>
                </div>
                <div class="field">
                    <div class="field-label">Priority</div>
                    <input class="field-input" type="text" value="<?php echo $data['request']->priority; ?>" disabled>
                </div>
                <div class="field">
                    <div class="field-label">Status</div>
                    <input class="field-input" type="text" value="<?php echo $data['request']->status; ?>" disabled>
                </div>
                <div class="field full-width">
                    <div class="field-label">Reason</div>
                    <textarea class="field-textarea" disabled rows="4"><?php echo htmlspecialchars($data['request']->reason); ?></textarea>
                </div>
            </div>
        </div>

    <?php else: ?>

        <!-- Can Edit - Show Editable Form -->
        <div class="application-form" style="flex: 1 1 100%; max-width: 800px; margin: 0 auto;">
            <h3 style="color: var(--primary-color); margin-top: 0;">Edit Equipment Request</h3>

            <form method="POST" action="<?php echo URL_ROOT; ?>/caretaker/updateEquipmentRequest/<?php echo $data['request']->id; ?>" id="editEquipmentForm">

                <div class="form-grid">

                    <!-- Equipment Name -->
                    <div class="field">
                        <div class="field-label">Equipment Name <span class="required">*</span></div>
                        <input
                            class="field-input"
                            type="text"
                            name="equipment_name"
                            value="<?php echo htmlspecialchars($data['request']->equipment_name); ?>"
                            placeholder="e.g., Flashlight"
                            list="equipment-suggestions"
                            required>
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
                    </div>

                    <!-- Quantity -->
                    <div class="field">
                        <div class="field-label">Quantity <span class="required">*</span></div>
                        <input
                            class="field-input"
                            type="number"
                            name="quantity"
                            min="1"
                            value="<?php echo $data['request']->quantity; ?>"
                            id="quantity"
                            required>
                    </div>

                    <!-- Estimated Cost -->
                    <div class="field">
                        <div class="field-label">Estimated Cost (per unit) <span class="required">*</span></div>
                        <div class="input-with-prefix">
                            <span class="prefix">Rs.</span>
                            <input
                                type="number"
                                name="estimated_cost"
                                min="0"
                                step="0.01"
                                value="<?php echo $data['request']->estimated_cost; ?>"
                                id="estimated_cost"
                                required>
                        </div>
                    </div>

                    <!-- Total Cost (Read-only) -->
                    <div class="field">
                        <div class="field-label">Total Estimated Cost</div>
                        <div class="cost-display">
                            <span class="currency">Rs.</span>
                            <span id="total_estimated" class="amount">
                                <?php echo number_format($data['request']->quantity * $data['request']->estimated_cost, 2); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Priority -->
                    <div class="field">
                        <div class="field-label">Priority Level <span class="required">*</span></div>
                        <select class="field-select" name="priority" required>
                            <option value="Low" <?php echo $data['request']->priority == 'Low' ? 'selected' : ''; ?>>Low</option>
                            <option value="Medium" <?php echo $data['request']->priority == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="High" <?php echo $data['request']->priority == 'High' ? 'selected' : ''; ?>>High</option>
                        </select>
                    </div>

                    <!-- Requested Date (Read-only) -->
                    <div class="field">
                        <div class="field-label">Requested Date</div>
                        <input class="field-input" type="text" value="<?php echo date('M d, Y', strtotime($data['request']->requested_date)); ?>" disabled>
                    </div>

                    <!-- Reason -->
                    <div class="field full-width">
                        <div class="field-label">Reason <span class="required">*</span></div>
                        <textarea
                            class="field-textarea"
                            name="reason"
                            rows="5"
                            required
                            minlength="10"><?php echo htmlspecialchars($data['request']->reason); ?></textarea>
                    </div>

                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="btn btn-light" onclick="window.location.href='<?php echo URL_ROOT; ?>/caretaker/equipmentRequests'">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Request</button>
                </div>

            </form>
        </div>

    <?php endif; ?>
</main>

<script>
    // Auto-calculate total cost when editing
    const quantityInput = document.getElementById('quantity');
    const costInput = document.getElementById('estimated_cost');
    const totalDisplay = document.getElementById('total_estimated');

    if (quantityInput && costInput && totalDisplay) {
        function calculateTotal() {
            const quantity = parseFloat(quantityInput.value) || 0;
            const cost = parseFloat(costInput.value) || 0;
            const total = (quantity * cost).toFixed(2);
            totalDisplay.textContent = total;
        }

        quantityInput.addEventListener('input', calculateTotal);
        costInput.addEventListener('input', calculateTotal);
    }
</script>

<?php flash('msg') ?>

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