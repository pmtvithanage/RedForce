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
            margin-left: 70px;
            margin-top: 10px;
            padding: 30px;
            min-height: calc(100vh - 70px);
        }
        
        .page-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: #333;
        }
        
        .filter-section {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .filter-label {
            font-weight: 500;
            color: #333;
            font-size: 16px;
        }
        
        .filter-select {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            min-width: 200px;
        }
        
        .filter-select:focus {
            outline: none;
            border-color: #d32f2f;
            box-shadow: 0 0 0 2px rgba(211, 47, 47, 0.1);
        }
        
        .guards-table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .guards-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .guards-table th {
            background: #f8f9fa;
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .guards-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        
        .guards-table tr:last-child td {
            border-bottom: none;
        }
        
        .guards-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .officer-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .officer-avatar {
            width: 35px;
            height: 35px;
            background: #d32f2f;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        
        .officer-name {
            font-weight: 500;
            color: #333;
        }
        
        .officer-id {
            font-family: 'Courier New', monospace;
            color: #666;
            font-size: 13px;
            font-weight: 500;
        }
        
        .rank-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            min-width: 50px;
        }
        
        .rank-jso {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .rank-sso {
            background: #f3e5f5;
            color: #7b1fa2;
        }
        
        .rank-lso {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .rank-oic {
            background: #e8f5e8;
            color: #2e7d32;
        }
        
        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            min-width: 80px;
        }
        
        .status-on-duty {
            background: #e8f5e8;
            color: #2e7d32;
        }
        
        .status-off-duty {
            background: #ffebee;
            color: #d32f2f;
        }
        
        .status-on-break {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .site-info {
            color: #333;
            font-size: 13px;
            line-height: 1.4;
        }
        
        .search-container {
            position: relative;
            margin-bottom: 25px;
        }
        
        .search-input {
            width: 100%;
            max-width: 400px;
            padding: 12px 40px 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            background: white;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #d32f2f;
            box-shadow: 0 0 0 2px rgba(211, 47, 47, 0.1);
        }
        
        .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
        }
        
        .table-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .guards-count {
            color: #666;
            font-size: 14px;
        }
        
        .export-btn {
            background: #d32f2f;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .export-btn:hover {
            background: #b71c1c;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s ease;
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 80%;
            max-width: 500px;
            animation: slideIn 0.3s ease;
        }
        
        .modal-header {
            background: #d32f2f;
            color: white;
            padding: 20px;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            font-size: 20px;
            font-weight: 600;
        }
        
        .close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }
        
        .close:hover {
            opacity: 0.8;
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .guard-detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .guard-detail-row:last-child {
            border-bottom: none;
        }
        
        .guard-detail-label {
            font-weight: 600;
            color: #333;
        }
        
        .guard-detail-value {
            color: #666;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .filter-section {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .guards-table-container {
                overflow-x: auto;
            }
            
            .guards-table {
                min-width: 800px;
            }
            
            .table-actions {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>

    <!-- Content will be loaded here -->
    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">View Guards</h1>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <label class="filter-label">Filter by</label>
            <select class="filter-select" id="siteFilter" onchange="filterBySite()">
                <option value="">Site</option>
                <?php foreach($data['sites'] as $site): ?>
                    <option value="<?php echo htmlspecialchars($site); ?>"><?php echo htmlspecialchars($site); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <!-- Search and Actions -->
        <div class="table-actions">
            <div class="search-container">
                <input type="text" class="search-input" id="searchInput" placeholder="Search guards..." onkeyup="searchGuards()">
                <span class="search-icon">🔍</span>
            </div>
            <div>
                <span class="guards-count" id="guardsCount"><?php echo count($data['guards']); ?> guards found</span>
                <button class="export-btn" onclick="exportData()">Export</button>
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
                    <?php foreach($data['guards'] as $guard): ?>
                    <tr class="guard-row" onclick="openGuardModal('<?php echo htmlspecialchars($guard['id']); ?>', '<?php echo htmlspecialchars($guard['name']); ?>', '<?php echo htmlspecialchars($guard['rank']); ?>', '<?php echo htmlspecialchars($guard['status']); ?>', '<?php echo htmlspecialchars($guard['site']); ?>')">
                        <td>
                            <span class="officer-id"><?php echo htmlspecialchars($guard['id']); ?></span>
                        </td>
                        <td>
                            <div class="officer-info">
                                <div class="officer-avatar">
                                    <?php echo strtoupper(substr($guard['name'], 0, 1)); ?>
                                </div>
                                <span class="officer-name"><?php echo htmlspecialchars($guard['name']); ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="rank-badge rank-<?php echo strtolower($guard['rank']); ?>">
                                <?php echo htmlspecialchars($guard['rank']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-<?php echo str_replace(' ', '-', strtolower($guard['status'])); ?>">
                                <?php echo htmlspecialchars($guard['status']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="site-info"><?php echo htmlspecialchars($guard['site']); ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Guard Detail Modal -->
    <div id="guardModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Guard Details</h2>
                <span class="close" onclick="closeModal('guardModal')">&times;</span>
            </div>
            <div class="modal-body">
                <div class="guard-detail-row">
                    <span class="guard-detail-label">Officer ID:</span>
                    <span class="guard-detail-value" id="modalGuardId"></span>
                </div>
                <div class="guard-detail-row">
                    <span class="guard-detail-label">Name:</span>
                    <span class="guard-detail-value" id="modalGuardName"></span>
                </div>
                <div class="guard-detail-row">
                    <span class="guard-detail-label">Rank:</span>
                    <span class="guard-detail-value" id="modalGuardRank"></span>
                </div>
                <div class="guard-detail-row">
                    <span class="guard-detail-label">Current Status:</span>
                    <span class="guard-detail-value" id="modalGuardStatus"></span>
                </div>
                <div class="guard-detail-row">
                    <span class="guard-detail-label">Assigned Site:</span>
                    <span class="guard-detail-value" id="modalGuardSite"></span>
                </div>
                <div class="guard-detail-row">
                    <span class="guard-detail-label">Shift:</span>
                    <span class="guard-detail-value">Day Shift (8:00 AM - 8:00 PM)</span>
                </div>
                <div class="guard-detail-row">
                    <span class="guard-detail-label">Contact:</span>
                    <span class="guard-detail-value">+94 77 123 4567</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store original data for filtering
        const originalData = <?php echo json_encode($data['guards']); ?>;
        let currentData = [...originalData];
        
        function filterBySite() {
            const siteFilter = document.getElementById('siteFilter').value;
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            
            let filteredData = originalData;
            
            // Apply site filter
            if (siteFilter && siteFilter !== '') {
                if (siteFilter === 'All Sites') {
                    filteredData = originalData;
                } else {
                    filteredData = originalData.filter(guard => 
                        guard.site === siteFilter
                    );
                }
            }
            
            // Apply search filter
            if (searchInput) {
                filteredData = filteredData.filter(guard =>
                    guard.name.toLowerCase().includes(searchInput) ||
                    guard.id.toLowerCase().includes(searchInput) ||
                    guard.rank.toLowerCase().includes(searchInput) ||
                    guard.status.toLowerCase().includes(searchInput) ||
                    guard.site.toLowerCase().includes(searchInput)
                );
            }
            
            currentData = filteredData;
            renderTable(currentData);
            updateGuardsCount(currentData.length);
        }
        
        function searchGuards() {
            filterBySite(); // This will apply both filters
        }
        
        function renderTable(data) {
            const tbody = document.getElementById('guardsTableBody');
            tbody.innerHTML = '';
            
            data.forEach(guard => {
                const row = document.createElement('tr');
                row.className = 'guard-row';
                row.onclick = () => openGuardModal(guard.id, guard.name, guard.rank, guard.status, guard.site);
                
                row.innerHTML = `
                    <td>
                        <span class="officer-id">${escapeHtml(guard.id)}</span>
                    </td>
                    <td>
                        <div class="officer-info">
                            <div class="officer-avatar">
                                ${guard.name.charAt(0).toUpperCase()}
                            </div>
                            <span class="officer-name">${escapeHtml(guard.name)}</span>
                        </div>
                    </td>
                    <td>
                        <span class="rank-badge rank-${guard.rank.toLowerCase()}">
                            ${escapeHtml(guard.rank)}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge status-${guard.status.toLowerCase().replace(' ', '-')}">
                            ${escapeHtml(guard.status)}
                        </span>
                    </td>
                    <td>
                        <span class="site-info">${escapeHtml(guard.site)}</span>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }
        
        function updateGuardsCount(count) {
            document.getElementById('guardsCount').textContent = `${count} guards found`;
        }
        
        function openGuardModal(id, name, rank, status, site) {
            document.getElementById('modalGuardId').textContent = id;
            document.getElementById('modalGuardName').textContent = name;
            document.getElementById('modalGuardRank').textContent = rank;
            document.getElementById('modalGuardStatus').textContent = status;
            document.getElementById('modalGuardSite').textContent = site;
            
            document.getElementById('guardModal').style.display = 'block';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        function exportData() {
            // Simple CSV export functionality
            let csv = 'Officer ID,Officer Name,Rank,Status,Site\n';
            
            currentData.forEach(guard => {
                csv += `"${guard.id}","${guard.name}","${guard.rank}","${guard.status}","${guard.site}"\n`;
            });
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'guards_data.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('guardModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>

    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>