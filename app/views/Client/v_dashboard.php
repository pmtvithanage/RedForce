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
            background-color: #FFEAEA;
        }
        
        .main-content {
            margin-left: 80px;
            margin-top: 50px;
            padding: 30px;
            min-height: calc(100vh - 70px);
        }
        
        .dashboard-header {
            margin-bottom: 30px;
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
        
        /* Modal Styles - Fixed centering and blur effect */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }
        
        .modal-content {
            background-color: white;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: slideIn 0.3s ease;
        }
        
        .modal-header {
            background: #fff;
            color: #333;
            padding: 20px 25px;
            border-radius: 15px 15px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        
        .close {
            color: #999;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
        }
        
        .close:hover {
            background-color: #f0f0f0;
            color: #666;
        }
        
        .modal-body {
            padding: 20px 25px 25px 25px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        /* Incident List Styles in Modal */
        .modal .incident-item {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 12px;
            display: block;
            cursor: default;
            transition: all 0.2s ease;
        }
        
        /* High severity incidents - Red */
        .modal .incident-item.high {
            background: #ffebee;
            border: 1px solid #ef5350;
        }
        
        .modal .incident-item.high:hover {
            background: #ffcdd2;
        }
        
        /* Medium severity incidents - Yellow */
        .modal .incident-item.medium {
            background: #fffde7;
            border: 1px solid #ffca28;
        }
        
        .modal .incident-item.medium:hover {
            background: #fff9c4;
        }
        
        /* Low severity incidents - Light Red */
        .modal .incident-item.low {
            background: #fef7f7;
            border: 1px solid #f8bbd9;
        }
        
        .modal .incident-item.low:hover {
            background: #fdf2f2;
        }
        
        .modal .incident-item:last-child {
            margin-bottom: 0;
        }
        
        .modal .incident-header {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .modal .incident-warning {
            font-size: 20px;
            margin-right: 10px;
        }
        
        .modal .incident-item.high .incident-warning {
            color: #d32f2f;
        }
        
        .modal .incident-item.medium .incident-warning {
            color: #f57c00;
        }
        
        .modal .incident-item.low .incident-warning {
            color: #e91e63;
        }
        
        .modal .incident-title {
            font-weight: 600;
            color: #333;
            font-size: 15px;
        }
        
        .modal .incident-date {
            color: #666;
            font-size: 12px;
            margin-bottom: 8px;
        }
        
        .modal .incident-details {
            color: #555;
            font-size: 13px;
            line-height: 1.4;
        }
        
        .back-button {
            background: #f8f9fa;
            color: #666;
            border: 1px solid #dee2e6;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s ease;
            margin-top: 20px;
            display: block;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }
        
        .back-button:hover {
            background: #e9ecef;
            color: #495057;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { transform: translate(-50%, -60%); opacity: 0; }
            to { transform: translate(-50%, -50%); opacity: 1; }
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
            
            .modal-content {
                width: 95%;
                margin: 5% auto;
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
                    <!-- Removed the View All link for messages -->
                </div>
                <div class="section-content">
                    <?php foreach($data['messages'] as $message): ?>
                    <div class="message-item">
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
                    <div class="incident-item">
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
    
    <!-- Only Incidents Modal (matching your design) -->
    <div id="allIncidentsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Incident Reports</h2>
                <span class="close" onclick="closeModal('allIncidentsModal')">&times;</span>
            </div>
            <div class="modal-body">
                <?php foreach($data['incidents'] as $incident): ?>
                <div class="incident-item <?php echo isset($incident['severity']) ? $incident['severity'] : 'low'; ?>">
                    <div class="incident-header">
                        <span class="incident-warning">⚠️</span>
                        <span class="incident-title"><?php echo htmlspecialchars($incident['type']); ?> - <?php echo htmlspecialchars($incident['location']); ?></span>
                    </div>
                    <div class="incident-date"><?php echo isset($incident['date']) ? htmlspecialchars($incident['date']) : '01.01.2023'; ?> : <?php echo isset($incident['details']) ? htmlspecialchars($incident['details']) : '2 men wearing black tried to get into the vault'; ?></div>
                    <div class="incident-details"><?php echo htmlspecialchars($incident['description']); ?></div>
                </div>
                <?php endforeach; ?>
                
                <button class="back-button" onclick="closeModal('allIncidentsModal')">Back</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
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