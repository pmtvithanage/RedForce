<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<style>
/* Stat Cards Container */
.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin: 20px;
}

/* Stat Card */
.stat-card {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 15px;
  border-left: 5px solid #ccc;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:nth-child(1) { border-left-color: #3b82f6; } /* Blue */
.stat-card:nth-child(2) { border-left-color: #f59e0b; } /* Orange */
.stat-card:nth-child(3) { border-left-color: #8b5cf6; } /* Purple */
.stat-card:nth-child(4) { border-left-color: #10b981; } /* Green */

.stat-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
}

/* Icons */
.stat-icon {
  font-size: 40px;
  transition: transform 0.3s ease;
  color: #555;
}

.stat-card:nth-child(1) .stat-icon { color: #3b82f6; }
.stat-card:nth-child(2) .stat-icon { color: #f59e0b; }
.stat-card:nth-child(3) .stat-icon { color: #8b5cf6; }
.stat-card:nth-child(4) .stat-icon { color: #10b981; }

.stat-card:hover .stat-icon {
  transform: scale(1.15);
}

/* Stat text */
.stat-content {
  flex: 1;
}

.stat-value {
  font-size: 32px;
  font-weight: bold;
  color: #1f2937;
  line-height: 1;
  margin-bottom: 5px;
}

.stat-label {
  font-size: 14px;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Incidents Table Section */
.incidents-section {
  margin: 20px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  border: 1px solid #e5e7eb;
}

.incidents-header {
  padding: 20px;
  background: linear-gradient(135deg, #a40000, #bd0909);
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.incidents-title {
  font-size: 20px;
  font-weight: bold;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 10px;
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
  background: #f9fafb;
}

.incidents-table th {
  padding: 16px 12px;
  text-align: left;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: 12px;
  color: #374151;
  border-bottom: 2px solid #e5e7eb;
}

.incidents-table td {
  padding: 14px 12px;
  border-bottom: 1px solid #f1f5f9;
  color: #374151;
}

.incidents-table tbody tr {
  transition: background-color 0.3s ease;
}

.incidents-table tbody tr:hover {
  background-color: #f8fafc;
}

.incidents-table tbody tr:last-child td {
  border-bottom: none;
}

/* Status badges */
.status-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-pending {
  background: #fef3c7;
  color: #d97706;
}

.status-in-progress {
  background: #dbeafe;
  color: #2563eb;
}

.status-resolved {
  background: #d1fae5;
  color: #065f46;
}

.status-closed {
  background: #e5e7eb;
  color: #4b5563;
}

/* Priority badges */
.priority-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.priority-low {
  background: #dbeafe;
  color: #1e40af;
}

.priority-medium {
  background: #fef3c7;
  color: #b45309;
}

.priority-high {
  background: #fee2e2;
  color: #991b1b;
}

.priority-critical {
  background: #fecaca;
  color: #7f1d1d;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

/* Action buttons */
.table-action-btn {
  background: #6b7280;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-right: 6px;
}

.table-action-btn:hover {
  background: #4b5563;
  transform: translateY(-1px);
}

.table-action-btn.view {
  background: #a40000;
}

.table-action-btn.view:hover {
  background: #bd0909;
}

.table-action-btn.edit {
  background: #2563eb;
}

.table-action-btn.edit:hover {
  background: #1d4ed8;
}

.table-action-btn.delete {
  background: #ef4444;
}

.table-action-btn.delete:hover {
  background: #dc2626;
}

/* Empty state */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #6b7280;
}

.empty-state .material-symbols-outlined {
  font-size: 64px;
  margin-bottom: 16px;
  opacity: 0.5;
  color: #9ca3af;
}

.empty-state h3 {
  margin: 0 0 8px 0;
  font-size: 18px;
  color: #374151;
}

.empty-state p {
  margin: 0 0 20px 0;
  font-size: 14px;
}
</style>

<!-- Statistics Cards -->
<div class="stats-container">
  <!-- Total Incidents -->
  <div class="stat-card">
    <span class="material-symbols-outlined stat-icon">report</span>
    <div class="stat-content">
      <div class="stat-value">
        <?php echo isset($data['stats']->total) ? $data['stats']->total : 0; ?>
      </div>
      <div class="stat-label">Total Incidents</div>
    </div>
  </div>

  <!-- Pending Incidents -->
  <div class="stat-card">
    <span class="material-symbols-outlined stat-icon">pending_actions</span>
    <div class="stat-content">
      <div class="stat-value">
        <?php echo isset($data['stats']->pending) ? $data['stats']->pending : 0; ?>
      </div>
      <div class="stat-label">Pending</div>
    </div>
  </div>

  <!-- In Progress -->
  <div class="stat-card">
    <span class="material-symbols-outlined stat-icon">engineering</span>
    <div class="stat-content">
      <div class="stat-value">
        <?php echo isset($data['stats']->in_progress) ? $data['stats']->in_progress : 0; ?>
      </div>
      <div class="stat-label">In Progress</div>
    </div>
  </div>

  <!-- Resolved Incidents -->
  <div class="stat-card">
    <span class="material-symbols-outlined stat-icon">task_alt</span>
    <div class="stat-content">
      <div class="stat-value">
        <?php echo isset($data['stats']->resolved) ? $data['stats']->resolved : 0; ?>
      </div>
      <div class="stat-label">Resolved</div>
    </div>
  </div>
</div>

<!-- Incidents Table Section -->
<div class="incidents-section">
  <div class="incidents-header">
    <h2 class="incidents-title">
      <span class="material-symbols-outlined">report_problem</span>
      All Incident Reports
    </h2>
  </div>

  <div class="incidents-table-container">
    <?php if(empty($data['incidents'])): ?>
    <div class="empty-state">
      <span class="material-symbols-outlined">report_off</span>
      <h3>No Incidents Reported</h3>
      <p>There are no incident reports to display at this time.</p>
    </div>
    <?php else: ?>
    <table class="incidents-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Date & Time</th>
          <th>Reporter</th>
          <th>Site</th>
          <th>Type</th>
          <th>Description</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($data['incidents'] as $incident): ?>
        <tr>
          <td><strong>#<?php echo htmlspecialchars($incident->id); ?></strong></td>
          <td><?php echo date('M d, Y h:i A', strtotime($incident->created_at ?? $incident->incident_date . ' ' . $incident->incident_time)); ?></td>
          <td><?php echo htmlspecialchars($incident->officer_name ?? 'N/A'); ?></td>
          <td><?php echo htmlspecialchars($incident->site_name ?? 'N/A'); ?></td>
          <td><?php echo htmlspecialchars($incident->incident_type ?? 'General'); ?></td>
          <td><?php echo htmlspecialchars(substr($incident->incident_description ?? '', 0, 50)) . (strlen($incident->incident_description ?? '') > 50 ? '...' : ''); ?></td>
          <td>
            <span class="priority-badge priority-<?php echo strtolower($incident->priority ?? $incident->severity ?? 'medium'); ?>">
              <?php echo htmlspecialchars($incident->priority ?? $incident->severity ?? 'Medium'); ?>
            </span>
          </td>
          <td>
            <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $incident->status ?? 'pending')); ?>">
              <?php echo htmlspecialchars($incident->status ?? 'Pending'); ?>
            </span>
          </td>
          <td>
            <button class="table-action-btn view" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/viewIncident/<?php echo $incident->id; ?>'">
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

    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>