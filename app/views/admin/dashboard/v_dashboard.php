<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>
<link rel="stylesheet" href="<?= URL_ROOT ?>/css/admin/dashboard_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="dashboard">
  <!-- Stats -->
  <div class="card stat-card stat-total">
    <span class="stat-icon"><i class="fas fa-users"></i></span>
    <div class="stat-info">
      <div class="stat-value"><?php echo array_sum($data['userRoleChart']['data']); ?></div>
      <div class="stat-label">Total Users</div>
    </div>
  </div>

  <div class="card stat-card stat-officers">
    <span class="stat-icon"><i class="fas fa-user-shield"></i></span>
    <div class="stat-info">
      <div class="stat-value">
        <?php
        // Count officers
        $officerCount = 0;
        foreach ($data['userRoleChart']['labels'] as $index => $label) {
          if (in_array(strtolower($label), ['premise officer', 'mobile rider', 'caretaker'])) {
            $officerCount += $data['userRoleChart']['data'][$index];
          }
        }
        echo $officerCount;
        ?>
      </div>
      <div class="stat-label">Officers</div>
    </div>
  </div>

  <div class="card stat-card stat-active">
    <span class="stat-icon"><i class="fas fa-user-check"></i></span>
    <div class="stat-info">
      <div class="stat-value"><?php echo (int)($data['incidentStats']['active'] ?? 0); ?></div>
      <div class="stat-label">Active Incidents</div>
    </div>
  </div>

  <div class="card stat-card stat-incidents">
    <span class="stat-icon"><i class="fas fa-circle-exclamation"></i></span>
    <div class="stat-info">
      <div class="stat-value"><?php echo (int)($data['incidentStats']['pending'] ?? 0); ?></div>
      <div class="stat-label">Pending Incidents</div>
    </div>
  </div>



  <!-- Recent Activity -->
  <div class="card section recent">
    <h3>Recent Activity</h3>

    <?php if (!empty($data['recent_activities'])): ?>
      <div class="activity-list">
        <?php foreach ($data['recent_activities'] as $activity): ?>
          <div class="activity-item <?php echo $activity->activity_type; ?>">
            <div class="activity-header">
              <span class="material-symbols-outlined activity-icon">
                <?php
                $icon_map = [
                  'shift' => 'schedule',
                  'leave' => 'event_busy',
                  'alert' => 'notification_important',
                  'assignment' => 'assignment',
                  'registration' => 'person_add',
                  'update' => 'edit',
                  'incident' => 'report',
                  'message' => 'mail'
                ];
                echo $icon_map[$activity->activity_type] ?? 'notifications';
                ?>
              </span>
              <div class="activity-title">
                <strong><?php echo htmlspecialchars($activity->activity_titel); ?></strong>
              </div>
            </div>
            <div class="activity-details">
              <p><?php echo htmlspecialchars($activity->activity_details); ?></p>
            </div>
            <?php if (!empty($activity->user_name)): ?>
              <div class="activity-user">
                <small><?php echo date('M d, Y   |   h:i A', strtotime($activity->created_at)); ?></small>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-activity">
        <span class="material-symbols-outlined">inbox</span>
        <p>No recent activity to display.</p>
        <small>Once there are updates, they will appear here.</small>
      </div>
    <?php endif; ?>
  </div>

  <!-- User Role Chart -->
  <div class="card section chart-section">
    <h3>User Role Distribution</h3>
    <div class="chart-wrapper">
      <canvas id="userRoleChart"></canvas>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="card section" hidden>
    <h3>Quick Actions</h3>
    <div class="quick-actions">
      <button id="assignBtn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/assign'">
        <span class="material-symbols-outlined">add</span>Assign
      </button>
      <button id="alertBtn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/alerts'">
        <span class="material-symbols-outlined">notifications</span>Send Alert
      </button>
      <button onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/scheduling'">
        <span class="material-symbols-outlined">event</span>Create Shift
      </button>
      <button onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/officers'">
        <span class="material-symbols-outlined">visibility</span>View Officers
      </button>
    </div>
  </div>

  <!-- Messages & Pending Activities -->
  <div class="card messages section">
    <h3>Messages</h3>
    <div class="view-button-container">
      <button class="view-button" id="viewMessagesBtn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/messages'">View</button>
    </div>
  </div>

  <div class="card pending section">
    <h3>Pending Leave Requests</h3>
    <div class="view-button-container">
      <button class="view-button" id="viewPendingBtn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/pendings'">View</button>
    </div>
  </div>

</div>

<script>
  // Initialize User Role Chart
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('userRoleChart').getContext('2d');

    const chartData = {
      labels: <?php echo json_encode($data['userRoleChart']['labels']); ?>,
      datasets: [{
        data: <?php echo json_encode($data['userRoleChart']['data']); ?>,
        backgroundColor: <?php echo json_encode($data['userRoleChart']['colors']); ?>,
        borderWidth: 1
      }]
    };

    new Chart(ctx, {
      type: 'doughnut',
      data: chartData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right'
          }
        }
      }
    });
  });
</script>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>