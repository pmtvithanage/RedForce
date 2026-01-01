<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>
<link rel="stylesheet" href="<?= URL_ROOT ?>/css/admin/dashboard_style.css">

<style>
  /* Recent Activities Styles */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 15px;
}

.activity-item {
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #007bff;
    transition: transform 0.2s ease;
}

.activity-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.activity-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.activity-icon {
    color: #007bff;
    font-size: 20px;
}

.activity-title {
    flex: 1;
}

.activity-title strong {
    display: block;
    color: #333;
    margin-bottom: 3px;
}

.activity-title small {
    color: #666;
    font-size: 12px;
}

.activity-details p {
    margin: 0;
    color: #555;
    font-size: 14px;
    line-height: 1.4;
}

.activity-user {
    margin-top: 8px;
    text-align: right;
}

.activity-user small {
    color: #777;
    font-size: 12px;
}


.alert, .alert .activity-icon {
  border-left-color: #e74c3c; /* Red */
  color: #e74c3c;
}

.shift, .shift .activity-icon {
  border-left-color: #2ecc71; /* Green */
  color: #2ecc71;
}

.leave, .leave .activity-icon {
  border-left-color: #f39c12; /* Orange */
  color: #f39c12;
}

.registration, .registration .activity-icon {
  border-left-color: #3498db; /* Blue */
  color: #3498db;
}

.assignment, .assignment .activity-icon {
  border-left-color: #9b59b6; /* Purple */
  color: #9b59b6;
}

.update, .update .activity-icon {
  border-left-color: #1abc9c; /* Teal */
  color: #1abc9c;
}

.incident, .incident .activity-icon {
  border-left-color: #c0392b; /* Dark Red */
  color: #c0392b;
}

.message, .message .activity-icon {
  border-left-color: #2980b9; /* Dark Blue */
  color: #2980b9;
}

.recent{
  max-height:550px;
  overflow-y: auto;
}
</style>
<!-- Content will be loaded here -->
<div class="dashboard">
<!-- Stats -->
<div class="card stat-card">
  <span class="material-symbols-outlined stat-icon">group</span>
  <div>
    <div class="stat-value">0</div>
    <div>Total Officers</div>
  </div>
</div>

<div class="card stat-card">
  <span class="material-symbols-outlined stat-icon">shield_person</span>
  <div>
    <div class="stat-value">0</div>
    <div>On Duty</div>
  </div>
</div>

<div class="card stat-card">
  <span class="material-symbols-outlined stat-icon">verified_user</span>
  <div>
    <div class="stat-value">0</div>
    <div>Active</div>
  </div>
</div>

<div class="card stat-card">
  <span class="material-symbols-outlined stat-icon">report</span>
  <div>
    <div class="stat-value">0</div>
    <div>Incidents</div>
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
              // Map activity types to icons
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

<!-- Quick Actions -->
<div class="card section">
  <h3>Quick Actions</h3>
  <div class="quick-actions">
    <button id="assignBtn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/assign'"><span class="material-symbols-outlined">add</span>Assign</button>
    <button id="alertBtn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/alerts'"><span class="material-symbols-outlined">notifications</span>Send Alert</button>
    <button onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/scheduling'">
      <span class="material-symbols-outlined">event</span>Create Shift
    </button>
    <button onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/officers'">
      <span class="material-symbols-outlined">visibility</span>View Officers
    </button>
  </div>
</div>


    <!-- Messages -->
    <div class="card messages section">
        <h3>Messages</h3>
        <div class="view-button-container">
            <button class="view-button" id="viewMessagesBtn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/messages'">View</button>

        </div>
    </div>



    <!-- Pending Activities -->
    <div class="card pending section">
        <h3>Pending Leave Requests</h3>
    <div class="view-button-container">
        <button class="view-button" id="viewPendingBtn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/pendings'">View</button>
    </div>
    </div>


</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>


<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>