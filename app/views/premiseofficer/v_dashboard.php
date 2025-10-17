<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_premiseofficer_sidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Dashboard CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/premiseofficer/dashboard_style.css">

<!-- Dashboard Content -->
<div class="main-content">
    <!-- Stats Cards Section -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">
                <span class="material-icons">location_on</span>
            </div>
            <div class="stat-info">
                <h3>1</h3>
                <p>Site</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <span class="material-icons">event_busy</span>
            </div>
            <div class="stat-info">
                <h3>3</h3>
                <p>No of Leaves</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <span class="material-icons">schedule</span>
            </div>
            <div class="stat-info">
                <h3>24</h3>
                <p>Overtime Hours</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <span class="material-icons">work</span>
            </div>
            <div class="stat-info">
                <h3>48</h3>
                <p>Total Shifts</p>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="content-grid">
        <!-- Messages Section - LEFT SIDE -->
        <div class="section">
            <div class="section-header">
                <h3 class="section-title">Messages</h3>
            </div>
            <div class="section-content">
                <div class="search-container">
                    <input type="text" placeholder="Search" class="search-input">
                </div>
                
                <div class="message-item" onclick="openChatModal(1)">
                    <div class="message-avatar">A</div>
                    <div class="message-content">
                        <div class="message-header">
                            <div class="message-sender">Admin - Red Force</div>
                            <div class="message-time">14:32</div>
                        </div>
                        <div class="message-text">Dear Officer, kindly note that we are assigning you to the night shift tonight...</div>
                    </div>
                </div>

                <div class="message-item" onclick="openChatModal(2)">
                    <div class="message-avatar">J</div>
                    <div class="message-content">
                        <div class="message-header">
                            <div class="message-sender">John Silva</div>
                            <div class="message-time">12:32</div>
                        </div>
                        <div class="message-text">Your shift schedule has been updated...</div>
                        <div class="message-subtitle">Supervisor</div>
                    </div>
                </div>

                <div class="message-item" onclick="openChatModal(3)">
                    <div class="message-avatar">N</div>
                    <div class="message-content">
                        <div class="message-header">
                            <div class="message-sender">Naduni Senanayake</div>
                            <div class="message-time">01:42</div>
                        </div>
                        <div class="message-text">Please submit your monthly report...</div>
                        <div class="message-subtitle">HR Officer</div>
                    </div>
                </div>

                <div class="message-item" onclick="openChatModal(4)">
                    <div class="message-avatar">M</div>
                    <div class="message-content">
                        <div class="message-header">
                            <div class="message-sender">Manager</div>
                            <div class="message-time">01:22</div>
                        </div>
                        <div class="message-text">Team meeting scheduled for tomorrow...</div>
                        <div class="message-subtitle">Site Manager</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advertisements Section - RIGHT SIDE -->
        <div class="section">
            <div class="section-header">
                <h3 class="section-title">Advertisements</h3>
            </div>
            <div class="section-content">
                <div class="advertisement-empty">
                    <div class="empty-ad-message">
                        <span class="material-icons">campaign</span>
                        <p>No advertisements available</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chat Modal -->
<div id="chatModal" class="modal chat-modal">
    <div class="modal-content chat-modal-content">
        <div class="chat-header">
            <div class="chat-info">
                <h3 id="chatTitle">Messages</h3>
                <p id="chatParticipant">Admin - Red Force</p>
            </div>
            <span class="close" onclick="closeModal('chatModal')">&times;</span>
        </div>
        
        <div class="chat-body">
            <div id="chatContent" class="chat-messages">
                <!-- Messages will be loaded here dynamically -->
            </div>
        </div>
        
        <div class="chat-footer">
            <div class="message-input-container">
                <input type="text" id="messageInput" placeholder="Type a message" onkeypress="handleMessageKeyPress(event)">
                <button onclick="sendMessage()" class="send-btn">
                    <span class="material-icons">mic</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/premiseofficer/dashboard.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>