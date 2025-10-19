<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/dashboard.style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Statistics Cards -->
    <section class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">0</div>
                <div class="stat-label">Total Officers</div>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">0</div>
                <div class="stat-label">On Duty</div>
            </div>
        </div>

        <div class="stat-card yellow">
            <div class="stat-icon">
                <i class="fas fa-shield-check"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">0</div>
                <div class="stat-label">Active</div>
            </div>
        </div>

        <div class="stat-card red">
            <div class="stat-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">0</div>
                <div class="stat-label">Incidents</div>
            </div>
        </div>
    </section>

    <!-- Dashboard Content -->
    <section class="content-grid">
        <!-- Attendance Card -->
        <div class="attendance-card">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input id="searchInput" type="text" placeholder="Search">
            </div>
            <div class="table-wrapper">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceBody">
                        <tr>
                            <td><a href="#" class="name-link">John Smith</a></td>
                            <td><span class="status-badge present">Present</span></td>
                            <td>
                                <button class="action-btn approve" title="Approve"><i class="fas fa-check"></i></button>
                                <button class="action-btn reject" title="Reject"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="#" class="name-link">Sarah Johnson</a></td>
                            <td><span class="status-badge present">Present</span></td>
                            <td>
                                <button class="action-btn approve" title="Approve"><i class="fas fa-check"></i></button>
                                <button class="action-btn reject" title="Reject"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="#" class="name-link">Mitchell Brown</a></td>
                            <td><span class="status-badge present">Present</span></td>
                            <td>
                                <button class="action-btn approve" title="Approve"><i class="fas fa-check"></i></button>
                                <button class="action-btn reject" title="Reject"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="#" class="name-link">Jennifer Thompson</a></td>
                            <td><span class="status-badge present">Present</span></td>
                            <td>
                                <button class="action-btn approve" title="Approve"><i class="fas fa-check"></i></button>
                                <button class="action-btn reject" title="Reject"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="#" class="name-link">David Martinez</a></td>
                            <td><span class="status-badge present">Present</span></td>
                            <td>
                                <button class="action-btn approve" title="Approve"><i class="fas fa-check"></i></button>
                                <button class="action-btn reject" title="Reject"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="#" class="name-link">Lisa Anderson</a></td>
                            <td><span class="status-badge present">Present</span></td>
                            <td>
                                <button class="action-btn approve" title="Approve"><i class="fas fa-check"></i></button>
                                <button class="action-btn reject" title="Reject"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Advertisements Card -->
        <div class="advertisements-card">
            <h2 class="card-title">Advertisements</h2>
            <div class="ads-content">
                <p class="no-ads-message">There are no advertisements yet</p>
            </div>
        </div>
    </section>

    <!-- Mark Attendance Button -->
    <section class="scan-qr">
        <button id="scanBtn" class="scan-btn">Mark Attendance</button>
    </section>
        </main>
    </div>

    
     

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
<script src="<?= URL_ROOT ?>/js/caretaker/dashboard.js"></script>
