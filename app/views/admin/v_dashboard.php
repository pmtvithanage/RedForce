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
  <div class="view-button-container">
    <button class="view-button" id="viewActivityBtn">View All</button>
  </div>
</div>
<!-- Recent Activity Popup -->
<div id="activityPopup" class="popup-overlay">
  <div class="popup-content activity-popup">
    <div class="popup-header">
      <h3>Recent Activity</h3>
      <span class="close-btn" id="closeActivityPopup">&times;</span>
    </div>

    <div class="popup-body">
      <ul class="activity-list">
        <div class="empty-activity">
          <span class="material-symbols-outlined">inbox</span>
          <p>No recent activity to display.</p>
          <small>Once there are updates, they will appear here.</small>
        </div>
      </ul>
    </div>

    <div class="popup-footer">
      <button class="cancel-btn" id="closeActivityFooter">Close</button>
    </div>
  </div>
</div>

    <!-- Quick Actions -->
<div class="card section">
  <h3>Quick Actions</h3>
  <div class="quick-actions">
    <button id="assignBtn"><span class="material-symbols-outlined">add</span>Assign</button>
    <button id="alertBtn"><span class="material-symbols-outlined">notifications</span>Send Alert</button>
    <button onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/scheduling'">
      <span class="material-symbols-outlined">event</span>Create Shift
    </button>
    <button onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/officers'">
      <span class="material-symbols-outlined">visibility</span>View Officers
    </button>
  </div>
</div>

<!-- Assign Officer Popup -->
<div id="assignPopup" class="popup-overlay">
  <div class="popup-content assign-popup">
    <div class="popup-header">
      <h3>Assign Officer</h3>
      <span class="close-btn" id="closePopup">&times;</span>
    </div>

    <div class="popup-body">

      <!-- Search Free Officer -->
      <div class="field-row">
        <label>Search Free Officer</label>
        <input type="text" id="officer" placeholder="Search" />
        <button class="small-btn">Select</button>
      </div>

      <!-- Client and Site -->
      <div class="field-row">
        <div class="field-box">
          <label>Client</label>
          <input type="text" id="client" placeholder="Search" />
          <button class="view-btn">View</button>
        </div>
        <div class="field-box">
          <label>Site</label>
          <input type="text" id="site" placeholder="Search" />
          <button class="view-btn">View</button>
        </div>
      </div>

      <!-- Date & Time -->
      <div class="field-row date-time">
        <div>
          <label>Date :</label>
          <input type="date" id="date" />
        </div>
        <div>
          <label>Time :</label>
          <input type="time" id="time" />
        </div>
      </div>

      <!-- Description -->
      <div class="field-row">
        <label>Description</label>
        <textarea id="description"></textarea>
      </div>

    </div>

    <div class="popup-footer">
      <button class="cancel-btn" id="cancelAssign">Cancel</button>
      <button class="submit-btn" id="submitAssign">Assign</button>
    </div>
  </div>
</div>

<!-- Send Alert Popup -->
<div id="alertPopup" class="popup-overlay">
  <div class="popup-content alert-popup">
    <div class="popup-header">
      <h3>Send Alert</h3>
      <span class="close-btn" id="closeAlertPopup">&times;</span>
    </div>

    <div class="popup-body">

      <!-- Client -->
      <div class="field-box">
        <label>Client</label>
        <input type="text" id="alertClient" placeholder="Search" />
        <button class="view-btn">View</button>
      </div>

      <!-- Site -->
      <div class="field-box">
        <label>Site</label>
        <input type="text" id="alertSite" placeholder="Search" />
        <button class="view-btn">View</button>
      </div>

     

      <div class="alert-layout">
        <!-- Left: Roles -->
        <div class="roles-list">
          <label><input type="checkbox" checked /> Premise Officer</label>
          <label><input type="checkbox" /> Supervisor</label>
          <label><input type="checkbox" /> Care-Taker</label>
          <label><input type="checkbox" /> Mobile Rider</label>
        </div>

        <!-- Right: Alert Message -->
        <div class="alert-message">
          <label>Alert</label>
          <textarea id="alertMessage"></textarea>
        </div>
      </div>

    </div>

    <div class="popup-footer">
      <button class="cancel-btn" id="cancelAlert">Cancel</button>
      <button class="submit-btn" id="sendAlert">Send</button>
    </div>
  </div>
</div>

<!-- Messages -->
<div class="card messages section">
  <h3>Messages</h3>
  <div class="empty-messages">
    <span class="material-symbols-outlined">mail_outline</span>
    <p>No messages to display.</p>
    <small>Any new messages will appear here.</small>
  </div>
  <div class="view-button-container">
    <button class="view-button" id="viewMessagesBtn">View All</button>
  </div>
</div>

<!-- Messages Popup -->
<div id="messagesPopup" class="popup-overlay">
  <div class="popup-content messages-popup">
    <div class="popup-header">
      <h3>Messages</h3>
      <span class="close-btn" id="closeMessagesPopup">&times;</span>
    </div>

    <div class="popup-body">
      <ul class="messages-list">
        <div class="empty-messages">
          <span class="material-symbols-outlined">mail_outline</span>
          <p>No messages to display.</p>
          <small>Any new messages will appear here.</small>
        </div>
      </ul>
    </div>

    <div class="popup-footer">
      <button class="cancel-btn" id="closeMessagesFooter">Close</button>
    </div>
  </div>
</div>

    <!-- Pending Activities -->
<div class="card pending section">
  <h3>Pending Activities</h3>
  <div class="empty-pending">
    <span class="material-symbols-outlined">task_alt</span>
    <p>No pending activities.</p>
    <small>Any pending tasks will appear here.</small>
  </div>
  <div class="view-button-container">
    <button class="view-button">View All</button>
  </div>
</div>

<!-- Pending Activities Popup -->
<div id="pendingPopup" class="popup-overlay">
  <div class="popup-content">
    <div class="popup-header">
      <h3>Pending Activities</h3>
      <span class="close-btn" id="closePendingPopup">&times;</span>
    </div>
    <div class="popup-body">
      <div id="pendingList">
        <!-- Example pending items -->
        <div class="empty-pending">
          <span class="material-symbols-outlined">task_alt</span>
          <p>No pending activities.</p>
          <small>Any pending tasks will appear here.</small>
        </div>
        <!-- Add more dynamically from your server -->
      </div>
    </div>
    <div class="popup-footer">
      <button class="cancel-btn" id="closePendingFooter">Close</button>
    </div>
  </div>
</div>

  <script src="script.js"></script>
    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/admin/dashboard.js"></script>
    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>