<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/equipment_style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<main class="main-content">
    <div class="equipment-container">
        <!-- Flash Messages -->
        <?php flash('equipment_message'); ?>
        <?php flash('equipment_error'); ?>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card yellow">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">schedule</span>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $data['stats']->pending_count ?? 0; ?></div>
                    <div class="stat-label">Pending Requests</div>
                    <div class="stat-cost">Rs. <?php echo number_format($data['stats']->pending_cost ?? 0, 2); ?></div>
                </div>
            </div>

            <div class="stat-card green">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">task_alt</span>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $data['stats']->approved_count ?? 0; ?></div>
                    <div class="stat-label">Approved</div>
                    <div class="stat-cost">Rs. <?php echo number_format($data['stats']->approved_cost ?? 0, 2); ?></div>
                </div>
            </div>

            <div class="stat-card red">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">block</span>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $data['stats']->rejected_count ?? 0; ?></div>
                    <div class="stat-label">Rejected</div>
                    <div class="stat-cost">Rs. <?php echo number_format($data['stats']->rejected_cost ?? 0, 2); ?></div>
                </div>
            </div>

            <div class="stat-card blue">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">inventory</span>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $data['stats']->total_requests ?? 0; ?></div>
                    <div class="stat-label">Total Requests</div>
                    <div class="stat-cost">All Time</div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-section animate__animated animate__fadeInUp animate__delay-1s">
            <div class="filters-header">
                <h3>
                    <span class="material-symbols-outlined">filter_alt</span>
                    Filter Requests
                </h3>
                <div class="filter-toggle">
                    <button type="button" class="btn-toggle-filters" onclick="toggleFilters()">
                        <span class="material-symbols-outlined">expand_more</span>
                    </button>
                </div>
            </div>
            <form method="GET" action="<?php echo URL_ROOT; ?>/supervisor/equipmentRequests" class="filters-form" id="filtersForm">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label>
                            <span class="material-symbols-outlined">flag</span> 
                            Status
                        </label>
                        <select name="status" class="filter-select">
                            <option value="">All Status</option>
                            <option value="Pending" <?php echo (isset($data['filters']['status']) && $data['filters']['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Approved" <?php echo (isset($data['filters']['status']) && $data['filters']['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                            <option value="Rejected" <?php echo (isset($data['filters']['status']) && $data['filters']['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>
                            <span class="material-symbols-outlined">priority_high</span> 
                            Priority
                        </label>
                        <select name="priority" class="filter-select">
                            <option value="">All Priorities</option>
                            <option value="High" <?php echo (isset($data['filters']['priority']) && $data['filters']['priority'] == 'High') ? 'selected' : ''; ?>>High</option>
                            <option value="Medium" <?php echo (isset($data['filters']['priority']) && $data['filters']['priority'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                            <option value="Low" <?php echo (isset($data['filters']['priority']) && $data['filters']['priority'] == 'Low') ? 'selected' : ''; ?>>Low</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>
                            <span class="material-symbols-outlined">person</span> 
                            Caretaker
                        </label>
                        <select name="caretaker_id" class="filter-select">
                            <option value="">All Caretakers</option>
                            <?php if (!empty($data['caretakers'])): ?>
                                <?php foreach ($data['caretakers'] as $caretaker): ?>
                                    <option value="<?php echo $caretaker->id; ?>" <?php echo (isset($data['filters']['caretaker_id']) && $data['filters']['caretaker_id'] == $caretaker->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($caretaker->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>
                            <span class="material-symbols-outlined">calendar_today</span> 
                            From Date
                        </label>
                        <input type="date" name="date_from" class="filter-input" value="<?php echo $data['filters']['date_from'] ?? ''; ?>">
                    </div>

                    <div class="filter-group">
                        <label>
                            <span class="material-symbols-outlined">calendar_today</span> 
                            To Date
                        </label>
                        <input type="date" name="date_to" class="filter-input" value="<?php echo $data['filters']['date_to'] ?? ''; ?>">
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-filter btn-apply">
                            <span class="material-symbols-outlined">check</span> 
                            Apply Filters
                        </button>
                        <a href="<?php echo URL_ROOT; ?>/supervisor/equipmentRequests" class="btn-filter btn-reset">
                            <span class="material-symbols-outlined">refresh</span> 
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Requests Table -->
        <div class="table-wrapper animate__animated animate__fadeInUp animate__delay-2s">
            <div class="table-header">
                <h3>
                    <span class="material-symbols-outlined">list_alt</span>
                    Equipment Requests
                </h3>
                <div class="table-actions">
                    <button class="btn-icon" onclick="exportTable()" title="Export">
                        <span class="material-symbols-outlined">download</span>
                    </button>
                    <button class="btn-icon" onclick="printTable()" title="Print">
                        <span class="material-symbols-outlined">print</span>
                    </button>
                </div>
            </div>
            <div class="table-container">
                <table class="equipment-table">
                    <thead>
                        <tr>
                            <th><span class="material-symbols-outlined">calendar_month</span> Date</th>
                            <th><span class="material-symbols-outlined">person</span> Caretaker</th>
                            <th><span class="material-symbols-outlined">inventory</span> Equipment</th>
                            <th><span class="material-symbols-outlined">numbers</span> Qty</th>
                            <th><span class="material-symbols-outlined">payments</span> Est. Cost</th>
                            <th><span class="material-symbols-outlined">calculate</span> Total</th>
                            <th><span class="material-symbols-outlined">priority_high</span> Priority</th>
                            <th><span class="material-symbols-outlined">info</span> Status</th>
                            <th><span class="material-symbols-outlined">settings</span> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['requests'])): ?>
                            <?php foreach ($data['requests'] as $index => $request): ?>
                                <tr class="table-row animate__animated animate__fadeIn" style="animation-delay: <?php echo ($index * 0.05); ?>s;">
                                    <td>
                                        <div class="date-cell">
                                            <span class="date-day"><?php echo date('d', strtotime($request->requested_date)); ?></span>
                                            <span class="date-month"><?php echo date('M Y', strtotime($request->requested_date)); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="caretaker-cell">
                                            <div class="avatar"><?php echo strtoupper(substr($request->name, 0, 1)); ?></div>
                                            <span><?php echo htmlspecialchars($request->name); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="equipment-name">
                                            <span class="material-symbols-outlined">inventory_2</span>
                                            <strong><?php echo htmlspecialchars($request->equipment_name); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="quantity-badge"><?php echo $request->quantity; ?></span>
                                    </td>
                                    <td class="cost-cell">Rs. <?php echo number_format($request->estimated_cost, 2); ?></td>
                                    <td class="cost-cell total-cost">
                                        <?php if ($request->status == 'Approved' && $request->actual_cost > 0): ?>
                                            <strong class="actual-total">Rs. <?php echo number_format($request->quantity * $request->actual_cost, 2); ?></strong>
                                        <?php else: ?>
                                            <span class="estimated-total">~Rs. <?php echo number_format($request->quantity * $request->estimated_cost, 2); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="priority-badge priority-<?php echo strtolower($request->priority); ?>">
                                            <span class="material-symbols-outlined">flag</span>
                                            <?php echo htmlspecialchars($request->priority); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($request->status); ?>">
                                            <?php 
                                                $statusIcon = '';
                                                switch($request->status) {
                                                    case 'Pending': $statusIcon = 'schedule'; break;
                                                    case 'Approved': $statusIcon = 'check_circle'; break;
                                                    case 'Rejected': $statusIcon = 'cancel'; break;
                                                }
                                            ?>
                                            <span class="material-symbols-outlined"><?php echo $statusIcon; ?></span>
                                            <?php echo htmlspecialchars($request->status); ?>
                                        </span>
                                    </td>
                                    <td class="action-cell">
                                        <a href="<?php echo URL_ROOT; ?>/supervisor/reviewEquipmentRequest/<?php echo $request->id; ?>" 
                                           class="btn-view" 
                                           title="Review Request">
                                            <span class="material-symbols-outlined">visibility</span>
                                            <span class="btn-text">Review</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="no-data">
                                    <div class="empty-state">
                                        <span class="material-symbols-outlined">inbox</span>
                                        <p>No equipment requests found</p>
                                        <small>Try adjusting your filters</small>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
function toggleFilters() {
    const filtersForm = document.getElementById('filtersForm');
    const toggleBtn = document.querySelector('.btn-toggle-filters');
    
    filtersForm.classList.toggle('collapsed');
    
    if (filtersForm.classList.contains('collapsed')) {
        toggleBtn.innerHTML = '<span class="material-symbols-outlined">expand_more</span>';
    } else {
        toggleBtn.innerHTML = '<span class="material-symbols-outlined">expand_less</span>';
    }
}

function exportTable() {
    alert('Export functionality will be implemented soon!');
}

function printTable() {
    window.print();
}

// Auto-submit filters on change (optional)
document.querySelectorAll('.filter-select, .filter-input').forEach(element => {
    element.addEventListener('change', function() {
        // Uncomment to enable auto-submit
        // document.querySelector('.filters-form').submit();
    });
});
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
