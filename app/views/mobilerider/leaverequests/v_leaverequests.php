<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    .container {
        width: 90%;
        margin: 40px auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }

    /* Stats Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
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

    .stat-icon.total {
        background: #e3f2fd;
        color: #1976d2;
    }

    .stat-icon.pending {
        background: #fff3e0;
        color: #f57c00;
    }

    .stat-icon.approved {
        background: #e8f5e9;
        color: #388e3c;
    }

    .stat-icon.rejected {
        background: #ffebee;
        color: #d32f2f;
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

    /* Table Card */
    .table-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        overflow-x: auto;
    }

    /* Filters */
    .filter-controls {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .filter-select {
        min-width: 150px;
        height: 44px;
        border: 1px solid #dadada;
        border-radius: 6px;
        padding: 0 12px;
        background: #fff;
        font-size: 14px;
        color: #333;
        outline: none;
    }

    .filter-select:focus {
        border-color: #a40000;
    }

    /* Search Bar */
    .search-box {
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #dadada;
        padding: 10px 14px;
        border-radius: 6px;
        margin-bottom: 0;
        flex: 1;
        min-width: 280px;
    }

    .search-box:focus-within {
        border-color: #a40000;
    }

    .search-box input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 15px;
    }

    /* Table */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 12px;
        font-size: 14px;
        text-align: left;
    }

    thead th {
        background: #f9e9e9;
        font-weight: 600;
    }

    tbody tr {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-bottom: 1px solid #f0f0f0;
    }

    tbody tr:hover {
        background-color: #f9f9f9;
    }

    /* Status Badges */
    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        color: #374151;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        border: 1px solid transparent;
    }

    .badge.Pending {
        background: #fff3e0;
        color: #f57c00;
        border-color: #ffe0b2;
    }

    .badge.Approved {
        background: #e8f5e9;
        color: #2e7d32;
        border-color: #c8e6c9;
    }

    .badge.Rejected {
        background: #e74c3c;
    }

    /* Leave Type Tags */
    .leave-type-tag {
        background: #e8f4f8;
        color: #0066cc;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    /* Action Buttons */
    .actions {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        padding: 8px 12px;
        border: 1px solid transparent;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        line-height: 1;
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    .view-btn {
        background: #e3f2fd;
        color: #1976d2;
        border-color: #cfe5fc;
    }

    .view-btn:hover {
        background: #1976d2;
        color: #fff;
    }

    .edit-btn {
        background: #fff3e0;
        color: #f57c00;
        border-color: #ffe0b2;
    }

    .edit-btn:hover {
        background: #f57c00;
        color: #fff;
    }

    .delete-btn {
        background: #ffebee;
        color: #c62828;
        border-color: #ffcdd2;
    }

    .delete-btn:hover {
        background: #c62828;
        color: #fff;
    }

    .create-btn {
        background: #2e7d32;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .create-btn:hover {
        background: #1b5e20;
        transform: translateY(-1px);
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #666;
    }

    .empty-state .material-symbols-outlined {
        font-size: 64px;
        color: #ccc;
        margin-bottom: 16px;
    }

    /* Confirmation Modal */
    .confirm-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 10000;
        justify-content: center;
        align-items: center;
    }

    .confirm-modal.active {
        display: flex;
    }

    .confirm-modal-content {
        background: white;
        padding: 30px;
        border-radius: 12px;
        max-width: 450px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .confirm-modal-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .confirm-modal-icon {
        font-size: 32px;
        color: #ff9800;
    }

    .confirm-modal-title {
        font-size: 22px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .confirm-modal-message {
        font-size: 16px;
        color: #666;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .confirm-modal-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .modal-btn {
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modal-btn-cancel {
        background: #f1f1f1;
        color: #333;
    }

    .modal-btn-cancel:hover {
        background: #e0e0e0;
    }

    .modal-btn-confirm {
        background: #e74c3c;
        color: white;
    }

    .modal-btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="container">
    <div class="page-header">
        <h1>Leave Requests</h1>
        <button class="primary-btn create-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/MobileRider/createLeaveRequest'">
            <span class="material-symbols-outlined">add</span>
            New Leave Request
        </button>
    </div>

    <!-- Stats Cards -->
    <?php if (isset($data['stats'])): ?>
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $data['stats']->total_requests ?? 0; ?></h3>
                    <p>Total Requests</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $data['stats']->pending_requests ?? 0; ?></h3>
                    <p>Pending</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon approved">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $data['stats']->approved_requests ?? 0; ?></h3>
                    <p>Approved</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon rejected">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $data['stats']->rejected_requests ?? 0; ?></h3>
                    <p>Rejected</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="table-card">
        <div class="filter-controls">
            <div class="search-box">
                <span class="material-symbols-outlined">search</span>
                <input type="text" id="searchInput" placeholder="Search leave requests...">
            </div>
            <select id="statusFilter" class="filter-select">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            <select id="monthFilter" class="filter-select">
                <option value="">All Months</option>
            </select>
        </div>

        <?php if (empty($data['leaveRequests'])): ?>
            <div class="empty-state">
                <div>
                    <span class="material-symbols-outlined">event_busy</span>
                </div>
                <h3>No Leave Requests Yet</h3>
                <p>Create your first leave request to get started.</p>
            </div>
        <?php else: ?>
            <table id="leaveRequestsTable">
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Duration</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['leaveRequests'] as $request): ?>
                        <tr data-id="<?php echo $request->id; ?>" onclick="viewRequest(<?php echo $request->id; ?>)" style="cursor: pointer;">
                            <td>
                                <span class="leave-type-tag">
                                    <?php echo htmlspecialchars($request->leave_type); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($request->start_date)); ?></td>
                            <td><?php echo date('M d, Y', strtotime($request->end_date)); ?></td>
                            <td>
                                <?php
                                $start = new DateTime($request->start_date);
                                $end = new DateTime($request->end_date);
                                $interval = $start->diff($end);
                                echo ($interval->days + 1) . ' day(s)';
                                ?>
                            </td>
                            <td>
                                <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($request->reason); ?>">
                                    <?php echo htmlspecialchars($request->reason); ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge <?php echo $request->status; ?>">
                                    <?php echo ucfirst($request->status); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($request->created_at)); ?></td>
                            <td>
                                <div class="actions">
                                    <button class="action-btn view-btn"
                                        onclick="event.stopPropagation(); viewRequest(<?php echo $request->id; ?>)"
                                        title="View Details">
                                        <span class="material-symbols-outlined">visibility</span>
                                        View
                                    </button>
                                    <?php if ($request->status == 'Pending'): ?>
                                        <button class="action-btn edit-btn"
                                            onclick="event.stopPropagation(); editRequest(<?php echo $request->id; ?>)"
                                            title="Edit">
                                            <span class="material-symbols-outlined">edit</span>
                                            Edit
                                        </button>
                                        <button class="action-btn delete-btn"
                                            onclick="event.stopPropagation(); deleteRequest(<?php echo $request->id; ?>)"
                                            title="Delete">
                                            <span class="material-symbols-outlined">delete</span>
                                            Delete
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-modal-content">
        <div class="confirm-modal-header">
            <span class="material-symbols-outlined confirm-modal-icon">warning</span>
            <h3 class="confirm-modal-title" id="modalTitle">Confirm Action</h3>
        </div>
        <p class="confirm-modal-message" id="modalMessage">Are you sure you want to proceed?</p>
        <div class="confirm-modal-actions">
            <button class="modal-btn modal-btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="modal-btn modal-btn-confirm" id="modalConfirmBtn" onclick="confirmAction()">Confirm</button>
        </div>
    </div>
</div>

<?php flash('msg') ?>

<script>
    let currentAction = null;
    let actionData = null;

    // Show modal
    function showModal(title, message, action, data) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalMessage').textContent = message;

        currentAction = action;
        actionData = data;

        document.getElementById('confirmModal').classList.add('active');
    }

    // Close modal
    function closeModal() {
        document.getElementById('confirmModal').classList.remove('active');
        currentAction = null;
        actionData = null;
    }

    // Confirm action
    function confirmAction() {
        if (currentAction) {
            currentAction(actionData);
        }
        closeModal();
    }

    // Close modal on background click
    document.getElementById('confirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    function applyFilters() {
        const searchTerm = (document.getElementById('searchInput')?.value || '').toLowerCase();
        const statusFilter = (document.getElementById('statusFilter')?.value || '').toLowerCase();
        const monthFilter = document.getElementById('monthFilter')?.value || '';
        const table = document.getElementById('leaveRequestsTable');
        if (!table) return;

        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (let row of rows) {
            const leaveType = row.cells[0].textContent.toLowerCase();
            const reason = row.cells[4].textContent.toLowerCase();
            const status = row.cells[5].textContent.toLowerCase();
            const createdDateText = row.cells[6].textContent.trim();
            const createdDate = new Date(createdDateText);
            const rowMonth = !Number.isNaN(createdDate.getTime()) ?
                `${createdDate.getFullYear()}-${String(createdDate.getMonth() + 1).padStart(2, '0')}` :
                '';

            const matchesSearch = !searchTerm || leaveType.includes(searchTerm) || reason.includes(searchTerm) || status.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;
            const matchesMonth = !monthFilter || rowMonth === monthFilter;

            if (matchesSearch && matchesStatus && matchesMonth) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    function populateMonthFilter() {
        const table = document.getElementById('leaveRequestsTable');
        const monthFilter = document.getElementById('monthFilter');
        if (!table || !monthFilter) return;

        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        const monthMap = new Map();

        for (let row of rows) {
            const createdDateText = row.cells[6].textContent.trim();
            const createdDate = new Date(createdDateText);
            if (Number.isNaN(createdDate.getTime())) continue;

            const value = `${createdDate.getFullYear()}-${String(createdDate.getMonth() + 1).padStart(2, '0')}`;
            const label = createdDate.toLocaleString('default', {
                month: 'long',
                year: 'numeric'
            });
            monthMap.set(value, label);
        }

        const sortedMonths = Array.from(monthMap.entries()).sort((a, b) => b[0].localeCompare(a[0]));
        for (const [value, label] of sortedMonths) {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = label;
            monthFilter.appendChild(option);
        }
    }

    document.getElementById('searchInput')?.addEventListener('input', applyFilters);
    document.getElementById('statusFilter')?.addEventListener('change', applyFilters);
    document.getElementById('monthFilter')?.addEventListener('change', applyFilters);
    populateMonthFilter();

    // View leave request
    function viewRequest(id) {
        window.location.href = '<?php echo URL_ROOT; ?>/MobileRider/viewLeaveRequest/' + id;
    }

    // Edit leave request
    function editRequest(id) {
        window.location.href = '<?php echo URL_ROOT; ?>/MobileRider/editLeaveRequest/' + id;
    }

    // Delete leave request
    function deleteRequest(id) {
        showModal(
            'Delete Leave Request',
            'Are you sure you want to delete this leave request? This action cannot be undone.',
            performDeleteRequest,
            id
        );
    }

    function performDeleteRequest(id) {
        window.location.href = '<?php echo URL_ROOT; ?>/MobileRider/deleteLeaveRequest/' + id;
    }

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