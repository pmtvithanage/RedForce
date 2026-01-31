<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/components/flash_msg.css">
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/client_requests_style.css">

<div class="main-content">
  <div class="page-header">
    
    <a href="<?php echo URL_ROOT; ?>/admin/clients" class="back-btn">
      <span class="material-symbols-outlined">arrow_back</span> Back to Clients
    </a>
  </div>

  <!-- Stats Cards -->
  <div class="stats-container">
    <div class="stat-card">
      <div class="stat-icon pending">
        <span class="material-symbols-outlined">schedule</span>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?php echo ($data['packageStats']->pending ?? 0); ?></div>
        <div class="stat-label">Pending</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon approved">
        <span class="material-symbols-outlined">check_circle</span>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?php echo ($data['packageStats']->approved ?? 0); ?></div>
        <div class="stat-label">Approved</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon rejected">
        <span class="material-symbols-outlined">cancel</span>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?php echo ($data['packageStats']->rejected ?? 0); ?></div>
        <div class="stat-label">Rejected</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon total">
        <span class="material-symbols-outlined">list_alt</span>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?php echo ($data['packageStats']->total ?? 0); ?></div>
        <div class="stat-label">Total Requests</div>
      </div>
    </div>
  </div>

  <!-- Service Requests List -->
  <div class="requests-container">
    <?php if (empty($data['serviceRequests']) && empty($data['packageRequests'])): ?>
      <div class="no-requests">
        <span class="material-symbols-outlined">inbox</span>
        <p>No service requests found</p>
      </div>
    <?php else: ?>
      
      <!-- PACKAGE REQUESTS (NEW SYSTEM) -->
      <?php if (!empty($data['packageRequests'])): ?>
        <?php foreach ($data['packageRequests'] as $request): ?>
          <div class="request-card">
            <div class="request-header">
              <div class="request-title-section">
                <h3>📦 <?php echo htmlspecialchars($request->package_name); ?></h3>
                <span class="status-badge status-<?php echo strtolower($request->status); ?>">
                  <?php echo strtoupper($request->status); ?>
                </span>
              </div>
              <div class="request-client-info">
                <span class="material-symbols-outlined">person</span>
                <span><?php echo htmlspecialchars($request->client_name); ?></span>
              </div>
            </div>

            <div class="request-body">
              <div class="request-detail">
                <span class="detail-label">Site Name:</span>
                <span class="detail-value"><?php echo htmlspecialchars($request->site_name ?? 'N/A'); ?></span>
              </div>

              <div class="request-detail">
                <span class="detail-label">City:</span>
                <span class="detail-value"><?php echo htmlspecialchars($request->city ?? 'N/A'); ?></span>
              </div>

              <div class="request-detail">
                <span class="detail-label">Site Address:</span>
                <span class="detail-value"><?php echo htmlspecialchars($request->site_address); ?></span>
              </div>

              <div class="request-detail">
                <span class="detail-label">Service Period:</span>
                <span class="detail-value">
                  <?php echo date('M d, Y', strtotime($request->start_date)); ?> to 
                  <?php echo date('M d, Y', strtotime($request->end_date)); ?>
                </span>
              </div>

              <div class="request-detail">
                <span class="detail-label">Number of Guards:</span>
                <span class="detail-value">
                  <?php echo $request->number_of_guards; ?> Guard<?php echo $request->number_of_guards != 1 ? 's' : ''; ?>
                  <?php if ($request->day_guards !== null && $request->night_guards !== null): ?>
                    <br><small style="color: #666;">(<?php echo $request->day_guards; ?> Day, <?php echo $request->night_guards; ?> Night)</small>
                  <?php endif; ?>
                </span>
              </div>

              <div class="request-detail">
                <span class="detail-label">Monthly Price:</span>
                <span class="detail-value" style="color: #a40000; font-weight: 700;">
                  LKR <?php echo number_format($request->package_price, 2); ?>/=
                </span>
              </div>

              <?php if (!empty($request->comments)): ?>
              <div class="request-detail">
                <span class="detail-label">Comments:</span>
                <span class="detail-value"><?php echo htmlspecialchars($request->comments); ?></span>
              </div>
              <?php endif; ?>

              <div class="request-detail">
                <span class="detail-label">Submitted:</span>
                <span class="detail-value"><?php echo date('M d, Y h:i A', strtotime($request->submitted_date)); ?></span>
              </div>
            </div>

            <?php if ($request->admin_notes && $request->status !== 'Pending'): ?>
              <div style="background: <?php echo $request->status === 'Approved' ? '#d4edda' : '#f8d7da'; ?>; border-left: 4px solid <?php echo $request->status === 'Approved' ? '#28a745' : '#dc3545'; ?>; padding: 15px; margin-top: 15px; border-radius: 4px;">
                <strong style="display: block; margin-bottom: 8px; color: #333; font-size: 13px;">
                  <?php echo $request->status === 'Approved' ? 'Admin Response:' : 'Rejection Reason:'; ?>
                </strong>
                <p style="margin: 0; font-size: 14px; color: #555;"><?php echo htmlspecialchars($request->admin_notes); ?></p>
              </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <?php if ($request->status === 'Pending'): ?>
            <div class="request-actions">
              <button type="button" class="action-btn review-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/reviewPackageRequest/<?php echo $request->id; ?>'">
                Review Request
              </button>
            </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <!-- OLD SERVICE REQUESTS (LEGACY SYSTEM) -->
      <?php if (!empty($data['serviceRequests'])): ?>
        <?php foreach ($data['serviceRequests'] as $request): ?>
        <div class="request-card">
          <div class="request-header">
            <div class="request-title-section">
              <h3><?php echo htmlspecialchars($request->event_name); ?></h3>
              <span class="status-badge status-<?php echo strtolower($request->status); ?>">
                <?php echo strtoupper($request->status); ?>
              </span>
            </div>
            <div class="request-client-info">
              <span class="material-symbols-outlined">person</span>
              <span><?php echo htmlspecialchars($request->client_name); ?></span>
            </div>
          </div>

          <div class="request-body">
            <div class="request-detail">
              <span class="detail-label">Description:</span>
              <span class="detail-value"><?php echo htmlspecialchars($request->event_description); ?></span>
            </div>

            <div class="request-detail">
              <span class="detail-label">Date:</span>
              <span class="detail-value">
                <?php echo date('M d, Y', strtotime($request->start_date)); ?> to 
                <?php echo date('M d, Y', strtotime($request->end_date)); ?>
              </span>
            </div>

            <div class="request-detail">
              <span class="detail-label">Time:</span>
              <span class="detail-value">
                <?php echo date('h:i A', strtotime($request->start_time)); ?> - 
                <?php echo date('h:i A', strtotime($request->end_time)); ?>
              </span>
            </div>

            <div class="request-detail">
              <span class="detail-label">Location:</span>
              <span class="detail-value"><?php echo htmlspecialchars($request->location); ?></span>
            </div>

            <div class="request-detail">
              <span class="detail-label">Number of Guards:</span>
              <span class="detail-value"><?php echo $request->number_of_guards ?? $request->guard_count ?? 'N/A'; ?> Guards</span>
            </div>

            <div class="request-detail">
              <span class="detail-label">Client Email:</span>
              <span class="detail-value"><?php echo htmlspecialchars($request->client_email); ?></span>
            </div>

            <?php if (!empty($request->comments)): ?>
            <div class="request-detail">
              <span class="detail-label">Comments:</span>
              <span class="detail-value"><?php echo htmlspecialchars($request->comments); ?></span>
            </div>
            <?php endif; ?>

            <div class="request-detail">
              <span class="detail-label">Submitted:</span>
              <span class="detail-value"><?php echo date('M d, Y h:i A', strtotime($request->submitted_date)); ?></span>
            </div>
          </div>

          <!-- Action Buttons (only show for Pending requests) -->
          <?php if ($request->status === 'Pending'): ?>
          <div class="request-actions">
            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to approve this request?');">
              <input type="hidden" name="request_id" value="<?php echo $request->id; ?>">
              <button type="submit" name="approve_request" class="action-btn approve-btn">
                <span class="material-symbols-outlined">check_circle</span>
                Approve
              </button>
            </form>

            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to reject this request?');">
              <input type="hidden" name="request_id" value="<?php echo $request->id; ?>">
              <button type="submit" name="reject_request" class="action-btn reject-btn">
                <span class="material-symbols-outlined">cancel</span>
                Reject
              </button>
            </form>
          </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<!-- Approve Modal for Package Requests -->
<div id="approveModal" style="display:none;position:fixed;z-index:1000;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:12px;padding:0;max-width:500px;width:90%;box-shadow:0 4px 20px rgba(0,0,0,0.2);">
    <div style="display:flex;justify-content:space-between;align-items:center;padding:20px 25px;border-bottom:1px solid #e0e0e0;">
      <h3 style="margin:0;font-size:20px;color:#333;">Approve Package Request</h3>
      <span style="font-size:28px;font-weight:bold;color:#aaa;cursor:pointer;" onclick="closeApproveModal()">&times;</span>
    </div>
    <form method="POST" action="<?php echo URL_ROOT; ?>/admin/clientRequests" style="padding:25px;">
      <input type="hidden" name="request_id" id="approve_request_id">
      <input type="hidden" name="approve_package_request" value="1">
      <p style="margin:0 0 20px 0;font-size:15px;color:#555;">Approve <strong id="approve_package_name"></strong>?</p>
      <div style="margin-bottom:20px;">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:14px;">Admin Notes (Optional):</label>
        <textarea name="admin_notes" id="admin_notes_approve" rows="3" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-family:inherit;font-size:14px;resize:vertical;"></textarea>
      </div>
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:25px;">
        <button type="button" class="secondary-btn" onclick="closeApproveModal()">Cancel</button>
        <button type="submit" class="primary-btn">Approve</button>
      </div>
    </form>
  </div>
</div>

<!-- Reject Modal for Package Requests -->
<div id="rejectModal" style="display:none;position:fixed;z-index:1000;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:12px;padding:0;max-width:500px;width:90%;box-shadow:0 4px 20px rgba(0,0,0,0.2);">
    <div style="display:flex;justify-content:space-between;align-items:center;padding:20px 25px;border-bottom:1px solid #e0e0e0;">
      <h3 style="margin:0;font-size:20px;color:#333;">Reject Package Request</h3>
      <span style="font-size:28px;font-weight:bold;color:#aaa;cursor:pointer;" onclick="closeRejectModal()">&times;</span>
    </div>
    <form method="POST" action="<?php echo URL_ROOT; ?>/admin/clientRequests" style="padding:25px;">
      <input type="hidden" name="request_id" id="reject_request_id">
      <input type="hidden" name="reject_package_request" value="1">
      <p style="margin:0 0 20px 0;font-size:15px;color:#555;">Reject <strong id="reject_package_name"></strong>?</p>
      <div style="margin-bottom:20px;">
        <label for="rejection_reason" style="display:block;margin-bottom:8px;font-weight:600;color:#333;font-size:14px;">Rejection Reason <span style="color:red;">*</span>:</label>
        <textarea name="rejection_reason" id="rejection_reason" rows="4" required style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-family:inherit;font-size:14px;resize:vertical;"></textarea>
      </div>
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:25px;">
        <button type="button" class="secondary-btn" onclick="closeRejectModal()">Cancel</button>
        <button type="submit" class="primary-btn">Reject</button>
      </div>
    </form>
    </form>
  </div>
</div>

<script>
function showApproveModal(id, name) {
    console.log("showApproveModal called with ID:", id, "Name:", name);
    document.getElementById('approve_request_id').value = id;
    document.getElementById('approve_package_name').textContent = name;
    document.getElementById('approveModal').style.display = 'flex';
}

function closeApproveModal() {
    document.getElementById('approveModal').style.display = 'none';
    document.getElementById('admin_notes_approve').value = '';
}

function showRejectModal(id, name) {
    console.log("showRejectModal called with ID:", id, "Name:", name);
    document.getElementById('reject_request_id').value = id;
    document.getElementById('reject_package_name').textContent = name;
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejection_reason').value = '';
}

window.onclick = function(e) {
    const am = document.getElementById('approveModal');
    const rm = document.getElementById('rejectModal');
    if (e.target === am) closeApproveModal();
    if (e.target === rm) closeRejectModal();
}

// Add form submit handlers for debugging
document.addEventListener('DOMContentLoaded', function() {
    const approveForms = document.querySelectorAll('form[action*="clientRequests"]');
    approveForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            console.log("Form submitting with data:", new FormData(form));
            console.log("Form action:", form.action);
            console.log("Form method:", form.method);
        });
    });
});
</script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/components/showNotification.php'; ?>
<script>
// Show flash notifications
<?php 
$requestSuccess = flash('request_success');
$requestError = flash('request_error');
$siteSuccess = flash('site_success');
$siteError = flash('site_error');
?>
<?php if ($requestSuccess): ?>
  showNotification('<?php echo addslashes($requestSuccess); ?>', 'success');
<?php endif; ?>
<?php if ($requestError): ?>
  showNotification('<?php echo addslashes($requestError); ?>', 'error');
<?php endif; ?>
<?php if ($siteSuccess): ?>
  showNotification('<?php echo addslashes($siteSuccess); ?>', 'success');
<?php endif; ?>
<?php if ($siteError): ?>
  showNotification('<?php echo addslashes($siteError); ?>', 'error');
<?php endif; ?>
</script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>

