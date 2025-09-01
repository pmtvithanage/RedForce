
<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Officers CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/officers_style.css">

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
                <tr class="guard-row" onclick="openGuardModal('RF001', 'John Silva', 'OIC', 'On Duty', 'Colombo Main Branch')">
                    <td>
                        <span class="officer-id">RF001</span>
                    </td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">J</div>
                            <span class="officer-name">John Silva</span>
                        </div>
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
                
                <tr class="guard-row" onclick="openGuardModal('RF002', 'Sarah Perera', 'SSO', 'Off Duty', 'Kurunegala Branch')">
                    <td>
                        <span class="officer-id">RF002</span>
                    </td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">S</div>
                            <span class="officer-name">Sarah Perera</span>
                        </div>
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
                
                <tr class="guard-row" onclick="openGuardModal('RF003', 'Michael Fernando', 'JSO', 'On Break', 'Nuwara Eliya Branch')">
                    <td>
                        <span class="officer-id">RF003</span>
                    </td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">M</div>
                            <span class="officer-name">Michael Fernando</span>
                        </div>
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
                
                <tr class="guard-row" onclick="openGuardModal('RF004', 'Lisa Jayawardene', 'LSO', 'On Duty', 'Galle Branch')">
                    <td>
                        <span class="officer-id">RF004</span>
                    </td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">L</div>
                            <span class="officer-name">Lisa Jayawardene</span>
                        </div>
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
                
                <tr class="guard-row" onclick="openGuardModal('RF005', 'David Rathnayake', 'OIC', 'On Duty', 'Kandy Branch')">
                    <td>
                        <span class="officer-id">RF005</span>
                    </td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">D</div>
                            <span class="officer-name">David Rathnayake</span>
                        </div>
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
                
                <tr class="guard-row" onclick="openGuardModal('RF006', 'Amanda Wickramasinghe', 'SSO', 'Off Duty', 'Colombo Main Branch')">
                    <td>
                        <span class="officer-id">RF006</span>
                    </td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">A</div>
                            <span class="officer-name">Amanda Wickramasinghe</span>
                        </div>
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
                
                <tr class="guard-row" onclick="openGuardModal('RF007', 'Pradeep Kumara', 'JSO', 'On Duty', 'Kurunegala Branch')">
                    <td>
                        <span class="officer-id">RF007</span>
                    </td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">P</div>
                            <span class="officer-name">Pradeep Kumara</span>
                        </div>
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
                
                <tr class="guard-row" onclick="openGuardModal('RF008', 'Nisha Mendis', 'LSO', 'On Break', 'Galle Branch')">
                    <td>
                        <span class="officer-id">RF008</span>
                    </td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">N</div>
                            <span class="officer-name">Nisha Mendis</span>
                        </div>
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
                
                <tr class="guard-row" onclick="openGuardModal('RF009', 'Ruwan Dissanayake', 'JSO', 'Off Duty', 'Nuwara Eliya Branch')">
                    <td>
                        <span class="officer-id">RF009</span>
                    </td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">R</div>
                            <span class="officer-name">Ruwan Dissanayake</span>
                        </div>
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
                
                <tr class="guard-row" onclick="openGuardModal('RF010', 'Chamila Rajapakse', 'SSO', 'On Duty', 'Kandy Branch')">
                    <td>
                        <span class="officer-id">RF010</span>
                    </td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">C</div>
                            <span class="officer-name">Chamila Rajapakse</span>
                        </div>
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
                
                <tr class="guard-row" onclick="openGuardModal('RF011', 'Kasun Weerasinghe', 'LSO', 'On Duty', 'Colombo Main Branch')">
                    <td>
                        <span class="officer-id">RF011</span>
                    </td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">K</div>
                            <span class="officer-name">Kasun Weerasinghe</span>
                        </div>
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
                
                <tr class="guard-row" onclick="openGuardModal('RF012', 'Tharaka Gunasekera', 'JSO', 'On Break', 'Kurunegala Branch')">
                    <td>
                        <span class="officer-id">RF012</span>
                    </td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">T</div>
                            <span class="officer-name">Tharaka Gunasekera</span>
                        </div>
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

<!-- Guard Detail Modal -->
<div id="guardModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" onclick="closeModal('guardModal')">&times;</span>
            <h2 class="modal-title">Officers rating</h2>
        </div>
        <div class="modal-body">
            <div class="officer-profile">
                <div class="profile-left">
                    <div class="profile-avatar" id="modalGuardAvatar">
                        W
                        <div class="status-indicator" id="statusIndicator"></div>
                    </div>
                </div>
                <div class="profile-right">
                    <div class="officer-details">
                        <div class="detail-row">
                            <span class="detail-label">Name:</span>
                            <span class="detail-value" id="modalGuardName">W.W.Nuwan Perera</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Officer ID:</span>
                            <span class="detail-value" id="modalGuardId">PP231</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Rank:</span>
                            <span class="detail-value" id="modalGuardRank">OIC</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Location:</span>
                            <span class="detail-value" id="modalGuardSite">People's Bank PLC</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Rating:</span>
                            <span class="detail-value">1403</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="evaluation-section">
                <div class="evaluation-header">Evaluate Officer</div>
                <div class="rating-container">
                    <input type="text" class="rating-input" id="ratingInput" placeholder="Description">
                    <div class="star-rating">
                        <span class="star" onclick="setRating(1)">★</span>
                        <span class="star" onclick="setRating(2)">★</span>
                        <span class="star" onclick="setRating(3)">★</span>
                        <span class="star" onclick="setRating(4)">★</span>
                        <span class="star" onclick="setRating(5)">★</span>
                    </div>
                    <div class="modal-actions">
                        <button class="btn btn-save" onclick="saveEvaluation()">Save Changes</button>
                        <button class="btn btn-close" onclick="closeModal('guardModal')">Close</button>
                    </div>
                </div>
            </div>
        </div>
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