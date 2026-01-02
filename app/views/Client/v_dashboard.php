<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>


<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">


<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Dashboard CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/dashboard_style.css">

<!-- Dashboard Content -->
<div class="main-content">
    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon sites">
                <span class="material-icons">location_on</span>
            </div>
            <div class="stat-info">
                <h3>5</h3>
                <p>No.of Sites</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon officers">
                <span class="material-icons">group</span>
            </div>
            <div class="stat-info">
                <h3>24</h3>
                <p>Total Officers</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon incidents">
                <span class="material-icons">warning</span>
            </div>
            <div class="stat-info">
                <h3>8</h3>
                <p>Incidents</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon payment">
                <span class="material-icons">event</span>
            </div>
            <div class="stat-info">
                <h3>12/21</h3>
                <p>Payment due date</p>
            </div>
        </div>
    </div>
    
    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Messages Section -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Messages</h2>
                <a href="<?php echo URL_ROOT; ?>/client/messages" class="tertiary-btn">
                    View All
                </a>
            </div>
            <div class="section-content">
                <div class="search-container">
                    <input type="text" placeholder="Search" class="search-input">
                </div>
                
                <a href="<?php echo URL_ROOT; ?>/client/messages" class="message-item">
                    <div class="message-avatar">A</div>
                    <div class="message-content">
                        <div class="message-sender">Admin - Red Force</div>
                        <div class="message-text">Dear Mr. Fernando, kindly note that we are assigning 2 officers tonight to the Kurune...</div>
                    </div>
                </a>

                <a href="<?php echo URL_ROOT; ?>/client/messages" class="message-item">
                    <div class="message-avatar">J</div>
                    <div class="message-content">
                        <div class="message-sender">John Silva</div>
                        <div class="message-text">Officer Ravindu Fernando was...</div>
                    </div>
                </a>

                <a href="<?php echo URL_ROOT; ?>/client/messages" class="message-item">
                    <div class="message-avatar">N</div>
                    <div class="message-content">
                        <div class="message-sender">Nadi Senanayake</div>
                        <div class="message-text">Officer training certificates...</div>
                    </div>
                </a>

                <a href="<?php echo URL_ROOT; ?>/client/messages" class="message-item">
                    <div class="message-avatar">N</div>
                    <div class="message-content">
                        <div class="message-sender">Nishadi Dissanayake</div>
                        <div class="message-text">Updated shift schedules for all...</div>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Incident Reports Section -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Incident Reports</h2>
                <a href="<?php echo URL_ROOT; ?>/client/incidents" class="tertiary-btn">
                    View All
                </a>
            </div>
            <div class="section-content">
                <div class="incident-item">
                    <div class="incident-icon low">
                        <span class="material-icons">warning</span>
                    </div>
                    <div class="incident-content">
                        <div class="incident-type">Attempted Robbery - Kurunegala Branch</div>
                        <div class="incident-description">2 men wearing black tried to get into the vault but successfully contained by the guards...</div>
                    </div>
                </div>
                
                <div class="incident-item">
                    <div class="incident-icon high">
                        <span class="material-icons">error</span>
                    </div>
                    <div class="incident-content">
                        <div class="incident-type">Attempted Robbery - Nuwara Eliya Branch</div>
                        <div class="incident-description">Shots were fired and 1 guard is critically injured...</div>
                    </div>
                </div>
                
                <div class="incident-item">
                    <div class="incident-icon medium">
                        <span class="material-icons">report_problem</span>
                    </div>
                    <div class="incident-content">
                        <div class="incident-type">Security Officer Attacked - Colombo 5</div>
                        <div class="incident-description">During an inspection, an uneasy man got into a fight with one of the guards, both...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>