<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  .incidents-page {
    width: 90%;
    margin: 24px auto;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
  }

  .stat-card {
    background: #fff;
    padding: 24px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
    font-size: 24px;
    flex-shrink: 0;
  }

  .stat-icon.total {
    background: #e3f2fd;
    color: #1976d2;
  }

  .stat-icon.pending {
    background: #fff3e0;
    color: #f57c00;
  }

  .stat-icon.progress {
    background: #eef4ff;
    color: #1d4ed8;
  }

  .stat-icon.resolved {
    background: #e8f5e9;
    color: #2e7d32;
  }

  .stat-content {
    display: flex;
    flex-direction: column;
  }

  .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.1;
  }

  .stat-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-top: 6px;
  }

  .incidents-section {
    background: #fff;
    border-radius: 8px;
    border: 1px solid #ececec;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
  }

  .incidents-header {
    padding: 16px 20px;
    background: #fff;
    border-bottom: 1px solid #ececec;
    color: #1a1a1a;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .incidents-title {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .incidents-title i {
    color: #c41212;
  }

  .add-incident-btn {
    background: #c41212;
    color: #fff;
    border: none;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s ease, transform 0.2s ease;
  }

  .add-incident-btn:hover {
    background: #a80f0f;
    transform: translateY(-1px);
  }

  .incidents-table-container {
    overflow-x: auto;
  }

  .incidents-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
  }

  .incidents-table thead {
    background: #f8f9fa;
  }

  .incidents-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    font-size: 12px;
    color: #444;
    border-bottom: 1px solid #ececec;
  }

  .incidents-table td {
    padding: 16px;
    border-bottom: 1px solid #f0f0f0;
    color: #333;
  }

  .incidents-table tbody tr:hover {
    background: #fcfcfc;
  }

  .incidents-table tbody tr:last-child td {
    border-bottom: none;
  }

  .incident-id {
    background: #f2f4f7;
    color: #344054;
    border: 1px solid #e4e7ec;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
  }

  .status-badge,
  .priority-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid transparent;
  }

  .status-pending {
    background: #fff5e8;
    color: #b54708;
    border-color: #ffe2c2;
  }

  .status-in-progress {
    background: #eef4ff;
    color: #1d4ed8;
    border-color: #dbe6ff;
  }

  .status-resolved,
  .status-closed {
    background: #edf7ee;
    color: #1f6f3f;
    border-color: #cce9d3;
  }

  .priority-low {
    background: #eef4ff;
    color: #1d4ed8;
    border-color: #dbe6ff;
  }

  .priority-medium {
    background: #fff5e8;
    color: #b54708;
    border-color: #ffe2c2;
  }

  .priority-high,
  .priority-critical {
    background: #fdecec;
    color: #b42318;
    border-color: #f7cdcd;
  }

  .table-action-btn {
    background: #fff;
    color: #344054;
    border: 1px solid #d0d5dd;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease, border-color 0.2s ease;
  }

  .table-action-btn:hover {
    background: #f2f4f7;
    border-color: #98a2b3;
  }

  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
  }

  .empty-state i {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.45;
    color: #9ca3af;
    display: block;
  }

  .empty-state h3 {
    margin: 0 0 8px 0;
    font-size: 18px;
    color: #374151;
  }

  .empty-state p {
    margin: 0;
    font-size: 14px;
  }

  @media (max-width: 768px) {
    .incidents-page {
      width: 95%;
      margin: 16px auto;
    }

    .incidents-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
    }

    .add-incident-btn {
      width: 100%;
      justify-content: center;
    }

    .incidents-table th,
    .incidents-table td {
      padding: 12px;
    }
  }
</style>

<div class="incidents-page">
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon total"><i class="fa-solid fa-triangle-exclamation"></i></div>
      <div class="stat-content">
        <div class="stat-value"><?php echo isset($data['total_incidents']) ? $data['total_incidents'] : '0'; ?></div>
        <div class="stat-label">Total Incidents</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon pending"><i class="fa-regular fa-clock"></i></div>
      <div class="stat-content">
        <div class="stat-value"><?php echo isset($data['pending_incidents']) ? $data['pending_incidents'] : '0'; ?></div>
        <div class="stat-label">Pending</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon progress"><i class="fa-solid fa-rotate"></i></div>
      <div class="stat-content">
        <div class="stat-value"><?php echo isset($data['inprogress_incidents']) ? $data['inprogress_incidents'] : '0'; ?></div>
        <div class="stat-label">In Progress</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon resolved"><i class="fa-regular fa-circle-check"></i></div>
      <div class="stat-content">
        <div class="stat-value"><?php echo isset($data['resolved_incidents']) ? $data['resolved_incidents'] : '0'; ?></div>
        <div class="stat-label">Resolved</div>
      </div>
    </div>
  </div>

  <div class="incidents-section">
    <div class="incidents-header">
      <h2 class="incidents-title">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Incident Reports
      </h2>
      <button class="add-incident-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/supervisor/createIncident'">
        <i class="fa-solid fa-plus"></i>
        Report Incident
      </button>
    </div>

    <div class="incidents-table-container">
      <?php if (empty($data['incidents'])): ?>
        <div class="empty-state">
          <i class="fa-regular fa-folder-open"></i>
          <h3>No Incidents Reported</h3>
          <p>There are no incident reports to display at this time.</p>
        </div>
      <?php else: ?>
        <table class="incidents-table">
          <thead>
            <tr>
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
                <td><span class="incident-id">#<?php echo htmlspecialchars($incident->id); ?></span></td>
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
                  <button class="table-action-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/supervisor/viewIncident/<?php echo $incident->id; ?>'">
                    View
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
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>