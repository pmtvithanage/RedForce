<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f5f5;
    }
    
    .main-content {
        margin-left: 100px; /* keep sidebar space */
        margin-top: 70px;  /* keep topbar space */
        padding: 30px;
        min-height: calc(100vh - 70px);

        /* Center the content */
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .dashboard-header {
        margin-bottom: 30px;
        text-align: center;
    }
    
    .dashboard-title {
        font-size: 28px;
        font-weight: 600;
        color: #333;
    }
    
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
        max-width: 900px;
        width: 100%;
    }
    
    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 24px;
    }
    
    .stat-icon.sites {
        background: #e8f5e8;
        color: #2e7d32;
    }
    
    .stat-icon.officers {
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .stat-icon.incidents {
        background: #ffebee;
        color: #d32f2f;
    }
    
    .stat-icon.payment {
        background: #f3e5f5;
        color: #7b1fa2;
    }
    
    .stat-info h3 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .stat-info p {
        color: #666;
        font-size: 14px;
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        max-width: 1000px;
        width: 100%;
    }
    
    .section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .section-header {
        background: #f8f9fa;
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .view-all-btn {
        color: #d32f2f;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
    }
    
    .view-all-btn:hover {
        text-decoration: underline;
    }
    
    .section-content {
        padding: 20px;
    }
    
    .message-item, .incident-item {
        display: flex;
        align-items: flex-start;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .message-item:hover, .incident-item:hover {
        background-color: #f8f9fa;
    }
    
    .message-item:last-child, .incident-item:last-child {
        border-bottom: none;
    }
    
    .message-avatar {
        width: 40px;
        height: 40px;
        background: #d32f2f;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .message-content {
        flex: 1;
    }
    
    .message-sender {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    
    .message-text {
        color: #666;
        font-size: 14px;
        line-height: 1.4;
    }
    
    .incident-icon {
        width: 40px;
        height: 40px;
        background: #ffebee;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .incident-icon.high {
        background: #ffebee;
        color: #d32f2f;
    }
    
    .incident-icon.medium {
        background: #fff3e0;
        color: #f57c00;
    }
    
    .incident-content {
        flex: 1;
    }
    
    .incident-type {
        font-weight: 600;
        color: #333;
        margin-bottom: 3px;
    }
    
    .incident-location {
        color: #666;
        font-size: 13px;
        margin-bottom: 5px;
    }
    
    .incident-description {
        color: #666;
        font-size: 14px;
        line-height: 1.4;
    }
    
    /* Modal Styles (centered like screenshot) */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-content {
        background-color: white;
        border-radius: 15px;
        width: 450px;
        max-width: 90%;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        animation: popupFade 0.3s ease;
        overflow: hidden;
    }
    
    .modal-header {
        background: #f5f5f5;
        padding: 15px 20px;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .close {
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
        color: #333;
    }
    
    .modal-body {
        padding: 20px;
        max-height: 400px;
        overflow-y: auto;
    }
    
    @keyframes popupFade {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 20px;
        }
        
        .content-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Content will be loaded here -->
<div class="main-content">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Dashboard</h1>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon sites">📍</div>
            <div class="stat-info">
                <h3><?php echo $data['stats']['sites']; ?></h3>
                <p>No.of Sites</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon officers">👥</div>
            <div class="stat-info">
                <h3><?php echo $data['stats']['officers']; ?></h3>
                <p>Total Officers</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon incidents">⚠️</div>
            <div class="stat-info">
                <h3><?php echo $data['stats']['incidents']; ?></h3>
                <p>Incidents</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon payment">📅</div>
            <div class="stat-info">
                <h3><?php echo $data['stats']['payment_due']; ?></h3>
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
                <a href="#" class="view-all-btn" onclick="openModal('allMessagesModal')">View All</a>
            </div>
            <div class="section-content">
                <?php foreach($data['messages'] as $message): ?>
                <div class="message-item" onclick="openMessageModal('<?php echo htmlspecialchars($message['sender']); ?>', '<?php echo htmlspecialchars($message['message']); ?>')">
                    <div class="message-avatar">
                        <?php echo strtoupper(substr($message['sender'], 0, 1)); ?>
                    </div>
                    <div class="message-content">
                        <div class="message-sender"><?php echo htmlspecialchars($message['sender']); ?></div>
                        <div class="message-text"><?php echo htmlspecialchars($message['message']); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Incident Reports Section -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Incident Reports</h2>
                <a href="#" class="view-all-btn" onclick="openModal('allIncidentsModal')">View All</a>
            </div>
            <div class="section-content">
                <?php foreach($data['incidents'] as $incident): ?>
                <div class="incident-item" onclick="openIncidentModal('<?php echo htmlspecialchars($incident['type']); ?>', '<?php echo htmlspecialchars($incident['location']); ?>', '<?php echo htmlspecialchars($incident['description']); ?>')">
                    <div class="incident-icon <?php echo $incident['severity']; ?>">
                        ⚠️
                    </div>
                    <div class="incident-content">
                        <div class="incident-type"><?php echo htmlspecialchars($incident['type']); ?> - <?php echo htmlspecialchars($incident['location']); ?></div>
                        <div class="incident-description"><?php echo htmlspecialchars($incident['description']); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->

<!-- All Messages Modal -->
<div id="allMessagesModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">All Messages</h2>
            <span class="close" onclick="closeModal('allMessagesModal')">&times;</span>
        </div>
        <div class="modal-body">
            <div style="max-height: 400px; overflow-y: auto;">
                <?php foreach($data['messages'] as $message): ?>
                <div class="message-item">
                    <div class="message-avatar">
                        <?php echo strtoupper(substr($message['sender'], 0, 1)); ?>
                    </div>
                    <div class="message-content">
                        <div class="message-sender"><?php echo htmlspecialchars($message['sender']); ?></div>
                        <div class="message-text"><?php echo htmlspecialchars($message['message']); ?></div>
                        <small style="color: #999;"><?php echo $message['time']; ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- All Incidents Modal -->
<div id="allIncidentsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">All Incident Reports</h2>
            <span class="close" onclick="closeModal('allIncidentsModal')">&times;</span>
        </div>
        <div class="modal-body">
            <div style="max-height: 400px; overflow-y: auto;">
                <?php foreach($data['incidents'] as $incident): ?>
                <div class="incident-item">
                    <div class="incident-icon <?php echo $incident['severity']; ?>">
                        ⚠️
                    </div>
                    <div class="incident-content">
                        <div class="incident-type"><?php echo htmlspecialchars($incident['type']); ?></div>
                        <div class="incident-location"><?php echo htmlspecialchars($incident['location']); ?></div>
                        <div class="incident-description"><?php echo htmlspecialchars($incident['description']); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Individual Message Modal -->
<div id="messageModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="messageModalTitle">Message Details</h2>
            <span class="close" onclick="closeModal('messageModal')">&times;</span>
        </div>
        <div class="modal-body">
            <h4 id="messageModalSender"></h4>
            <p id="messageModalContent" style="margin-top: 15px; line-height: 1.6;"></p>
        </div>
    </div>
</div>

<!-- Individual Incident Modal -->
<div id="incidentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="incidentModalTitle">Incident Details</h2>
            <span class="close" onclick="closeModal('incidentModal')">&times;</span>
        </div>
        <div class="modal-body">
            <h4 id="incidentModalType"></h4>
            <p><strong>Location:</strong> <span id="incidentModalLocation"></span></p>
            <p style="margin-top: 15px; line-height: 1.6;"><strong>Description:</strong></p>
            <p id="incidentModalDescription" style="margin-top: 5px; line-height: 1.6;"></p>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
    
    function openMessageModal(sender, message) {
        document.getElementById('messageModalSender').textContent = sender;
        document.getElementById('messageModalContent').textContent = message;
        openModal('messageModal');
    }
    
    function openIncidentModal(type, location, description) {
        document.getElementById('incidentModalType').textContent = type;
        document.getElementById('incidentModalLocation').textContent = location;
        document.getElementById('incidentModalDescription').textContent = description;
        openModal('incidentModal');
    }
    
    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modals = document.getElementsByClassName('modal');
        for (let modal of modals) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    }
</script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
