<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/equipment_style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<main class="main-content">
    <div class="equipment-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><span class="material-symbols-outlined">rate_review</span> <?php echo $data['pageTitle']; ?></h1>
            <a href="<?php echo URL_ROOT; ?>/supervisor/equipmentRequests" class="btn-secondary">
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
                            <strong><?php echo htmlspecialchars($request->name); ?></strong><br>
                            <small>NIC: <?php echo htmlspecialchars($request->nic ?? 'N/A'); ?> | Contact: <?php echo htmlspecialchars($request->mobile ?? 'N/A'); ?></small>
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
                        <div class="detail-value">Rs. <?php echo number_format($request->estimated_cost, 2); ?></div>
                    </div>

                    <div class="detail-item">
                        <label>Total Estimated Cost:</label>
                        <div class="detail-value cost-highlight">
                            Rs. <?php echo number_format($request->quantity * $request->estimated_cost, 2); ?>
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

                    <?php if ($request->status != 'Pending'): ?>
                        <div class="detail-item">
                            <label>Decision Date:</label>
                            <div class="detail-value"><?php echo $request->approved_date ? date('F d, Y', strtotime($request->approved_date)) : '-'; ?></div>
                        </div>

                        <?php if ($request->status == 'Approved'): ?>
                            <div class="detail-item">
                                <label>Actual Cost (per unit):</label>
                                <div class="detail-value">Rs. <?php echo number_format($request->actual_cost, 2); ?></div>
                            </div>
                            <div class="detail-item">
                                <label>Final Total Cost:</label>
                                <div class="detail-value cost-highlight">
                                    Rs. <?php echo number_format($request->quantity * $request->actual_cost, 2); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($request->supervisor_notes): ?>
                            <div class="detail-item full-width">
                                <label>Supervisor Notes:</label>
                                <div class="detail-value"><?php echo nl2br(htmlspecialchars($request->supervisor_notes)); ?></div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Action Forms (Only for Pending requests) -->
        <?php if ($request->status == 'Pending'): ?>
            <div class="action-section">
                <!-- Approve Form -->
                <div class="action-card approve-card">
                    <h4><span class="material-symbols-outlined">check_circle</span> Approve Request</h4>
                    <form method="POST" action="<?php echo URL_ROOT; ?>/supervisor/approveEquipmentRequest/<?php echo $request->id; ?>">
                        <div class="form-group">
                            <label>Actual Cost (per unit) <span class="required">*</span></label>
                            <div class="input-with-prefix">
                                <span class="prefix">Rs.</span>
                                <input type="number" 
                                       name="actual_cost" 
                                       step="0.01" 
                                       min="0" 
                                       value="<?php echo $request->estimated_cost; ?>" 
                                       required>
                            </div>
                            <small class="form-hint">Enter the final cost per unit after purchase</small>
                        </div>

                        <div class="form-group">
                            <label>Supervisor Notes</label>
                            <textarea name="supervisor_notes" 
                                      rows="4" 
                                      placeholder="Add purchase details, vendor name, delivery date, etc."></textarea>
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
                    <form method="POST" action="<?php echo URL_ROOT; ?>/supervisor/rejectEquipmentRequest/<?php echo $request->id; ?>">
                        <div class="form-group">
                            <label>Reason for Rejection <span class="required">*</span></label>
                            <textarea name="supervisor_notes" 
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
