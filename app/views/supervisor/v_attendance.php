<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/attendance_style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Content will be loaded here -->
    <main class="main-content">
        <div class="attendance-container">
            <div class="page-header">
                <h1><span class="material-symbols-outlined">assignment</span> <?php echo $data['pageTitle']; ?></h1>
                <a href="<?php echo URL_ROOT; ?>/supervisor/markAttendancePage" class="btn-add">
                    <span class="material-symbols-outlined">add</span> Mark Attendance
                </a>
            </div>

            <!-- Flash Messages -->
            <?php if (isset($_SESSION['attendance_success'])): ?>
                <div class="flash-message success">
                    <span class="material-symbols-outlined">check_circle</span>
                    <?php 
                        echo $_SESSION['attendance_success'];
                        unset($_SESSION['attendance_success']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['attendance_error'])): ?>
                <div class="flash-message error">
                    <span class="material-symbols-outlined">error</span>
                    <?php 
                        echo $_SESSION['attendance_error'];
                        unset($_SESSION['attendance_error']);
                    ?>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $data['stats']->total_officers ?? 0; ?></h3>
                        <p>Total Marked Today</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon green">
                        <span class="material-symbols-outlined">person_check</span>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $data['stats']->present ?? 0; ?></h3>
                        <p>Present</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon red">
                        <span class="material-symbols-outlined">person_remove</span>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $data['stats']->absent ?? 0; ?></h3>
                        <p>Absent</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon orange">
                        <span class="material-symbols-outlined">schedule</span>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $data['stats']->late ?? 0; ?></h3>
                        <p>Late</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-section">
                <form method="GET" action="<?php echo URL_ROOT; ?>/supervisor/attendance" class="filters-form">
                    <div class="filter-group">
                        <label><span class="material-symbols-outlined">calendar_today</span> Date</label>
                        <input type="date" name="date" value="<?php echo $data['filters']['date']; ?>">
                    </div>

                    <div class="filter-group">
                        <label><span class="material-symbols-outlined">flag</span> Status</label>
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="Present" <?php echo $data['filters']['status'] === 'Present' ? 'selected' : ''; ?>>Present</option>
                            <option value="Absent" <?php echo $data['filters']['status'] === 'Absent' ? 'selected' : ''; ?>>Absent</option>
                            <option value="Late" <?php echo $data['filters']['status'] === 'Late' ? 'selected' : ''; ?>>Late</option>
                            <option value="Half Day" <?php echo $data['filters']['status'] === 'Half Day' ? 'selected' : ''; ?>>Half Day</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label><span class="material-symbols-outlined">search</span> Officer ID/Name</label>
                        <input type="text" name="officer_id" placeholder="Search..." value="<?php echo $data['filters']['officer_id']; ?>">
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-filter">
                            <span class="material-symbols-outlined">filter_alt</span> Apply Filters
                        </button>
                        <a href="<?php echo URL_ROOT; ?>/supervisor/attendance" class="btn-reset">
                            <span class="material-symbols-outlined">refresh</span> Reset
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
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        <form method="POST" action="<?php echo URL_ROOT; ?>/supervisor/deleteAttendance/<?php echo $record->id; ?>" 
                                              style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this attendance record?');">
                                            <button type="submit" class="btn-delete" title="Delete">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="no-data">
                                    <span class="material-symbols-outlined" style="font-size: 3rem; color: #999; display: block; margin-bottom: 0.5rem;">inbox</span>
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

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
