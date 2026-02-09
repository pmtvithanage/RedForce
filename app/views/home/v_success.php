<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<!-- External Styles -->
 <link rel="stylesheet" href="<?= URL_ROOT ?>/css/style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">
<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>/css/home/getservice_style.css">

<style>
  strong{
    font-weight: 600;
    color: #a30f0fff;
  }

 
</style>
<header class="topbar">
  <div class="topbar__inner">
    <div class="topbar__left">
      <button class="go-back-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/home/index'">
        Back to Home
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
      <h2>Request Successfully Submitted!</h2> <br>
      
    </div>
    <div class="section-body">
      <p>Thank you for choosing Red Force Security Service. We appreciate your interest in our services and will be in touch with you shortly.</p>
      <h3>Submitted Business Registration Information:</h3> <br>
    </div>

    <div class="form-and-photo">

      <!-- Company Info Form -->
      <div class="application-form" >
        <!-- Logo Upload Section -->
        <div class="photo-upload">
          
            <img class="imagePlaceholder" src="<?php echo URL_ROOT; ?>/uploads/clientLogos/<?php echo $data['image_name']; ?>" id="photoPreview" alt="Uploaded logo preview"  />
          
        </div>

        <div class="field">
          <div class="field-label"><strong>Legal/Registered Company Name:</strong> <?php echo $data['legal_company_name'] ?? 'N/A'; ?></div>
        </div>

        <div class="field">
          <div class="field-label"><strong>Company Type:</strong> <?php echo $data['company_type'] ?? 'N/A'; ?></div>
        </div>

        <div class="field">
          <div class="field-label"><strong>Business Registration Number:</strong> <?php echo $data['business_registration_number'] ?? 'N/A'; ?></div>
        </div>

        <div class="field">
          <div class="field-label"><strong>Registered Business Address:</strong> <?php echo nl2br($data['registered_address'] ?? 'N/A'); ?></div>
        </div>

        <div class="field">
          <div class="field-label"><strong>Primary Business Email:</strong> <?php echo $data['email'] ?? 'N/A'; ?></div>
          
        </div>

        <div class="field">
          <div class="field-label"><strong>Primary Business Phone:</strong><?php echo $data['phone_number'] ?? 'N/A'; ?></div>
          
        </div>

        <div class="field">
          <div class="field-label"><strong>Contact Person's Name:</strong><?php echo $data['contact_person_name'] ?? 'N/A'; ?></div>
          
        </div>

        <div class="field" style="border-top: 2px solid #f0f0f0; padding-top: 15px; margin-top: 15px;">
          <div class="field-label"><strong>Business Registration Document:</strong></div>
          <?php if(!empty($data['business_document'])): ?>
            <a href="<?php echo URL_ROOT; ?>/uploads/businessDocuments/<?php echo $data['business_document']; ?>" target="_blank" style="color: #a30f0f; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; margin-top: 5px;">
              <span class="material-symbols-outlined" style="font-size: 18px;">description</span>
              View Document
            </a>
          <?php else: ?>
            <span style="color: #999;">No document uploaded</span>
          <?php endif; ?>
        </div>
        
        <input type="hidden" id="logo_path" name="logo_path">

        <div class="form-actions">
          <button type="button" class="btn btn-light" onclick="window.location.href='<?php echo URL_ROOT; ?>/home/index'">Back to Home</button>
        </div>
      </div>
    </div>
  </section>

  
</main>
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
  });
</script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>