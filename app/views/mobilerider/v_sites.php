<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>


<!-- Content will be loaded here -->

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Dashboard CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/mobilerider/sites.css">

<!-- Dashboard Content -->

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search" id="searchInput">
                <span class="search-icon material-symbols-outlined" >search</span>
            </div>

            <div class="stats-cards">
                <div class="stat-card">
                    <span class="stat-icon material-symbols-outlined">assignment</span>
                    <div class="stat-title">Incident Reports</div>
                </div>

                <div class="stat-card">
                    <span class="stat-icon material-symbols-outlined">apartment</span>
                    <div class="stat-title">Duty Sites</div>
                    <div class="stat-value">6</div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            <table class="data-table" id="sitesTable">
                <thead>
                    <tr>
                        <th></th>
                        <th>Site Name</th>
                        <th>Care Taker</th>
                        <th>Supervisor</th>
                        <th>Region</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-site="People's Leasing HQ" data-caretaker="Nuwan Perera" data-supervisor="Suresh Madushan"
                        data-region="Colombo 07" data-status="active">
                        <td class="row-number">1.</td>
                        <td class="site-name">People's Leasing HQ</td>
                        <td>
                            <div class="person-info">
                                <span class="person-icon material-symbols-outlined" style="color: black;">person</span>
                                <span class="person-name">Nuwan Perera</span>
                            </div>
                        </td>
                        <td>
                            <div class="person-info">
                                <span class="person-icon material-symbols-outlined" style="color: black;" >person</span>
                                <span class="person-name">Suresh Madushan</span>
                            </div>
                        </td>
                        <td class="region">Colombo 07</td>
                        <td><span class="status-badge status-active">Active</span></td>
                    </tr>

                    <tr data-site="Mobitel Office" data-caretaker="Nuwan Perera" data-supervisor="Pradeep Perera"
                        data-region="Colombo 03" data-status="check">
                        <td class="row-number">2.</td>
                        <td class="site-name">Mobitel Office</td>
                        <td>
                            <div class="person-info">
                                <span class="person-icon material-symbols-outlined" style="color: black;">person</span>
                                <span class="person-name">Nuwan Perera</span>
                            </div>
                        </td>
                        <td>
                            <div class="person-info">
                                <span class="person-icon material-symbols-outlined" style="color: black;">person</span>
                                <span class="person-name">Pradeep Perera</span>
                            </div>
                        </td>
                        <td class="region">Colombo 03</td>
                        <td><span class="status-badge status-check">Check</span></td>
                    </tr>

                    <tr data-site="Abans Warehouse" data-caretaker="Chamara Fernando" data-supervisor="Amith Rodrigo"
                        data-region="Malabe" data-status="active">
                        <td class="row-number">3.</td>
                        <td class="site-name">Abans Warehouse</td>
                        <td>
                            <div class="person-info">
                                <span class="person-icon material-symbols-outlined" style="color: black;">person</span>
                                <span class="person-name">Chamara Fernando</span>
                            </div>
                        </td>
                        <td>
                            <div class="person-info">
                                <span class="person-icon material-symbols-outlined" style="color: black;">person</span>
                                <span class="person-name">Amith Rodrigo</span>
                            </div>
                        </td>
                        <td class="region">Malabe</td>
                        <td><span class="status-badge status-active">Active</span></td>
                    </tr>

                    <tr data-site="SLT Data Center" data-caretaker="Ruwan Priyantha" data-supervisor="John Silva"
                        data-region="Malabe" data-status="issue">
                        <td class="row-number">4.</td>
                        <td class="site-name">SLT Data Center</td>
                        <td>
                            <div class="person-info">
                                <span class="person-icon material-symbols-outlined" style="color: black;">person</span>
                                <span class="person-name">Ruwan Priyantha</span>
                            </div>
                        </td>
                        <td>
                            <div class="person-info">
                                <span class="person-icon material-symbols-outlined" style="color: black;">person</span>
                                <span class="person-name">John Silva</span>
                            </div>
                        </td>
                        <td class="region">Malabe</td>
                        <td><span class="status-badge status-issue">Issue</span></td>
                    </tr>

                    <tr data-site="National Hospital Wing B" data-caretaker="Ruwan Priyantha"
                        data-supervisor="Nalaka Jayawardena" data-region="Union Place" data-status="active">
                        <td class="row-number">5.</td>
                        <td class="site-name">National Hospital Wing B</td>
                        <td>
                            <div class="person-info">
                                <span class="person-icon material-symbols-outlined" style="color: black;">person</span>
                                <span class="person-name">Ruwan Priyantha</span>
                            </div>
                        </td>
                        <td>
                            <div class="person-info">
                                <span class="person-icon material-symbols-outlined" style="color: black;">person</span>
                                <span class="person-name">Nalaka Jayawardena</span>
                            </div>
                        </td>
                        <td class="region">Union Place</td>
                        <td><span class="status-badge status-active">Active</span></td>
                    </tr>

                    <tr data-site="Dialog Tower" data-caretaker="Nuwan Perera" data-supervisor="Ruwan Wickrama"
                        data-region="Union Place" data-status="active">
                        <td class="row-number">6.</td>
                        <td class="site-name">Dialog Tower</td>
                        <td>
                            <div class="person-info">
                                <span class="person-icon material-symbols-outlined" style="color: black;">person</span>
                                <span class="person-name">Nuwan Perera</span>
                            </div>
                        </td>
                        <td>
                            <div class="person-info">
                                <span class="person-icon material-symbols-outlined" style="color: black;">person</span>
                                <span class="person-name">Ruwan Wickrama</span>
                            </div>
                        </td>
                        <td class="region">Union Place</td>
                        <td><span class="status-badge status-active">Active</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('sitesTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            Array.from(rows).forEach(row => {
                const site = row.dataset.site.toLowerCase();
                const caretaker = row.dataset.caretaker.toLowerCase();
                const supervisor = row.dataset.supervisor.toLowerCase();
                const region = row.dataset.region.toLowerCase();
                const status = row.dataset.status.toLowerCase();

                const isVisible = site.includes(searchTerm) ||
                    caretaker.includes(searchTerm) ||
                    supervisor.includes(searchTerm) ||
                    region.includes(searchTerm) ||
                    status.includes(searchTerm);

                row.style.display = isVisible ? '' : 'none';
            });
        });

        // Add row click handlers
        Array.from(rows).forEach(row => {
            row.addEventListener('click', function() {
                const siteName = this.dataset.site;
                console.log(`Selected site: ${siteName}`);

                // Add visual feedback
                this.style.backgroundColor = 'rgba(79, 195, 247, 0.1)';
                setTimeout(() => {
                    this.style.backgroundColor = '';
                }, 300);
            });
        });

        // Add hover effects to status badges
        document.querySelectorAll('.status-badge').forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });

            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    </script>
</body>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>