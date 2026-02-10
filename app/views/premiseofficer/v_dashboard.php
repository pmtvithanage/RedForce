<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_premiseofficer_sidebar.php'; ?>
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
}

@media (max-width: 480px) {
  .stat-value {
    font-size: 20px;
  }
  
  .stat-card > div > div:last-child {
    font-size: 12px;
  }
}

/* Badge Styles */
.badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
}

.badge-pending {
  background-color: #fff4e6;
  color: #f39c12;
}

.badge-approved {
  background-color: #e8f5e9;
  color: #27ae60;
}

.badge-rejected {
  background-color: #fee;
  color: #e74c3c;
}

.activity-header {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}
</style>

<div class="dashboard">
<!-- Stats -->
<div class="card stat-card">
  <span class="material-symbols-outlined stat-icon">location_on</span>
  <div>
    <div class="stat-value"><?php echo $data['stats']['sites'] ?? '0'; ?></div>
    <div>Assigned Sites</div>
  </div>
</div>

<div class="card stat-card">
  <span class="material-symbols-outlined stat-icon">event_busy</span>
  <div>
    <div class="stat-value"><?php echo $data['stats']['leaves'] ?? '0'; ?></div>
    <div>Pending Leaves</div>
  </div>
</div>

<div class="card stat-card">
  <span class="material-symbols-outlined stat-icon">schedule</span>
  <div>
    <div class="stat-value"><?php echo $data['stats']['shifts'] ?? '0'; ?></div>
    <div>Active Shifts</div>
  </div>
</div>

<div class="card stat-card">
  <span class="material-symbols-outlined stat-icon">notifications_active</span>
  <div>
    <div class="stat-value"><?php echo $data['stats']['notifications'] ?? '0'; ?></div>
    <div>Notifications</div>
  </div>
</div>



<!-- Upcoming Shifts -->
<div class="card section">
    <h3>Upcoming Shifts</h3>
    
    <?php if (!empty($data['upcoming_shifts'])): ?>
      <div class="activity-list">
        <?php foreach ($data['upcoming_shifts'] as $shift): ?>
          <div class="activity-item shift">
            <div class="activity-header">
              <span class="material-symbols-outlined activity-icon">schedule</span>
              <div class="activity-title">
                <strong><?php echo htmlspecialchars($shift->site_name); ?></strong>
              </div>
            </div>
            <div class="activity-details">
              <p>
                <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">calendar_today</span>
                <?php echo date('M d, Y', strtotime($shift->assignment_start)); ?>
                <?php if (!empty($shift->assignment_end)): ?>
                  - <?php echo date('M d, Y', strtotime($shift->assignment_end)); ?>
                <?php else: ?>
                  - Ongoing
                <?php endif; ?>
              </p>
              <?php if (!empty($shift->site_address)): ?>
                <p>
                  <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">location_on</span>
                  <?php echo htmlspecialchars($shift->site_address); ?>
                </p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-activity">
        <span class="material-symbols-outlined">event_available</span>
        <p>No upcoming shifts scheduled.</p>
        <small>Check your schedule page for more details.</small>
      </div>
    <?php endif; ?>
</div>

<!-- Leave Requests -->
<div class="card section">
    <h3>Recent Leave Requests</h3>
    
    <?php if (!empty($data['leave_requests'])): ?>
      <div class="activity-list">
        <?php foreach ($data['leave_requests'] as $leave): ?>
          <div class="activity-item leave">
            <div class="activity-header">
              <span class="material-symbols-outlined activity-icon">event_busy</span>
              <div class="activity-title">
                <strong><?php echo htmlspecialchars($leave->leave_type); ?></strong>
              </div>
              <span class="badge badge-<?php echo strtolower($leave->status); ?>">
                <?php echo htmlspecialchars($leave->status); ?>
              </span>
            </div>
            <div class="activity-details">
              <p>
                <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">date_range</span>
                <?php echo date('M d, Y', strtotime($leave->start_date)); ?> - 
                <?php echo date('M d, Y', strtotime($leave->end_date)); ?>
              </p>
              <?php if (!empty($leave->reason)): ?>
                <p><?php echo htmlspecialchars(substr($leave->reason, 0, 100)) . (strlen($leave->reason) > 100 ? '...' : ''); ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-activity">
        <span class="material-symbols-outlined">event_available</span>
        <p>No leave requests.</p>
        <small>Your leave requests will appear here.</small>
      </div>
    <?php endif; ?>
</div>

<!-- Recent Activities -->
<div class="card section recent">
    <h3>Recent Activities</h3>
    
    <?php if (!empty($data['recent_activities'])): ?>
      <div class="activity-list">
        <?php foreach ($data['recent_activities'] as $activity): ?>
          <div class="activity-item <?php echo htmlspecialchars($activity->activity_type); ?>">
            <div class="activity-header">
              <span class="material-symbols-outlined activity-icon">
                <?php 
                $icon_map = [
                  'attendance' => 'how_to_reg',
                  'visit' => 'location_on',
                  'incident' => 'report',
                  'patrol' => 'local_police',
                  'task' => 'task_alt',
                  'shift' => 'schedule',
                  'leave' => 'event_busy',
                  'assignment' => 'assignment'
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
        <small>Your work activities will appear here.</small>
      </div>
    <?php endif; ?>
</div>

<!-- Advertisements -->
<?php require_once APP_ROOT . '/views/components/advertisements.php'; ?>

</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
