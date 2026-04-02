<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    :root {
        --primary-color: #a40000;
        --primary-light: #c41e1e;
        --success-color: #2e7d32;
        --warning-color: #f57c00;
        --danger-color: #c62828;
        --info-color: #1976d2;
        --bg-light: #f8f9fa;
        --border-color: #e0e0e0;
        --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        --radius: 8px;
    }

    * {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    *::-webkit-scrollbar {
        display: none;
    }

    .main-content {
        padding: 30px;
        max-width: 1600px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .page-header p {
        color: #666;
        font-size: 15px;
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 24px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        flex-shrink: 0;
    }

    .stat-icon.total {
        background: #e3f2fd;
        color: var(--info-color);
    }

    .stat-icon.pending {
        background: #fff3e0;
        color: var(--warning-color);
    }

    .stat-icon.in-progress {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .stat-icon.resolved {
        background: #e8f5e9;
        color: var(--success-color);
    }

    .stat-info h3 {
        margin: 0 0 6px 0;
        font-size: 14px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
    }

    /* Filters Section */
    .filters-section {
        background: white;
        padding: 20px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 24px;
    }

    .filters-bar {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: center;
    }

    .search-box {
        flex: 1;
        min-width: 250px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 12px 12px 12px 44px;
        border: 2px solid var(--border-color);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.1);
    }

    .search-box .fa-search {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }

    .filter-select {
        padding: 12px 16px;
        border: 2px solid var(--border-color);
        border-radius: var(--radius);
        font-size: 14px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 150px;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    /* Incidents Table Section */
    .incidents-table-section {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .table-container {
        overflow-x: auto;
    }

    .incidents-table {
        width: 100%;
        border-collapse: collapse;
    }

    .incidents-table thead {
        background: var(--bg-light);
        border-bottom: 2px solid var(--border-color);
    }

    .incidents-table th {
        padding: 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .incidents-table td {
        padding: 16px;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
        color: #333;
        vertical-align: middle;
    }

    .incidents-table tbody tr {
        transition: background 0.2s ease;
    }

    .incidents-table tbody tr:hover {
        background: var(--bg-light);
    }

    .reporter-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .reporter-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }

    .reporter-details {
        display: flex;
        flex-direction: column;
    }

    .reporter-name {
        font-weight: 600;
        color: #1a1a1a;
        line-height: 1.4;
    }

    .reporter-email {
        font-size: 12px;
        color: #666;
    }

    .site-info {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #333;
    }

    .site-info .fa-map-marker-alt {
        color: var(--primary-color);
    }

    .incident-type {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status.resolved {
        background: #e8f5e9;
        color: var(--success-color);
    }

    .status.pending {
        background: #fff3e0;
        color: var(--warning-color);
    }

    .status.in-progress {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .status .fa {
        font-size: 10px;
    }

    .date-cell {
        color: #666;
        font-size: 13px;
    }

    .priority {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .priority.low {
        background: #e3f2fd;
        color: var(--info-color);
    }

    .priority.medium {
        background: #fff3e0;
        color: var(--warning-color);
    }

    .priority.critical {
        background: #ffebee;
        color: var(--danger-color);
    }

    .incident-id {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: #666;
        background: var(--bg-light);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 13px;
    }

    .actions-cell {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn.view {
        background: #e3f2fd;
        color: var(--info-color);
    }

    .action-btn.view:hover {
        background: var(--info-color);
        color: white;
    }

    .action-btn.edit {
        background: #fff3e0;
        color: var(--warning-color);
    }

    .action-btn.edit:hover {
        background: var(--warning-color);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state .fa-exclamation-circle {
        font-size: 72px;
        color: #ddd;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 20px;
        font-weight: 600;
        color: #666;
        margin: 0 0 8px 0;
    }

    .empty-state p {
        font-size: 14px;
        color: #999;
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 20px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filters-bar {
            flex-direction: column;
        }

        .search-box,
        .filter-select {
            width: 100%;
        }

        .incidents-table {
            font-size: 12px;
        }

        .incidents-table th,
        .incidents-table td {
            padding: 12px 8px;
        }

        .actions-cell {
            flex-direction: column;
        }
    }
</style>

<main class="main-content">
    <!-- Flash Messages -->
    <?php flash('incident_success'); ?>
    <?php flash('incident_error'); ?>

    <!-- Page Header -->
    <div class="page-header">
        <h2>Incidents Management</h2>
        <p>Monitor and manage all incident reports</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-info">
                <h3>Total Incidents</h3>
                <div class="stat-value">10</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>Pending</h3>
                <div class="stat-value">1</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon in-progress">
                <i class="fas fa-spinner"></i>
            </div>
            <div class="stat-info">
                <h3>In Progress</h3>
                <div class="stat-value">3</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon resolved">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3>Resolved</h3>
                <div class="stat-value">6</div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchIncidents" placeholder="Search by incident ID, reporter, or site...">
            </div>

            <select id="statusFilter" class="filter-select">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="in-progress">In Progress</option>
                <option value="resolved">Resolved</option>
            </select>

            <select id="priorityFilter" class="filter-select">
                <option value="">All Priority</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="critical">Critical</option>
            </select>
        </div>
    </div>

    <!-- Incidents Table -->
    <div class="incidents-table-section">
        <div class="table-container">

            <table class="incidents-table" id="incidentsTable">
                <thead>
                    <tr>
                        <th>Incident ID</th>
                        <th>Reporter</th>
                        <th>Site</th>
                        <th>Type</th>
                        <th>Date & Time</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="incidentsTableBody">
                    <tr data-incident-id="16" data-status="pending" data-priority="low">
                        <td><span class="incident-id">#16</span></td>
                        <td>
                            <div class="reporter-info">
                                <div class="reporter-avatar">S</div>
                                <div class="reporter-details">
                                    <span class="reporter-name">Sarah Madushanka</span>
                                    <span class="reporter-email">sarah@example.com</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="site-info">
                                <i class="fas fa-map-marker-alt"></i>
                                Darley Road
                            </div>
                        </td>
                        <td>
                            <span class="incident-type">Fire Alarm</span>
                        </td>
                        <td class="date-cell">Feb 06, 2026 11:50 AM</td>
                        <td>
                            <span class="priority low">LOW</span>
                        </td>
                        <td>
                            <span class="status pending">
                                <i class="fas fa-clock"></i>
                                PENDING
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <button class="action-btn view" title="View Details">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn edit" title="Edit Incident">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr data-incident-id="15" data-status="resolved" data-priority="critical">
                        <td><span class="incident-id">#15</span></td>
                        <td>
                            <div class="reporter-info">
                                <div class="reporter-avatar">S</div>
                                <div class="reporter-details">
                                    <span class="reporter-name">Sarah Madushanka</span>
                                    <span class="reporter-email">sarah@example.com</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="site-info">
                                <i class="fas fa-map-marker-alt"></i>
                                Darley Road
                            </div>
                        </td>
                        <td>
                            <span class="incident-type">Security Breach</span>
                        </td>
                        <td class="date-cell">Feb 01, 2026 07:25 PM</td>
                        <td>
                            <span class="priority critical">CRITICAL</span>
                        </td>
                        <td>
                            <span class="status resolved">
                                <i class="fas fa-check-circle"></i>
                                RESOLVED
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <button class="action-btn view" title="View Details">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn edit" title="Edit Incident">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr data-incident-id="14" data-status="resolved" data-priority="low">
                        <td><span class="incident-id">#14</span></td>
                        <td>
                            <div class="reporter-info">
                                <div class="reporter-avatar">S</div>
                                <div class="reporter-details">
                                    <span class="reporter-name">Sarah Madushanka</span>
                                    <span class="reporter-email">sarah@example.com</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="site-info">
                                <i class="fas fa-map-marker-alt"></i>
                                Darley Road
                            </div>
                        </td>
                        <td>
                            <span class="incident-type">Security Breach</span>
                        </td>
                        <td class="date-cell">Jan 27, 2026 09:42 AM</td>
                        <td>
                            <span class="priority low">LOW</span>
                        </td>
                        <td>
                            <span class="status resolved">
                                <i class="fas fa-check-circle"></i>
                                RESOLVED
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <button class="action-btn view" title="View Details">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn edit" title="Edit Incident">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr data-incident-id="13" data-status="resolved" data-priority="medium">
                        <td><span class="incident-id">#13</span></td>
                        <td>
                            <div class="reporter-info">
                                <div class="reporter-avatar">S</div>
                                <div class="reporter-details">
                                    <span class="reporter-name">Sarah Madushanka</span>
                                    <span class="reporter-email">sarah@example.com</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="site-info">
                                <i class="fas fa-map-marker-alt"></i>
                                Darley Road
                            </div>
                        </td>
                        <td>
                            <span class="incident-type">Security Breach</span>
                        </td>
                        <td class="date-cell">Jan 27, 2026 09:37 AM</td>
                        <td>
                            <span class="priority medium">MEDIUM</span>
                        </td>
                        <td>
                            <span class="status resolved">
                                <i class="fas fa-check-circle"></i>
                                RESOLVED
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <button class="action-btn view" title="View Details">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn edit" title="Edit Incident">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr data-incident-id="12" data-status="resolved" data-priority="critical">
                        <td><span class="incident-id">#12</span></td>
                        <td>
                            <div class="reporter-info">
                                <div class="reporter-avatar">S</div>
                                <div class="reporter-details">
                                    <span class="reporter-name">Sarah Madushanka</span>
                                    <span class="reporter-email">sarah@example.com</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="site-info">
                                <i class="fas fa-map-marker-alt"></i>
                                Darley Road
                            </div>
                        </td>
                        <td>
                            <span class="incident-type">Equipment Malfunction</span>
                        </td>
                        <td class="date-cell">Jan 27, 2026 03:13 AM</td>
                        <td>
                            <span class="priority critical">CRITICAL</span>
                        </td>
                        <td>
                            <span class="status resolved">
                                <i class="fas fa-check-circle"></i>
                                RESOLVED
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <button class="action-btn view" title="View Details">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn edit" title="Edit Incident">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr data-incident-id="11" data-status="resolved" data-priority="medium">
                        <td><span class="incident-id">#11</span></td>
                        <td>
                            <div class="reporter-info">
                                <div class="reporter-avatar">P</div>
                                <div class="reporter-details">
                                    <span class="reporter-name">P.M.T Vithanage</span>
                                    <span class="reporter-email">pmt@example.com</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="site-info">
                                <i class="fas fa-map-marker-alt"></i>
                                National Hospital Galle
                            </div>
                        </td>
                        <td>
                            <span class="incident-type">Unauthorized Access</span>
                        </td>
                        <td class="date-cell">Jan 26, 2026 11:55 AM</td>
                        <td>
                            <span class="priority medium">MEDIUM</span>
                        </td>
                        <td>
                            <span class="status resolved">
                                <i class="fas fa-check-circle"></i>
                                RESOLVED
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <button class="action-btn view" title="View Details">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn edit" title="Edit Incident">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr data-incident-id="10" data-status="in-progress" data-priority="medium">
                        <td><span class="incident-id">#10</span></td>
                        <td>
                            <div class="reporter-info">
                                <div class="reporter-avatar">G</div>
                                <div class="reporter-details">
                                    <span class="reporter-name">Gajanayake</span>
                                    <span class="reporter-email">gajan@example.com</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="site-info">
                                <i class="fas fa-map-marker-alt"></i>
                                Colombo City Centre Mall
                            </div>
                        </td>
                        <td>
                            <span class="incident-type">Theft</span>
                        </td>
                        <td class="date-cell">Jan 26, 2026 11:47 AM</td>
                        <td>
                            <span class="priority medium">MEDIUM</span>
                        </td>
                        <td>
                            <span class="status in-progress">
                                <i class="fas fa-spinner"></i>
                                IN PROGRESS
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <button class="action-btn view" title="View Details">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn edit" title="Edit Incident">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>