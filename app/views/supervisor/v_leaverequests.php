<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">


<link rel="stylesheet" href="<?= URL_ROOT ?>/css/supervisor/leaverequest.style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Content will be loaded here -->
     <main class="main-content">
          
            <!-- Flash Messages -->
            <?php flash('leave_success'); ?>
            <?php flash('leave_error'); ?>

            <!-- Request Leave Form -->
            <section class="request-leave-section">
                <h2>Request Leave</h2>
                <form id="leaveForm" action="<?= URL_ROOT ?>/supervisor/addLeave" method="POST" enctype="multipart/form-data" class="leave-form">
                    <div class="form-group">
                        <label for="leaveType">Leave Type</label>
                        <select id="leaveType" name="leave_type">
                            <option value="">Select leave type</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Vacation">Vacation</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="leaveReason">Leave Reason</label>
                        <textarea id="leaveReason" name="reason" placeholder="Enter leave reason"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="startDate">Starting Date</label>
                            <input type="date" id="startDate" name="start_date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="endDate">End Date</label>
                            <input type="date" id="endDate" name="end_date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="leaveProof">Leave Proof (Optional)</label>
                        <input type="file" id="fileInput" name="proof_file" accept=".pdf,.jpg,.jpeg,.png" style="width: 100%; padding: 0.75rem; border: 1px solid #e7c8ce; border-radius: 8px;">
                    </div>
                    
                    <button type="submit" class="submit-btn">Request Leave</button>
                </form>
            </section>

            <!-- Leave History -->
            <section class="leave-history-section">
                <h2>Leave History</h2>
                <div class="table-container">
                    <table class="leave-table">
                        <thead>
                            <tr>
                                <th>Leave Type</th>
                                <th>Reason</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Proof</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="leaveHistoryBody">
                            <?php if (!empty($data['leaveRequests'])): ?>
                                <?php foreach($data['leaveRequests'] as $leave): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($leave->leave_type) ?></td>
                                        <td><?= htmlspecialchars($leave->reason) ?></td>
                                        <td><?= date('d/m/Y', strtotime($leave->start_date)) ?></td>
                                        <td><?= date('d/m/Y', strtotime($leave->end_date)) ?></td>
                                        <td>
                                            <span class="status <?= strtolower($leave->status) ?>">
                                                <?= $leave->status ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($leave->proof_file): ?>
                                                <a href="<?= URL_ROOT ?>/<?= $leave->proof_file ?>" target="_blank" class="view-file-btn">View File</a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <form method="POST" action="<?= URL_ROOT ?>/supervisor/deleteLeave/<?= $leave->id ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this leave request?');">
                                                <button type="submit" class="delete-btn" title="Delete">
                                                    <span class="material-symbols-outlined">delete</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align:center; padding: 20px; color: #666;">No leave requests found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
    
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>