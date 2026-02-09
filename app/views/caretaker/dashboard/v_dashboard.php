<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>
<link rel="stylesheet" href="<?= URL_ROOT ?>/css/admin/dashboard_style.css">

<style>
/* Mobile Responsive Styles */
@media (max-width: 768px) {
  .dashboard {
    padding: 10px;
  }
  
  .stat-card {
    padding: 15px;
  }
  
  .stat-icon {
    font-size: 32px;
  }
  
  .stat-value {
    font-size: 24px;
  }
  
  .card.section {
    padding: 15px;
  }
  
  .card.section h3 {
    font-size: 18px;
  }
  
  .activity-item {
    padding: 12px;
  }
  
  .activity-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }
  
  .activity-icon {
    font-size: 20px;
  }
  
  .ad-item {
    flex-direction: column;
  }
  
  .ad-image {
    width: 100%;
  }
  
  .ad-image img {
    width: 100%;
    height: auto;
  }
}

@media (max-width: 480px) {
  .stat-value {
    font-size: 20px;
  }
  
  .stat-card > div > div:last-child {
    font-size: 12px;
  }
}
</style>

<div class="dashboard">
<!-- Stats -->
<div class="card stat-card">
  <span class="material-symbols-outlined stat-icon">group</span>
  <div>
    <div class="stat-value"><?php echo $data['stats']['total_officers']; ?></div>
    <div>Total Officers</div>
  </div>
</div>

<div class="card stat-card">
  <span class="material-symbols-outlined stat-icon">check_circle</span>
  <div>
    <div class="stat-value"><?php echo $data['stats']['on_duty']; ?></div>
    <div>Officers On Duty</div>
  </div>
</div>

<div class="card stat-card">
  <span class="material-symbols-outlined stat-icon">verified</span>
  <div>
    <div class="stat-value"><?php echo $data['stats']['active']; ?></div>
    <div>Active Officers</div>
  </div>
</div>

<div class="card stat-card">
  <span class="material-symbols-outlined stat-icon">report</span>
  <div>
    <div class="stat-value"><?php echo $data['stats']['incidents']; ?></div>
    <div>Incidents</div>
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
                  'attendance' => 'how_to_reg',
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
        <small>Officer attendance activities will appear here.</small>
      </div>
    <?php endif; ?>
  </div>

  <!-- Advertisements -->
  <?php require_once APP_ROOT . '/views/components/advertisements.php'; ?>

</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
