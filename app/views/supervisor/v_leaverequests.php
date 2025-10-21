<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>
<link rel="stylesheet" href="<?= URL_ROOT ?>/css/supervisor/leaverequest.style.css">

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
                            <div class="date-input-wrapper">
                                <input type="text" id="startDate" name="start_date" placeholder="Select start date" class="date-picker" readonly>
                                <div class="inline-calendar" id="startCalendar">
                                    <div class="calendar-header">
                                        <button class="calendar-nav prev" onclick="changeMonth('startCalendar', -1)"><span>‹</span></button>
                                        <span class="calendar-title" id="startCalendarTitle">December 2025</span>
                                        <button class="calendar-nav next" onclick="changeMonth('startCalendar', 1)"><span>›</span></button>
                                    </div>
                                    <div class="calendar-weekdays">
                                        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                                    </div>
                                    <div class="calendar-days" id="startCalendarDays"></div>
                                </div>
                                <i class="calendar-icon" onclick="toggleCalendar('startCalendar')">📅</i>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="endDate">End Date</label>
                            <div class="date-input-wrapper">
                                <input type="text" id="endDate" name="end_date" placeholder="Select end date" class="date-picker" readonly>
                                <div class="inline-calendar" id="endCalendar">
                                    <div class="calendar-header">
                                        <button class="calendar-nav prev" onclick="changeMonth('endCalendar', -1)"><span>‹</span></button>
                                        <span class="calendar-title" id="endCalendarTitle">December 2025</span>
                                        <button class="calendar-nav next" onclick="changeMonth('endCalendar', 1)"><span>›</span></button>
                                    </div>
                                    <div class="calendar-weekdays">
                                        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                                    </div>
                                    <div class="calendar-days" id="endCalendarDays"></div>
                                </div>
                                <i class="calendar-icon" onclick="toggleCalendar('endCalendar')">📅</i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="leaveProof">Leave Proof (Optional)</label>
                        <div class="file-upload">
                            <button type="button" id="attachFile" class="attach-btn">
                                <span>📎</span>
                                Attach File
                            </button>
                            <input type="file" id="fileInput" name="proof_file" accept=".pdf,.jpg,.jpeg,.png" hidden>
                            <span id="fileName" class="file-name"></span>
                        </div>
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
                                            <button onclick="deleteLeave(<?= $leave->id ?>)" class="delete-btn" title="Delete">
                                                <span>🗑️</span>
                                            </button>
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

 <script src="<?= URL_ROOT ?>/js/supervisor/leaverequest.js"></script>