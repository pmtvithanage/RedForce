<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

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

.back-btn-container {
    margin-bottom: 20px;
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
    box-shadow: 0 0 10px rgba(0,0,0,0.08);
    overflow-x: auto;
}

/* Filter Section */
.filter-section {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #dadada;
    padding: 10px 14px;
    border-radius: 6px;
    flex: 1;
    min-width: 250px;
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

.filter-select {
    padding: 10px 14px;
    border: 1px solid #dadada;
    border-radius: 6px;
    font-size: 15px;
    min-width: 150px;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
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
    color: white;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.badge.Pending {
    background: #ff9800;
}

.badge.Approved {
    background: #4caf50;
}

.badge.Rejected {
    background: #e74c3c;
}

/* Role Badges */
.role-badge {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

/* Leave Type Tags */
.leave-type-tag {
    background: #f3e5f5;
    color: #7b1fa2;
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
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 4px;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.approve-btn {
    background: #4caf50;
    color: white;
}

.reject-btn {
    background: #e74c3c;
    color: white;
}

.view-btn {
    background: #2196F3;
    color: white;
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
    max-width: 500px;
    width: 90%;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
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
}

.confirm-modal-icon.approve {
    color: #4caf50;
}

.confirm-modal-icon.reject {
    color: #e74c3c;
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
    margin-bottom: 20px;
}

.confirm-modal-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #dadada;
    border-radius: 6px;
    font-size: 15px;
    margin-bottom: 20px;
    font-family: inherit;
}

.confirm-modal-input:focus {
    outline: none;
    border-color: #a40000;
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
    color: white;
}

.modal-btn-confirm.approve {
    background: #4caf50;
}

.modal-btn-confirm.reject {
    background: #e74c3c;
}

.modal-btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>

<div class="back-btn-container" style="margin: 20px;">
    <button class="tertiary-btn" style="display:flex; width:100px; align-items:center;" onclick="history.back()"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>
</div>

<div class="container">
    <div class="page-header">
        <h1>Leave Requests Management</h1>
    </div>

    <!-- Stats Cards -->
    <?php if (isset($data['leaveStats'])): ?>
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon total">
                <span class="material-symbols-outlined">list_alt</span>
            </div>
            <div class="stat-content">
                <h3><?php echo $data['leaveStats']->total ?? 0; ?></h3>
                <p>Total Requests</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pending">
                <span class="material-symbols-outlined">pending</span>
            </div>
            <div class="stat-content">
                <h3><?php echo $data['leaveStats']->pending ?? 0; ?></h3>
                <p>Pending</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon approved">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
            <div class="stat-content">
                <h3><?php echo $data['leaveStats']->approved ?? 0; ?></h3>
                <p>Approved</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon rejected">
                <span class="material-symbols-outlined">cancel</span>
            </div>
            <div class="stat-content">
                <h3><?php echo $data['leaveStats']->rejected ?? 0; ?></h3>
                <p>Rejected</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="table-card">
        <div class="filter-section">
            <div class="search-box">
                <span class="material-symbols-outlined">search</span>
                <input type="text" id="searchInput" placeholder="Search by name, role, or leave type...">
            </div>
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
            <select class="filter-select" id="roleFilter">
                <option value="">All Roles</option>
                <option value="Premise Officer">Premise Officer</option>
                <option value="Supervisor">Supervisor</option>
                <option value="Caretaker">Caretaker</option>
                <option value="Mobile Rider">Mobile Rider</option>
            </select>
        </div>

        <?php if (empty($data['leaveRequests'])): ?>
            <div class="empty-state">
                <div>
                    <span class="material-symbols-outlined">event_busy</span>
                </div>
                <h3>No Leave Requests</h3>
                <p>There are no leave requests in the system yet.</p>
            </div>
        <?php else: ?>
            <table id="leaveRequestsTable">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Role</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['leaveRequests'] as $request): ?>
                        <tr data-status="<?php echo $request->status; ?>" data-role="<?php echo $request->employee_role ?? ''; ?>">
                            <td>
                                <div>
                                    <div style="font-weight: 500;"><?php echo htmlspecialchars($request->employee_name ?? 'Unknown'); ?></div>
                                    <div style="font-size: 12px; color: #666;"><?php echo htmlspecialchars($request->employee_email ?? ''); ?></div>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge">
                                    <?php echo htmlspecialchars($request->employee_role ?? 'Unknown'); ?>
                                </span>
                            </td>
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
                                <span class="badge <?php echo $request->status; ?>">
                                    <?php echo ucfirst($request->status); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($request->created_at)); ?></td>
                            <td>
                                <div class="actions">
                                    <button class="action-btn view-btn" 
                                            onclick="viewRequest(<?php echo $request->id; ?>)"
                                            title="View Details">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </button>
                                    <?php if ($request->status == 'Pending'): ?>
                                    <button class="action-btn approve-btn" 
                                            onclick="approveRequest(<?php echo $request->id; ?>)"
                                            title="Approve">
                                        <span class="material-symbols-outlined">check</span>
                                    </button>
                                    <button class="action-btn reject-btn" 
                                            onclick="rejectRequest(<?php echo $request->id; ?>)"
                                            title="Reject">
                                        <span class="material-symbols-outlined">close</span>
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
            <span class="material-symbols-outlined confirm-modal-icon" id="modalIcon">help</span>
            <h3 class="confirm-modal-title" id="modalTitle">Confirm Action</h3>
        </div>
        <p class="confirm-modal-message" id="modalMessage">Are you sure you want to proceed?</p>
        <textarea class="confirm-modal-input" id="modalInput" placeholder="Enter reason (optional)" style="display: none;" rows="3"></textarea>
        <div class="confirm-modal-actions">
            <button class="modal-btn modal-btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="modal-btn modal-btn-confirm" id="modalConfirmBtn" onclick="confirmAction()">Confirm</button>
        </div>
    </div>
</div>

<?php flash('msg')?>

<script>
let currentAction = null;
let currentRequestId = null;

// Show modal for approve
function showApproveModal(requestId) {
    document.getElementById('modalTitle').textContent = 'Approve Leave Request';
    document.getElementById('modalMessage').textContent = 'Are you sure you want to approve this leave request?';
    document.getElementById('modalIcon').textContent = 'check_circle';
    document.getElementById('modalIcon').className = 'material-symbols-outlined confirm-modal-icon approve';
    document.getElementById('modalInput').style.display = 'none';
    document.getElementById('modalConfirmBtn').className = 'modal-btn modal-btn-confirm approve';
    document.getElementById('modalConfirmBtn').textContent = 'Approve';
    
    currentAction = 'approve';
    currentRequestId = requestId;
    
    document.getElementById('confirmModal').classList.add('active');
}

// Show modal for reject
function showRejectModal(requestId) {
    document.getElementById('modalTitle').textContent = 'Reject Leave Request';
    document.getElementById('modalMessage').textContent = 'Please provide a reason for rejecting this leave request:';
    document.getElementById('modalIcon').textContent = 'cancel';
    document.getElementById('modalIcon').className = 'material-symbols-outlined confirm-modal-icon reject';
    document.getElementById('modalInput').style.display = 'block';
    document.getElementById('modalInput').value = '';
    document.getElementById('modalConfirmBtn').className = 'modal-btn modal-btn-confirm reject';
    document.getElementById('modalConfirmBtn').textContent = 'Reject';
    
    currentAction = 'reject';
    currentRequestId = requestId;
    
    document.getElementById('confirmModal').classList.add('active');
}

// Close modal
function closeModal() {
    document.getElementById('confirmModal').classList.remove('active');
    currentAction = null;
    currentRequestId = null;
    document.getElementById('modalInput').value = '';
}

// Confirm action
function confirmAction() {
    if (currentAction === 'approve') {
        window.location.href = '<?php echo URL_ROOT; ?>/admin/approveLeaveRequest/' + currentRequestId;
    } else if (currentAction === 'reject') {
        const reason = document.getElementById('modalInput').value.trim();
        if (!reason) {
            alert('Please provide a reason for rejection');
            return;
        }
        window.location.href = '<?php echo URL_ROOT; ?>/admin/rejectLeaveRequest/' + currentRequestId + '?reason=' + encodeURIComponent(reason);
    }
    closeModal();
}

// Close modal on background click
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Search and filter functionality
document.getElementById('searchInput')?.addEventListener('input', filterTable);
document.getElementById('statusFilter')?.addEventListener('change', filterTable);
document.getElementById('roleFilter')?.addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const roleFilter = document.getElementById('roleFilter').value;
    const table = document.getElementById('leaveRequestsTable');
    
    if (!table) return;
    
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let row of rows) {
        const employeeName = row.cells[0].textContent.toLowerCase();
        const role = row.getAttribute('data-role');
        const leaveType = row.cells[2].textContent.toLowerCase();
        const status = row.getAttribute('data-status');
        
        const matchesSearch = employeeName.includes(searchTerm) || 
                             role.toLowerCase().includes(searchTerm) || 
                             leaveType.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesRole = !roleFilter || role === roleFilter;
        
        if (matchesSearch && matchesStatus && matchesRole) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

// View request details
function viewRequest(id) {    
    window.location.href = '<?php echo URL_ROOT; ?>/admin/viewLeaveRequest/' + id;
}

// Approve request
function approveRequest(id) {
    showApproveModal(id);
}

// Reject request
function rejectRequest(id) {
    showRejectModal(id);
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
