<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/attendance_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Content will be loaded here -->
<main class="main-content">
    <div class="attendance-container">
        <div class="page-header">
            <div class="page-header-left">
                <div class="live-datetime" id="liveDateTime"></div>
            </div>
            <a href="<?php echo URL_ROOT; ?>/supervisor/markAttendancePage" class="btn-add">
                Mark Attendance
            </a>
        </div>

        <!-- Flash Messages -->
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

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Marked Today</h3>
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

            <div class="stat-card">
                <div class="stat-icon late">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>Late</h3>
                    <div class="stat-value"><?php echo $data['stats']->late ?? 0; ?></div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="<?php echo URL_ROOT; ?>/supervisor/attendance" class="filters-form">
                <div class="filter-group">
                    <label>Date</label>
                    <input type="date" name="date" value="<?php echo $data['filters']['date']; ?>">
                </div>

                <div class="filter-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="Present" <?php echo $data['filters']['status'] === 'Present' ? 'selected' : ''; ?>>Present</option>
                        <option value="Absent" <?php echo $data['filters']['status'] === 'Absent' ? 'selected' : ''; ?>>Absent</option>
                        <option value="Late" <?php echo $data['filters']['status'] === 'Late' ? 'selected' : ''; ?>>Late</option>
                        <option value="Half Day" <?php echo $data['filters']['status'] === 'Half Day' ? 'selected' : ''; ?>>Half Day</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Officer ID/Name</label>
                    <input type="text" name="officer_id" placeholder="Search..." value="<?php echo $data['filters']['officer_id']; ?>">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fa-solid fa-filter"></i> Apply Filters
                    </button>
                    <a href="<?php echo URL_ROOT; ?>/supervisor/attendance" class="btn-reset">
                        <i class="fa-solid fa-rotate-right"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Attendance Table -->
        <div class="table-container">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Officer ID</th>
                        <th>Officer Name</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['attendanceRecords'])): ?>
                        <?php foreach ($data['attendanceRecords'] as $record): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($record->attendance_date)); ?></td>
                                <td><span class="officer-id-badge"><?php echo htmlspecialchars($record->officer_id); ?></span></td>
                                <td><?php echo htmlspecialchars($record->officer_name); ?></td>
                                <td><?php echo $record->check_in_time ? date('h:i A', strtotime($record->check_in_time)) : '-'; ?></td>
                                <td><?php echo $record->check_out_time ? date('h:i A', strtotime($record->check_out_time)) : '-'; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $record->status)); ?>">
                                        <?php echo $record->status; ?>
                                    </span>
                                </td>
                                <td class="notes-cell"><?php echo htmlspecialchars(substr($record->notes ?? '', 0, 30)); ?><?php echo strlen($record->notes ?? '') > 30 ? '...' : ''; ?></td>
                                <td class="action-buttons">
                                    <a href="<?php echo URL_ROOT; ?>/supervisor/editAttendancePage/<?php echo $record->id; ?>" class="btn-edit" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form method="POST" action="<?php echo URL_ROOT; ?>/supervisor/deleteAttendance/<?php echo $record->id; ?>"
                                        style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this attendance record?');">
                                        <button type="submit" class="btn-delete" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="no-data">
                                <i class="fa-solid fa-inbox"></i>
                                <p>No attendance records found. Click "Mark Attendance" to add a record.</p>
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
    // Live Date and Time
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

    // Auto-hide flash messages
    setTimeout(() => {
        const flashMessages = document.querySelectorAll('.flash-message');
        flashMessages.forEach(msg => {
            msg.style.display = 'none';
        });
    }, 5000);
</script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>