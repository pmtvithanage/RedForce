<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php
// Handle form submission
$showSuccessMessage = false;
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
    // Get current date
    $currentDate = date('Y-m-d');
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    
    // Validate dates - ensure they are not before today
    if ($startDate < $currentDate) {
        $errorMessage = 'Starting date cannot be in the past. Please select today or a future date.';
    } elseif ($endDate < $currentDate) {
        $errorMessage = 'Ending date cannot be in the past. Please select today or a future date.';
    } elseif ($endDate < $startDate) {
        $errorMessage = 'Ending date cannot be before starting date.';
    } else {
        // Process form data here (save to database, etc.)
        $showSuccessMessage = true;
    }
}

// Get current date for HTML date input restrictions
$todayDate = date('Y-m-d');

// Mock data for previous requests (replace with actual database query)
$previousRequests = [
    [
        'id' => 1,
        'eventName' => 'Corporate Conference',
        'eventDescription' => 'Annual company meeting with 200+ attendees',
        'startDate' => '2024-10-15',
        'endDate' => '2024-10-15',
        'startTime' => '09:00',
        'endTime' => '17:00',
        'location' => 'Hilton Hotel, Colombo',
        'guardType' => 'Armed',
        'guardCount' => '3',
        'comments' => 'Need experienced personnel',
        'status' => 'Approved',
        'submittedDate' => '2024-09-01'
    ],
    [
        'id' => 2,
        'eventName' => 'Wedding Ceremony',
        'eventDescription' => 'Traditional wedding celebration',
        'startDate' => '2024-11-20',
        'endDate' => '2024-11-20',
        'startTime' => '14:00',
        'endTime' => '23:00',
        'location' => 'Taj Samudra Hotel',
        'guardType' => 'Regular',
        'guardCount' => '2',
        'comments' => 'Formal attire required',
        'status' => 'Pending',
        'submittedDate' => '2024-09-05'
    ]
];

// Handle history popup
$showHistory = isset($_GET['show_history']);
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
                <?php echo $request['status']; ?>
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
              <div class="detail-row">
                <span class="label">Guard Type:</span>
                <span><?php echo $request['guardType']; ?> (<?php echo $request['guardCount']; ?> guards)</span>
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

        <!-- Guard Type -->
        <div class="form-group">
          <label>*Guard Type :</label>
          <div class="guard-type">
            <label>
              <input type="radio" name="guardType" value="Armed" 
                     <?php echo (isset($_POST['guardType']) && $_POST['guardType'] == 'Armed') ? 'checked' : ''; ?> 
                     required /> Armed
            </label>
            <label>
              <input type="radio" name="guardType" value="Regular" 
                     <?php echo (isset($_POST['guardType']) && $_POST['guardType'] == 'Regular') ? 'checked' : ''; ?> /> Regular
            </label>
            <select id="guardCount" name="guardCount">
              <option value="1" <?php echo (isset($_POST['guardCount']) && $_POST['guardCount'] == '1') ? 'selected' : ''; ?>>1</option>
              <option value="2" <?php echo (isset($_POST['guardCount']) && $_POST['guardCount'] == '2') ? 'selected' : ''; ?>>2</option>
              <option value="3" <?php echo (isset($_POST['guardCount']) && $_POST['guardCount'] == '3') ? 'selected' : ''; ?>>3</option>
              <option value="4" <?php echo (isset($_POST['guardCount']) && $_POST['guardCount'] == '4') ? 'selected' : ''; ?>>4</option>
            </select>
          </div>
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