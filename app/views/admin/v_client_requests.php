<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">


<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/client_requests_style.css">

<?php
// Get flash messages
$successMessage = flash('request_success');
$errorMessage = flash('request_error');
?>

<div class="main-content">
  <div class="page-header">
    
    <a href="<?php echo URL_ROOT; ?>/admin/clients" class="back-btn">
      <span class="material-symbols-outlined">arrow_back</span> Back to Clients
    </a>
  </div>

  <!-- Flash Messages -->
  <?php if (!empty($successMessage)): ?>
  <div class="alert alert-success">
    <span class="material-symbols-outlined">check_circle</span>
    <?php echo $successMessage; ?>
  </div>
  <?php endif; ?>

  <?php if (!empty($errorMessage)): ?>
  <div class="alert alert-error">
    <span class="material-symbols-outlined">error</span>
    <?php echo $errorMessage; ?>
  </div>
  <?php endif; ?>

  <!-- Stats Cards -->
  <div class="stats-container">
    <div class="stat-card">
      <div class="stat-icon pending">
        <span class="material-symbols-outlined">schedule</span>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?php echo $data['requestStats']->pending ?? 0; ?></div>
        <div class="stat-label">Pending</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon approved">
        <span class="material-symbols-outlined">check_circle</span>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?php echo $data['requestStats']->approved ?? 0; ?></div>
        <div class="stat-label">Approved</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon rejected">
        <span class="material-symbols-outlined">cancel</span>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?php echo $data['requestStats']->rejected ?? 0; ?></div>
        <div class="stat-label">Rejected</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon total">
        <span class="material-symbols-outlined">list_alt</span>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?php echo $data['requestStats']->total ?? 0; ?></div>
        <div class="stat-label">Total Requests</div>
      </div>
    </div>
  </div>

  <!-- Service Requests List -->
  <div class="requests-container">
    <?php if (empty($data['serviceRequests'])): ?>
      <div class="no-requests">
        <span class="material-symbols-outlined">inbox</span>
        <p>No service requests found</p>
      </div>
    <?php else: ?>
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
  </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
