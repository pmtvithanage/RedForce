<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>
<link rel="stylesheet" href="<?= URL_ROOT ?>/css/caretaker/dashboard_style.css">
<!-- Google Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Content will be loaded here -->


            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Top Section - User Profile and Instructions -->
                <section class="top-section">
                    <div class="instructions-section">
                        <h2>Instructions from Admin</h2>
                        <div class="instructions-content">
                            <p>Good morning!</p>
                            <p>Please ensure that all security officers at Site A have submitted their attendance by 9:00 AM.</p>
                            <p>Also, don't forget to update the incident report if there were any issues during the night shift.</p>
                            <p>Let me know once it's done.</p>
                            <p>Thank you.</p>
                        </div>
                    </div>

                    <!-- Right-side stat cards -->
                    <div class="stats-column">
                        <div class="stat-card purple">
                            <div class="stat-icon"><span class="material-icons">groups</span></div>
                            <div class="stat-text">
                                <div class="stat-value">0</div>
                                <div class="stat-label">Total Officers</div>
                            </div>
                        </div>
                        <div class="stat-card green">
                            <div class="stat-icon"><span class="material-icons">person</span></div>
                            <div class="stat-text">
                                <div class="stat-value">0</div>
                                <div class="stat-label">On Duty</div>
                            </div>
                        </div>
                        <div class="stat-card yellow">
                            <div class="stat-icon"><span class="material-icons">verified_user</span></div>
                            <div class="stat-text">
                                <div class="stat-value">0</div>
                                <div class="stat-label">Active</div>
                            </div>
                        </div>
                        <div class="stat-card red">
                            <div class="stat-icon"><span class="material-icons">report_problem</span></div>
                            <div class="stat-text">
                                <div class="stat-value">0</div>
                                <div class="stat-label">Incidents</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Bottom Section - Upload and Advertisements -->
                <section class="bottom-section">
                    <div class="upload-section">
                        <h2>Upoload Evidence/Photo</h2>
                        <div class="upload-content">
                            <textarea placeholder="Description" class="description-area"></textarea>
                            <div class="file-previews">
                                <div class="file-preview">
                                    <span class="file-name">2023.8.2.1.jpg</span>
                                    <button class="remove-file" onclick="removeFile(this)">×</button>
                                </div>
                                <div class="file-preview">
                                    <span class="file-name">2023.8.2.2.jpg</span>
                                    <button class="remove-file" onclick="removeFile(this)">×</button>
                                </div>
                                <div class="file-preview">
                                    <span class="file-name">2023.8.2.3.jpg</span>
                                    <button class="remove-file" onclick="removeFile(this)">×</button>
                                </div>
                                <div class="file-preview">
                                    <span class="file-name">2023.8.2.4.jpg</span>
                                    <button class="remove-file" onclick="removeFile(this)">×</button>
                                </div>
                            </div>
                            <div class="upload-actions">
                                <button class="upload-btn">Upload Photos</button>
                                <button class="submit-btn" style="display: none;">Submit Report</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="advertisements-section">
                        <h2><i class="fas fa-ad"></i> Advertisements</h2>
                        <div class="no-ads-message">
                            <i class="fas fa-info-circle"></i>
                            <p>There are no advertisements yet</p>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
    <script src="<?= URL_ROOT ?>/js/caretaker/dashboard.js"></script>
