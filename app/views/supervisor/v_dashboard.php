<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/dashboard.style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Dashboard Header -->
   

    <!-- Content Grid -->
    <section class="content-grid">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="avatar-wrapper">
                <div class="avatar">
                    <i class="fas fa-user"></i>
                    <span class="status-dot"></span>
                </div>
                <button class="edit-btn" type="button">Edit</button>
            </div>
            <div class="details">
                <div class="detail"><label>Name:</label><span>W.W. Nuwan Perera</span></div>
                <div class="detail"><label>Officer ID:</label><span>PF231</span></div>
                <div class="detail"><label>Rank:</label><span>OIC</span></div>
                <div class="detail address">
                    <label>Location:</label>
                    <span>People's Bank PLC, 123 Sample Road, Colombo 01</span>
                </div>
                <div class="detail"><label>Rating:</label><span>1403</span></div>
            </div>
        </div>

        <!-- Attendance List -->
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
