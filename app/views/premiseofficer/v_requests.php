<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_premiseofficer_sidebar.php'; ?>

<div class="main-content">
  <div class="request-services-container">
    <div class="page-header">
      
      <form method="get" style="display: inline;">
        <input type="hidden" name="show_history" value="1">
        <button type="submit" class="history-btn">View Leave History</button>
      </form>
    </div>

    <div class="request-card">
      <h3 class="section-title">Leave Details</h3>

      <form method="POST" action="" enctype="multipart/form-data">
        <!-- Full Name -->
        <div class="form-group">
          <label for="fullName">*Full Name :</label>
          <input type="text" id="fullName" name="fullName" 
                 value="<?php echo isset($_POST['fullName']) ? htmlspecialchars($_POST['fullName']) : ''; ?>" 
                 required />
        </div>

        <!-- ID Number -->
        <div class="form-group">
          <label for="idNumber">*ID Number :</label>
          <input type="text" id="idNumber" name="idNumber" 
                 value="<?php echo isset($_POST['idNumber']) ? htmlspecialchars($_POST['idNumber']) : ''; ?>" 
                 required />
        </div>

        <!-- Current Site -->
        <div class="form-group">
          <label for="currentSite">*Current Site :</label>
          <input type="text" id="currentSite" name="currentSite" 
                 value="<?php echo isset($_POST['currentSite']) ? htmlspecialchars($_POST['currentSite']) : ''; ?>" 
                 required />
        </div>

        <!-- Leave Dates -->
        <div class="form-row">
          <div class="form-group">
            <label for="leaveStartDate">*Leave to be taken from :</label>
            <input type="date" id="leaveStartDate" name="leaveStartDate" 
                   min="<?php echo date('Y-m-d'); ?>"
                   value="<?php echo isset($_POST['leaveStartDate']) ? $_POST['leaveStartDate'] : ''; ?>" 
                   required />
          </div>
          <div class="form-group">
            <label for="leaveEndDate">*to :</label>
            <input type="date" id="leaveEndDate" name="leaveEndDate" 
                   min="<?php echo date('Y-m-d'); ?>"
                   value="<?php echo isset($_POST['leaveEndDate']) ? $_POST['leaveEndDate'] : ''; ?>" 
                   required />
          </div>
        </div>

        <!-- Describe the form of leave -->
        <div class="form-group">
          <label for="leaveType">*Describe the form of leave :</label>
          <textarea id="leaveType" name="leaveType" rows="3" required><?php echo isset($_POST['leaveType']) ? htmlspecialchars($_POST['leaveType']) : ''; ?></textarea>
        </div>

        <!-- Attach medical reports/proof -->
        <div class="form-group">
          <label for="medicalProof">Attach medical reports/proof for taking the leave :</label>
          <input type="file" id="medicalProof" name="medicalProof" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" />
          <small class="file-info">Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max 5MB)</small>
        </div>

        <!-- Additional Comments -->
        <div class="form-group">
          <label for="additionalComments">Additional Comments :</label>
          <textarea id="additionalComments" name="additionalComments" rows="2"><?php echo isset($_POST['additionalComments']) ? htmlspecialchars($_POST['additionalComments']) : ''; ?></textarea>
        </div>

        <!-- Submit -->
        <div class="form-actions">
          <button type="submit" name="submit_leave_request" class="submit-btn">Submit Leave Request</button>
        </div>
      </form>
    </div>
  </div>
</div>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/premiseofficer/requests_style.css">

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/premiseofficer/requests.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>