<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

  <style>
    .container {
    max-width: 1200px;
    margin: 0 auto;
}

.reports-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    margin-top: 100px;
    margin-bottom: 40px;
    justify-content: center;
    padding: 20px;
}

.report-card {
    background: white;
    border-radius: 20px;
    padding: 40px 45px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    min-width: 280px;
    max-width: 350px;
    height: 180px;
}

.report-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.report-card:hover::before {
    transform: scaleX(1);
}

.report-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.card-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-icon .material-symbols-outlined {
    font-size: 4.5rem;
    color: #2c3e50;
    transition: all 0.3s ease;
}

.report-card:hover .card-icon .material-symbols-outlined {
    transform: scale(1.1);
    color: #dc3545;
}

.card-text {
    font-size: 1.4rem;
    font-weight: 600;
    color: #2c3e50;
    transition: color 0.3s ease;
    text-align: center;
}

.report-card:hover .card-text {
    color: #dc3545;
}

  </style>

    <!-- Content will be loaded here -->
    <div class="container"> 
        <div class="reports-grid">
            <!-- Attendance Report Card -->
            <button class="report-card" data-report="attendance" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/attendencereports'">
                <div class="card-icon">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
                <div class="card-text">Attendance</div>
            </button>

            <!-- Incident Reports Card -->
            <button class="report-card" data-report="incident" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/incidentsreports'">
                <div class="card-icon">
                    <span class="material-symbols-outlined">description</span>
                </div>
                <div class="card-text">Incident Reports</div>
            </button>

            <!-- Officer Performance Card -->
            <button class="report-card" data-report="officer" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/performancereports'">
                <div class="card-icon">
                    <span class="material-symbols-outlined">security</span>
                </div>
                <div class="card-text">Officer Performance</div>
            </button>

            <!-- Site Reports Card -->
            <button class="report-card" data-report="site" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/sitereports'">
                <div class="card-icon">
                    <span class="material-symbols-outlined">bar_chart</span>
                </div>
                <div class="card-text">Site Reports</div>
            </button>

            <!-- Payment Reports Card -->
            <button class="report-card" data-report="payment" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/paymentsreports'">
                <div class="card-icon">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <div class="card-text">Payment Reports</div>
            </button>

            <!-- Client Transactions Card -->
            <button class="report-card" data-report="client" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/requestsreports'">
                <div class="card-icon">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <div class="card-text">Client Requests</div>
            </button>
        </div>
    </div>
    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>