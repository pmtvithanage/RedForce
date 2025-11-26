<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/attendance_style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <main class="main-content">
        <div class="attendance-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1><span class="material-symbols-outlined">edit</span> Edit Attendance</h1>
                <a href="<?php echo URL_ROOT; ?>/supervisor/attendance" class="btn-secondary">
                    <span class="material-symbols-outlined">arrow_back</span> Back to List
                </a>
            </div>

            <!-- Edit Attendance Form -->
            <div class="form-card">
                <form method="POST" action="<?php echo URL_ROOT; ?>/supervisor/updateAttendance/<?php echo $data['attendance']->id; ?>">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>
                                Officer ID <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="officer_id" 
                                value="<?php echo htmlspecialchars($data['attendance']->officer_id); ?>"
                                placeholder="Enter officer ID" 
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label>
                                Officer Name <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="officer_name" 
                                value="<?php echo htmlspecialchars($data['attendance']->officer_name); ?>"
                                placeholder="Enter officer name" 
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label>
                                Attendance Date <span class="required">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="attendance_date" 
                                value="<?php echo $data['attendance']->attendance_date; ?>"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label>
                                Status <span class="required">*</span>
                            </label>
                            <select name="status" required>
                                <option value="Present" <?php echo $data['attendance']->status === 'Present' ? 'selected' : ''; ?>>Present</option>
                                <option value="Absent" <?php echo $data['attendance']->status === 'Absent' ? 'selected' : ''; ?>>Absent</option>
                                <option value="Late" <?php echo $data['attendance']->status === 'Late' ? 'selected' : ''; ?>>Late</option>
                                <option value="Half Day" <?php echo $data['attendance']->status === 'Half Day' ? 'selected' : ''; ?>>Half Day</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Check In Time</label>
                            <input 
                                type="time" 
                                name="check_in_time"
                                value="<?php echo $data['attendance']->check_in_time; ?>"
                            >
                        </div>

                        <div class="form-group">
                            <label>Check Out Time</label>
                            <input 
                                type="time" 
                                name="check_out_time"
                                value="<?php echo $data['attendance']->check_out_time; ?>"
                            >
                        </div>

                        <div class="form-group full-width">
                            <label>Notes</label>
                            <textarea 
                                name="notes" 
                                placeholder="Add any notes..."
                                rows="4"
                            ><?php echo htmlspecialchars($data['attendance']->notes ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="<?php echo URL_ROOT; ?>/supervisor/attendance" class="btn-cancel">
                            Cancel
                        </a>
                        <button type="submit" class="btn-submit">
                            <span class="material-symbols-outlined">save</span> Update Attendance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
