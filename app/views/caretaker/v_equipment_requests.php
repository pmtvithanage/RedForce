<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/caretaker/equipment_style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<main class="main-content">
    <div class="equipment-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><span class="material-symbols-outlined">inventory</span></h1>
                <div class="live-datetime" id="liveDateTime"></div>
            </div>
            <a href="<?php echo URL_ROOT; ?>/caretaker/addEquipmentPage" class="btn-add">
                <span class="material-symbols-outlined">add</span> Request Equipment
            </a>
        </div>

        <!-- Flash Messages -->
        <?php flash('equipment_message'); ?>
        <?php flash('equipment_error'); ?>

        <!-- Statistics Cards -->
        <section class="stats-section">
            <div class="stats-row">
                <div class="stat-card yellow">
                    <div class="stat-icon"><span class="material-icons">pending</span></div>
                    <div class="stat-text">
                        <div class="stat-value"><?php echo $data['stats']->pending ?? 0; ?></div>
                        <div class="stat-label">Pending Requests</div>
                        <div class="stat-sublabel">Rs. <?php echo number_format($data['stats']->pending_cost ?? 0, 2); ?></div>
                    </div>
                </div>

                <div class="stat-card green">
                    <div class="stat-icon"><span class="material-icons">check_circle</span></div>
                    <div class="stat-text">
                        <div class="stat-value"><?php echo $data['stats']->approved ?? 0; ?></div>
                        <div class="stat-label">Approved</div>
                        <div class="stat-sublabel">Rs. <?php echo number_format($data['stats']->approved_cost ?? 0, 2); ?></div>
                    </div>
                </div>

                <div class="stat-card red">
                    <div class="stat-icon"><span class="material-icons">cancel</span></div>
                    <div class="stat-text">
                        <div class="stat-value"><?php echo $data['stats']->rejected ?? 0; ?></div>
                        <div class="stat-label">Rejected</div>
                    </div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-icon"><span class="material-icons">inventory_2</span></div>
                    <div class="stat-text">
                        <div class="stat-value"><?php echo $data['stats']->total ?? 0; ?></div>
                        <div class="stat-label">Total Requests</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Requests Table -->
        <div class="table-container">
            <table class="equipment-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Equipment</th>
                        <th>Qty</th>
                        <th>Est. Cost</th>
                        <th>Actual Cost</th>
                        <th>Total</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['requests'])): ?>
                        <?php foreach ($data['requests'] as $request): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($request->requested_date)); ?></td>
                                <td><strong><?php echo htmlspecialchars($request->equipment_name); ?></strong></td>
                                <td><?php echo $request->quantity; ?></td>
                                
                                <!-- Estimated Cost -->
                                <td class="cost-cell">Rs. <?php echo number_format($request->estimated_cost, 2); ?></td>
                                
                                <!-- Actual Cost -->
                                <td class="cost-cell">
                                    <?php if ($request->status == 'Approved' && $request->actual_cost > 0): ?>
                                        <span class="actual-cost">Rs. <?php echo number_format($request->actual_cost, 2); ?></span>
                                    <?php else: ?>
                                        <span class="pending-text">-</span>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Total Cost -->
                                <td class="cost-cell total-cost">
                                    <?php if ($request->status == 'Approved' && $request->total_cost > 0): ?>
                                        <strong>Rs. <?php echo number_format($request->total_cost, 2); ?></strong>
                                    <?php else: ?>
                                        <span class="estimated-total">
                                            ~Rs. <?php echo number_format($request->quantity * $request->estimated_cost, 2); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Priority -->
                                <td>
                                    <span class="priority-badge priority-<?php echo strtolower($request->priority); ?>">
                                        <?php echo $request->priority; ?>
                                    </span>
                                </td>
                                
                                <!-- Status -->
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($request->status); ?>">
                                        <?php echo $request->status; ?>
                                    </span>
                                </td>
                                
                                <!-- Actions -->
                                <td class="action-buttons">
                                    <?php if ($request->status == 'Pending'): ?>
                                        <!-- Edit Button -->
                                        <a href="<?php echo URL_ROOT; ?>/caretaker/editEquipmentPage/<?php echo $request->id; ?>" 
                                           class="btn-edit" 
                                           title="Edit">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <form method="POST" 
                                              action="<?php echo URL_ROOT; ?>/caretaker/deleteEquipmentRequest/<?php echo $request->id; ?>" 
                                              style="display: inline;"
                                              onsubmit="return confirm('Are you sure you want to delete this equipment request?\n\nEquipment: <?php echo htmlspecialchars($request->equipment_name); ?>\nQuantity: <?php echo $request->quantity; ?>\n\nThis action cannot be undone.')">
                                            <button type="submit" class="btn-delete" title="Delete">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <!-- View Details (for approved/rejected) -->
                                        <button class="btn-view" 
                                                title="View Details"
                                                onclick="viewDetails(<?php echo $request->id; ?>)">
                                            <span class="material-symbols-outlined">visibility</span>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="no-data">
                                <span class="material-symbols-outlined">inventory</span>
                                <p>No equipment requests found. Click "Request Equipment" to submit a request.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <span class="material-symbols-outlined">info</span>
            <div>
                <strong>Note:</strong>
                <ul>
                    <li>You can only edit or delete <strong>Pending</strong> requests</li>
                    <li>Once approved or rejected, requests cannot be modified</li>
                    <li>Provide accurate cost estimates for faster approval</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<!-- View Details Modal -->
<div id="detailsModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modalBody">
            <!-- Details will be loaded here -->
        </div>
    </div>
</div>

<script src="<?php echo URL_ROOT; ?>/js/caretaker/equipment.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<script>
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

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
