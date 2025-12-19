<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>
<link rel="stylesheet" href="<?= URL_ROOT ?>/css/admin/dashboard_style.css">

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
<div class="card section">
  <h3>Recent Activity</h3>
  <div class="empty-activity">
    <span class="material-symbols-outlined">inbox</span>
    <p>No recent activity to display.</p>
    <small>Once there are updates, they will appear here.</small>
  </div>
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