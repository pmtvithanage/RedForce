<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/attendance_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<main class="main-content">
    <div class="attendance-container">
        <div class="page-header">
            <div class="page-header-left">
                <div class="live-datetime" id="liveDateTime"></div>
            </div>
        </div>

        <?php if (!empty($data['site'])): ?>
            <div class="filters-section attendance-site-banner">
                <strong>Site:</strong> <?php echo htmlspecialchars($data['site']->site_name ?? 'Assigned Site'); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['attendance_success'])): ?>
            <div class="flash-message success">
                <i class="fa-solid fa-circle-check"></i>
                <?php
                echo $_SESSION['attendance_success'];
                unset($_SESSION['attendance_success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['attendance_error'])): ?>
            <div class="flash-message error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?php
                echo $_SESSION['attendance_error'];
                unset($_SESSION['attendance_error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Staff For Date</h3>
                    <div class="stat-value"><?php echo $data['stats']->total_officers ?? 0; ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon present">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div class="stat-info">
                    <h3>Present</h3>
                    <div class="stat-value"><?php echo $data['stats']->present ?? 0; ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon absent">
                    <i class="fa-solid fa-user-xmark"></i>
                </div>
                <div class="stat-info">
                    <h3>Absent</h3>
                    <div class="stat-value"><?php echo $data['stats']->absent ?? 0; ?></div>
                </div>
            </div>
        </div>

        <div class="attendance-blocks">
            <section class="form-card attendance-block">
                <h2 class="block-title">Add Duty Points</h2>
                <form method="POST" action="<?php echo URL_ROOT; ?>/supervisor/addDutyPoint" class="inline-form">
                    <div class="form-group">
                        <label for="dutyPointName">Duty Point Name</label>
                        <input type="text" id="dutyPointName" name="duty_point_name" placeholder="e.g. Main Gate" required maxlength="120">
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-plus"></i> Add Duty Point
                    </button>
                </form>

                <div class="duty-points-list-wrap">
                    <h3 class="duty-points-list-title">Existing Duty Points</h3>
                    <?php if (!empty($data['dutyPoints'])): ?>
                        <div class="duty-points-list">
                            <?php foreach ($data['dutyPoints'] as $point): ?>
                                <div class="duty-point-item" data-duty-point-id="<?php echo (int)$point->id; ?>">
                                    <span class="duty-point-chip"><?php echo htmlspecialchars($point->duty_point_name); ?></span>

                                    <div class="duty-point-actions">
                                        <button
                                            type="button"
                                            class="btn-edit-duty"
                                            data-duty-point-id="<?php echo (int)$point->id; ?>"
                                            data-duty-point-name="<?php echo htmlspecialchars($point->duty_point_name, ENT_QUOTES, 'UTF-8'); ?>"
                                            title="Edit duty point"
                                            aria-label="Edit duty point">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <form method="POST" action="<?php echo URL_ROOT; ?>/supervisor/deleteDutyPoint" class="duty-delete-form">
                                            <input type="hidden" name="duty_point_id" value="<?php echo (int)$point->id; ?>">
                                            <button type="submit" class="btn-delete-duty" title="Delete duty point" aria-label="Delete duty point">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <form method="POST" action="<?php echo URL_ROOT; ?>/supervisor/updateDutyPoint" class="duty-edit-form" hidden>
                                        <input type="hidden" name="duty_point_id" value="<?php echo (int)$point->id; ?>">
                                        <input
                                            type="text"
                                            name="duty_point_name"
                                            class="duty-edit-input"
                                            value="<?php echo htmlspecialchars($point->duty_point_name); ?>"
                                            maxlength="120"
                                            required>
                                        <button type="submit" class="btn-save-duty" title="Save duty point" aria-label="Save duty point"><i class="fa-solid fa-check"></i></button>
                                        <button type="button" class="btn-cancel-duty" title="Cancel edit" aria-label="Cancel edit"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="duty-points-empty">No duty points added yet.</p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="form-card attendance-block">
                <h2 class="block-title">Mark Attendance</h2>
                <form method="POST" action="<?php echo URL_ROOT; ?>/supervisor/addAttendance" class="form-grid compact-grid">
                    <div class="form-group">
                        <label for="attendanceDate">Date</label>
                        <input type="date" id="attendanceDate" name="attendance_date" value="<?php echo htmlspecialchars($data['selectedDate'] ?? date('Y-m-d')); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="staffUserId">Officer / Caretaker</label>
                        <select id="staffUserId" name="staff_user_id" required>
                            <option value="">Select staff member</option>
                            <?php foreach (($data['eligibleStaff'] ?? []) as $staff): ?>
                                <option value="<?php echo (int)$staff->staff_user_id; ?>">
                                    <?php echo htmlspecialchars(($staff->staff_name ?? '') . ' (' . ($staff->staff_code ?? '-') . ') - ' . ($staff->staff_role ?? '')); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dutyPointId">Assign Duty Point</label>
                        <select id="dutyPointId" name="duty_point_id" required>
                            <option value="">Select duty point</option>
                            <?php foreach (($data['dutyPoints'] ?? []) as $point): ?>
                                <option value="<?php echo (int)$point->id; ?>"><?php echo htmlspecialchars($point->duty_point_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="attendanceNotes">Notes</label>
                        <textarea id="attendanceNotes" name="notes" rows="3" placeholder="Optional notes"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-check"></i> Mark Attendance
                        </button>
                    </div>
                </form>
            </section>
        </div>

        <div class="filters-section">
            <form method="GET" action="<?php echo URL_ROOT; ?>/supervisor/attendance" class="filters-form">
                <div class="filter-group">
                    <label>Date</label>
                    <input type="date" name="date" value="<?php echo htmlspecialchars($data['filters']['date'] ?? date('Y-m-d')); ?>">
                </div>

                <div class="filter-group">
                    <label>Officer ID/Name</label>
                    <input type="text" name="officer_id" placeholder="Search..." value="<?php echo htmlspecialchars($data['filters']['officer_id'] ?? ''); ?>">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter"><i class="fa-solid fa-filter"></i> Apply</button>
                    <a href="<?php echo URL_ROOT; ?>/supervisor/attendance" class="btn-reset"><i class="fa-solid fa-rotate-right"></i> Reset</a>
                </div>
            </form>
        </div>

        <div class="table-container">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Officer ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Duty Point</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['attendanceRecords'])): ?>
                        <?php foreach ($data['attendanceRecords'] as $record): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($record->attendance_date)); ?></td>
                                <td><span class="officer-id-badge"><?php echo htmlspecialchars($record->officer_id); ?></span></td>
                                <td><?php echo htmlspecialchars($record->officer_name); ?></td>
                                <td><?php echo htmlspecialchars($record->staff_role ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($record->duty_point ?? '-'); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $record->status)); ?>">
                                        <?php echo htmlspecialchars($record->status); ?>
                                    </span>
                                </td>
                                <td class="notes-cell"><?php echo htmlspecialchars($record->notes ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="no-data">
                                <i class="fa-solid fa-inbox"></i>
                                <p>No attendance records found for the selected date.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script>
    function updateDateTime() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        document.getElementById('liveDateTime').textContent = now.toLocaleDateString('en-US', options);
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);

    setTimeout(() => {
        const flashMessages = document.querySelectorAll('.flash-message');
        flashMessages.forEach((msg) => {
            msg.style.display = 'none';
        });
    }, 5000);

    document.querySelectorAll('.btn-edit-duty').forEach((button) => {
        button.addEventListener('click', () => {
            const item = button.closest('.duty-point-item');
            if (!item) {
                return;
            }

            const actions = item.querySelector('.duty-point-actions');
            const editForm = item.querySelector('.duty-edit-form');
            const editInput = item.querySelector('.duty-edit-input');

            if (actions) {
                actions.style.display = 'none';
            }

            if (editForm) {
                editForm.hidden = false;
            }

            if (editInput) {
                editInput.focus();
                editInput.select();
            }
        });
    });

    document.querySelectorAll('.btn-cancel-duty').forEach((button) => {
        button.addEventListener('click', () => {
            const item = button.closest('.duty-point-item');
            if (!item) {
                return;
            }

            const actions = item.querySelector('.duty-point-actions');
            const editForm = item.querySelector('.duty-edit-form');

            if (editForm) {
                editForm.hidden = true;
            }

            if (actions) {
                actions.style.display = 'inline-flex';
            }
        });
    });

    document.querySelectorAll('.duty-delete-form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const shouldDelete = window.confirm('Delete this duty point?');
            if (!shouldDelete) {
                event.preventDefault();
            }
        });
    });
</script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>