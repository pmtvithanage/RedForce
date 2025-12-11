<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/equipment_style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<main class="main-content">
    <div class="equipment-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><span class="material-symbols-outlined">inventory_2</span> <?php echo $data['pageTitle']; ?></h1>
        </div>

        <!-- Flash Messages -->
        <?php flash('equipment_message'); ?>
        <?php flash('equipment_error'); ?>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon yellow">
                    <span class="material-symbols-outlined">pending_actions</span>
                </div>
                <div class="stat-info">
                    <h3><?php echo $data['stats']->pending_count ?? 0; ?></h3>
                    <p>Pending Requests</p>
                    <small>Rs. <?php echo number_format($data['stats']->pending_cost ?? 0, 2); ?></small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
                <div class="stat-info">
                    <h3><?php echo $data['stats']->approved_count ?? 0; ?></h3>
                    <p>Approved</p>
                    <small>Rs. <?php echo number_format($data['stats']->approved_cost ?? 0, 2); ?></small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon red">
                    <span class="material-symbols-outlined">cancel</span>
                </div>
                <div class="stat-info">
                    <h3><?php echo $data['stats']->rejected_count ?? 0; ?></h3>
                    <p>Rejected</p>
                    <small>Rs. <?php echo number_format($data['stats']->rejected_cost ?? 0, 2); ?></small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon blue">
                    <span class="material-symbols-outlined">inventory_2</span>
                </div>
                <div class="stat-info">
                    <h3><?php echo $data['stats']->total_requests ?? 0; ?></h3>
                    <p>Total Requests</p>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <form method="GET" action="<?php echo URL_ROOT; ?>/supervisor/equipmentRequests" class="filters-form">
                <div class="filter-group">
                    <label><span class="material-symbols-outlined">flag</span> Status</label>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="Pending" <?php echo (isset($data['filters']['status']) && $data['filters']['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="Approved" <?php echo (isset($data['filters']['status']) && $data['filters']['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                        <option value="Rejected" <?php echo (isset($data['filters']['status']) && $data['filters']['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label><span class="material-symbols-outlined">priority_high</span> Priority</label>
                    <select name="priority">
                        <option value="">All Priorities</option>
                        <option value="High" <?php echo (isset($data['filters']['priority']) && $data['filters']['priority'] == 'High') ? 'selected' : ''; ?>>High</option>
                        <option value="Medium" <?php echo (isset($data['filters']['priority']) && $data['filters']['priority'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                        <option value="Low" <?php echo (isset($data['filters']['priority']) && $data['filters']['priority'] == 'Low') ? 'selected' : ''; ?>>Low</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label><span class="material-symbols-outlined">person</span> Caretaker</label>
                    <select name="caretaker_id">
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
                    <label><span class="material-symbols-outlined">calendar_today</span> From Date</label>
                    <input type="date" name="date_from" value="<?php echo $data['filters']['date_from'] ?? ''; ?>">
                </div>

                <div class="filter-group">
                    <label><span class="material-symbols-outlined">calendar_today</span> To Date</label>
                    <input type="date" name="date_to" value="<?php echo $data['filters']['date_to'] ?? ''; ?>">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <span class="material-symbols-outlined">filter_alt</span> Apply Filters
                    </button>
                    <a href="<?php echo URL_ROOT; ?>/supervisor/equipmentRequests" class="btn-reset">
                        <span class="material-symbols-outlined">refresh</span> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Requests Table -->
        <div class="table-container">
            <table class="equipment-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Caretaker</th>
                        <th>Equipment</th>
                        <th>Qty</th>
                        <th>Est. Cost</th>
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
                                <td><?php echo htmlspecialchars($request->name); ?></td>
                                <td><strong><?php echo htmlspecialchars($request->equipment_name); ?></strong></td>
                                <td><?php echo $request->quantity; ?></td>
                                <td class="cost-cell">Rs. <?php echo number_format($request->estimated_cost, 2); ?></td>
                                <td class="cost-cell total-cost">
                                    <?php if ($request->status == 'Approved' && $request->actual_cost > 0): ?>
                                        <strong>Rs. <?php echo number_format($request->quantity * $request->actual_cost, 2); ?></strong>
                                    <?php else: ?>
                                        <span class="estimated-total">~Rs. <?php echo number_format($request->quantity * $request->estimated_cost, 2); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="priority-badge priority-<?php echo strtolower($request->priority); ?>">
                                        <?php echo htmlspecialchars($request->priority); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($request->status); ?>">
                                        <?php echo htmlspecialchars($request->status); ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <a href="<?php echo URL_ROOT; ?>/supervisor/reviewEquipmentRequest/<?php echo $request->id; ?>" 
                                       class="btn-view" 
                                       title="Review">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="no-data">
                                <span class="material-symbols-outlined">inbox</span>
                                <p>No equipment requests found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
