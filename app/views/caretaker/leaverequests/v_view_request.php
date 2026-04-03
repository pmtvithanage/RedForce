<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>

<!-- Import global stylesheet -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">

<!-- Import Google Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<style>
    .page {
        margin: 0 40px;
        padding: 0 20px;
    }

    .back-btn-container {
        margin-bottom: 20px;
    }

    .details-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #a40000 0%, #d32f2f 100%);
        color: white;
        padding: 25px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .status-badge {
        padding: 8px 18px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
    }

    .status-badge.Pending {
        background: #fff3e0;
        color: #f57c00;
    }

    .status-badge.Approved {
        background: #e8f5e9;
        color: #388e3c;
    }

    .status-badge.Rejected {
        background: #ffebee;
        color: #d32f2f;
    }

    .card-body {
        padding: 30px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 25px;
    }

    .detail-item {
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        border-left: 4px solid var(--primary-color);
    }

    .detail-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detail-label .material-symbols-outlined {
        font-size: 16px;
    }

    .detail-value {
        font-size: 16px;
        color: #333;
        font-weight: 600;
    }

    .leave-type-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        display: inline-block;
    }

    .reason-section {
        margin: 25px 0;
        padding: 20px;
        background: #f5f5f5;
        border-radius: 8px;
    }

    .reason-section h3 {
        margin: 0 0 12px 0;
        font-size: 16px;
        color: #666;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .reason-section p {
        margin: 0;
        font-size: 15px;
        line-height: 1.6;
        color: #333;
    }

    .proof-section {
        margin: 25px 0;
        padding: 20px;
        background: #f5f5f5;
        border-radius: 8px;
    }

    .proof-section h3 {
        margin: 0 0 12px 0;
        font-size: 16px;
        color: #666;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .proof-file {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: white;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
    }

    .proof-file .material-symbols-outlined {
        font-size: 32px;
        color: var(--primary-color);
    }

    .proof-file-info {
        flex: 1;
    }

    .proof-file-name {
        font-size: 14px;
        font-weight: 500;
        color: #333;
        margin-bottom: 4px;
    }

    .proof-file-link {
        font-size: 13px;
        color: var(--primary-color);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .proof-file-link:hover {
        text-decoration: underline;
    }

    .no-proof {
        padding: 12px;
        background: white;
        border-radius: 6px;
        color: #999;
        font-style: italic;
        text-align: center;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px solid #f0f0f0;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back {
        background: #f1f1f1;
        color: #333;
    }

    .btn-back:hover {
        background: #e0e0e0;
    }

    .btn-edit {
        background: #f39c12;
        color: white;
    }

    .btn-edit:hover {
        background: #e67e22;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .btn-delete {
        background: #e74c3c;
        color: white;
        margin-left: auto;
    }

    .btn-delete:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .timeline-section {
        margin-top: 30px;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 8px;
    }

    .timeline-section h3 {
        margin: 0 0 15px 0;
        font-size: 16px;
        color: #666;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .timeline-item {
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .timeline-content {
        flex: 1;
        padding: 8px 0;
    }

    .timeline-date {
        font-size: 13px;
        color: #999;
    }

    .timeline-text {
        font-size: 14px;
        color: #333;
        margin-top: 2px;
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
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
</style>

<div class="back-btn-container" style="margin: 20px;">
    <button class="tertiary-btn" style="display:flex; width:100px; align-items:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/caretaker/leaverequests'"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>
</div>

<main class="page">
    <div class="details-card">
        <div class="card-header">
            <h2>Leave Request Details</h2>
            <span class="status-badge <?php echo $data['leaveRequest']->status; ?>">
                <?php echo $data['leaveRequest']->status; ?>
            </span>
        </div>

        <div class="card-body">
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">
                        <span class="material-symbols-outlined">category</span>
                        Leave Type
                    </div>
                    <div class="detail-value">
                        <span class="leave-type-badge">
                            <?php echo htmlspecialchars($data['leaveRequest']->leave_type); ?>
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">
                        <span class="material-symbols-outlined">calendar_month</span>
                        Duration
                    </div>
                    <div class="detail-value">
                        <?php 
                        $start = new DateTime($data['leaveRequest']->start_date);
                        $end = new DateTime($data['leaveRequest']->end_date);
                        $interval = $start->diff($end);
                        echo ($interval->days + 1) . ' Day(s)';
                        ?>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">
                        <span class="material-symbols-outlined">event</span>
                        Start Date
                    </div>
                    <div class="detail-value">
                        <?php echo date('M d, Y', strtotime($data['leaveRequest']->start_date)); ?>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">
                        <span class="material-symbols-outlined">event</span>
                        End Date
                    </div>
                    <div class="detail-value">
                        <?php echo date('M d, Y', strtotime($data['leaveRequest']->end_date)); ?>
                    </div>
                </div>
            </div>

            <div class="reason-section">
                <h3>
                    <span class="material-symbols-outlined">description</span>
                    Reason for Leave
                </h3>
                <p><?php echo nl2br(htmlspecialchars($data['leaveRequest']->reason)); ?></p>
            </div>

            <div class="proof-section">
                <h3>
                    <span class="material-symbols-outlined">attach_file</span>
                    Supporting Document
                </h3>
                <?php if (!empty($data['leaveRequest']->proof_file)): ?>
                    <div class="proof-file">
                        <?php 
                        $file_extension = pathinfo($data['leaveRequest']->proof_file, PATHINFO_EXTENSION);
                        $icon = 'description';
                        if (in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                            $icon = 'image';
                        } elseif (strtolower($file_extension) == 'pdf') {
                            $icon = 'picture_as_pdf';
                        }
                        ?>
                        <span class="material-symbols-outlined"><?php echo $icon; ?></span>
                        <div class="proof-file-info">
                            <div class="proof-file-name">
                                <?php echo basename($data['leaveRequest']->proof_file); ?>
                            </div>
                            <a href="<?php echo URL_ROOT . $data['leaveRequest']->proof_file; ?>" 
                               target="_blank" 
                               class="proof-file-link">
                                <span class="material-symbols-outlined" style="font-size: 14px;">open_in_new</span>
                                View Document
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-proof">No supporting document attached</div>
                <?php endif; ?>
            </div>

            <div class="timeline-section">
                <h3>
                    <span class="material-symbols-outlined">history</span>
                    Timeline
                </h3>
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <span class="material-symbols-outlined">add</span>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            <?php echo date('M d, Y \a\t g:i A', strtotime($data['leaveRequest']->created_at)); ?>
                        </div>
                        <div class="timeline-text">Request submitted</div>
                    </div>
                </div>
                <?php if (isset($data['leaveRequest']->updated_at) && $data['leaveRequest']->updated_at != $data['leaveRequest']->created_at): ?>
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <span class="material-symbols-outlined">update</span>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            <?php echo date('M d, Y \a\t g:i A', strtotime($data['leaveRequest']->updated_at)); ?>
                        </div>
                        <div class="timeline-text">Request updated</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="action-buttons">
                <button class="btn btn-back" onclick="window.location.href='<?php echo URL_ROOT; ?>/caretaker/leaverequests'">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Back to List
                </button>
                <?php if ($data['leaveRequest']->status == 'Pending'): ?>
                <button class="btn btn-edit" onclick="window.location.href='<?php echo URL_ROOT; ?>/caretaker/editLeaveRequest/<?php echo $data['leaveRequest']->id; ?>'">
                    <span class="material-symbols-outlined">edit</span>
                    Edit Request
                </button>
                <button class="btn btn-delete" onclick="confirmDelete()">
                    <span class="material-symbols-outlined">delete</span>
                    Delete Request
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Confirmation Modal -->
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-modal-content">
        <div class="confirm-modal-header">
            <span class="material-symbols-outlined confirm-modal-icon">warning</span>
            <h3 class="confirm-modal-title">Delete Leave Request</h3>
        </div>
        <p class="confirm-modal-message">Are you sure you want to delete this leave request? This action cannot be undone.</p>
        <div class="confirm-modal-actions">
            <button class="modal-btn modal-btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="modal-btn modal-btn-confirm" onclick="performDelete()">Delete</button>
        </div>
    </div>
</div>

<?php flash('msg')?>

<script>
// Show delete confirmation modal
function confirmDelete() {
    document.getElementById('confirmModal').classList.add('active');
}

// Close modal
function closeModal() {
    document.getElementById('confirmModal').classList.remove('active');
}

// Perform delete
function performDelete() {
    window.location.href = '<?php echo URL_ROOT; ?>/caretaker/deleteLeaveRequest/<?php echo $data['leaveRequest']->id; ?>';
}

// Close modal on background click
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

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
