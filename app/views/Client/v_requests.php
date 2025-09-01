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

<style>
  .request-services-container {
    max-width: 800px;
    margin: 0 auto;
  }

  .request-card {
    background: #fff0f0;
    border-radius: 12px;
    padding: 25px 30px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }

  .page-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #222;
  }

  .section-title {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 20px;
    color: #333;
  }

  .form-group {
    margin-bottom: 18px;
    display: flex;
    flex-direction: column;
  }

  .form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 18px;
  }

  label {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 6px;
    color: #333;
  }

  input,
  textarea,
  select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    width: 100%;
  }

  textarea {
    resize: none;
  }

  .guard-type {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .guard-type select {
    width: 80px;
  }

  .form-actions {
    text-align: center;
    margin-top: 20px;
  }

  .submit-btn {
    background: #fff;
    border: 1px solid #333;
    border-radius: 20px;
    padding: 8px 20px;
    cursor: pointer;
    transition: background 0.2s;
  }

  .submit-btn:hover {
    background: #ffeaea;
  }
</style>

<script>
  document.getElementById("requestForm").addEventListener("submit", function (e) {
    e.preventDefault();
    alert("Request Submitted!");
  });
</script>

    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>