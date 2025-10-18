<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Officers CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/officers.style.css">

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
            <input type="text" class="search-input" id="searchInput" placeholder="Search officers..." onkeyup="searchGuards()">
            <span class="search-icon">
                <span class="material-icons">search</span>
            </span>
        </div>
        <div>
            <span class="guards-count" id="guardsCount">12 officers found</span>
            <button class="export-btn" onclick="exportData()">
                <span class="material-icons" style="font-size: 16px;">download</span>
                Export
            </button>
        </div>
    </div>
    
    <!-- Officers Table -->
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
                    <td><span class="officer-id">RF001</span></td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">J</div>
                            <span class="officer-name">John Silva</span>
                        </div>
                    </td>
                    <td><span class="rank-badge rank-oic">OIC</span></td>
                    <td><span class="status-badge status-on-duty">On Duty</span></td>
                    <td><span class="site-info">Colombo Main Branch</span></td>
                </tr>
                
                <tr class="guard-row">
                    <td><span class="officer-id">RF002</span></td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">S</div>
                            <span class="officer-name">Sarah Perera</span>
                        </div>
                    </td>
                    <td><span class="rank-badge rank-sso">SSO</span></td>
                    <td><span class="status-badge status-off-duty">Off Duty</span></td>
                    <td><span class="site-info">Kurunegala Branch</span></td>
                </tr>
                
                <tr class="guard-row">
                    <td><span class="officer-id">RF003</span></td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">M</div>
                            <span class="officer-name">Michael Fernando</span>
                        </div>
                    </td>
                    <td><span class="rank-badge rank-jso">JSO</span></td>
                    <td><span class="status-badge status-on-break">On Break</span></td>
                    <td><span class="site-info">Nuwara Eliya Branch</span></td>
                </tr>
                
                <tr class="guard-row">
                    <td><span class="officer-id">RF004</span></td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">L</div>
                            <span class="officer-name">Lisa Jayawardene</span>
                        </div>
                    </td>
                    <td><span class="rank-badge rank-lso">LSO</span></td>
                    <td><span class="status-badge status-on-duty">On Duty</span></td>
                    <td><span class="site-info">Galle Branch</span></td>
                </tr>
                
                <tr class="guard-row">
                    <td><span class="officer-id">RF005</span></td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">D</div>
                            <span class="officer-name">David Rathnayake</span>
                        </div>
                    </td>
                    <td><span class="rank-badge rank-oic">OIC</span></td>
                    <td><span class="status-badge status-on-duty">On Duty</span></td>
                    <td><span class="site-info">Kandy Branch</span></td>
                </tr>
                
                <tr class="guard-row">
                    <td><span class="officer-id">RF006</span></td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">A</div>
                            <span class="officer-name">Amanda Wickramasinghe</span>
                        </div>
                    </td>
                    <td><span class="rank-badge rank-sso">SSO</span></td>
                    <td><span class="status-badge status-off-duty">Off Duty</span></td>
                    <td><span class="site-info">Colombo Main Branch</span></td>
                </tr>
                
                <tr class="guard-row">
                    <td><span class="officer-id">RF007</span></td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">P</div>
                            <span class="officer-name">Pradeep Kumara</span>
                        </div>
                    </td>
                    <td><span class="rank-badge rank-jso">JSO</span></td>
                    <td><span class="status-badge status-on-duty">On Duty</span></td>
                    <td><span class="site-info">Kurunegala Branch</span></td>
                </tr>
                
                <tr class="guard-row">
                    <td><span class="officer-id">RF008</span></td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">N</div>
                            <span class="officer-name">Nisha Mendis</span>
                        </div>
                    </td>
                    <td><span class="rank-badge rank-lso">LSO</span></td>
                    <td><span class="status-badge status-on-break">On Break</span></td>
                    <td><span class="site-info">Galle Branch</span></td>
                </tr>
                
                <tr class="guard-row">
                    <td><span class="officer-id">RF009</span></td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">R</div>
                            <span class="officer-name">Ruwan Dissanayake</span>
                        </div>
                    </td>
                    <td><span class="rank-badge rank-jso">JSO</span></td>
                    <td><span class="status-badge status-off-duty">Off Duty</span></td>
                    <td><span class="site-info">Nuwara Eliya Branch</span></td>
                </tr>
                
                <tr class="guard-row">
                    <td><span class="officer-id">RF010</span></td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">C</div>
                            <span class="officer-name">Chamila Rajapakse</span>
                        </div>
                    </td>
                    <td><span class="rank-badge rank-sso">SSO</span></td>
                    <td><span class="status-badge status-on-duty">On Duty</span></td>
                    <td><span class="site-info">Kandy Branch</span></td>
                </tr>
                
                <tr class="guard-row">
                    <td><span class="officer-id">RF011</span></td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">K</div>
                            <span class="officer-name">Kasun Weerasinghe</span>
                        </div>
                    </td>
                    <td><span class="rank-badge rank-lso">LSO</span></td>
                    <td><span class="status-badge status-on-duty">On Duty</span></td>
                    <td><span class="site-info">Colombo Main Branch</span></td>
                </tr>
                
                <tr class="guard-row">
                    <td><span class="officer-id">RF012</span></td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">T</div>
                            <span class="officer-name">Tharaka Gunasekera</span>
                        </div>
                    </td>
                    <td><span class="rank-badge rank-jso">JSO</span></td>
                    <td><span class="status-badge status-on-break">On Break</span></td>
                    <td><span class="site-info">Kurunegala Branch</span></td>
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
<script src="<?php echo URL_ROOT; ?>/js/supervisor/officers.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>