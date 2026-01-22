<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>


<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">


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
        <select class="filter-select" id="siteFilter">
            <option value="">All Sites</option>
            <option value="Colombo Main Branch">Colombo Main Branch</option>
            <option value="Kurunegala Branch">Kurunegala Branch</option>
            <option value="Galle Branch">Galle Branch</option>
        </select>
    </div>
    
    <!-- Search and Actions -->
    <div class="table-actions">
        <div class="search-container">
            <input type="text" class="search-input" id="searchInput" placeholder="Search guards...">
            <span class="search-icon">
                <span class="material-icons">search</span>
            </span>
        </div>
        <div>
            <span class="guards-count" id="guardsCount">3 guards found</span>
            <button class="export-btn">
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="guardsTableBody">
                <tr class="guard-row">
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
                    <td>
                        <a href="<?php echo URL_ROOT; ?>/client/rateOfficer/RF001" class="secondary-btn">
                            <span class="material-icons">star</span>
                            Rate
                        </a>
                    </td>
                </tr>
                
                <tr class="guard-row">
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
                    <td>
                        <a href="<?php echo URL_ROOT; ?>/client/rateOfficer/RF002" class="secondary-btn">
                            <span class="material-icons">star</span>
                            Rate
                        </a>
                    </td>
                </tr>
                
                <tr class="guard-row">
                    <td>
                        <span class="officer-id">RF003</span>
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
                    <td>
                        <a href="<?php echo URL_ROOT; ?>/client/rateOfficer/RF003" class="secondary-btn">
                            <span class="material-icons">star</span>
                            Rate
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>