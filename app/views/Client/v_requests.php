<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<div class="main-content">
  <div class="request-services-container">
    <h2 class="page-title">Request Services</h2>

    <div class="request-card">
      <h3 class="section-title">Occasion Details</h3>

      <form id="requestForm">
        <!-- Event Name -->
        <div class="form-group">
          <label for="eventName">*Event Name :</label>
          <input type="text" id="eventName" name="eventName" required />
        </div>

        <!-- Event Description -->
        <div class="form-group">
          <label for="eventDescription">*Event Description :</label>
          <textarea id="eventDescription" name="eventDescription" rows="3" required></textarea>
        </div>

        <!-- Dates -->
        <div class="form-row">
          <div class="form-group">
            <label for="startDate">*Starting Date :</label>
            <input type="date" id="startDate" name="startDate" required />
          </div>
          <div class="form-group">
            <label for="endDate">*Ending Date :</label>
            <input type="date" id="endDate" name="endDate" required />
          </div>
        </div>

        <!-- Times -->
        <div class="form-row">
          <div class="form-group">
            <label for="startTime">*Starting Time :</label>
            <input type="time" id="startTime" name="startTime" required />
          </div>
          <div class="form-group">
            <label for="endTime">*Ending Time :</label>
            <input type="time" id="endTime" name="endTime" required />
          </div>
        </div>

        <!-- Location -->
        <div class="form-group">
          <label for="location">*Location :</label>
          <input type="text" id="location" name="location" required />
        </div>

        <!-- Guard Type -->
        <div class="form-group">
          <label>*Guard Type :</label>
          <div class="guard-type">
            <label>
              <input type="radio" name="guardType" value="Armed" required /> Armed
            </label>
            <label>
              <input type="radio" name="guardType" value="Regular" /> Regular
            </label>
            <select id="guardCount" name="guardCount">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
            </select>
          </div>
        </div>

        <!-- Additional Comments -->
        <div class="form-group">
          <label for="comments">Additional Comments :</label>
          <textarea id="comments" name="comments" rows="2"></textarea>
        </div>

        <!-- Submit -->
        <div class="form-actions">
          <button type="submit" class="submit-btn">Submit</button>
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