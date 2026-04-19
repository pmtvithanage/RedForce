<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<!-- Import global stylesheet -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">

<!-- Import Google Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<style>
    .page {
        margin: 20px;
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

    .employee-section {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        border-left: 4px solid var(--primary-color);
    }

    .employee-section h3 {
        margin: 0 0 15px 0;
        font-size: 16px;
        color: #666;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .employee-info {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .employee-detail {
        flex: 1;
        min-width: 200px;
    }

    .employee-label {
        font-size: 12px;
        color: #999;
        margin-bottom: 4px;
    }

    .employee-value {
        font-size: 15px;
        color: #333;
        font-weight: 600;
    }

    .role-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
        display: inline-block;
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
        background: #f3e5f5;
        color: #7b1fa2;
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

    .response-section {
        margin: 25px 0;
        padding: 20px;
        background: #ffebee;
        border-radius: 8px;
        border-left: 4px solid #e74c3c;
    }

    .response-section h3 {
        margin: 0 0 12px 0;
        font-size: 16px;
        color: #c62828;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .response-section p {
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

    .btn-approve {
        background: #4caf50;
        color: white;
    }

    .btn-approve:hover {
        background: #388e3c;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .btn-reject {
        background: #e74c3c;
        color: white;
    }

    .btn-reject:hover {
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

    .timeline-icon.approved {
        background: #4caf50;
    }

    .timeline-icon.rejected {
        background: #e74c3c;
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

<main class="page">
    <div class="details-card">
        <div class="card-header">
            <h2>Leave Request Details</h2>
            <span class="status-badge <?php echo $data['leaveRequest']->status; ?>">
                <?php echo $data['leaveRequest']->status; ?>
            </span>
        </div>

        <div class="card-body">
            <!-- Employee Information -->
            <div class="employee-section">
                <h3>
                    <span class="material-symbols-outlined">person</span>
                    Employee Information
                </h3>
                <div class="employee-info">
                    <div class="employee-detail">
                        <div class="employee-label">Name</div>
                        <div class="employee-value"><?php echo htmlspecialchars($data['leaveRequest']->employee_name ?? 'Unknown'); ?></div>
                    </div>
                    <div class="employee-detail">
                        <div class="employee-label">Email</div>
                        <div class="employee-value"><?php echo htmlspecialchars($data['leaveRequest']->employee_email ?? 'N/A'); ?></div>
                    </div>
                    <div class="employee-detail">
                        <div class="employee-label">Role</div>
                        <div class="employee-value">
                            <span class="role-badge">
                                <?php echo htmlspecialchars($data['leaveRequest']->employee_role ?? 'Unknown'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave Request Details -->
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

            <!-- Reason for Leave -->
            <div class="reason-section">
                <h3>
                    <span class="material-symbols-outlined">description</span>
                    Reason for Leave
                </h3>
                <p><?php echo nl2br(htmlspecialchars($data['leaveRequest']->reason)); ?></p>
            </div>

            <!-- Admin Response (if rejected) -->
            <?php if ($data['leaveRequest']->status == 'Rejected' && !empty($data['leaveRequest']->admin_response)): ?>
            <div class="response-section">
                <h3>
                    <span class="material-symbols-outlined">info</span>
                    Rejection Reason
                </h3>
                <p><?php echo nl2br(htmlspecialchars($data['leaveRequest']->admin_response)); ?></p>
            </div>
            <?php endif; ?>

            <!-- Supporting Document -->
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

            <!-- Timeline -->
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
                <?php if (isset($data['leaveRequest']->reviewed_at) && !empty($data['leaveRequest']->reviewed_at)): ?>
                <div class="timeline-item">
                    <div class="timeline-icon <?php echo strtolower($data['leaveRequest']->status); ?>">
                        <span class="material-symbols-outlined">
                            <?php echo $data['leaveRequest']->status == 'Approved' ? 'check' : 'close'; ?>
                        </span>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            <?php echo date('M d, Y \a\t g:i A', strtotime($data['leaveRequest']->reviewed_at)); ?>
                        </div>
                        <div class="timeline-text">Request <?php echo strtolower($data['leaveRequest']->status); ?> by admin</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <?php
            $isPendingPremiseOfficer = (
                $data['leaveRequest']->status == 'Pending' &&
                !empty($data['leaveRequest']->premiseofficer_id)
            );
            ?>

            <?php if ($isPendingPremiseOfficer): ?>
            <div style="margin: 20px 0; padding: 16px; border: 1px solid #e5e7eb; border-radius: 10px; background: #fafafa;">
                <h3 style="margin: 0 0 10px 0; display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-outlined">group_add</span>
                    Replacement Officer Assignment
                </h3>

                <?php if (!empty($data['replacementContext'])): ?>
                    <p style="margin: 0 0 12px 0; color: #4b5563;">
                        Site: <strong><?php echo htmlspecialchars($data['replacementContext']->site_name); ?></strong>
                        (<?php echo date('M d, Y', strtotime($data['leaveRequest']->start_date)); ?> - <?php echo date('M d, Y', strtotime($data['leaveRequest']->end_date)); ?>)
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(4, minmax(180px, 1fr)); gap: 10px; margin: 14px 0;">
                        <div>
                            <label style="font-size: 12px; color: #6b7280; display: block; margin-bottom: 6px;">District Filter</label>
                            <select id="leaveDistrictFilter" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                                <option value="same-district">Same District</option>
                                <option value="any-district">Any District</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 12px; color: #6b7280; display: block; margin-bottom: 6px;">City Filter</label>
                            <select id="leaveCityFilter" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                                <option value="same-city">Same City</option>
                                <option value="any-city">Any City</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 12px; color: #6b7280; display: block; margin-bottom: 6px;">Availability</label>
                            <select id="leaveAvailabilityFilter" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                                <option value="available">Available Only</option>
                                <option value="assigned">Assigned Only</option>
                                <option value="all">All Officers</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 12px; color: #6b7280; display: block; margin-bottom: 6px;">Employment Status</label>
                            <select id="leaveStatusFilter" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                                <option value="Active">Active</option>
                                <option value="On Leave">On Leave</option>
                                <option value="Suspended">Suspended</option>
                                <option value="all">All</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; margin-bottom: 12px;">
                        <button type="button" class="btn btn-back" onclick="loadReplacementOfficers()" style="padding: 10px 14px;">
                            <span class="material-symbols-outlined">search</span>
                            Search Officers
                        </button>
                        <button type="button" class="btn btn-back" onclick="resetReplacementFilters()" style="padding: 10px 14px;">
                            <span class="material-symbols-outlined">refresh</span>
                            Reset Filters
                        </button>
                    </div>

                    <div id="replacementOfficerResults" style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; background: #fff;">
                        <div style="padding: 14px; color: #6b7280;">Use filters and click <strong>Search Officers</strong> to manually find an officer, then assign before approving.</div>
                    </div>

                    <?php if (!empty($data['plannedReplacementOfficerId']) && !empty($data['plannedReplacementOfficer'])): ?>
                        <div style="margin-top: 12px; padding: 10px 12px; border-radius: 8px; background: #ecfdf3; color: #065f46; border: 1px solid #a7f3d0;">
                            Replacement selected: <strong><?php echo htmlspecialchars($data['plannedReplacementOfficer']->name); ?></strong>
                            (ID: <?php echo htmlspecialchars($data['plannedReplacementOfficer']->userID); ?>)
                        </div>
                    <?php else: ?>
                        <div style="margin-top: 12px; padding: 10px 12px; border-radius: 8px; background: #fff7ed; color: #9a3412; border: 1px solid #fed7aa;">
                            Select and assign one replacement officer first. Approval is blocked until this step is complete.
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="padding: 10px 12px; border-radius: 8px; background: #fff7ed; color: #9a3412; border: 1px solid #fed7aa;">
                        This requesting officer is not currently assigned to a site for the leave start date, so replacement assignment cannot be prepared automatically.
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="action-buttons">
                <button class="btn btn-back" onclick="history.back()">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Back
                </button>
                <?php if ($data['leaveRequest']->status == 'Pending' && !$isPendingPremiseOfficer): ?>
                <button class="btn btn-approve" onclick="showApproveModal()">
                    <span class="material-symbols-outlined">check</span>
                    Approve Request
                </button>
                <button class="btn btn-reject" onclick="showRejectModal()">
                    <span class="material-symbols-outlined">close</span>
                    Reject Request
                </button>
                <?php endif; ?>
                <?php if ($data['leaveRequest']->status == 'Pending' && $isPendingPremiseOfficer): ?>
                <button class="btn btn-approve" onclick="showApproveModal()" <?php echo empty($data['plannedReplacementOfficerId']) ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : ''; ?>>
                    <span class="material-symbols-outlined">check</span>
                    Approve Request
                </button>
                <button class="btn btn-reject" onclick="showRejectModal()">
                    <span class="material-symbols-outlined">close</span>
                    Reject Request
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
            <span class="material-symbols-outlined confirm-modal-icon" id="modalIcon">help</span>
            <h3 class="confirm-modal-title" id="modalTitle">Confirm Action</h3>
        </div>
        <p class="confirm-modal-message" id="modalMessage">Are you sure you want to proceed?</p>
        <textarea class="confirm-modal-input" id="modalInput" placeholder="Enter reason" style="display: none;" rows="3"></textarea>
        <div class="confirm-modal-actions">
            <button class="modal-btn modal-btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="modal-btn modal-btn-confirm" id="modalConfirmBtn" onclick="confirmAction()">Confirm</button>
        </div>
    </div>
</div>

<?php flash('msg')?>

<script>
let currentAction = null;
let pendingReplacementOfficerId = null;
let pendingReplacementOfficerName = '';

const leaveRequestId = <?php echo (int)$data['leaveRequest']->id; ?>;
const urlRoot = '<?php echo URL_ROOT; ?>';
const isPendingPremiseOfficer = <?php echo ($isPendingPremiseOfficer ? 'true' : 'false'); ?>;
const leaveSiteInfo = {
    site_id: <?php echo !empty($data['replacementContext']) ? (int)$data['replacementContext']->site_id : 'null'; ?>,
    city: '<?php echo !empty($data['replacementContext']) ? addslashes($data['replacementContext']->city ?? '') : ''; ?>',
    district: '<?php echo !empty($data['replacementContext']) ? addslashes($data['replacementContext']->district ?? '') : ''; ?>'
};

function renderReplacementOfficers(officers) {
    const container = document.getElementById('replacementOfficerResults');
    if (!container) {
        return;
    }

    if (!officers || officers.length === 0) {
        container.innerHTML = '<div style="padding:14px; color:#991b1b; background:#fef2f2;">No officers found for current filters.</div>';
        return;
    }

    let html = '<table style="width:100%; border-collapse: collapse;">';
    html += '<thead><tr style="background:#f9fafb;">';
    html += '<th style="text-align:left; padding:10px; border-bottom:1px solid #e5e7eb;">Officer</th>';
    html += '<th style="text-align:left; padding:10px; border-bottom:1px solid #e5e7eb;">Location</th>';
    html += '<th style="text-align:left; padding:10px; border-bottom:1px solid #e5e7eb;">Status</th>';
    html += '<th style="text-align:left; padding:10px; border-bottom:1px solid #e5e7eb;">Action</th>';
    html += '</tr></thead><tbody>';

    officers.forEach(officer => {
        const disabled = officer.current_assignment ? 'disabled' : '';
        const btnText = officer.current_assignment ? 'Already Assigned' : 'Assign Replacement';
        const rowBg = officer.current_assignment ? '#fff7ed' : '#ffffff';
        html += `<tr style="background:${rowBg};">`;
        html += `<td style="padding:10px; border-bottom:1px solid #f3f4f6;"><strong>${officer.name}</strong><br><span style="font-size:12px;color:#6b7280;">${officer.officerID || officer.userID || ''}</span></td>`;
        html += `<td style="padding:10px; border-bottom:1px solid #f3f4f6;">${officer.city || '-'}, ${officer.district || '-'}</td>`;
        html += `<td style="padding:10px; border-bottom:1px solid #f3f4f6;">${officer.employment_status || '-'}</td>`;
        html += `<td style="padding:10px; border-bottom:1px solid #f3f4f6;"><button ${disabled} onclick="assignReplacementOfficer(${officer.user_id}, '${(officer.name || '').replace(/'/g, "\\'")}')" style="padding:8px 10px; border:1px solid #d1d5db; border-radius:6px; background:${officer.current_assignment ? '#f3f4f6' : '#dcfce7'}; cursor:${officer.current_assignment ? 'not-allowed' : 'pointer'};">${btnText}</button></td>`;
        html += '</tr>';
    });

    html += '</tbody></table>';
    container.innerHTML = html;
}

function loadReplacementOfficers() {
    if (!isPendingPremiseOfficer || !leaveSiteInfo.site_id) {
        return;
    }

    const districtFilter = document.getElementById('leaveDistrictFilter').value;
    const cityFilter = document.getElementById('leaveCityFilter').value;
    const availability = document.getElementById('leaveAvailabilityFilter').value;
    const status = document.getElementById('leaveStatusFilter').value;

    const container = document.getElementById('replacementOfficerResults');
    container.innerHTML = '<div style="padding:14px; color:#6b7280;">Loading officers...</div>';

    fetch(urlRoot + '/admin/getAvailableOfficers', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            site_id: leaveSiteInfo.site_id,
            district_filter: districtFilter,
            city_filter: cityFilter,
            availability: availability,
            status: status,
            district: leaveSiteInfo.district,
            city: leaveSiteInfo.city
        })
    })
    .then(response => response.json())
    .then(data => {
        renderReplacementOfficers(data.officers || []);
    })
    .catch(() => {
        container.innerHTML = '<div style="padding:14px; color:#991b1b; background:#fef2f2;">Failed to load officers.</div>';
    });
}

function resetReplacementFilters() {
    if (!isPendingPremiseOfficer) {
        return;
    }
    document.getElementById('leaveDistrictFilter').value = 'same-district';
    document.getElementById('leaveCityFilter').value = 'same-city';
    document.getElementById('leaveAvailabilityFilter').value = 'available';
    document.getElementById('leaveStatusFilter').value = 'Active';
}

function assignReplacementOfficer(officerId, officerName) {
    pendingReplacementOfficerId = officerId;
    pendingReplacementOfficerName = officerName;

    document.getElementById('modalTitle').textContent = 'Assign Replacement Officer';
    document.getElementById('modalMessage').textContent = 'Assign ' + officerName + ' as replacement for this leave request?';
    document.getElementById('modalIcon').textContent = 'group_add';
    document.getElementById('modalIcon').className = 'material-symbols-outlined confirm-modal-icon approve';
    document.getElementById('modalInput').style.display = 'none';
    document.getElementById('modalConfirmBtn').className = 'modal-btn modal-btn-confirm approve';
    document.getElementById('modalConfirmBtn').textContent = 'Assign';

    currentAction = 'assign_replacement';
    document.getElementById('confirmModal').classList.add('active');
}

function submitReplacementAssignment(officerId, officerName) {
    if (!officerId) {
        showToast('error', 'No replacement officer selected');
        return;
    }

    fetch(urlRoot + '/admin/planReplacementOfficerForLeave', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            leave_request_id: leaveRequestId,
            replacement_officer_id: officerId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            showToast('error', data.message || 'Failed to assign replacement officer');
            return;
        }
        showToast('success', officerName + ' assigned as replacement officer');
        setTimeout(() => window.location.reload(), 800);
    })
    .catch(() => {
        showToast('error', 'Failed to assign replacement officer');
    });
}

// Show approve modal
function showApproveModal() {
    document.getElementById('modalTitle').textContent = 'Approve Leave Request';
    document.getElementById('modalMessage').textContent = 'Are you sure you want to approve this leave request?';
    document.getElementById('modalIcon').textContent = 'check_circle';
    document.getElementById('modalIcon').className = 'material-symbols-outlined confirm-modal-icon approve';
    document.getElementById('modalInput').style.display = 'none';
    document.getElementById('modalConfirmBtn').className = 'modal-btn modal-btn-confirm approve';
    document.getElementById('modalConfirmBtn').textContent = 'Approve';
    
    currentAction = 'approve';
    document.getElementById('confirmModal').classList.add('active');
}

// Show reject modal
function showRejectModal() {
    document.getElementById('modalTitle').textContent = 'Reject Leave Request';
    document.getElementById('modalMessage').textContent = 'Please provide a reason for rejecting this leave request. Any selected replacement will be ignored.';
    document.getElementById('modalIcon').textContent = 'cancel';
    document.getElementById('modalIcon').className = 'material-symbols-outlined confirm-modal-icon reject';
    document.getElementById('modalInput').style.display = 'block';
    document.getElementById('modalInput').value = '';
    document.getElementById('modalConfirmBtn').className = 'modal-btn modal-btn-confirm reject';
    document.getElementById('modalConfirmBtn').textContent = 'Reject';
    
    currentAction = 'reject';
    document.getElementById('confirmModal').classList.add('active');
}

// Close modal
function closeModal() {
    document.getElementById('confirmModal').classList.remove('active');
    currentAction = null;
    pendingReplacementOfficerId = null;
    pendingReplacementOfficerName = '';
    document.getElementById('modalInput').value = '';
}

// Confirm action
function confirmAction() {
    const requestId = <?php echo $data['leaveRequest']->id; ?>;
    
    if (currentAction === 'approve') {
        window.location.href = '<?php echo URL_ROOT; ?>/admin/approveLeaveRequest/' + requestId;
    } else if (currentAction === 'reject') {
        const reason = document.getElementById('modalInput').value.trim();
        if (!reason) {
            showToast('error', 'Please provide a reason for rejection');
            return;
        }
        window.location.href = '<?php echo URL_ROOT; ?>/admin/rejectLeaveRequest/' + requestId + '?reason=' + encodeURIComponent(reason);
    } else if (currentAction === 'assign_replacement') {
        const officerId = pendingReplacementOfficerId;
        const officerName = pendingReplacementOfficerName;
        submitReplacementAssignment(officerId, officerName);
    }
    closeModal();
}

function showToast(type, message) {
    const toast = document.createElement('div');
    const bg = type === 'success' ? '#16a34a' : '#dc2626';
    toast.style.cssText = 'position: fixed; top: 18px; right: 18px; z-index: 12000; color: #fff; background: ' + bg + '; padding: 10px 14px; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); font-size: 14px;';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 180ms ease';
        setTimeout(() => toast.remove(), 220);
    }, 2200);
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
