<?php
/**
 * Leave Request Component
 * 
 * A reusable component for displaying leave request information
 * 
 * Props:
 * - $leaveRequest: Leave request object with all details
 * - $showActions: Boolean to show/hide action buttons (default: false)
 * - $role: User role for role-specific actions
 */

$showActions = $showActions ?? false;
$role = $role ?? '';
?>

<style>
.leave-request-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.leave-request-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.leave-request-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.leave-request-type {
    background: #e3f2fd;
    color: #1976d2;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
}

.leave-request-status {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.leave-request-status.Pending {
    background: #fff3e0;
    color: #f57c00;
}

.leave-request-status.Approved {
    background: #e8f5e9;
    color: #388e3c;
}

.leave-request-status.Rejected {
    background: #ffebee;
    color: #d32f2f;
}

.leave-request-body {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.leave-request-field {
    display: flex;
    flex-direction: column;
}

.leave-request-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.leave-request-value {
    font-size: 15px;
    color: #333;
    font-weight: 500;
}

.leave-request-reason {
    background: #f9f9f9;
    padding: 12px;
    border-radius: 6px;
    margin: 15px 0;
}

.leave-request-reason-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.leave-request-reason-text {
    font-size: 14px;
    color: #333;
    line-height: 1.6;
}

.leave-request-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

.leave-request-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}

.leave-request-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.leave-request-btn-view {
    background: #2196F3;
    color: white;
}

.leave-request-btn-approve {
    background: #4caf50;
    color: white;
}

.leave-request-btn-reject {
    background: #e74c3c;
    color: white;
}

.leave-request-btn-delete {
    background: #e74c3c;
    color: white;
}
</style>

<div class="leave-request-card">
    <div class="leave-request-header">
        <span class="leave-request-type"><?php echo htmlspecialchars($leaveRequest->leave_type); ?></span>
        <span class="leave-request-status <?php echo $leaveRequest->status; ?>">
            <?php echo $leaveRequest->status; ?>
        </span>
    </div>

    <div class="leave-request-body">
        <?php if (isset($leaveRequest->requester_name) && !empty($leaveRequest->requester_name)): ?>
        <div class="leave-request-field">
            <div class="leave-request-label">Requested By</div>
            <div class="leave-request-value"><?php echo htmlspecialchars($leaveRequest->requester_name); ?></div>
        </div>
        <?php endif; ?>

        <div class="leave-request-field">
            <div class="leave-request-label">Start Date</div>
            <div class="leave-request-value">
                <?php echo date('M d, Y', strtotime($leaveRequest->start_date)); ?>
            </div>
        </div>

        <div class="leave-request-field">
            <div class="leave-request-label">End Date</div>
            <div class="leave-request-value">
                <?php echo date('M d, Y', strtotime($leaveRequest->end_date)); ?>
            </div>
        </div>

        <div class="leave-request-field">
            <div class="leave-request-label">Duration</div>
            <div class="leave-request-value">
                <?php 
                $start = new DateTime($leaveRequest->start_date);
                $end = new DateTime($leaveRequest->end_date);
                $interval = $start->diff($end);
                echo ($interval->days + 1) . ' Day(s)';
                ?>
            </div>
        </div>

        <div class="leave-request-field">
            <div class="leave-request-label">Submitted On</div>
            <div class="leave-request-value">
                <?php echo date('M d, Y', strtotime($leaveRequest->created_at)); ?>
            </div>
        </div>
    </div>

    <div class="leave-request-reason">
        <div class="leave-request-reason-label">Reason</div>
        <div class="leave-request-reason-text">
            <?php 
            $reason = htmlspecialchars($leaveRequest->reason);
            echo strlen($reason) > 150 ? substr($reason, 0, 150) . '...' : $reason;
            ?>
        </div>
    </div>

    <?php if ($showActions): ?>
    <div class="leave-request-actions">
        <button class="leave-request-btn leave-request-btn-view" 
                onclick="window.location.href='<?php echo URL_ROOT; ?>/<?php echo strtolower($role); ?>/viewLeaveRequest/<?php echo $leaveRequest->id; ?>'">
            <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span>
            View Details
        </button>

        <?php if ($role == 'admin' && $leaveRequest->status == 'Pending'): ?>
            <button class="leave-request-btn leave-request-btn-approve" 
                    onclick="approveLeaveRequest(<?php echo $leaveRequest->id; ?>)">
                <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span>
                Approve
            </button>
            <button class="leave-request-btn leave-request-btn-reject" 
                    onclick="rejectLeaveRequest(<?php echo $leaveRequest->id; ?>)">
                <span class="material-symbols-outlined" style="font-size: 16px;">cancel</span>
                Reject
            </button>
        <?php elseif ($role == 'premiseofficer' && $leaveRequest->status == 'Pending'): ?>
            <button class="leave-request-btn leave-request-btn-delete" 
                    onclick="deleteLeaveRequest(<?php echo $leaveRequest->id; ?>)">
                <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                Delete
            </button>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
