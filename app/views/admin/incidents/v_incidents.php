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

  .main-content {
    padding: 30px;
    max-width: 1600px;
    margin: 0 40px;
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

  .stat-icon.progress {
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

  .export-btn {
    padding: 12px 20px;
    background: var(--success-color);
    color: white;
    border: none;
    border-radius: var(--radius);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
  }

  .export-btn:hover {
    background: #1b5e20;
    transform: translateY(-1px);
  }

  .payments-table-section {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
  }

  .table-container {
    overflow-x: auto;
  }

  .payments-table {
    width: 100%;
    border-collapse: collapse;
  }

  .payments-table thead {
    background: var(--bg-light);
    border-bottom: 2px solid var(--border-color);
  }

  .payments-table th {
    padding: 16px;
    text-align: left;
    font-size: 13px;
    font-weight: 700;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
  }

  .payments-table td {
    padding: 16px;
    border-bottom: 1px solid var(--border-color);
    font-size: 14px;
    color: #333;
    vertical-align: middle;
  }

  .payments-table tbody tr {
    transition: background 0.2s ease;
  }

  .payments-table tbody tr:hover {
    background: var(--bg-light);
  }

  .incident-id {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: #666;
  }

  .date-cell {
    color: #666;
    font-size: 12px;
    white-space: nowrap;
  }

  .site-name {
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .site-name .fa-map-marker-alt {
    color: var(--primary-color);
    font-size: 12px;
  }

  .incident-type {
    display: inline-block;
    color: #374151;
    font-weight: 500;
    white-space: nowrap;
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
    white-space: nowrap;
  }

  .status.pending {
    background: #fff3e0;
    color: var(--warning-color);
  }

  .status.resolved {
    background: #e8f5e9;
    color: var(--success-color);
  }

  .status.in-progress {
    background: #e3f2fd;
    color: var(--info-color);
  }

  .status.closed {
    background: #edeff3;
    color: #6b7280;
  }

  .priority {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0;
    white-space: nowrap;
  }

  .priority.low {
    background: #e3f2fd;
    color: var(--info-color);
  }

  .priority.medium {
    background: #fff3e0;
    color: var(--warning-color);
  }

  .priority.high,
  .priority.critical {
    background: #ffebee;
    color: var(--danger-color);
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
    color: #fff;
  }

  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #999;
  }

  .empty-state .fa-exclamation-circle {
    font-size: 56px;
    color: #d1d5db;
    margin-bottom: 12px;
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
    margin: 0;
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
      align-items: stretch;
    }

    .search-box,
    .filter-select {
      width: 100%;
    }

    .payments-table {
      font-size: 12px;
    }

    .payments-table th,
    .payments-table td {
      padding: 12px 8px;
    }
  }
</style>

<main class="main-content">
  <div class="page-header">
    <h2>Incidents Dashboard</h2>
    <p>Monitor and manage all incident reports</p>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon total"><i class="fas fa-exclamation-circle"></i></div>
      <div class="stat-info">
        <h3>Total Incidents</h3>
        <div class="stat-value"><?php echo isset($data['stats']->total) ? $data['stats']->total : 0; ?></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon pending"><i class="fas fa-clock"></i></div>
      <div class="stat-info">
        <h3>Pending</h3>
        <div class="stat-value"><?php echo isset($data['stats']->pending) ? $data['stats']->pending : 0; ?></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon progress"><i class="fas fa-spinner"></i></div>
      <div class="stat-info">
        <h3>In Progress</h3>
        <div class="stat-value"><?php echo isset($data['stats']->in_progress) ? $data['stats']->in_progress : 0; ?></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon resolved"><i class="fas fa-check-circle"></i></div>
      <div class="stat-info">
        <h3>Resolved</h3>
        <div class="stat-value"><?php echo isset($data['stats']->resolved) ? $data['stats']->resolved : 0; ?></div>
      </div>
    </div>
  </div>

  <div class="filters-section">
    <div class="filters-bar">
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchIncidents" placeholder="Search by ID, reporter, site, or type...">
      </div>
      <select id="statusFilter" class="filter-select">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="in-progress">In Progress</option>
        <option value="resolved">Resolved</option>
        <option value="closed">Closed</option>
      </select>
      <select id="priorityFilter" class="filter-select">
        <option value="">All Priority</option>
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
        <option value="critical">Critical</option>
      </select>

      <button class="export-btn" onclick="exportIncidents()">
        <i class="fas fa-download"></i>
        Export to CSV
      </button>
    </div>
  </div>

  <div class="payments-table-section">
    <div class="table-container">
      <?php if (empty($data['incidents'])): ?>
        <div class="empty-state">
          <i class="fas fa-exclamation-circle"></i>
          <h3>No Incidents Reported</h3>
          <p>There are no incident reports to display at this time.</p>
        </div>
      <?php else: ?>
        <table class="payments-table" id="incidentsTable">
          <thead>
            <tr>
              <th>Incident #</th>
              <th>Reporter</th>
              <th>Site</th>
              <th>Type</th>
              <th>Date &amp; Time</th>
              <th>Priority</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="incidentsTableBody">
            <?php foreach ($data['incidents'] as $incident): ?>
              <?php
              $statusValue = strtolower(str_replace(' ', '-', $incident->status ?? 'pending'));
              $priorityValue = strtolower($incident->priority ?? $incident->severity ?? 'medium');
              $reporterName = $incident->officer_name ?? 'N/A';
              $siteName = $incident->site_name ?? 'N/A';
              $typeName = $incident->incident_type ?? 'General';
              $dateValue = strtotime($incident->created_at ?? (($incident->incident_date ?? date('Y-m-d')) . ' ' . ($incident->incident_time ?? '00:00:00')));
              ?>
              <tr data-status="<?php echo htmlspecialchars($statusValue); ?>" data-priority="<?php echo htmlspecialchars($priorityValue); ?>">
                <td><span class="incident-id">#<?php echo htmlspecialchars($incident->id); ?></span></td>
                <td><?php echo htmlspecialchars($reporterName); ?></td>
                <td>
                  <span class="site-name">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php echo htmlspecialchars($siteName); ?>
                  </span>
                </td>
                <td><span class="incident-type"><?php echo htmlspecialchars($typeName); ?></span></td>
                <td class="date-cell"><?php echo date('d M Y h:i A', $dateValue); ?></td>
                <td><span class="priority <?php echo htmlspecialchars($priorityValue); ?>"><?php echo strtoupper(htmlspecialchars($priorityValue)); ?></span></td>
                <td><span class="status <?php echo htmlspecialchars($statusValue); ?>"><?php echo strtoupper(htmlspecialchars($incident->status ?? 'Pending')); ?></span></td>
                <td>
                  <div class="actions-cell">
                    <button class="action-btn view" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/viewIncident/<?php echo $incident->id; ?>'">
                      <i class="fas fa-eye"></i> View
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

  <script>
    (function() {
      const searchInput = document.getElementById('searchIncidents');
      const statusFilter = document.getElementById('statusFilter');
      const priorityFilter = document.getElementById('priorityFilter');
      const rows = document.querySelectorAll('#incidentsTableBody tr');

      function applyFilters() {
        const searchValue = (searchInput.value || '').toLowerCase().trim();
        const statusValue = (statusFilter.value || '').toLowerCase();
        const priorityValue = (priorityFilter.value || '').toLowerCase();

        rows.forEach(function(row) {
          const text = row.textContent.toLowerCase();
          const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
          const rowPriority = (row.getAttribute('data-priority') || '').toLowerCase();

          const matchesSearch = !searchValue || text.includes(searchValue);
          const matchesStatus = !statusValue || rowStatus === statusValue;
          const matchesPriority = !priorityValue || rowPriority === priorityValue;

          row.style.display = matchesSearch && matchesStatus && matchesPriority ? '' : 'none';
        });
      }

      if (searchInput) searchInput.addEventListener('input', applyFilters);
      if (statusFilter) statusFilter.addEventListener('change', applyFilters);
      if (priorityFilter) priorityFilter.addEventListener('change', applyFilters);
    })();

    function exportIncidents() {
      const table = document.getElementById('incidentsTable');
      if (!table) {
        return;
      }

      const headers = Array.from(table.querySelectorAll('thead th')).map(function(th) {
        return th.innerText.trim();
      });

      const rows = Array.from(table.querySelectorAll('tbody tr')).filter(function(row) {
        return row.style.display !== 'none';
      }).map(function(row) {
        return Array.from(row.querySelectorAll('td')).map(function(td) {
          return td.innerText.replace(/\s+/g, ' ').trim();
        });
      });

      const csv = [headers].concat(rows).map(function(row) {
        return row.map(function(cell) {
          return '"' + String(cell).replace(/"/g, '""') + '"';
        }).join(',');
      }).join('\n');

      const blob = new Blob([csv], {
        type: 'text/csv;charset=utf-8;'
      });
      const url = URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.download = 'incident-reports.csv';
      link.click();
      URL.revokeObjectURL(url);
    }
  </script>


</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>