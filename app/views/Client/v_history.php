<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php
// Mock data for history periods - replace with actual database query
$historyPeriods = [
    [
        'year' => '2025',
        'start_date' => '01-01-2025',
        'end_date' => '31-12-2025',
        'total_requests' => 15,
        'completed_services' => 12,
        'active_services' => 3
    ],
    [
        'year' => '2024',
        'start_date' => '01-01-2024',
        'end_date' => '31-12-2024',
        'total_requests' => 28,
        'completed_services' => 25,
        'active_services' => 0
    ],
    [
        'year' => '2023',
        'start_date' => '01-01-2023',
        'end_date' => '31-12-2023',
        'total_requests' => 22,
        'completed_services' => 22,
        'active_services' => 0
    ]
];

// Handle year selection for popup
$showYearPopup = false;
$selectedYear = null;
$selectedYearData = null;

if (isset($_GET['year'])) {
    $showYearPopup = true;
    $selectedYear = $_GET['year'];
    
    // Mock detailed data for the selected year
    $yearDetailData = [
        '2025' => [
            'period' => '01-01-2025 TO 31-12-2025',
            'sites' => [
                ['name' => 'Peoples Bank PLC - Colombo', 'premise_officers' => 8, 'supervisors' => 2, 'requested_officers' => 2, 'incidents' => 2, 'working_hours' => 6112, 'cost' => '122,400/-'],
                ['name' => 'Peoples Bank PLC - Kurunegala', 'premise_officers' => 10, 'supervisors' => 2, 'requested_officers' => 1, 'incidents' => 2, 'working_hours' => 6112, 'cost' => '150,000/-'],
                ['name' => 'Peoples Bank PLC - Kandy', 'premise_officers' => 7, 'supervisors' => 5, 'requested_officers' => 3, 'incidents' => 0, 'working_hours' => 6279, 'cost' => '145,000/-'],
                ['name' => 'Peoples Bank PLC - Polonnaruwa', 'premise_officers' => 6, 'supervisors' => 2, 'requested_officers' => 2, 'incidents' => 0, 'working_hours' => 6300, 'cost' => '148,000/-'],
                ['name' => 'Peoples Bank PLC - Jaffna', 'premise_officers' => 6, 'supervisors' => 2, 'requested_officers' => 1, 'incidents' => 0, 'working_hours' => 6179, 'cost' => '140,000/-'],
                ['name' => 'Peoples Bank PLC - Galle', 'premise_officers' => 4, 'supervisors' => 3, 'requested_officers' => 1, 'incidents' => 0, 'working_hours' => 6500, 'cost' => '135,000/-'],
                ['name' => 'Peoples Bank PLC - Kalmunai', 'premise_officers' => 3, 'supervisors' => 2, 'requested_officers' => 3, 'incidents' => 1, 'working_hours' => 6130, 'cost' => '148,000/-'],
                ['name' => 'Peoples Bank PLC - Jails', 'premise_officers' => 3, 'supervisors' => 2, 'requested_officers' => 2, 'incidents' => 0, 'working_hours' => 6200, 'cost' => '140,000/-']
            ],
            'summary' => [
                'total_sites' => 8,
                'total_officers' => 98,
                'total_incidents' => 5,
                'total_cost' => '1,000,100/-',
                'total_supervisors' => 8,
                'total_hours' => 51000
            ]
        ],
        '2024' => [
            'period' => '01-01-2024 TO 31-12-2024',
            'sites' => [
                ['name' => 'Peoples Bank PLC - Colombo', 'premise_officers' => 12, 'supervisors' => 3, 'requested_officers' => 3, 'incidents' => 1, 'working_hours' => 7200, 'cost' => '180,000/-'],
                ['name' => 'Peoples Bank PLC - Kurunegala', 'premise_officers' => 15, 'supervisors' => 4, 'requested_officers' => 2, 'incidents' => 0, 'working_hours' => 7500, 'cost' => '200,000/-'],
                ['name' => 'Peoples Bank PLC - Kandy', 'premise_officers' => 10, 'supervisors' => 2, 'requested_officers' => 4, 'incidents' => 2, 'working_hours' => 6800, 'cost' => '165,000/-']
            ],
            'summary' => [
                'total_sites' => 3,
                'total_officers' => 37,
                'total_incidents' => 3,
                'total_cost' => '545,000/-',
                'total_supervisors' => 9,
                'total_hours' => 21500
            ]
        ],
        '2023' => [
            'period' => '01-01-2023 TO 31-12-2023',
            'sites' => [
                ['name' => 'Peoples Bank PLC - Colombo', 'premise_officers' => 8, 'supervisors' => 2, 'requested_officers' => 2, 'incidents' => 0, 'working_hours' => 6000, 'cost' => '120,000/-'],
                ['name' => 'Peoples Bank PLC - Galle', 'premise_officers' => 6, 'supervisors' => 1, 'requested_officers' => 1, 'incidents' => 1, 'working_hours' => 5800, 'cost' => '110,000/-']
            ],
            'summary' => [
                'total_sites' => 2,
                'total_officers' => 14,
                'total_incidents' => 1,
                'total_cost' => '230,000/-',
                'total_supervisors' => 3,
                'total_hours' => 11800
            ]
        ]
    ];
    
    $selectedYearData = isset($yearDetailData[$selectedYear]) ? $yearDetailData[$selectedYear] : null;
}
?>

<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to History CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/history_style.css">

<!-- Year Details Popup -->
<?php if ($showYearPopup && $selectedYearData): ?>
<div class="popup-overlay">
    <div class="year-popup">
        <div class="popup-header">
            <div class="header-content">
                <div class="company-logo">
                    <img src="<?php echo URL_ROOT; ?>/img/logo.png" alt="Red Force" class="logo-img">
                </div>
                <div class="period-info">
                    <div class="clock-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z" fill="#d32f2f"/>
                            <path d="M12.5 7H11V13L16.25 16.15L17 14.92L12.5 12.25V7Z" fill="#d32f2f"/>
                        </svg>
                    </div>
                    <span class="period-text"><?php echo $selectedYearData['period']; ?></span>
                </div>
            </div>
            <a href="?" class="close-btn">&times;</a>
        </div>
        
        <div class="popup-content">
            <div class="filter-section">
                <label>Filter by</label>
                <select class="site-filter">
                    <option value="">Site</option>
                    <?php foreach ($selectedYearData['sites'] as $site): ?>
                        <option value="<?php echo htmlspecialchars($site['name']); ?>"><?php echo htmlspecialchars($site['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Site</th>
                            <th>Premise Officers</th>
                            <th>Supervisors</th>
                            <th>Requested Officers</th>
                            <th>No of Incidents</th>
                            <th>Total Working Hours</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($selectedYearData['sites'] as $site): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($site['name']); ?></td>
                            <td><?php echo $site['premise_officers']; ?></td>
                            <td><?php echo $site['supervisors']; ?></td>
                            <td><?php echo $site['requested_officers']; ?></td>
                            <td><?php echo $site['incidents']; ?></td>
                            <td><?php echo $site['working_hours']; ?></td>
                            <td><?php echo $site['cost']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="summary-cards">
                <div class="summary-row">
                    <div class="summary-card">
                        <div class="card-icon location-icon">
                            <span class="material-icons">place</span>
                        </div>
                        <div class="card-content">
                            <div class="card-label">No.of Sites</div>
                            <div class="card-value"><?php echo $selectedYearData['summary']['total_sites']; ?></div>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="card-icon officers-icon">
                            <span class="material-icons">groups</span>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Total Officers</div>
                            <div class="card-value"><?php echo $selectedYearData['summary']['total_officers']; ?></div>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="card-icon incidents-icon">
                            <span class="material-icons">warning</span>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Total Incidents</div>
                            <div class="card-value"><?php echo $selectedYearData['summary']['total_incidents']; ?></div>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="card-icon cost-icon">
                            <span class="material-icons">attach_money</span>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Total Cost</div>
                            <div class="card-value"><?php echo $selectedYearData['summary']['total_cost']; ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="summary-row">
                    <div class="summary-card">
                        <div class="card-icon supervisors-icon">
                            <span class="material-icons">supervisor_account</span>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Total Supervisors</div>
                            <div class="card-value"><?php echo $selectedYearData['summary']['total_supervisors']; ?></div>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="card-icon hours-icon">
                            <span class="material-icons">schedule</span>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Total Hours</div>
                            <div class="card-value"><?php echo number_format($selectedYearData['summary']['total_hours']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="main-content">
    <div class="history-container">
        <div class="history-header">
            <h2 class="page-title">History</h2>
        </div>
        
        <div class="history-list">
            <?php foreach ($historyPeriods as $period): ?>
            <a href="?year=<?php echo $period['year']; ?>" class="history-item">
                <div class="history-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z" fill="#d32f2f"/>
                        <path d="M12.5 7H11V13L16.25 16.15L17 14.92L12.5 12.25V7Z" fill="#d32f2f"/>
                    </svg>
                </div>
                <div class="history-details">
                    <span class="history-period">
                        <?php echo $period['start_date']; ?> TO <?php echo $period['end_date']; ?>
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>