<?php
// Quick fix for undefined variables
if (!isset($data)) {
    $data = [];
}
if (!isset($data['title'])) {
    $data['title'] = 'Request Services - RedForce';
}
?>

<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php
// Get data passed from controller
$showSuccessMessage = $data['showSuccessMessage'] ?? false;
$errorMessage = $data['errorMessage'] ?? '';
$previousRequests = $data['previousRequests'] ?? [];
$showHistory = $data['showHistory'] ?? false;
$todayDate = $data['todayDate'] ?? date('Y-m-d');

// Ensure $previousRequests is always an array
if (!is_array($previousRequests)) {
    $previousRequests = [];
}

// Convert database results to match your existing format
$formattedRequests = [];
if (!empty($previousRequests)) {
    foreach ($previousRequests as $request) {
        // Make sure $request is an object before accessing properties
        if (is_object($request)) {
            $formattedRequests[] = [
                'id' => $request->id ?? '',
                'eventName' => $request->event_name ?? '',
                'eventDescription' => $request->event_description ?? '',
                'startDate' => $request->start_date ?? '',
                'endDate' => $request->end_date ?? '',
                'startTime' => $request->start_time ?? '',
                'endTime' => $request->end_time ?? '',
                'location' => $request->location ?? '',
                'numberOfGuards' => $request->number_of_guards ?? '',  // Updated field name
                'comments' => $request->comments ?? '',
                'status' => $request->status ?? 'Pending',
                'submittedDate' => isset($request->submitted_date) ? date('Y-m-d', strtotime($request->submitted_date)) : ''
            ];
        }
    }
}
$previousRequests = $formattedRequests;
?>

<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<!-- Success Message Popup -->
<?php if ($showSuccessMessage): ?>
<div class="popup-overlay">
  <div class="popup-message">
    <div class="popup-content">
      <h3>✓ Submitted Successfully!</h3>
      <p>Your service request has been submitted successfully. We will contact you soon.</p>
      <form method="get">
        <button type="submit" class="popup-btn">OK</button>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Error Message Popup -->
<?php if (!empty($errorMessage)): ?>
<div class="popup-overlay">
  <div class="popup-message">
    <div class="popup-content">
      <h3>❌ Error</h3>
      <p><?php echo htmlspecialchars($errorMessage); ?></p>
      <form method="get">
        <button type="submit" class="popup-btn error-btn">OK</button>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Request History Popup -->
<?php if ($showHistory): ?>
<div class="popup-overlay">
  <div class="history-popup">
    <div class="history-header">
      <h3>Request History</h3>
      <form method="get" style="display: inline;">
        <button type="submit" class="close-btn">&times;</button>
      </form>
    </div>
    <div class="history-content">
      <?php if (empty($previousRequests)): ?>
        <p class="no-requests">No previous requests found.</p>
      <?php else: ?>
        <?php foreach ($previousRequests as $request): ?>
          <div class="request-item">
           <div class="request-header">
  <h4><?php echo htmlspecialchars($request['eventName']); ?></h4>
  <span class="status status-<?php echo strtolower($request['status']); ?>">
    <?php echo strtoupper($request['status']); ?>
  </span>
</div>
            <div class="request-details">
              <div class="detail-row">
                <span class="label">Description:</span>
                <span><?php echo htmlspecialchars($request['eventDescription']); ?></span>
              </div>
              <div class="detail-row">
                <span class="label">Date:</span>
                <span><?php echo $request['startDate']; ?> to <?php echo $request['endDate']; ?></span>
              </div>
              <div class="detail-row">
                <span class="label">Time:</span>
                <span><?php echo $request['startTime']; ?> - <?php echo $request['endTime']; ?></span>
              </div>
              <div class="detail-row">
                <span class="label">Location:</span>
                <span><?php echo htmlspecialchars($request['location']); ?></span>
              </div>
              <?php if (!empty($request['comments'])): ?>
              <div class="detail-row">
                <span class="label">Comments:</span>
                <span><?php echo htmlspecialchars($request['comments']); ?></span>
              </div>
              <?php endif; ?>
              <div class="detail-row">
                <span class="label">Submitted:</span>
                <span><?php echo $request['submittedDate']; ?></span>
              </div>
              <div class="detail-row">
                <span class="label">Number of Guards:</span>
                <span><?php echo $request['numberOfGuards']; ?> guards</span>
              </div>
              
              <!-- ADD DELETE BUTTON HERE -->
              <div class="delete-section">
                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
                  <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                  <button type="submit" name="delete_request" class="delete-request-btn">
                    🗑️ Delete Request
                  </button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="main-content">
  <div class="request-services-container">
    <div class="page-header">
      <h2 class="page-title">Request Services</h2>
      <form method="get" style="display: inline;">
        <input type="hidden" name="show_history" value="1">
        <button type="submit" class="history-btn">View Request History</button>
      </form>
    </div>

    <div class="request-card">
      <h3 class="section-title">Occasion Details</h3>

      <form method="POST" action="">
        <!-- Event Name -->
        <div class="form-group">
          <label for="eventName">*Event Name :</label>
          <input type="text" id="eventName" name="eventName" 
                 value="<?php echo isset($_POST['eventName']) ? htmlspecialchars($_POST['eventName']) : ''; ?>" 
                 required />
        </div>

        <!-- Event Description -->
        <div class="form-group">
          <label for="eventDescription">*Event Description :</label>
          <textarea id="eventDescription" name="eventDescription" rows="3" required><?php echo isset($_POST['eventDescription']) ? htmlspecialchars($_POST['eventDescription']) : ''; ?></textarea>
        </div>

        <!-- Dates -->
        <div class="form-row">
          <div class="form-group">
            <label for="startDate">*Starting Date :</label>
            <input type="date" id="startDate" name="startDate" 
                   min="<?php echo $todayDate; ?>"
                   value="<?php echo isset($_POST['startDate']) && $_POST['startDate'] >= $todayDate ? $_POST['startDate'] : ''; ?>" 
                   required />
          </div>
          <div class="form-group">
            <label for="endDate">*Ending Date :</label>
            <input type="date" id="endDate" name="endDate" 
                   min="<?php echo $todayDate; ?>"
                   value="<?php echo isset($_POST['endDate']) && $_POST['endDate'] >= $todayDate ? $_POST['endDate'] : ''; ?>" 
                   required />
          </div>
        </div>

        <!-- Times -->
        <div class="form-row">
          <div class="form-group">
            <label for="startTime">*Starting Time :</label>
            <input type="time" id="startTime" name="startTime" 
                   value="<?php echo isset($_POST['startTime']) ? $_POST['startTime'] : ''; ?>" 
                   required />
          </div>
          <div class="form-group">
            <label for="endTime">*Ending Time :</label>
            <input type="time" id="endTime" name="endTime" 
                   value="<?php echo isset($_POST['endTime']) ? $_POST['endTime'] : ''; ?>" 
                   required />
          </div>
        </div>

        <!-- Location -->
        <div class="form-group">
          <label for="location">*Location :</label>
          <input type="text" id="location" name="location" 
                 value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>" 
                 required />
        </div>

    
        <!-- Number of Guards -->
        <div class="form-group">
          <label for="numberOfGuards">*Number of Guards :</label>
          <select id="numberOfGuards" name="numberOfGuards" required>
            <option value="">Select number of guards</option>
            <option value="1" <?php echo (isset($_POST['numberOfGuards']) && $_POST['numberOfGuards'] == '1') ? 'selected' : ''; ?>>1 Guard</option>
            <option value="2" <?php echo (isset($_POST['numberOfGuards']) && $_POST['numberOfGuards'] == '2') ? 'selected' : ''; ?>>2 Guards</option>
            <option value="3" <?php echo (isset($_POST['numberOfGuards']) && $_POST['numberOfGuards'] == '3') ? 'selected' : ''; ?>>3 Guards</option>
            <option value="4" <?php echo (isset($_POST['numberOfGuards']) && $_POST['numberOfGuards'] == '4') ? 'selected' : ''; ?>>4 Guards</option>
            <option value="5" <?php echo (isset($_POST['numberOfGuards']) && $_POST['numberOfGuards'] == '5') ? 'selected' : ''; ?>>5 Guards</option>
            <option value="6" <?php echo (isset($_POST['numberOfGuards']) && $_POST['numberOfGuards'] == '6') ? 'selected' : ''; ?>>6 Guards</option>
          </select>
        </div>

        <!-- Additional Comments -->
        <div class="form-group">
          <label for="comments">Additional Comments :</label>
          <textarea id="comments" name="comments" rows="2"><?php echo isset($_POST['comments']) ? htmlspecialchars($_POST['comments']) : ''; ?></textarea>
        </div>

        <!-- Submit -->
        <div class="form-actions">
          <button type="submit" name="submit_request" class="submit-btn">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/requests_style.css">

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/client/requests.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>