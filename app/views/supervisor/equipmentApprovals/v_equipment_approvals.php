<?php require_once APP_ROOT . '/views/components/v_supervisorsidebar.php'; ?>

<style>
    .equipment-approvals-container {
        padding: 24px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 6px 20px rgba(20,20,40,0.08);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(20,20,40,0.15);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    .stat-icon.pending {
        background: linear-gradient(135deg, #fff5e6 0%, #ffe8cc 100%);
        color: #ff9800;
    }

    .stat-icon.approved {
        background: linear-gradient(135deg, #e7f7ef 0%, #d4f1e0 100%);
        color: #0a8f4e;
    }

    .stat-icon.rejected {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        color: #dc2626;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
        font-weight: 600;
    }

    .requests-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 8px 24px rgba(20,20,40,0.08);
        border: 1px solid rgba(164, 0, 0, 0.08);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f0f0;
    }

    .section-title {
        font-size: 22px;
        font-weight: 800;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .requests-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
    }

    .requests-table thead th {
        background: #f8f9fa;
        padding: 14px 16px;
        text-align: left;
        font-weight: 700;
        font-size: 13px;
        color: #1a1a1a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e0e0e0;
    }

    .requests-table tbody tr {
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .requests-table tbody tr:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .requests-table tbody td {
        padding: 16px;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
    }

    .requests-table tbody td:first-child {
        border-left: 1px solid #f0f0f0;
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .requests-table tbody td:last-child {
        border-right: 1px solid #f0f0f0;
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .priority-badge.high {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        color: #dc2626;
    }

    .priority-badge.medium {
        background: linear-gradient(135deg, #fff5e6 0%, #ffe8cc 100%);
        color: #ff9800;
    }

    .priority-badge.low {
        background: linear-gradient(135deg, #e3f2fd 0%, #d1e7f5 100%);
        color: #2196f3;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-badge.pending {
        background: linear-gradient(135deg, #fff5e6 0%, #ffe8cc 100%);
        color: #ff9800;
    }

    .action-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn.approve {
        background: linear-gradient(135deg, #0a8f4e 0%, #0d7a43 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(10, 143, 78, 0.3);
    }

    .action-btn.approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(10, 143, 78, 0.4);
    }

    .action-btn.reject {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .action-btn.reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
    }

    .no-requests {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .no-requests .material-symbols-outlined {
        font-size: 80px;
        color: #ddd;
        margin-bottom: 16px;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 32px;
        max-width: 600px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .modal-header {
        font-size: 22px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        min-height: 100px;
    }

    .form-group textarea:focus {
        outline: none;
        border-color: var(--accent);
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 24px;
    }

    .btn-cancel {
        padding: 12px 24px;
        background: #f0f0f0;
        color: #333;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #e0e0e0;
    }

    .btn-submit {
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--accent) 0%, #8a0000 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(164, 0, 0, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(164, 0, 0, 0.4);
    }
</style>

<div class="equipment-approvals-container">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon pending">
                <span class="material-symbols-outlined" style="font-size: 32px;">pending_actions</span>
            </div>
            <div class="stat-value"><?php echo $data['stats']['pending'] ?? 0; ?></div>
            <div class="stat-label">Pending Approval</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon approved">
                <span class="material-symbols-outlined" style="font-size: 32px;">check_circle</span>
            </div>
            <div class="stat-value"><?php echo $data['stats']['approved'] ?? 0; ?></div>
            <div class="stat-label">Approved</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon rejected">
                <span class="material-symbols-outlined" style="font-size: 32px;">cancel</span>
            </div>
            <div class="stat-value"><?php echo $data['stats']['rejected'] ?? 0; ?></div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>

    <!-- Pending Requests Section -->
    <div class="requests-section">
        <div class="section-header">
            <h2 class="section-title">
                <span class="material-symbols-outlined">inventory</span>
                Equipment Requests Pending Approval
            </h2>
        </div>

        <?php if (!empty($data['requests'])): ?>
            <table class="requests-table">
                <thead>
                    <tr>
                        <th>Caretaker</th>
                        <th>Equipment</th>
                        <th>Quantity</th>
                        <th>Est. Cost</th>
                        <th>Priority</th>
                        <th>Requested</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['requests'] as $request): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($request->caretaker_name); ?></strong>
                                <br>
                                <small style="color: #666;"><?php echo htmlspecialchars($request->site_name ?? 'N/A'); ?></small>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($request->equipment_name); ?></strong>
                                <br>
                                <small style="color: #666;"><?php echo htmlspecialchars($request->reason); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($request->quantity); ?></td>
                            <td>$<?php echo number_format($request->estimated_cost, 2); ?></td>
                            <td>
                                <span class="priority-badge <?php echo strtolower($request->priority); ?>">
                                    <?php echo htmlspecialchars($request->priority); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($request->requested_date)); ?></td>
                            <td>
                                <button class="action-btn approve" onclick="showApproveModal(<?php echo $request->id; ?>, '<?php echo addslashes($request->equipment_name); ?>')">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">check</span>
                                    Approve
                                </button>
                                <button class="action-btn reject" onclick="showRejectModal(<?php echo $request->id; ?>, '<?php echo addslashes($request->equipment_name); ?>')">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">close</span>
                                    Reject
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-requests">
                <span class="material-symbols-outlined">inventory_2</span>
                <h3 style="margin: 16px 0 8px; font-size: 20px; font-weight: 700;">No Pending Requests</h3>
                <p style="color: #999;">All equipment requests have been processed</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h3 class="modal-header">
            <span class="material-symbols-outlined" style="color: #0a8f4e;">check_circle</span>
            Approve Equipment Request
        </h3>
        <form id="approveForm" method="POST" action="<?php echo URL_ROOT; ?>/supervisor/approveEquipmentRequest">
            <input type="hidden" name="request_id" id="approve_request_id">
            <p style="margin-bottom: 20px; color: #666;">
                Equipment: <strong id="approve_equipment_name"></strong>
            </p>
            <div class="form-group">
                <label for="supervisor_notes">Notes (Optional)</label>
                <textarea name="supervisor_notes" id="supervisor_notes" placeholder="Add any notes or comments..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeApproveModal()">Cancel</button>
                <button type="submit" class="btn-submit">Approve & Send to Client</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h3 class="modal-header">
            <span class="material-symbols-outlined" style="color: #dc2626;">cancel</span>
            Reject Equipment Request
        </h3>
        <form id="rejectForm" method="POST" action="<?php echo URL_ROOT; ?>/supervisor/rejectEquipmentRequest">
            <input type="hidden" name="request_id" id="reject_request_id">
            <p style="margin-bottom: 20px; color: #666;">
                Equipment: <strong id="reject_equipment_name"></strong>
            </p>
            <div class="form-group">
                <label for="rejection_reason">Rejection Reason <span style="color: #dc2626;">*</span></label>
                <textarea name="rejection_reason" id="rejection_reason" required placeholder="Please provide a reason for rejection..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">Reject Request</button>
            </div>
        </form>
    </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script>
function showApproveModal(requestId, equipmentName) {
    document.getElementById('approve_request_id').value = requestId;
    document.getElementById('approve_equipment_name').textContent = equipmentName;
    document.getElementById('approveModal').style.display = 'flex';
}

function closeApproveModal() {
    document.getElementById('approveModal').style.display = 'none';
    document.getElementById('approveForm').reset();
}

function showRejectModal(requestId, equipmentName) {
    document.getElementById('reject_request_id').value = requestId;
    document.getElementById('reject_equipment_name').textContent = equipmentName;
    document.getElementById('rejectModal').style.display = 'none';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejectForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const approveModal = document.getElementById('approveModal');
    const rejectModal = document.getElementById('rejectModal');
    
    if (event.target === approveModal) {
        closeApproveModal();
    }
    if (event.target === rejectModal) {
        closeRejectModal();
    }
}
</script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
