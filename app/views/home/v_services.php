<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<!-- External Styles -->
 <link rel="stylesheet" href="<?= URL_ROOT ?>/css/style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">
<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>/css/home/getservice_style.css">

<header class="topbar">
  <div class="topbar__inner">
    <div class="topbar__left">
      <button class="go-back-btn" onclick="history.back()">
        <span class="material-symbols-outlined">arrow_back</span> Go Back
      </button>
      <span>Request Service</span>
    </div>
    <div class="topbar__brand">Red Force Security Service</div>
    <div class="topbar__logo">
      <img src="<?php echo URL_ROOT; ?>/public/img/logo.png" alt="RED FORCE" class="logo-img">
    </div>
  </div>
</header>

<main class="page">
  <section class="card-section">
    <div class="section-head">
      <h2>Business Registration Information</h2>
      <p style="color: #666; margin-top: 10px; font-size: 14px;">Please provide accurate business registration details as they appear on official documents</p>
    </div>

    <div class="form-and-photo">

      <!-- Company Info Form -->
      <form class="application-form" id="serviceForm" method="POST" action="<?php echo URL_ROOT; ?>/home/service" enctype="multipart/form-data">
        <!-- Logo Upload Section -->
        <div class="photo-upload">
          
        <img class="imagePlaceholder" src="<?php echo URL_ROOT; ?>/public/img/photo.png" 
         alt="Uploaded logo preview" 
         id="imagePlaceholder"
         data-default-src="<?php echo URL_ROOT; ?>/public/img/photo.png" />
         <span class="form-input-error"><?php echo $data['image_err'];?></span>

          
          <div class="btn-upload" id="addImageBtn" onClick="toggleBrowse()">Add Logo</div>
          <div class="btn-upload" id="removeImageBtn" style="display: none;" onClick="removeImage()">Remove</div>
          <input type="file" name="image" id="image" accept="image/*" hidden />
        </div>

        <div class="field">
          <div class="field-label">Company/Brand Name: <span style="color: red;">*</span></div>
          <input type="text" class="field-input" id="company-name" name="company_name" value="<?php echo $data['company_name'] ?? ''; ?>" placeholder="Your company or brand name" required>
          <span class="form-input-error"><?php echo $data['company_name_err'] ?? '';?></span>
        </div>

        <div class="field">
          <div class="field-label">Legal/Registered Company Name: <span style="color: red;">*</span></div>
          <input type="text" class="field-input" id="legal-company-name" name="legal_company_name" value="<?php echo $data['legal_company_name'] ?? ''; ?>" placeholder="Exactly as on registration documents" required>
          <span class="form-input-error"><?php echo $data['legal_company_name_err'] ?? '';?></span>
        </div>

        <div class="field">
          <div class="field-label">Company Type: <span style="color: red;">*</span></div>
          <select class="field-input" id="company-type" name="company_type" required>
            <option value="">Select company type</option>
            <option value="LLC" <?php echo (isset($data['company_type']) && $data['company_type'] == 'LLC') ? 'selected' : ''; ?>>LLC (Limited Liability Company)</option>
            <option value="Inc" <?php echo (isset($data['company_type']) && $data['company_type'] == 'Inc') ? 'selected' : ''; ?>>Inc (Incorporated)</option>
            <option value="PLC" <?php echo (isset($data['company_type']) && $data['company_type'] == 'PLC') ? 'selected' : ''; ?>>PLC (Public Limited Company)</option>
            <option value="Partnership" <?php echo (isset($data['company_type']) && $data['company_type'] == 'Partnership') ? 'selected' : ''; ?>>Partnership</option>
            <option value="Sole Proprietor" <?php echo (isset($data['company_type']) && $data['company_type'] == 'Sole Proprietor') ? 'selected' : ''; ?>>Sole Proprietor</option>
            <option value="LTD" <?php echo (isset($data['company_type']) && $data['company_type'] == 'LTD') ? 'selected' : ''; ?>>LTD (Private Limited)</option>
            <option value="Corporation" <?php echo (isset($data['company_type']) && $data['company_type'] == 'Corporation') ? 'selected' : ''; ?>>Corporation</option>
            <option value="Other" <?php echo (isset($data['company_type']) && $data['company_type'] == 'Other') ? 'selected' : ''; ?>>Other</option>
          </select>
          <span class="form-input-error"><?php echo $data['company_type_err'] ?? '';?></span>
        </div>

        <div class="field">
          <div class="field-label">Business Registration Number: <span style="color: red;">*</span></div>
          <input type="text" class="field-input" id="registration-number" name="business_registration_number" value="<?php echo $data['business_registration_number'] ?? ''; ?>" placeholder="VAT Number, GSTIN, EIN, Company Number, CIF/NIF, etc." required>
          <small style="color: #666; font-size: 12px;">e.g., VAT Number, GSTIN, EIN (US), Company Number (UK), CIF/NIF (ES)</small>
          <span class="form-input-error"><?php echo $data['business_registration_number_err'] ?? '';?></span>
        </div>

        <div class="field">
          <div class="field-label">Registered Business Address: <span style="color: red;">*</span></div>
          <textarea class="field-input" id="registered-address" name="registered_address" rows="3" placeholder="Official legal address as on registration documents" required><?php echo $data['registered_address'] ?? ''; ?></textarea>
          <span class="form-input-error"><?php echo $data['registered_address_err'] ?? '';?></span>
        </div>

        <div class="field">
          <div class="field-label">Primary Business Email Address: <span style="color: red;">*</span></div>
          <input type="email" class="field-input" id="email" name="email" value="<?php echo $data['email'] ?? '' ?>" placeholder="For official communications" required>
          <span class="form-input-error"><?php echo $data['email_err'] ?? '';?></span>
        </div>

        <div class="field">
          <div class="field-label">Primary Business Phone Number: <span style="color: red;">*</span></div>
          <input type="tel" class="field-input" id="phone" name="phone_number" value="<?php echo $data['phone_number'] ?? '' ?>" placeholder="(e.g., 011*******)" required>
          <span class="form-input-error"><?php echo $data['phone_number_err'] ?? '';?></span>
        </div>

        <div class="field">
          <div class="field-label">Contact Person's Name: <span style="color: red;">*</span></div>
          <input type="text" class="field-input" id="owner-name" name="contact_person_name" value="<?php echo $data['contact_person_name'] ?? '' ?>" placeholder="Primary contact for this account" required>
          <span class="form-input-error"><?php echo $data['contact_person_name_err'] ?? '';?></span>
        </div>

        <div class="field file-field" style="border-top: 2px solid #f0f0f0; padding-top: 20px; margin-top: 20px;">
          <div class="field-label" style="font-weight: 600; margin-bottom: 10px;">Business Registration Document: <span style="color: red;">*</span></div>
          <small style="color: #666; font-size: 12px; display: block; margin-bottom: 10px;">Upload your business registration certificate, incorporation documents, or relevant business license (PDF, JPG, PNG - Max 5MB)</small>
          <div class="custom-file">
            <input type="file" id="business-document" name="business_document" accept=".pdf,.jpg,.jpeg,.png" hidden required>
            <label for="business-document" class="btn btn-upload">Choose File</label>
            <span id="selectedBusinessDoc"></span>
            <span class="form-input-error"><?php echo $data['business_document_err'] ?? '';?></span>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-light" onclick="window.history.back()">Cancel</button>
          <input type="submit" value="Submit" class="btn btn-primary">
        </div>
      </form>
    </div>
  </section>

  
</main>

<script src="<?php echo URL_ROOT; ?>/js/home/getservice.js"></script>
<?php flash('msg')?>

<script>
  // Wait for DOM to be fully loaded
  document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.getElementById('msg-flash');
    
    if (flashMessage) {
      // Auto-remove after 5 seconds (5000ms)
      setTimeout(function() {
        // Add fade-out animation
        flashMessage.classList.add('fade-out');
        
        // Remove element after animation completes
        setTimeout(function() {
          if (flashMessage.parentNode) {
            flashMessage.parentNode.removeChild(flashMessage);
          }
        }, 300); // Match animation duration (300ms from CSS)
      }, 5000); // Display for 5 seconds
    }

    // Business Document File Upload - With PDF/Image Icon
    const docInput = document.getElementById("business-document");
    const docUploadBtn = document.querySelector('label[for="business-document"]');

    if (docInput && docUploadBtn) {
      docInput.addEventListener("change", function () {
        if (this.files && this.files.length > 0) {
          const fileName = this.files[0].name;
          const fileExtension = fileName.split('.').pop().toLowerCase();
          let iconName = 'description'; // Default icon
          
          // Set icon based on file type
          if (fileExtension === 'pdf') {
            iconName = 'picture_as_pdf';
          } else if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
            iconName = 'image';
          }
          
          docUploadBtn.innerHTML = `
            <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 5px; font-size: 18px;">
              ${iconName}
            </span>
            ${fileName}
          `;
        } else {
          docUploadBtn.textContent = "Choose File";
        }
      });
    }
  });
</script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>