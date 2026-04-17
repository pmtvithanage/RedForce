<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_premiseofficer_sidebar.php'; ?>
<link rel="stylesheet" href="<?= URL_ROOT ?>/css/admin/dashboard_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  .dashboard {
    width: 90%;
    margin: 0 40px;
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

  .dashboard .stat-icon.sites {
    background: #e3f2fd;
    color: #1976d2;
  }

  .dashboard .stat-icon.leaves {
    background: #e8f5e9;
    color: #2e7d32;
  }

  .dashboard .stat-icon.shifts {
    background: #fff3e0;
    color: #f57c00;
  }

  .dashboard .stat-icon.notifications {
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
    <div class="stat-icon sites"><i class="fas fa-map-marker-alt"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $data['stats']['sites'] ?? '0'; ?></div>
      <div class="stat-label">Assigned Sites</div>
    </div>
  </div>

  <div class="card stat-card">
    <div class="stat-icon leaves"><i class="fas fa-calendar-times"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $data['stats']['leaves'] ?? '0'; ?></div>
      <div class="stat-label">Pending Leaves</div>
    </div>
  </div>

  <div class="card stat-card">
    <div class="stat-icon shifts"><i class="fas fa-clock"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $data['stats']['shifts'] ?? '0'; ?></div>
      <div class="stat-label">Active Shifts</div>
    </div>
  </div>

  <div class="card stat-card">
    <div class="stat-icon notifications"><i class="fas fa-bell"></i></div>
    <div class="stat-info">
      <div class="stat-value"><?php echo $data['stats']['notifications'] ?? '0'; ?></div>
      <div class="stat-label">Notifications</div>
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