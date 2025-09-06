<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>
  <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/dashboard.style.css">


    <!-- Content will be loaded here -->

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
                                <!-- Rows injected by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Scan QR -->
            <section class="scan-qr">
                <button id="scanBtn" class="scan-btn">Mark Attendance</button>
            </section>
        </main>
    </div>
     

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
<script src="<?= URL_ROOT ?>/js/caretaker/dashboard.js"></script>
