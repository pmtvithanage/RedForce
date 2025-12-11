<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/caretaker/equipment_style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<main class="main-content">
    <div class="equipment-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><span class="material-symbols-outlined">edit</span> <?php echo $data['pageTitle']; ?></h1>
            <a href="<?php echo URL_ROOT; ?>/caretaker/equipmentRequests" class="btn-secondary">
                <span class="material-symbols-outlined">arrow_back</span> Back to List
            </a>
        </div>

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
            <div class="form-card">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Equipment Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($data['request']->equipment_name); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" value="<?php echo $data['request']->quantity; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Estimated Cost (per unit)</label>
                        <input type="text" value="Rs. <?php echo number_format($data['request']->estimated_cost, 2); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Total Cost</label>
                        <input type="text" value="Rs. <?php echo number_format($data['request']->total_cost, 2); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Priority</label>
                        <input type="text" value="<?php echo $data['request']->priority; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" value="<?php echo $data['request']->status; ?>" disabled>
                    </div>
                    <div class="form-group full-width">
                        <label>Reason</label>
                        <textarea disabled rows="4"><?php echo htmlspecialchars($data['request']->reason); ?></textarea>
                    </div>
                </div>
            </div>

        <?php else: ?>
            
            <!-- Can Edit - Show Editable Form -->
            <div class="form-card">
                <form method="POST" action="<?php echo URL_ROOT; ?>/caretaker/updateEquipmentRequest/<?php echo $data['request']->id; ?>" id="editEquipmentForm">
                    
                    <div class="form-grid">
                        
                        <!-- Equipment Name -->
                        <div class="form-group">
                            <label>Equipment Name <span class="required">*</span></label>
                            <input 
                                type="text" 
                                name="equipment_name" 
                                value="<?php echo htmlspecialchars($data['request']->equipment_name); ?>"
                                placeholder="e.g., Flashlight"
                                list="equipment-suggestions"
                                required
                            >
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
                        <div class="form-group">
                            <label>Quantity <span class="required">*</span></label>
                            <input 
                                type="number" 
                                name="quantity" 
                                min="1"
                                value="<?php echo $data['request']->quantity; ?>"
                                id="quantity"
                                required
                            >
                        </div>

                        <!-- Estimated Cost -->
                        <div class="form-group">
                            <label>Estimated Cost (per unit) <span class="required">*</span></label>
                            <div class="input-with-prefix">
                                <span class="prefix">Rs.</span>
                                <input 
                                    type="number" 
                                    name="estimated_cost" 
                                    min="0" 
                                    step="0.01"
                                    value="<?php echo $data['request']->estimated_cost; ?>"
                                    id="estimated_cost"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Total Cost (Read-only) -->
                        <div class="form-group">
                            <label>Total Estimated Cost</label>
                            <div class="cost-display">
                                <span class="currency">Rs.</span>
                                <span id="total_estimated" class="amount">
                                    <?php echo number_format($data['request']->quantity * $data['request']->estimated_cost, 2); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Priority -->
                        <div class="form-group">
                            <label>Priority Level <span class="required">*</span></label>
                            <select name="priority" required>
                                <option value="Low" <?php echo $data['request']->priority == 'Low' ? 'selected' : ''; ?>>Low</option>
                                <option value="Medium" <?php echo $data['request']->priority == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                                <option value="High" <?php echo $data['request']->priority == 'High' ? 'selected' : ''; ?>>High</option>
                            </select>
                        </div>

                        <!-- Requested Date (Read-only) -->
                        <div class="form-group">
                            <label>Requested Date</label>
                            <input type="text" value="<?php echo date('M d, Y', strtotime($data['request']->requested_date)); ?>" disabled>
                        </div>

                        <!-- Reason -->
                        <div class="form-group full-width">
                            <label>Reason <span class="required">*</span></label>
                            <textarea 
                                name="reason" 
                                rows="5"
                                required
                                minlength="10"
                            ><?php echo htmlspecialchars($data['request']->reason); ?></textarea>
                        </div>

                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="<?php echo URL_ROOT; ?>/caretaker/equipmentRequests" class="btn-cancel">
                            Cancel
                        </a>
                        <button type="submit" class="btn-submit">
                            <span class="material-symbols-outlined">save</span>
                            Update Request
                        </button>
                    </div>

                </form>
            </div>

        <?php endif; ?>

    </div>
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

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
