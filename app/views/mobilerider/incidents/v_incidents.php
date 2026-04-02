<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  :root {
    --primary-color: #a40000;
    --success-color: #2e7d32;
    --warning-color: #f57c00;
    --danger-color: #c62828;
    --info-color: #1976d2;
    --bg-light: #f8f9fa;
    --border-color: #e0e0e0;
    --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    --radius: 8px;
  }

  .content-wrap {
    width: 90%;
    margin: 30px auto;
  }

  .page-header {
    margin-bottom: 24px;
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
    min-width: 150px;
  }

  .top-actions {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 16px;
  }

  .add-incident-btn {
    padding: 12px 20px;
    background: var(--primary-color);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
  }

  .stat-card {
    background: #fff;
    padding: 24px;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 16px;
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
    background: #f3e8ff;
    color: #7c3aed;
  }

  .stat-icon.resolved {
    background: #e8f5e9;
    color: var(--success-color);
  }

  .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.1;
  }

  .stat-label {
    margin-top: 6px;
    font-size: 14px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
  }

  .incidents-section {
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
  }

  .incidents-table-container {
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

  .incidents-table tbody tr:hover {
    background: var(--bg-light);
  }

  .status-badge,
  .priority-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .status-pending {
    background: #fff3e0;
    color: var(--warning-color);
  }

  .status-in-progress {
    background: #e3f2fd;
    color: var(--info-color);
  }

  .status-resolved {
    background: #e8f5e9;
    color: var(--success-color);
  }

  .status-closed {
    background: #e5e7eb;
    color: #4b5563;
  }

  .priority-low {
    background: #e3f2fd;
    color: var(--info-color);
  }

  .priority-medium {
    background: #fff3e0;
    color: var(--warning-color);
  }

  .priority-high,
  .priority-critical {
    background: #ffebee;
    color: var(--danger-color);
  }

  .table-action-btn {
    padding: 8px 12px;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e3f2fd;
    color: var(--info-color);
  }

  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #999;
  }

  .empty-state .material-symbols-outlined {
    font-size: 64px;
    color: #ddd;
    margin-bottom: 16px;
  }

  .empty-state h3 {
    margin: 0 0 8px 0;
    color: #666;
  }

  @media (max-width: 768px) {
    .content-wrap {
      width: calc(100% - 30px);
      margin: 15px;
    }

    .stats-container {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="content-wrap">
  <div class="top-actions">

  </div>

  <div class="page-header">
    <h2>Incident Reports Management</h2>
    <p>Monitor and manage all incident reports</p>
  </div>

  <div class="filters-section">
    <div class="filters-bar">
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchIncidents" placeholder="Search by incident ID, site, or type...">
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
        <option value="high">High</option>
        <option value="critical">Critical</option>
      </select>

      <button class="add-incident-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/MobileRider/createIncident'">
        <i class="fas fa-plus"></i>
        Report Incident
      </button>
    </div>
  </div>

  <div class="stats-container">
    <div class="stat-card">
      <div class="stat-icon total"><i class="fas fa-clipboard-list"></i></div>
      <div class="stat-content">
        <div class="stat-value"><?php echo isset($data['total_incidents']) ? $data['total_incidents'] : '0'; ?></div>
        <div class="stat-label">Total Incidents</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon pending"><i class="fas fa-clock"></i></div>
      <div class="stat-content">
        <div class="stat-value"><?php echo isset($data['pending_incidents']) ? $data['pending_incidents'] : '0'; ?></div>
        <div class="stat-label">Pending</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon progress"><i class="fas fa-spinner"></i></div>
      <div class="stat-content">
        <div class="stat-value"><?php echo isset($data['inprogress_incidents']) ? $data['inprogress_incidents'] : '0'; ?></div>
        <div class="stat-label">In Progress</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon resolved"><i class="fas fa-check-circle"></i></div>
      <div class="stat-content">
        <div class="stat-value"><?php echo isset($data['resolved_incidents']) ? $data['resolved_incidents'] : '0'; ?></div>
        <div class="stat-label">Resolved</div>
      </div>
    </div>
  </div>

  <!-- Incidents Table Section -->
  <div class="incidents-section">

    <div class="incidents-table-container">
      <?php if (empty($data['incidents'])): ?>
        <div class="empty-state">
          <span class="material-symbols-outlined">report_off</span>
          <h3>No Incidents Reported</h3>
          <p>There are no incident reports to display at this time.</p>
        </div>
      <?php else: ?>
        <table class="incidents-table">
          <thead>
            <tr data-status="<?php echo strtolower(str_replace(' ', '-', $incident->status ?? 'pending')); ?>" data-priority="<?php echo strtolower($incident->priority ?? 'medium'); ?>">
              <th>ID</th>
              <th>Date & Time</th>
              <th>Site</th>
              <th>Type</th>
              <th>Priority</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data['incidents'] as $incident): ?>
              <tr>
                <td><strong>#<?php echo htmlspecialchars($incident->id); ?></strong></td>
                <td><?php echo date('M d, Y h:i A', strtotime($incident->created_at)); ?></td>
                <td><?php echo htmlspecialchars($incident->site_name ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($incident->incident_type ?? 'General'); ?></td>
                <td>
                  <span class="priority-badge priority-<?php echo strtolower($incident->priority ?? 'medium'); ?>">
                    <?php echo htmlspecialchars($incident->priority ?? 'Medium'); ?>
                  </span>
                </td>
                <td>
                  <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $incident->status ?? 'pending')); ?>">
                    <?php echo htmlspecialchars($incident->status ?? 'Pending'); ?>
                  </span>
                </td>
                <td>
                  <button class="table-action-btn view" onclick="window.location.href='<?php echo URL_ROOT; ?>/MobileRider/viewIncident/<?php echo $incident->id; ?>'">
                    <i class="fas fa-eye"></i> View
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>


</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script>
  (function() {
    const searchInput = document.getElementById('searchIncidents');
    const statusFilter = document.getElementById('statusFilter');
    const priorityFilter = document.getElementById('priorityFilter');
    const rows = document.querySelectorAll('.incidents-table tbody tr');

    function applyFilters() {
      const q = (searchInput?.value || '').toLowerCase().trim();
      const status = (statusFilter?.value || '').toLowerCase();
      const priority = (priorityFilter?.value || '').toLowerCase();

      rows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
        const rowPriority = (row.getAttribute('data-priority') || '').toLowerCase();

        const okSearch = !q || text.includes(q);
        const okStatus = !status || rowStatus === status;
        const okPriority = !priority || rowPriority === priority;

        row.style.display = okSearch && okStatus && okPriority ? '' : 'none';
      });
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (priorityFilter) priorityFilter.addEventListener('change', applyFilters);
  })();
</script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>