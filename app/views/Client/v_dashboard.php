
<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

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
            </div>
            <div class="section-content">
                <div class="message-item">
                    <div class="message-avatar">J</div>
                    <div class="message-content">
                        <div class="message-sender">John Smith</div>
                        <div class="message-text">Security patrol completed for Building A. All clear, no issues reported.</div>
                    </div>
                </div>
                
                <div class="message-item">
                    <div class="message-avatar">S</div>
                    <div class="message-content">
                        <div class="message-sender">Sarah Wilson</div>
                        <div class="message-text">Monthly security report is ready for review. Please check the attached document.</div>
                    </div>
                </div>
                
                <div class="message-item">
                    <div class="message-avatar">M</div>
                    <div class="message-content">
                        <div class="message-sender">Mike Johnson</div>
                        <div class="message-text">New security protocols have been implemented. Training session scheduled for next week.</div>
                    </div>
                </div>
                
                <div class="message-item">
                    <div class="message-avatar">L</div>
                    <div class="message-content">
                        <div class="message-sender">Lisa Chen</div>
                        <div class="message-text">Access control system maintenance completed successfully. All systems operational.</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Incident Reports Section -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Incident Reports</h2>
                <a href="#" class="view-all-btn" onclick="openModal('allIncidentsModal')">View All</a>
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

<!-- Incidents Modal -->
<div id="allIncidentsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Incident Reports</h2>
            <span class="close" onclick="closeModal('allIncidentsModal')">&times;</span>
        </div>
        <div class="modal-body">
            <div class="incident-item low">
                <div class="incident-header">
                    <span class="incident-warning">
                        <span class="material-icons">warning</span>
                    </span>
                    <span class="incident-title">Attempted Robbery - Kurunegala Branch</span>
                </div>
                <div class="incident-date">01.01.2023 : 2 men wearing black tried to get into the vault but successfully contained by the guards and alerted the police.</div>
                <div class="incident-details">2 men wearing black tried to get into the vault but successfully contained by the guards and alerted the police.</div>
            </div>
            
            <div class="incident-item high">
                <div class="incident-header">
                    <span class="incident-warning">
                        <span class="material-icons">error</span>
                    </span>
                    <span class="incident-title">Attempted Robbery - Nuwara Eliya Branch</span>
                </div>
                <div class="incident-date">01.01.2023 : 2 men wearing black tried to get into the vault but got disturbed on the way by the guards. Shots were fired and 1 guard is critically injured.</div>
                <div class="incident-details">2 men wearing black tried to get into the vault but got disturbed on the way by the guards. Shots were fired and 1 guard is critically injured.</div>
            </div>
            
            <div class="incident-item medium">
                <div class="incident-header">
                    <span class="incident-warning">
                        <span class="material-icons">report_problem</span>
                    </span>
                    <span class="incident-title">Attempted Robbery - Nuwara Eliya Branch</span>
                </div>
                <div class="incident-date">01.01.2023 : 2 men wearing black tried to get into the vault but got disturbed on the way by the guards. Shots were fired and 1 guard is critically injured.</div>
                <div class="incident-details">2 men wearing black tried to get into the vault but got disturbed on the way by the guards. Shots were fired and 1 guard is critically injured.</div>
            </div>
            
            <div class="incident-item high">
                <div class="incident-header">
                    <span class="incident-warning">
                        <span class="material-icons">error</span>
                    </span>
                    <span class="incident-title">Security Officer Attacked - Colombo 5</span>
                </div>
                <div class="incident-date">02.01.2023 : During an inspection, an uneasy man got into a fight with one of the guards, both were injured.</div>
                <div class="incident-details">During an inspection, an uneasy man got into a fight with one of the guards, both were injured.</div>
            </div>
            
            <div class="incident-item low">
                <div class="incident-header">
                    <span class="incident-warning">
                        <span class="material-icons">warning</span>
                    </span>
                    <span class="incident-title">Suspicious Activity - Galle Branch</span>
                </div>
                <div class="incident-date">03.01.2023 : Unknown individual was seen taking photos of the building perimeter.</div>
                <div class="incident-details">Unknown individual was seen taking photos of the building perimeter.</div>
            </div>
            
            <button class="back-button" onclick="closeModal('allIncidentsModal')">Back</button>
        </div>
    </div>
</div>

<!-- Link to Dashboard JavaScript -->
<script src="<?php echo URL_ROOT; ?>/js/client/dashboard.js"></script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>