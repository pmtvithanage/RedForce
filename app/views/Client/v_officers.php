<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php
// Handle rating submission
$showRatingModal = false;
$selectedOfficer = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rate_officer'])) {
    // Process rating here (save to database)
    $officerId = $_POST['officer_id'];
    $rating = $_POST['rating'];
    $description = $_POST['description'];
    
    // Here you would save the rating to the database
    // For now, we'll just show a success message
    echo "<script>alert('Rating submitted successfully!');</script>";
}

if (isset($_GET['rate_officer'])) {
    $showRatingModal = true;
    $officerId = $_GET['rate_officer'];
    
    // Mock officer data - in real application, fetch from database
    $officers = [
        'RF001' => ['name' => 'John Silva', 'rank' => 'OIC', 'site' => 'Colombo Main Branch', 'rating' => 4.2],
        'RF002' => ['name' => 'Sarah Perera', 'rank' => 'SSO', 'site' => 'Kurunegala Branch', 'rating' => 4.8],
        'RF003' => ['name' => 'Michael Fernando', 'rank' => 'JSO', 'site' => 'Nuwara Eliya Branch', 'rating' => 3.9],
        'RF004' => ['name' => 'Lisa Jayawardene', 'rank' => 'LSO', 'site' => 'Galle Branch', 'rating' => 4.5],
        'RF005' => ['name' => 'David Rathnayake', 'rank' => 'OIC', 'site' => 'Kandy Branch', 'rating' => 4.1],
        'RF006' => ['name' => 'Amanda Wickramasinghe', 'rank' => 'SSO', 'site' => 'Colombo Main Branch', 'rating' => 4.7],
        'RF007' => ['name' => 'Pradeep Kumara', 'rank' => 'JSO', 'site' => 'Kurunegala Branch', 'rating' => 4.3],
        'RF008' => ['name' => 'Nisha Mendis', 'rank' => 'LSO', 'site' => 'Galle Branch', 'rating' => 4.0],
        'RF009' => ['name' => 'Ruwan Dissanayake', 'rank' => 'JSO', 'site' => 'Nuwara Eliya Branch', 'rating' => 3.8],
        'RF010' => ['name' => 'Chamila Rajapakse', 'rank' => 'SSO', 'site' => 'Kandy Branch', 'rating' => 4.6],
        'RF011' => ['name' => 'Kasun Weerasinghe', 'rank' => 'LSO', 'site' => 'Colombo Main Branch', 'rating' => 4.4],
        'RF012' => ['name' => 'Tharaka Gunasekera', 'rank' => 'JSO', 'site' => 'Kurunegala Branch', 'rating' => 4.2]
    ];
    
    $selectedOfficer = isset($officers[$officerId]) ? $officers[$officerId] : null;
    $selectedOfficer['id'] = $officerId;
}
?>

<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Officers CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/officers_style.css">

<!-- Rating Modal -->
<?php if ($showRatingModal && $selectedOfficer): ?>
<div class="modal-overlay">
    <div class="rating-modal">
        <div class="modal-content">
            <div class="officer-profile">
                <div class="officer-avatar-large">
                    <?php echo strtoupper(substr($selectedOfficer['name'], 0, 1)); ?>
                    <div class="status-indicator"></div>
                </div>
                <div class="officer-details">
                    <h3>Name: <?php echo htmlspecialchars($selectedOfficer['name']); ?></h3>
                    <p>Officer ID: <?php echo htmlspecialchars($selectedOfficer['id']); ?></p>
                    <p>Rank: <?php echo htmlspecialchars($selectedOfficer['rank']); ?></p>
                    <p>Location: <?php echo htmlspecialchars($selectedOfficer['site']); ?></p>
                    <p>Rating: <?php echo number_format($selectedOfficer['rating'], 1); ?></p>
                </div>
            </div>
            
            <div class="rating-section">
                <h4>Evaluate Officer</h4>
                <form method="POST" action="">
                    <input type="hidden" name="officer_id" value="<?php echo htmlspecialchars($selectedOfficer['id']); ?>">
                    
                    <div class="description-input">
                        <textarea name="description" placeholder="Description" rows="3"></textarea>
                    </div>
                    
                    <div class="star-rating">
                        <input type="radio" id="star5" name="rating" value="5" required>
                        <label for="star5" class="star">★</label>
                        
                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4" class="star">★</label>
                        
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3" class="star">★</label>
                        
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2" class="star">★</label>
                        
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1" class="star">★</label>
                    </div>
                    
                    <div class="modal-actions">
                        <button type="submit" name="rate_officer" class="save-btn">Save Changes</button>
                        <a href="?close_modal=1" class="close-btn">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Officers Content -->
<div class="main-content">
    <!-- Filter Section -->
    <div class="filter-section">
        <label class="filter-label">Filter by</label>
        <select class="filter-select" id="siteFilter" onchange="filterBySite()">
            <option value="">All Sites</option>
            <option value="Colombo Main Branch">Colombo Main Branch</option>
            <option value="Kurunegala Branch">Kurunegala Branch</option>
            <option value="Nuwara Eliya Branch">Nuwara Eliya Branch</option>
            <option value="Galle Branch">Galle Branch</option>
            <option value="Kandy Branch">Kandy Branch</option>
        </select>
    </div>
    
    <!-- Search and Actions -->
    <div class="table-actions">
        <div class="search-container">
            <input type="text" class="search-input" id="searchInput" placeholder="Search guards..." onkeyup="searchGuards()">
            <span class="search-icon">
                <span class="material-icons">search</span>
            </span>
        </div>
        <div>
            <span class="guards-count" id="guardsCount">12 guards found</span>
            <button class="export-btn" onclick="exportData()">
                <span class="material-icons" style="font-size: 16px;">download</span>
                Export
            </button>
        </div>
    </div>
    
    <!-- Guards Table -->
    <div class="guards-table-container">
        <table class="guards-table" id="guardsTable">
            <thead>
                <tr>
                    <th>Officer ID</th>
                    <th>Officer</th>
                    <th>Rank</th>
                    <th>Status</th>
                    <th>Site</th>
                </tr>
            </thead>
            <tbody id="guardsTableBody">
                <tr class="guard-row">
                    <td>
                        <a href="?rate_officer=RF001" class="officer-link">
                            <span class="officer-id">RF001</span>
                        </a>
                    </td>
                    <td>
                        <a href="?rate_officer=RF001" class="officer-link">
                            <div class="officer-info">
                                <div class="officer-avatar">J</div>
                                <span class="officer-name">John Silva</span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <span class="rank-badge rank-oic">OIC</span>
                    </td>
                    <td>
                        <span class="status-badge status-on-duty">On Duty</span>
                    </td>
                    <td>
                        <span class="site-info">Colombo Main Branch</span>
                    </td>
                </tr>
                
                <tr class="guard-row">
                    <td>
                        <a href="?rate_officer=RF002" class="officer-link">
                            <span class="officer-id">RF002</span>
                        </a>
                    </td>
                    <td>
                        <a href="?rate_officer=RF002" class="officer-link">
                            <div class="officer-info">
                                <div class="officer-avatar">S</div>
                                <span class="officer-name">Sarah Perera</span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <span class="rank-badge rank-sso">SSO</span>
                    </td>
                    <td>
                        <span class="status-badge status-off-duty">Off Duty</span>
                    </td>
                    <td>
                        <span class="site-info">Kurunegala Branch</span>
                    </td>
                </tr>
                
                <tr class="guard-row">
                    <td>
                        <a href="?rate_officer=RF003" class="officer-link">
                            <span class="officer-id">RF003</span>
                        </a>
                    </td>
                    <td>
                        <a href="?rate_officer=RF003" class="officer-link">
                            <div class="officer-info">
                                <div class="officer-avatar">M</div>
                                <span class="officer-name">Michael Fernando</span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <span class="rank-badge rank-jso">JSO</span>
                    </td>
                    <td>
                        <span class="status-badge status-on-break">On Break</span>
                    </td>
                    <td>
                        <span class="site-info">Nuwara Eliya Branch</span>
                    </td>
                </tr>
                
                <tr class="guard-row">
                    <td>
                        <a href="?rate_officer=RF004" class="officer-link">
                            <span class="officer-id">RF004</span>
                        </a>
                    </td>
                    <td>
                        <a href="?rate_officer=RF004" class="officer-link">
                            <div class="officer-info">
                                <div class="officer-avatar">L</div>
                                <span class="officer-name">Lisa Jayawardene</span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <span class="rank-badge rank-lso">LSO</span>
                    </td>
                    <td>
                        <span class="status-badge status-on-duty">On Duty</span>
                    </td>
                    <td>
                        <span class="site-info">Galle Branch</span>
                    </td>
                </tr>
                
                <tr class="guard-row">
                    <td>
                        <a href="?rate_officer=RF005" class="officer-link">
                            <span class="officer-id">RF005</span>
                        </a>
                    </td>
                    <td>
                        <a href="?rate_officer=RF005" class="officer-link">
                            <div class="officer-info">
                                <div class="officer-avatar">D</div>
                                <span class="officer-name">David Rathnayake</span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <span class="rank-badge rank-oic">OIC</span>
                    </td>
                    <td>
                        <span class="status-badge status-on-duty">On Duty</span>
                    </td>
                    <td>
                        <span class="site-info">Kandy Branch</span>
                    </td>
                </tr>
                
                <tr class="guard-row">
                    <td>
                        <a href="?rate_officer=RF006" class="officer-link">
                            <span class="officer-id">RF006</span>
                        </a>
                    </td>
                    <td>
                        <a href="?rate_officer=RF006" class="officer-link">
                            <div class="officer-info">
                                <div class="officer-avatar">A</div>
                                <span class="officer-name">Amanda Wickramasinghe</span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <span class="rank-badge rank-sso">SSO</span>
                    </td>
                    <td>
                        <span class="status-badge status-off-duty">Off Duty</span>
                    </td>
                    <td>
                        <span class="site-info">Colombo Main Branch</span>
                    </td>
                </tr>
                
                <tr class="guard-row">
                    <td>
                        <a href="?rate_officer=RF007" class="officer-link">
                            <span class="officer-id">RF007</span>
                        </a>
                    </td>
                    <td>
                        <a href="?rate_officer=RF007" class="officer-link">
                            <div class="officer-info">
                                <div class="officer-avatar">P</div>
                                <span class="officer-name">Pradeep Kumara</span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <span class="rank-badge rank-jso">JSO</span>
                    </td>
                    <td>
                        <span class="status-badge status-on-duty">On Duty</span>
                    </td>
                    <td>
                        <span class="site-info">Kurunegala Branch</span>
                    </td>
                </tr>
                
                <tr class="guard-row">
                    <td>
                        <a href="?rate_officer=RF008" class="officer-link">
                            <span class="officer-id">RF008</span>
                        </a>
                    </td>
                    <td>
                        <a href="?rate_officer=RF008" class="officer-link">
                            <div class="officer-info">
                                <div class="officer-avatar">N</div>
                                <span class="officer-name">Nisha Mendis</span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <span class="rank-badge rank-lso">LSO</span>
                    </td>
                    <td>
                        <span class="status-badge status-on-break">On Break</span>
                    </td>
                    <td>
                        <span class="site-info">Galle Branch</span>
                    </td>
                </tr>
                
                <tr class="guard-row">
                    <td>
                        <a href="?rate_officer=RF009" class="officer-link">
                            <span class="officer-id">RF009</span>
                        </a>
                    </td>
                    <td>
                        <a href="?rate_officer=RF009" class="officer-link">
                            <div class="officer-info">
                                <div class="officer-avatar">R</div>
                                <span class="officer-name">Ruwan Dissanayake</span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <span class="rank-badge rank-jso">JSO</span>
                    </td>
                    <td>
                        <span class="status-badge status-off-duty">Off Duty</span>
                    </td>
                    <td>
                        <span class="site-info">Nuwara Eliya Branch</span>
                    </td>
                </tr>
                
                <tr class="guard-row">
                    <td>
                        <a href="?rate_officer=RF010" class="officer-link">
                            <span class="officer-id">RF010</span>
                        </a>
                    </td>
                    <td>
                        <a href="?rate_officer=RF010" class="officer-link">
                            <div class="officer-info">
                                <div class="officer-avatar">C</div>
                                <span class="officer-name">Chamila Rajapakse</span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <span class="rank-badge rank-sso">SSO</span>
                    </td>
                    <td>
                        <span class="status-badge status-on-duty">On Duty</span>
                    </td>
                    <td>
                        <span class="site-info">Kandy Branch</span>
                    </td>
                </tr>
                
                <tr class="guard-row">
                    <td>
                        <a href="?rate_officer=RF011" class="officer-link">
                            <span class="officer-id">RF011</span>
                        </a>
                    </td>
                    <td>
                        <a href="?rate_officer=RF011" class="officer-link">
                            <div class="officer-info">
                                <div class="officer-avatar">K</div>
                                <span class="officer-name">Kasun Weerasinghe</span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <span class="rank-badge rank-lso">LSO</span>
                    </td>
                    <td>
                        <span class="status-badge status-on-duty">On Duty</span>
                    </td>
                    <td>
                        <span class="site-info">Colombo Main Branch</span>
                    </td>
                </tr>
                
                <tr class="guard-row">
                    <td>
                        <a href="?rate_officer=RF012" class="officer-link">
                            <span class="officer-id">RF012</span>
                        </a>
                    </td>
                    <td>
                        <a href="?rate_officer=RF012" class="officer-link">
                            <div class="officer-info">
                                <div class="officer-avatar">T</div>
                                <span class="officer-name">Tharaka Gunasekera</span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <span class="rank-badge rank-jso">JSO</span>
                    </td>
                    <td>
                        <span class="status-badge status-on-break">On Break</span>
                    </td>
                    <td>
                        <span class="site-info">Kurunegala Branch</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Pass data to JavaScript for filtering -->
<script>
    const guardsData = [
        {id: 'RF001', name: 'John Silva', rank: 'OIC', status: 'On Duty', site: 'Colombo Main Branch'},
        {id: 'RF002', name: 'Sarah Perera', rank: 'SSO', status: 'Off Duty', site: 'Kurunegala Branch'},
        {id: 'RF003', name: 'Michael Fernando', rank: 'JSO', status: 'On Break', site: 'Nuwara Eliya Branch'},
        {id: 'RF004', name: 'Lisa Jayawardene', rank: 'LSO', status: 'On Duty', site: 'Galle Branch'},
        {id: 'RF005', name: 'David Rathnayake', rank: 'OIC', status: 'On Duty', site: 'Kandy Branch'},
        {id: 'RF006', name: 'Amanda Wickramasinghe', rank: 'SSO', status: 'Off Duty', site: 'Colombo Main Branch'},
        {id: 'RF007', name: 'Pradeep Kumara', rank: 'JSO', status: 'On Duty', site: 'Kurunegala Branch'},
        {id: 'RF008', name: 'Nisha Mendis', rank: 'LSO', status: 'On Break', site: 'Galle Branch'},
        {id: 'RF009', name: 'Ruwan Dissanayake', rank: 'JSO', status: 'Off Duty', site: 'Nuwara Eliya Branch'},
        {id: 'RF010', name: 'Chamila Rajapakse', rank: 'SSO', status: 'On Duty', site: 'Kandy Branch'},
        {id: 'RF011', name: 'Kasun Weerasinghe', rank: 'LSO', status: 'On Duty', site: 'Colombo Main Branch'},
        {id: 'RF012', name: 'Tharaka Gunasekera', rank: 'JSO', status: 'On Break', site: 'Kurunegala Branch'}
    ];
</script>

<!-- Link to Officers JavaScript -->
<script src="<?php echo URL_ROOT; ?>/js/client/officers.js"></script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>