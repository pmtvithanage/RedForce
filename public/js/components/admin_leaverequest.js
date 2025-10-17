<?php
<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_admin_sidebar.php'; ?>

<link rel="stylesheet" href="<?= URL_ROOT ?>/css/admin/leaverequest_style.css">

<main class="main-content">
    <!-- Flash Messages -->
    <?php flash('leave_success'); ?>
    <?php flash('leave_error'); ?>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon pending">
                <i class="material-icons">pending_actions</i>
            </div>
            <div class="stat-info">
                <h3><?= $data['leaveStats']->pending ?? 0 ?></h3>
                <p>Pending Requests</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon approved">
                <i class="material-icons">check_circle</i>
            </div>
            <div class="stat-info">
                <h3><?= $data['leaveStats']->approved ?? 0 ?></h3>
                <p>Approved</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon rejected">
                <i class="material-icons">cancel</i>
            </div>
            <div class="stat-info">
                <h3><?= $data['leaveStats']->rejected ?? 0 ?></h3>
                <p>Rejected</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="material-icons">assessment</i>
            </div>
            <div class="stat-info">
                <h3><?= $data['leaveStats']->total ?? 0 ?></h3>
                <p>Total Requests</p>
            </div>
        </div>
    </div>

    <!-- Pending Leave Requests Section -->
    <div class="pending-section">
        <h2>Pending Leave Requests</h2>
        
        <?php if (!empty($data['pendingLeaves'])): ?>
            <div class="pending-list">
                <?php foreach($data['pendingLeaves'] as $leave): ?>
                    <div class="leave-card">
                        <div class="leave-header">
                            <div class="caretaker-info">
                                <h3><?= htmlspecialchars($leave->caretaker_name) ?></h3>
                                <span class="email"><?= htmlspecialchars($leave->caretaker_email) ?></span>
                            </div>
                            <span class="status-badge pending">Pending</span>
                        </div>
                        
                        <div class="leave-body">
                            <div class="info-row">
                                <span class="label">Leave Type:</span>
                                <span class="value"><?= htmlspecialchars($leave->leave_type) ?></span>
                            </div>
                            <div class="info-row">
                                <span class="label">Duration:</span>
                                <span class="value">
                                    <?= date('d/m/Y', strtotime($leave->start_date)) ?> - 
                                    <?= date('d/m/Y', strtotime($leave->end_date)) ?>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="label">Reason:</span>
                                <span class="value"><?= htmlspecialchars($leave->reason) ?></span>
                            </div>
                            <?php if (!empty($leave->proof_file)): ?>
                                <div class="info-row">
                                    <span class="label">Proof:</span>
                                    <a href="<?= URL_ROOT ?>/<?= $leave->proof_file ?>" target="_blank" class="view-proof">
                                        <i class="material-icons">attach_file</i> View File
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="info-row">
                                <span class="label">Submitted:</span>
                                <span class="value"><?= date('d/m/Y H:i', strtotime($leave->created_at)) ?></span>
                            </div>
                        </div>
                        
                        <div class="leave-actions">
                            <form method="POST" action="<?= URL_ROOT ?>/admin/approveLeave/<?= $leave->id ?>" style="display:inline;">
                                <button type="submit" class="btn-approve" onclick="return confirm('Approve this leave request?')">
                                    <i class="material-icons">check</i> Approve
                                </button>
                            </form>
                            <button class="btn-reject" onclick="openRejectModal(<?= $leave->id ?>)">
                                <i class="material-icons">close</i> Reject
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="material-icons">inbox</i>
                <p>No pending leave requests</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Reject Modal -->
<div id="rejectModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reject Leave Request</h3>
            <button class="close-btn" onclick="closeRejectModal()">
                <i class="material-icons">close</i>
            </button>
        </div>
        <form id="rejectForm" method="POST" action="">
            <div class="modal-body">
                <label for="reason">Reason for Rejection *</label>
                <textarea id="reason" name="reason" rows="4" required placeholder="Please provide a reason for rejecting this leave request..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="btn-confirm-reject">Confirm Rejection</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(leaveId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = '<?= URL_ROOT ?>/admin/rejectLeave/' + leaveId;
    modal.style.display = 'flex';
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.style.display = 'none';
    document.getElementById('reason').value = '';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('rejectModal');
    if (event.target === modal) {
        closeRejectModal();
    }
}
</script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?></link>