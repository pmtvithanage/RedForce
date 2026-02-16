<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>


<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<style>
/* Equipment Container */
.equipment-container {
    margin: 30px;
}

/* Statistics Cards - Matching v_notes.php Style */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-card.yellow .stat-icon {
    background: #fff3e0;
    color: #f57c00;
}

.stat-card.green .stat-icon {
    background: #e8f5e9;
    color: #388e3c;
}

.stat-card.red .stat-icon {
    background: #ffebee;
    color: #d32f2f;
}

.stat-card.blue .stat-icon {
    background: #e3f2fd;
    color: #1976d2;
}

.stat-content h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #333;
}

.stat-content p {
    margin: 5px 0 0 0;
    font-size: 14px;
    color: #666;
}

/* Filters Section */
.filters-section {
    background: white;
    padding: 0;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    overflow: hidden;
}

.filters-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background: #6c757d;
    cursor: pointer;
}

.filters-header:hover {
    background: #5a6268;
}

.filters-header h3 {
    margin: 0;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 600;
}

.filters-header h3 .material-symbols-outlined {
    font-size: 22px;
}

.btn-toggle-filters {
    background: none;
    border: none;
    color: #ffffff;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
}

.btn-toggle-filters .material-symbols-outlined {
    font-size: 24px;
}

.filters-form {
    padding: 24px;
    transition: all 0.3s ease;
}

.filters-form.collapsed {
    display: none;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-group label {
    font-weight: 500;
    color: #555;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.filter-group label .material-symbols-outlined {
    font-size: 18px;
    color: #666;
}

.filter-select,
.filter-input {
    padding: 10px 14px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: #6c757d;
    box-shadow: 0 0 0 2px rgba(108, 117, 125, 0.1);
}

.filter-actions {
    grid-column: 1 / -1;
    display: flex;
    gap: 12px;
    margin-top: 10px;
}

.btn-filter {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s;
}

.btn-apply {
    background-color: #6c757d;
    color: white;
}

.btn-apply:hover {
    background-color: #5a6268;
}

.btn-reset {
    background-color: #f0f0f0;
    color: #333;
    text-decoration: none;
}

.btn-reset:hover {
    background-color: #e0e0e0;
}

/* Equipment Table Custom Styles */
.equipment-requests-table {
    margin: 0 0 30px !important;
}

.equipment-requests-table .data-table {
    min-width: 900px;
}

/* Custom Cell Styles for Equipment Table */

.date-cell {
    display: flex;
    flex-direction: column;
}

.date-day {
    font-size: 16px;
    font-weight: 700;
    color: #333;
}

.date-month {
    font-size: 11px;
    color: #666;
    text-transform: uppercase;
}

.caretaker-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #6c757d;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
}

.equipment-name {
    display: flex;
    align-items: center;
    gap: 8px;
}

.equipment-name .material-symbols-outlined {
    font-size: 20px;
    color: #666;
}

.quantity-badge {
    background: #f0f0f0;
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 600;
    color: #333;
}

.cost-cell {
    font-weight: 600;
    color: #555;
}

.total-cost {
    color: #27ae60;
    font-size: 15px;
}

.priority-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.priority-badge .material-symbols-outlined {
    font-size: 14px;
}

.priority-high {
    background: #ffebee;
    color: #c62828;
}

.priority-medium {
    background: #fff3e0;
    color: #ef6c00;
}

.priority-low {
    background: #e8f5e9;
    color: #2e7d32;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-transform: uppercase;
}

.status-badge .material-symbols-outlined {
    font-size: 14px;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-supervisor-approved {
    background: #d1ecf1;
    color: #0c5460;
}

.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.action-cell {
    white-space: nowrap;
}

.btn-approve,
.btn-view {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s;
}

.btn-approve {
    background: #6c757d;
    color: white;
}

.btn-approve:hover {
    background: #5a6268;
}

.btn-view {
    background: #f0f0f0;
    color: #333;
}

.btn-view:hover {
    background: #e0e0e0;
}

.btn-approve .material-symbols-outlined,
.btn-view .material-symbols-outlined {
    font-size: 18px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.empty-state .material-symbols-outlined {
    font-size: 64px;
    color: #ddd;
    margin-bottom: 16px;
}

.empty-state p {
    font-size: 16px;
    font-weight: 600;
    color: #666;
    margin: 0 0 8px 0;
}

.empty-state small {
    font-size: 13px;
    color: #999;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filters-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .equipment-container {
        margin: 15px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .stat-card {
        padding: 16px;
    }

    .filters-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .filter-actions {
        flex-direction: column;
    }

    .btn-filter {
        width: 100%;
        justify-content: center;
    }

    .table-container {
        -webkit-overflow-scrolling: touch;
    }

    .equipment-requests-table .data-table {
        min-width: 900px;
    }

    .btn-text {
        display: none;
    }
}

@media (max-width: 480px) {
    .stat-content h3 {
        font-size: 24px;
    }
}
</style>

<main class="main-content">
    <div class="equipment-container">
        <!-- Flash Messages -->
        <?php flash('equipment_message'); ?>
        <?php flash('equipment_error'); ?>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card yellow">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">pending_actions</span>
                </div>
                <div class="stat-content">
                    <h3><?php echo $data['stats']->pending_count ?? 0; ?></h3>
                    <p>Awaiting Decision</p>
                </div>
            </div>

            <div class="stat-card green">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">task_alt</span>
                </div>
                <div class="stat-content">
                    <h3><?php echo $data['stats']->approved_count ?? 0; ?></h3>
                    <p>Approved</p>
                </div>
            </div>

            <div class="stat-card red">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">block</span>
                </div>
                <div class="stat-content">
                    <h3><?php echo $data['stats']->rejected_count ?? 0; ?></h3>
                    <p>Rejected</p>
                </div>
            </div>

            <div class="stat-card blue">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">inventory</span>
                </div>
                <div class="stat-content">
                    <h3><?php echo $data['stats']->total_requests ?? 0; ?></h3>
                    <p>Total Requests</p>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
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
            <form method="GET" action="<?php echo URL_ROOT; ?>/client/equipmentRequests" class="filters-form" id="filtersForm">
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
                        <a href="<?php echo URL_ROOT; ?>/client/equipmentRequests" class="btn-filter btn-reset">
                            <span class="material-symbols-outlined">refresh</span> 
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Requests Table Component -->
        <?php
        // Prepare table configuration
        $tableConfig = [
            'title' => 'Equipment Requests from Caretakers',
            'headers' => ['Date', 'Caretaker', 'Equipment', 'Qty', 'Est. Cost', 'Total', 'Priority', 'Status', 'Actions'],
            'data' => $data['requests'] ?? [],
            'emptyMessage' => 'No equipment requests found. Try adjusting your filters.',
            'sectionClass' => 'table-section equipment-requests-table',
            'renderCallback' => function($request) {
                ?>
                <tr class="table-row">
                    <td>
                        <div class="date-cell">
                            <span class="date-day"><?php echo date('d', strtotime($request->requested_date)); ?></span>
                            <span class="date-month"><?php echo date('M Y', strtotime($request->requested_date)); ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="caretaker-cell">
                            <div class="avatar"><?php echo strtoupper(substr($request->caretaker_name ?? 'C', 0, 1)); ?></div>
                            <span><?php echo htmlspecialchars($request->caretaker_name ?? 'Unknown'); ?></span>
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
                    <td class="cost-cell">Rs. <?php echo number_format($request->estimated_cost ?? 0, 2); ?></td>
                    <td class="cost-cell total-cost">
                        <span class="estimated-total">Rs. <?php echo number_format($request->total_cost ?? 0, 2); ?></span>
                    </td>
                    <td>
                        <span class="priority-badge priority-<?php echo strtolower($request->priority); ?>">
                            <span class="material-symbols-outlined">flag</span>
                            <?php echo htmlspecialchars($request->priority); ?>
                        </span>
                    </td>
                    <td>
                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $request->status)); ?>">
                            <?php 
                                $statusIcon = '';
                                switch($request->status) {
                                    case 'Pending': $statusIcon = 'schedule'; break;
                                    case 'Supervisor Approved': $statusIcon = 'verified'; break;
                                    case 'Approved': $statusIcon = 'check_circle'; break;
                                    case 'Rejected': $statusIcon = 'cancel'; break;
                                }
                            ?>
                            <span class="material-symbols-outlined"><?php echo $statusIcon; ?></span>
                            <?php echo htmlspecialchars($request->status); ?>
                        </span>
                    </td>
                    <td class="action-cell">
                        <?php if ($request->status === 'Supervisor Approved'): ?>
                            <a href="<?php echo URL_ROOT; ?>/client/reviewEquipmentRequest/<?php echo $request->id; ?>" 
                               class="btn-approve" 
                               title="Review & Approve/Reject">
                                <span class="material-symbols-outlined">fact_check</span>
                                <span class="btn-text">Review & Decide</span>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo URL_ROOT; ?>/client/reviewEquipmentRequest/<?php echo $request->id; ?>" 
                               class="btn-view" 
                               title="View Details">
                                <span class="material-symbols-outlined">visibility</span>
                                <span class="btn-text">View Details</span>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
            }
        ];

        // Include table component
        include APP_ROOT . '/views/components/table.php';
        ?>
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
