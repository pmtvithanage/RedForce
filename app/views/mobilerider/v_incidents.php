<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>


<!-- Content will be loaded here -->

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Dashboard CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/mobilerider/incident.css">

<!-- Dashboard Content -->
<div class="container">
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon incidents">📋</div>
            <div class="stat-content">
                <div class="stat-label">Total Incidents</div>
                <div class="stat-value">4</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon open">⚠️</div>
            <div class="stat-content">
                <div class="stat-label">Open</div>
                <div class="stat-value">1</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon progress">📊</div>
            <div class="stat-content">
                <div class="stat-label">In Progress</div>
                <div class="stat-value">0</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon resolved">✅</div>
            <div class="stat-content">
                <div class="stat-label">Resolved</div>
                <div class="stat-value">3</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-grid">
        <!-- Recent Incidents -->
        <div class="section">
            <h2 class="section-title">Recent Incidents</h2>

            <div class="incident-item">
                <div class="incident-header">
                    <div class="incident-title">Database Performance Degradation</div>
                    <span class="severity-badge severity-medium">Medium</span>
                </div>
                <div class="incident-description">Customer database showing slow query responses</div>
                <div class="incident-meta">
                    <div class="meta-item">
                        <span>🕐</span>
                        <span>4/17/2023 01:20 PM</span>
                    </div>
                    <div class="meta-item">
                        <span>📍</span>
                        <span>Database Cluster</span>
                    </div>
                    <span class="status-badge status-open">Open</span>
                </div>
            </div>

            <div class="incident-item">
                <div class="incident-header">
                    <div class="incident-title">Network Connectivity Issues</div>
                    <span class="severity-badge severity-medium">Medium</span>
                </div>
                <div class="incident-description">Users in Building B reporting intermittent connectivity</div>
                <div class="incident-meta">
                    <div class="meta-item">
                        <span>🕐</span>
                        <span>4/16/2023 10:45 AM</span>
                    </div>
                    <div class="meta-item">
                        <span>📍</span>
                        <span>Building B</span>
                    </div>
                    <span class="status-badge status-resolved">Resolved</span>
                </div>
            </div>

            <div class="incident-item">
                <div class="incident-header">
                    <div class="incident-title">Server Outage</div>
                    <span class="severity-badge severity-high">High</span>
                </div>
                <div class="incident-description">Main production server went down unexpectedly</div>
                <div class="incident-meta">
                    <div class="meta-item">
                        <span>🕐</span>
                        <span>4/15/2023 02:30 PM</span>
                    </div>
                    <div class="meta-item">
                        <span>📍</span>
                        <span>Data Center A</span>
                    </div>
                    <span class="status-badge status-resolved">Resolved</span>
                </div>
            </div>

            <div class="incident-item">
                <div class="incident-header">
                    <div class="incident-title">Security Breach Attempt</div>
                    <span class="severity-badge severity-critical">Critical</span>
                </div>
                <div class="incident-description">Multiple failed login attempts detected from unknown IP</div>
                <div class="incident-meta">
                    <div class="meta-item">
                        <span>🕐</span>
                        <span>4/14/2023 09:15 AM</span>
                    </div>
                    <div class="meta-item">
                        <span>📍</span>
                        <span>Authentication System</span>
                    </div>
                    <span class="status-badge status-resolved">Resolved</span>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="right-column">
            <!-- Report Incident -->
            <div class="report-incident" onclick="reportIncident()">
                <span class="report-icon">🔺</span>
                <div class="report-text">Report Incident</div>
            </div>

            <!-- Incident Severity Distribution -->
            <div class="section">
                <h2 class="section-title">Incident Severity Distribution</h2>

                <div class="severity-item">
                    <div class="severity-info">
                        <div class="severity-label">Critical</div>
                        <div class="severity-bar critical" style="width: 25%;"></div>
                    </div>
                    <div class="severity-count">1</div>
                </div>

                <div class="severity-item">
                    <div class="severity-info">
                        <div class="severity-label">High</div>
                        <div class="severity-bar high" style="width: 25%;"></div>
                    </div>
                    <div class="severity-count">1</div>
                </div>

                <div class="severity-item">
                    <div class="severity-info">
                        <div class="severity-label">Medium</div>
                        <div class="severity-bar medium" style="width: 50%;"></div>
                    </div>
                    <div class="severity-count">2</div>
                </div>

                <div class="severity-item">
                    <div class="severity-info">
                        <div class="severity-label">Low</div>
                        <div class="severity-bar low" style="width: 0%;"></div>
                    </div>
                    <div class="severity-count">0</div>
                </div>
            </div>

            <!-- View All Reports -->
            <a href="#" class="view-all" onclick="viewAllReports()">
                <span class="view-all-icon">📂</span>
                <div class="view-all-text">View All Incident Reports</div>
            </a>
        </div>
    </div>
</div>

<script>
function reportIncident() {
    alert('Report Incident clicked! Opening incident form...');
}

function viewAllReports() {
    alert('View All Reports clicked! Opening reports page...');
}

// Add hover effects to incident items
document.querySelectorAll('.incident-item').forEach(item => {
    item.addEventListener('click', function() {
        const title = this.querySelector('.incident-title').textContent;
        console.log(`Selected incident: ${title}`);

        // Add visual feedback
        this.style.backgroundColor = 'rgba(236, 72, 153, 0.1)';
        setTimeout(() => {
            this.style.backgroundColor = '';
        }, 300);
    });
});

// Animate severity bars on load
window.addEventListener('load', function() {
    setTimeout(() => {
        document.querySelectorAll('.severity-bar').forEach((bar, index) => {
            bar.style.transition = 'width 1s ease';
            bar.style.width = bar.style.width || '0%';
        });
    }, 500);
});
</script>


</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>