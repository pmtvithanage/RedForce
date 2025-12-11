<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">


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
  <h3>Pending Leave Requests</h3>
  
  <?php if (!empty($data['pendingLeaves'])): ?>
    <div class="pending-list">
      <?php foreach(array_slice($data['pendingLeaves'], 0, 3) as $leave): ?>
        <div class="pending-item">
          <div class="pending-info">
            <strong><?= htmlspecialchars($leave->employee_name ?? 'N/A') ?></strong>
            <span class="pending-role"><?= htmlspecialchars($leave->employee_role ?? '') ?></span>
            <span class="pending-type"><?= htmlspecialchars($leave->leave_type) ?></span>
            <small><?= date('d/m/Y', strtotime($leave->start_date)) ?> - <?= date('d/m/Y', strtotime($leave->end_date)) ?></small>
          </div>
          <div class="pending-actions">
            <form method="POST" action="<?= URL_ROOT ?>/admin/approveLeave/<?= $leave->id ?>" style="display:inline;">
              <button type="submit" class="approve-btn" title="Approve">
                <span class="material-symbols-outlined">check_circle</span>
              </button>
            </form>
            <button class="reject-btn" onclick="openRejectModal(<?= $leave->id ?>)" title="Reject">
              <span class="material-symbols-outlined">cancel</span>
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="empty-pending">
      <span class="material-symbols-outlined">task_alt</span>
      <p>No pending leave requests.</p>
      <small>Any pending requests will appear here.</small>
    </div>
  <?php endif; ?>
  
  <div class="view-button-container">
    <button class="view-button" id="viewPendingBtn">View All</button>
  </div>
</div>

<!-- Pending Activities Popup -->
<div id="pendingPopup" class="popup-overlay">
  <div class="popup-content pending-details-popup">
    <div class="popup-header">
      <h3>All Pending Leave Requests</h3>
      <span class="close-btn" id="closePendingPopup">&times;</span>
    </div>
    <div class="popup-body">
      <div id="pendingList">
        <?php if (!empty($data['pendingLeaves'])): ?>
          <?php foreach($data['pendingLeaves'] as $leave): ?>
            <div class="leave-detail-card">
              <div class="leave-header">
                <div class="caretaker-info">
                  <h4><?= htmlspecialchars($leave->employee_name ?? 'N/A') ?></h4>
                  <span class="role-badge"><?= htmlspecialchars($leave->employee_role ?? '') ?></span>
                  <span class="email"><?= htmlspecialchars($leave->employee_email ?? '') ?></span>
                </div>
                <span class="status-badge pending">Pending</span>
              </div>
              
              <div class="leave-body">
                <div class="info-row">
                  <span class="label">Leave Type:</span>
                  <span class="value"><?= htmlspecialchars($leave->leave_type) ?></span>
                </div>
                <div class="info-row">
                  <span class="label">Duration:</span>
                  <span class="value">
                    <?= date('d/m/Y', strtotime($leave->start_date)) ?> - 
                    <?= date('d/m/Y', strtotime($leave->end_date)) ?>
                  </span>
                </div>
                <div class="info-row">
                  <span class="label">Reason:</span>
                  <span class="value"><?= htmlspecialchars($leave->reason) ?></span>
                </div>
                <?php if (!empty($leave->proof_file)): ?>
                  <div class="info-row">
                    <span class="label">Proof:</span>
                    <a href="<?= URL_ROOT ?>/<?= $leave->proof_file ?>" target="_blank" class="view-proof">
                      <span class="material-symbols-outlined">attach_file</span> View File
                    </a>
                  </div>
                <?php endif; ?>
                <div class="info-row">
                  <span class="label">Submitted:</span>
                  <span class="value"><?= date('d/m/Y H:i', strtotime($leave->created_at)) ?></span>
                </div>
              </div>
              
              <div class="leave-actions-full">
                <form method="POST" action="<?= URL_ROOT ?>/admin/approveLeave/<?= $leave->id ?>" style="display:inline;">
                  <button type="submit" class="btn-approve-full" onclick="return confirm('Approve this leave request?')">
                    <span class="material-symbols-outlined">check</span> Approve
                  </button>
                </form>
                <button class="btn-reject-full" onclick="openRejectModal(<?= $leave->id ?>)">
                  <span class="material-symbols-outlined">close</span> Reject
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="empty-pending">
            <span class="material-symbols-outlined">task_alt</span>
            <p>No pending leave requests.</p>
            <small>Any pending requests will appear here.</small>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="popup-footer">
      <button class="cancel-btn" id="closePendingFooter">Close</button>
    </div>
  </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="popup-overlay" style="display:none;">
  <div class="popup-content reject-modal">
    <div class="popup-header">
      <h3>Reject Leave Request</h3>
      <span class="close-btn" onclick="closeRejectModal()">&times;</span>
    </div>
    <form id="rejectForm" method="POST" action="">
      <div class="popup-body">
        <label for="reason">Reason for Rejection *</label>
        <textarea id="reason" name="reason" rows="4" required placeholder="Please provide a reason for rejecting this leave request..."></textarea>
      </div>
      <div class="popup-footer">
        <button type="button" class="cancel-btn" onclick="closeRejectModal()">Cancel</button>
        <button type="submit" class="submit-btn reject-confirm">Confirm Rejection</button>
      </div>
    </form>
  </div>
</div>

<script>
function openRejectModal(leaveId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = '<?= URL_ROOT ?>/admin/rejectLeave/' + leaveId;
    modal.style.display = 'flex';
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.style.display = 'none';
    document.getElementById('reason').value = '';
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('rejectModal');
    if (event.target === modal) {
        closeRejectModal();
    }
});
</script>

<script src="script.js"></script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/admin/dashboard.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>