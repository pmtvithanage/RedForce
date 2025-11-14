<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>
  <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/reports_style.css">


    <!-- Content will be loaded here -->
      <div class="container">
        <h1 class="dashboard-title">Admin Reports Dashboard</h1>
        
        <div class="reports-grid">
            <!-- Attendance Report Card -->
            <div class="report-card" data-report="attendance">
                <div class="card-icon">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
                <div class="card-text">Attendance</div>
            </div>

            <!-- Incident Reports Card -->
            <div class="report-card" data-report="incident">
                <div class="card-icon">
                    <span class="material-symbols-outlined">description</span>
                </div>
                <div class="card-text">Incident Reports</div>
            </div>

            <!-- Officer Performance Card -->
            <div class="report-card" data-report="officer">
                <div class="card-icon">
                    <span class="material-symbols-outlined">security</span>
                </div>
                <div class="card-text">Officer Performance</div>
            </div>

            <!-- Site Reports Card -->
            <div class="report-card" data-report="site">
                <div class="card-icon">
                    <span class="material-symbols-outlined">bar_chart</span>
                </div>
                <div class="card-text">Site Reports</div>
            </div>

            <!-- Payment Reports Card -->
            <div class="report-card" data-report="payment">
                <div class="card-icon">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <div class="card-text">Payment Reports</div>
            </div>

            <!-- Client Transactions Card -->
            <div class="report-card" data-report="client">
                <div class="card-icon">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <div class="card-text">Client Transactions</div>
            </div>
        </div>
    </div>

    <!-- Attendance Report Modal -->
    <div id="attendance-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><span class="material-symbols-outlined">check_circle</span> Attendance Report</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="report-filters">
                    <select id="attendance-period">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                    <input type="date" id="attendance-date">
                    <button class="generate-btn">Generate Report</button>
                </div>
                <div class="report-content">
                    <div class="report-preview">
                        <h3>Attendance Summary</h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-number">95%</span>
                                <span class="stat-label">Present</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">3%</span>
                                <span class="stat-label">Late</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">2%</span>
                                <span class="stat-label">Absent</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detailed-section">
                        <h3>Employee Attendance Details</h3>
                        <div class="attendance-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Officer ID</th>
                                        <th>Name</th>
                                        <th>Site</th>
                                        <th>Check-in Time</th>
                                        <th>Check-out Time</th>
                                        <th>Status</th>
                                        <th>Hours Worked</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>EMP001</td>

                                        <td>Chamika Karunarathna</td>
                                        <td>Security</td>

                                        <td>08:00 AM</td>
                                        <td>05:00 PM</td>
                                        <td><span class="status present">Present</span></td>
                                        <td>9.0 hrs</td>
                                    </tr>
                                    <tr>
                                        <td>EMP002</td>

                                        <td>Saman Edirimunee</td>

                                        <td>08:15 AM</td>
                                        <td>05:15 PM</td>
                                        <td><span class="status late">Late</span></td>
                                        <td>9.0 hrs</td>
                                    </tr>
                                    <tr>
                                        <td>EMP003</td>

                                        <td>Roy Dias</td>
                                        <td>Operations</td>
                                        <td>--</td>
                                        <td>--</td>
                                        <td><span class="status absent">Absent</span></td>
                                        <td>0.0 hrs</td>
                                    </tr>
                                    <tr>
                                        <td>EMP004</td>
                                        <td>Lionel Mendis</td>
                                        <td>Security</td>
                                        <td>07:45 AM</td>
                                        <td>05:30 PM</td>
                                        <td><span class="status present">Present</span></td>
                                        <td>9.75 hrs</td>
                                    </tr>
                                    <tr>
                                        <td>EMP005</td>
                                        <td>Dimuth Gamage</td>
                                        <td>Administration</td>
                                        <td>08:30 AM</td>
                                        <td>05:00 PM</td>
                                        <td><span class="status late">Late</span></td>
                                        <td>8.5 hrs</td>
                                    </tr>
                                    <tr>
                                        <td>EMP006</td>
                                        <td>Ravin Kanishka</td>
                                        <td>Operations</td>
                                        <td>08:00 AM</td>
                                        <td>05:00 PM</td>
                                        <td><span class="status present">Present</span></td>
                                        <td>9.0 hrs</td>
                                    </tr>
                                    <tr>
                                        <td>EMP007</td>
                                        <td>Charith Asalanka</td>
                                        <td>Security</td>

                                        <td>--</td>
                                        <td>--</td>
                                        <td><span class="status absent">Absent</span></td>
                                        <td>0.0 hrs</td>
                                    </tr>

                                    <tr>
                                        <td>EMP008</td>
                                        <td>Pathum Nissanka</td>
                                        <td>Administration</td>
                                        <td>07:30 AM</td>
                                        <td>05:15 PM</td>
                                        <td><span class="status present">Present</span></td>
                                        <td>9.75 hrs</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    
                    
                   
                </div>
            </div>
        </div>
    </div>

    <!-- Incident Reports Modal -->
    <div id="incident-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><span class="material-symbols-outlined">description</span> Incident Reports</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="report-filters">
                    <select id="incident-type">
                        <option value="all">All Types</option>
                        <option value="security">Pending</option>
                        <option value="medical">Resolved</option>
                        <option value="fire">Rejected</option>
                    </select>
                    <input type="date" id="incident-date">
                    <button class="generate-btn">Generate Report</button>
                </div>
                <div class="report-content">
                    <div class="report-preview">
                        <h3>Incident Summary</h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-number">12</span>
                                <span class="stat-label">Total Incidents</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">8</span>
                                <span class="stat-label">Pending</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">3</span>
                                <span class="stat-label">Resolved</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">1</span>
                                <span class="stat-label">Rejected</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detailed-section">
                        <h3>Detailed Incident Report</h3>
                        <div class="incident-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Incident ID</th>
                                        <th>Location</th>
                                        <th>Description</th>
                                        <th>Reported By</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                        <th>Resolution</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>INC001</td>
                                        
                                        <td>Z4332</td>
                                        <td>Unauthorized access attempt by unknown person</td>

                                        <td>Officer Chamika</td>

                                        <td>2024-01-15 14:30</td>
                                        <td><span class="status resolved">Resolved</span></td>
                                        <td>Person escorted off premises</td>
                                    </tr>
                                    <tr>
                                        <td>INC002</td>
                                        
                                        <td>Z2231</td>
                                        <td>Employee fainted due to heat exhaustion</td>

                                        <td>Manager Kasun</td>

                                        <td>2024-01-14 11:15</td>
                                        <td><span class="status resolved">Resolved</span></td>
                                        <td>First aid provided, employee sent home</td>
                                    </tr>
                                    <tr>
                                        <td>INC003</td>
                                       
                                        <td>Z33221</td>
                                        <td>Vehicle break-in reported</td>

                                        <td>Employee Dimuth</td>

                                        <td>2024-01-13 18:45</td>
                                        <td><span class="status pending">Pending</span></td>
                                        <td>Police report filed</td>
                                    </tr>
                                    <tr>
                                        <td>INC004</td>
                                        
                                        <td>Z55443</td>
                                        <td>Small grease fire in cafeteria kitchen</td>

                                        <td>Officer Madura</td>

                                        <td>2024-01-12 12:30</td>
                                        <td><span class="status resolved">Resolved</span></td>
                                        <td>Fire extinguished, no damage</td>
                                    </tr>
                                    <tr>
                                        <td>INC005</td>
                                        
                                        <td>Z44332</td>
                                        <td>Employee complained of chest pain</td>

                                        <td>Officer Saman</td>

                                        <td>2024-01-11 15:20</td>
                                        <td><span class="status resolved">Resolved</span></td>
                                        <td>Ambulance called, employee hospitalized</td>
                                    </tr>
                                    <tr>
                                        <td>INC006</td>
                                        
                                        <td>Z55544</td>
                                        <td>Unauthorized access to restricted area</td>

                                        <td>Officer Padme</td>

                                        <td>2024-01-10 09:45</td>
                                        <td><span class="status resolved">Resolved</span></td>
                                        <td>Access revoked, investigation completed</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Officer Performance Modal -->
    <div id="officer-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><span class="material-symbols-outlined">security</span> Officer Performance</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="report-filters">
                    <select id="officer-select">
                        <option value="all">All Officers</option>
                        <option value="officer1">Officer Wanidu</option>
                        <option value="officer2">Officer Matheesha</option>
                    </select>
                    <select id="performance-period">
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                    </select>
                    <button class="generate-btn">Generate Report</button>
                </div>
                <div class="report-preview">
                    <h3>Performance Metrics</h3>
                    <div class="performance-metrics">
                        <div class="metric">
                            <span class="metric-label">Response Time</span>
                            <span class="metric-value">2.3 min</span>
                        </div>
                        <div class="metric">
                            <span class="metric-label">Incidents Resolved</span>
                            <span class="metric-value">87%</span>
                        </div>
                        <div class="metric">
                            <span class="metric-label">Client Satisfaction</span>
                            <span class="metric-value">4.8/5</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Site Reports Modal -->
    <div id="site-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><span class="material-symbols-outlined">bar_chart</span> Site Reports</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="report-filters">
                    <select id="site-select">
                        <option value="all">All Sites</option>
                        <option value="site1">Bank</option>
                        <option value="site2">School</option>
                        <option value="site3">Retail Center</option>
                        <option value="site4">Communication Center</option>
                        <option value="site5">Data Center</option>
                    </select>
                    <select id="site-period">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                    <button class="generate-btn">Generate Report</button>
                </div>
                <div class="report-content">
                    <div class="report-preview">
                        <h3>Site Activity Summary</h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-number">1,247</span>
                                <span class="stat-label">Total Visits</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">89</span>
                                <span class="stat-label">Security Checks</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">3</span>
                                <span class="stat-label">Alerts</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">5</span>
                                <span class="stat-label">Active Sites</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detailed-section">
                        <h3>Site Activity Details</h3>
                        <div class="site-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Site ID</th>
                                        <th>Site Name</th>
                                        <th>Location</th>
                                        <th>Visits Today</th>
                                        <th>Security Checks</th>
                                        <th>Alerts</th>
                                        <th>Status</th>
                                        <th>Last Check</th>
                                        <th>Officer Assigned</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>SITE001</td>
                                        <td>Bank</td>
                                        <td>123 Main St, City Center</td>
                                        <td>156</td>
                                        <td>12</td>
                                        <td>0</td>
                                        <td><span class="status active">Active</span></td>
                                        <td>2024-01-15 16:30</td>
                                        <td>Officer Wanidu</td>
                                    </tr>
                                    <tr>
                                        <td>SITE002</td>
                                        <td>School</td>
                                        <td>456 ,Maradana RD</td>
                                        <td>89</td>
                                        <td>8</td>
                                        <td>1</td>
                                        <td><span class="status active">Active</span></td>
                                        <td>2024-01-15 15:45</td>
                                        <td>Officer Kamal</td>
                                    </tr>
                                    <tr>
                                        <td>SITE003</td>
                                        <td>Retail Center</td>
                                        <td>789 Sisil Ave</td>
                                        <td>234</td>
                                        <td>15</td>
                                        <td>2</td>
                                        <td><span class="status active">Active</span></td>
                                        <td>2024-01-15 17:15</td>
                                        <td>Officer Chasun</td>
                                    </tr>
                                    <tr>
                                        <td>SITE004</td>
                                        <td>Communication Center</td>
                                        <td>321 Factory Rd</td>
                                        <td>67</td>
                                        <td>6</td>
                                        <td>0</td>
                                        <td><span class="status active">Active</span></td>
                                        <td>2024-01-15 14:20</td>
                                        <td>Officer Adith</td>
                                    </tr>
                                    <tr>
                                        <td>SITE005</td>
                                        <td>Data Center</td>
                                        <td>654 Tech Park</td>
                                        <td>45</td>
                                        <td>4</td>
                                        <td>0</td>
                                        <td><span class="status active">Active</span></td>
                                        <td>2024-01-15 13:55</td>
                                        <td>Officer Supun</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="detailed-section">
                        <h3>Security Check Details</h3>
                        <div class="security-checks-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Check ID</th>
                                        <th>Site</th>
                                        <th>Check Type</th>
                                        <th>Officer</th>
                                        <th>Date & Time</th>
                                        <th>Findings</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>CHK001</td>
                                        <td>Bank</td>
                                        <td>Perimeter Check</td>
                                        <td>Officer Wanidu</td>
                                        <td>2024-01-15 08:00</td>
                                        <td>All clear</td>
                                        <td><span class="status resolved">Completed</span></td>
                                        <td>No issues found</td>
                                    </tr>
                                    <tr>
                                        <td>CHK002</td>
                                        <td>School</td>
                                        <td>Equipment Check</td>
                                        <td>Officer Kamal</td>
                                        <td>2024-01-15 10:30</td>
                                        <td>Minor damage to fence</td>
                                        <td><span class="status pending">Pending</span></td>
                                        <td>Maintenance notified</td>
                                    </tr>
                                    <tr>
                                        <td>CHK003</td>
                                        <td>Retail Center</td>
                                        <td>Access Control</td>
                                        <td>Officer Chasun</td>
                                        <td>2024-01-15 12:15</td>
                                        <td>Unauthorized vehicle</td>
                                        <td><span class="status resolved">Resolved</span></td>
                                        <td>Vehicle removed</td>
                                    </tr>
                                    <tr>
                                        <td>CHK004</td>
                                        <td>Communication Center</td>
                                        <td>Safety Inspection</td>
                                        <td>Officer Adith</td>
                                        <td>2024-01-15 14:00</td>
                                        <td>All clear</td>
                                        <td><span class="status resolved">Completed</span></td>
                                        <td>Safety protocols followed</td>
                                    </tr>
                                    <tr>
                                        <td>CHK005</td>
                                        <td>Data Center</td>
                                        <td>System Check</td>
                                        <td>Officer Supun</td>
                                        <td>2024-01-15 16:00</td>
                                        <td>All clear</td>
                                        <td><span class="status resolved">Completed</span></td>
                                        <td>All systems operational</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="detailed-section">
                        <h3>Site Performance Analysis</h3>
                        <div class="analysis-grid">
                            <div class="analysis-item">
                                <h4>Most Active Site</h4>
                                <p>Retail Center with 234 visits today</p>
                            </div>
                            <div class="analysis-item">
                                <h4>Security Check Frequency</h4>
                                <p>Average 2.1 checks per site per day</p>
                            </div>
                            <div class="analysis-item">
                                <h4>Alert Rate</h4>
                                <p>0.6% alert rate across all sites</p>
                            </div>
                            <div class="analysis-item">
                                <h4>Peak Activity Hours</h4>
                                <p>9:00 AM - 11:00 AM and 2:00 PM - 4:00 PM</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detailed-section">
                        <h3>Site Access Log</h3>
                        <div class="access-log-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Site</th>
                                        <th>Person/Vehicle</th>
                                        <th>Access Type</th>
                                        <th>Purpose</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>08:15</td>
                                        <td>Bank</td>
                                        <td>Wanidu (EMP001)</td>
                                        <td>Employee Entry</td>
                                        <td>Regular Work</td>
                                        <td>8.5 hrs</td>
                                        <td><span class="status present">Completed</span></td>
                                    </tr>
                                    <tr>
                                        <td>09:30</td>
                                        <td>School</td>
                                        <td>Delivery Truck (DLV001)</td>
                                        <td>Vehicle Entry</td>
                                        <td>Supply Delivery</td>
                                        <td>45 min</td>
                                        <td><span class="status present">Completed</span></td>
                                    </tr>
                                    <tr>
                                        <td>11:45</td>
                                        <td>Retail Center</td>
                                        <td>Maintenance Crew (MNT001)</td>
                                        <td>Contractor Entry</td>
                                        <td>HVAC Repair</td>
                                        <td>2.5 hrs</td>
                                        <td><span class="status present">Completed</span></td>
                                    </tr>
                                    <tr>
                                        <td>14:20</td>
                                        <td>Communication Center</td>
                                        <td>Chasun (EMP002)</td>
                                        <td>Employee Entry</td>
                                        <td>Regular Work</td>
                                        <td>6.5 hrs</td>
                                        <td><span class="status present">In Progress</span></td>
                                    </tr>
                                    <tr>
                                        <td>15:30</td>
                                        <td>Data Center</td>
                                        <td>IT Consultant (IT001)</td>
                                        <td>Visitor Entry</td>
                                        <td>System Maintenance</td>
                                        <td>1.5 hrs</td>
                                        <td><span class="status present">In Progress</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="detailed-section">
                        <h3>Site Security Metrics</h3>
                        <div class="metrics-chart">
                            <div class="chart-placeholder">
                                <p>📊 Site Security Performance Dashboard</p>
                                <p>Bank: 98% | School: 95% | Retail Center: 92% | Communication Center: 97% | Data Center: 99%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Reports Modal -->
    <div id="payment-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><span class="material-symbols-outlined">payments</span> Payment Reports</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="report-filters">
                    <select id="payment-status">
                        <option value="all">All Payments</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                    <input type="date" id="payment-date">
                    <button class="generate-btn">Generate Report</button>
                </div>
                <div class="report-preview">
                    <h3>Payment Summary</h3>
                    <div class="payment-summary">
                        <div class="payment-stat">
                            <span class="stat-label">Total Revenue</span>
                            <span class="stat-value">Rs45,230</span>
                        </div>
                        <div class="payment-stat">
                            <span class="stat-label">Transactions</span>
                            <span class="stat-value">156</span>
                        </div>
                        <div class="payment-stat">
                            <span class="stat-label">Success Rate</span>
                            <span class="stat-value">98.7%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client Transactions Modal -->
    <div id="client-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><span class="material-symbols-outlined">group</span> Client Transactions</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="report-filters">
                    <select id="client-select">
                        <option value="all">All Clients</option>
                        <option value="client1">ABC Corporation</option>
                        <option value="client2">XYZ Industries</option>
                    </select>
                    <select id="transaction-type">
                        <option value="all">All Types</option>
                        <option value="service">Service</option>
                        <option value="equipment">Equipment</option>
                    </select>
                    <button class="generate-btn">Generate Report</button>
                </div>
                <div class="report-preview">
                    <h3>Transaction History</h3>
                    <div class="transaction-list">
                        <div class="transaction-item">
                            <span class="client-name">ABC Corporation</span>
                            <span class="transaction-amount">Rs2,500</span>
                            <span class="transaction-date">2024-01-15</span>
                        </div>
                        <div class="transaction-item">
                            <span class="client-name">XYZ Industries</span>
                            <span class="transaction-amount">Rs1,800</span>
                            <span class="transaction-date">2024-01-14</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/admin/reports.js"></script>
    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>