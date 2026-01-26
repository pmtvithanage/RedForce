<?php 
// Dummy data for testing (remove when controller provides real data)
if (!isset($data['incidents']) || empty($data['incidents'])) {
  $data['incidents'] = [
    (object)[
      'id' => 1,
      'created_at' => '2026-01-26 08:30:00',
      'site_name' => 'Central Bank Tower',
      'incident_type' => 'Security Breach',
      'description' => 'Unauthorized person attempted to enter restricted area. Security officer intervened and person was escorted out.',
      'priority' => 'High',
      'status' => 'In Progress'
    ],
    (object)[
      'id' => 2,
      'created_at' => '2026-01-26 10:15:00',
      'site_name' => 'Shopping Mall Downtown',
      'incident_type' => 'Equipment Malfunction',
      'description' => 'CCTV camera #3 stopped working. Technician has been notified.',
      'priority' => 'Medium',
      'status' => 'Pending'
    ],
    (object)[
      'id' => 3,
      'created_at' => '2026-01-25 14:45:00',
      'site_name' => 'Office Complex A',
      'incident_type' => 'Medical Emergency',
      'description' => 'Employee experienced chest pain. Ambulance called and person transported to hospital.',
      'priority' => 'Critical',
      'status' => 'Resolved'
    ],
    (object)[
      'id' => 4,
      'created_at' => '2026-01-25 09:20:00',
      'site_name' => 'Warehouse District',
      'incident_type' => 'Fire Alarm',
      'description' => 'Fire alarm triggered in Sector B. Investigation revealed false alarm due to dust.',
      'priority' => 'High',
      'status' => 'Resolved'
    ],
    (object)[
      'id' => 5,
      'created_at' => '2026-01-24 16:30:00',
      'site_name' => 'Residential Complex',
      'incident_type' => 'Vandalism',
      'description' => 'Graffiti found on exterior wall. Cleaning crew scheduled.',
      'priority' => 'Low',
      'status' => 'Pending'
    ],
    (object)[
      'id' => 6,
      'created_at' => '2026-01-24 11:00:00',
      'site_name' => 'Tech Park Building',
      'incident_type' => 'Suspicious Activity',
      'description' => 'Unidentified vehicle parked in restricted area for extended period. Vehicle owner contacted and moved.',
      'priority' => 'Medium',
      'status' => 'Closed'
    ]
  ];
}

if (!isset($data['total_incidents'])) $data['total_incidents'] = count($data['incidents']);
if (!isset($data['pending_incidents'])) $data['pending_incidents'] = 2;
if (!isset($data['inprogress_incidents'])) $data['inprogress_incidents'] = 1;
if (!isset($data['resolved_incidents'])) $data['resolved_incidents'] = 3;
?>
<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>

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

.stat-card:nth-child(1) { border-left-color: #9333ea; } /* Purple */
.stat-card:nth-child(2) { border-left-color: #22c55e; } /* Green */
.stat-card:nth-child(3) { border-left-color: #f59e0b; } /* Gold */
.stat-card:nth-child(4) { border-left-color: #ef4444; } /* Red */

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

.stat-card:nth-child(1) .stat-icon { color: #9333ea; }
.stat-card:nth-child(2) .stat-icon { color: #22c55e; }
.stat-card:nth-child(3) .stat-icon { color: #f59e0b; }
.stat-card:nth-child(4) .stat-icon { color: #ef4444; }

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

.add-incident-btn {
  background: white;
  color: #a40000;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: all 0.3s ease;
}

.add-incident-btn:hover {
  background: #f3f4f6;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
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

<div class="stats-container">
  <div class="stat-card">
    <span class="material-symbols-outlined stat-icon">assessment</span>
    <div class="stat-content">
      <div class="stat-value"><?php echo isset($data['total_incidents']) ? $data['total_incidents'] : '0'; ?></div>
      <div class="stat-label">Total Incidents</div>
    </div>
  </div>

  <div class="stat-card">
    <span class="material-symbols-outlined stat-icon">schedule</span>
    <div class="stat-content">
      <div class="stat-value"><?php echo isset($data['pending_incidents']) ? $data['pending_incidents'] : '0'; ?></div>
      <div class="stat-label">Pending</div>
    </div>
  </div>

  <div class="stat-card">
    <span class="material-symbols-outlined stat-icon">sync</span>
    <div class="stat-content">
      <div class="stat-value"><?php echo isset($data['inprogress_incidents']) ? $data['inprogress_incidents'] : '0'; ?></div>
      <div class="stat-label">In Progress</div>
    </div>
  </div>

  <div class="stat-card">
    <span class="material-symbols-outlined stat-icon">check_circle</span>
    <div class="stat-content">
      <div class="stat-value"><?php echo isset($data['resolved_incidents']) ? $data['resolved_incidents'] : '0'; ?></div>
      <div class="stat-label">Resolved</div>
    </div>
  </div>
</div>

<!-- Incidents Table Section -->
<div class="incidents-section">
  <div class="incidents-header">
    <h2 class="incidents-title">
      <span class="material-symbols-outlined">report_problem</span>
      Incident Reports
    </h2>
    <button class="add-incident-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/MobileRider/createIncident'">
      <span class="material-symbols-outlined">add</span>
      Report Incident
    </button>
  </div>

  <div class="incidents-table-container">
    <?php if(empty($data['incidents'])): ?>
    <div class="empty-state">
      <span class="material-symbols-outlined">report_off</span>
      <h3>No Incidents Reported</h3>
      <p>There are no incident reports to display at this time.</p>
      <button class="add-incident-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/mobilerider/addincident'">
        <span class="material-symbols-outlined">add</span>
        Report First Incident
      </button>
    </div>
    <?php else: ?>
    <table class="incidents-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Date & Time</th>
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
          <td><?php echo date('M d, Y h:i A', strtotime($incident->created_at)); ?></td>
          <td><?php echo htmlspecialchars($incident->site_name ?? 'N/A'); ?></td>
          <td><?php echo htmlspecialchars($incident->incident_type ?? 'General'); ?></td>
          <td><?php echo htmlspecialchars(substr($incident->description ?? '', 0, 50)) . (strlen($incident->description ?? '') > 50 ? '...' : ''); ?></td>
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