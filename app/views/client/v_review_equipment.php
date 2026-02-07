<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/equipment_style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<main class="main-content">
    <div class="equipment-container">
        <!-- Page Header -->
        <div class="page-header">
            <a href="<?php echo URL_ROOT; ?>/client/equipmentRequests" class="btn-secondary">
                <span class="material-symbols-outlined">arrow_back</span> Back to List
            </a>
        </div>

        <!-- Flash Messages -->
        <?php flash('equipment_message'); ?>
        <?php flash('equipment_error'); ?>

        <?php $request = $data['request']; ?>

        <!-- Request Details Card -->
        <div class="review-card">
            <div class="review-header">
                <h3>Request Details</h3>
                <span class="status-badge status-<?php echo strtolower($request->status); ?>">
                    <?php echo htmlspecialchars($request->status); ?>
                </span>
            </div>

            <div class="review-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Requested By:</label>
                        <div class="detail-value">
                            <strong><?php echo htmlspecialchars($request->caretaker_name ?? 'Unknown'); ?></strong><br>
                            <small>Contact: <?php echo htmlspecialchars($request->contact_number ?? 'N/A'); ?></small>
                        </div>
                    </div>

                    <div class="detail-item">
                        <label>Request Date:</label>
                        <div class="detail-value"><?php echo date('F d, Y', strtotime($request->requested_date)); ?></div>
                    </div>

                    <div class="detail-item">
                        <label>Equipment Name:</label>
                        <div class="detail-value"><strong><?php echo htmlspecialchars($request->equipment_name); ?></strong></div>
                    </div>

                    <div class="detail-item">
                        <label>Quantity:</label>
                        <div class="detail-value"><?php echo $request->quantity; ?> unit(s)</div>
                    </div>

                    <div class="detail-item">
                        <label>Estimated Cost (per unit):</label>
                        <div class="detail-value">Rs. <?php echo number_format($request->estimated_cost ?? 0, 2); ?></div>
                    </div>

                    <div class="detail-item">
                        <label>Total Estimated Cost:</label>
                        <div class="detail-value cost-highlight">
                            Rs. <?php echo number_format(($request->quantity ?? 0) * ($request->estimated_cost ?? 0), 2); ?>
                        </div>
                    </div>

                    <div class="detail-item">
                        <label>Priority:</label>
                        <div class="detail-value">
                            <span class="priority-badge priority-<?php echo strtolower($request->priority); ?>">
                                <?php echo htmlspecialchars($request->priority); ?>
                            </span>
                        </div>
                    </div>

                    <div class="detail-item full-width">
                        <label>Reason for Request:</label>
                        <div class="detail-value reason-text"><?php echo nl2br(htmlspecialchars($request->reason)); ?></div>
                    </div>

                    <?php if ($request->status != 'Pending' && $request->supervisor_notes): ?>
                        <div class="detail-item full-width" style="background: <?php echo $request->status === 'Rejected' ? '#fee2e2' : '#dbeafe'; ?>; padding: 15px; border-radius: 8px; border-left: 4px solid <?php echo $request->status === 'Rejected' ? '#ef4444' : '#3b82f6'; ?>;">
                            <label style="color: <?php echo $request->status === 'Rejected' ? '#991b1b' : '#1e40af'; ?>; font-weight: 600;">
                                <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px;">note</span>
                                Supervisor Notes:
                            </label>
                            <div class="detail-value" style="color: <?php echo $request->status === 'Rejected' ? '#7f1d1d' : '#1e3a8a'; ?>; margin-top: 8px; font-style: italic;">
                                "<?php echo nl2br(htmlspecialchars($request->supervisor_notes)); ?>"
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($request->status != 'Pending'): ?>
                        <div class="detail-item">
                            <label>Decision Date:</label>
                            <div class="detail-value"><?php echo $request->approved_date ? date('F d, Y', strtotime($request->approved_date)) : '-'; ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Action Forms (Only for Supervisor Approved requests) -->
        <?php if ($request->status == 'Supervisor Approved'): ?>
            <div class="action-section">
                <!-- Approve Form -->
                <div class="action-card approve-card">
                    <h4><span class="material-symbols-outlined">check_circle</span> Approve Request</h4>
                    <form method="POST" action="<?php echo URL_ROOT; ?>/client/approveEquipmentRequest/<?php echo $request->id; ?>">
                        <div class="form-group">
                            <label>Client Notes</label>
                            <textarea name="client_notes" 
                                      rows="4" 
                                      placeholder="Add approval notes, purchase authorization, etc."></textarea>
                            <small class="form-hint">Optional: Add any relevant notes</small>
                        </div>

                        <button type="submit" class="btn-submit btn-approve">
                            <span class="material-symbols-outlined">check_circle</span> Approve Request
                        </button>
                    </form>
                </div>

                <!-- Reject Form -->
                <div class="action-card reject-card">
                    <h4><span class="material-symbols-outlined">cancel</span> Reject Request</h4>
                    <form method="POST" action="<?php echo URL_ROOT; ?>/client/rejectEquipmentRequest/<?php echo $request->id; ?>">
                        <div class="form-group">
                            <label>Reason for Rejection <span class="required">*</span></label>
                            <textarea name="client_notes" 
                                      rows="6" 
                                      placeholder="Explain why this request is being rejected..." 
                                      required></textarea>
                            <small class="form-hint">Provide clear reason for rejection</small>
                        </div>

                        <button type="submit" class="btn-submit btn-reject">
                            <span class="material-symbols-outlined">cancel</span> Reject Request
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
