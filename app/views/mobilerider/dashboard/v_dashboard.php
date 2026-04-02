<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_mobilerider_sidebar.php'; ?>
<link rel="stylesheet" href="<?= URL_ROOT ?>/css/admin/dashboard_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  .dashboard {
    width: 90%;
    margin: 40px auto;
    padding: 0;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
  }

  .dashboard .card.stat-card {
    border-left: none;
    padding: 24px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 16px;
    transform: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .dashboard .card.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .dashboard .stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    flex-shrink: 0;
  }

  .dashboard .stat-icon.total {
    background: #e3f2fd;
    color: #1976d2;
  }

  .dashboard .stat-icon.completed {
    background: #e8f5e9;
    color: #2e7d32;
  }

  .dashboard .stat-icon.incidents {
    background: #fff3e0;
    color: #f57c00;
  }

  .dashboard .stat-icon.response {
    background: #ffebee;
    color: #c62828;
  }

  .dashboard .stat-info {
    display: flex;
    flex-direction: column;
  }

  .dashboard .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.1;
  }

  .dashboard .stat-label {
    font-size: 14px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-top: 6px;
  }

  .dashboard .card.section {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    transform: none;
  }

  .dashboard .card.section:hover {
    transform: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  @media (max-width: 768px) {
    .dashboard {
      width: calc(100% - 30px);
      margin: 15px;
      padding: 0;
      grid-template-columns: 1fr;
    }

    .dashboard .card.stat-card {
      padding: 16px;
    }

    .dashboard .stat-icon {
      width: 48px;
      height: 48px;
      font-size: 22px;
    }

    .dashboard .stat-value {
      font-size: 22px;
    }
  }
</style>

<div class="dashboard">
  <!-- Stats -->
  <div class="card stat-card">
    <div class="stat-icon total"><i class="fas fa-route"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $data['stats']['total_sites']; ?></div>
      <div class="stat-label">Total Sites</div>
    </div>
  </div>

  <div class="card stat-card">
    <div class="stat-icon completed"><i class="fas fa-check-circle"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $data['stats']['completed_visits']; ?></div>
      <div class="stat-label">Completed Visits</div>
    </div>
  </div>

  <div class="card stat-card">
    <div class="stat-icon incidents"><i class="fas fa-exclamation-circle"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $data['stats']['incidents']; ?></div>
      <div class="stat-label">Incidents</div>
    </div>
  </div>

  <div class="card stat-card">
    <div class="stat-icon response"><i class="fas fa-clock"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo htmlspecialchars($data['stats']['avg_response_time']); ?></div>
      <div class="stat-label">Avg Response Time</div>
    </div>
  </div>

  <!-- Recent Activity -->
  <div class="card section recent">
    <h3>Recent Activities</h3>

    <?php if (!empty($data['recent_activities'])): ?>
      <div class="activity-list">
        <?php foreach ($data['recent_activities'] as $activity): ?>
          <div class="activity-item <?php echo $activity->activity_type; ?>">
            <div class="activity-header">
              <span class="material-symbols-outlined activity-icon">
                <?php
                $icon_map = [
                  'route' => 'route',
                  'visit' => 'location_on',
                  'incident' => 'report',
                  'patrol' => 'local_police',
                  'task' => 'task_alt',
                  'break' => 'free_breakfast',
                  'message' => 'mail',
                  'shift' => 'schedule'
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
            <?php if (!empty($activity->created_at)): ?>
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
        <small>Your patrol activities will appear here.</small>
      </div>
    <?php endif; ?>
  </div>

  <!-- Advertisements -->
  <?php require_once APP_ROOT . '/views/components/advertisements.php'; ?>

</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>