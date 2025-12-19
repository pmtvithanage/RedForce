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
      <h2>Company Information</h2>
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
          <div class="field-label">Company Name:</div>
          <input type="text" class="field-input" id="company-name" name="company_name" value="<?php echo $data['company_name']; ?>" placeholder="Enter company name">
          <span class="form-input-error"><?php echo $data['company_name_err'];?></span>
        </div>

        <div class="field">
          <div class="field-label">Email:</div>
          <input type="text" class="field-input" id="email" name="email" value="<?php echo $data['email'] ?>" placeholder="Enter email address">
          <span class="form-input-error"><?php echo $data['email_err'];?></span>
        </div>

        <div class="field">
          <div class="field-label">Phone Number:</div>
          <input type="tel" class="field-input" id="phone" name="phone_number" value="<?php echo $data['phone_number'] ?>" placeholder="Enter phone number">
          <span class="form-input-error"><?php echo $data['phone_number_err'];?></span>
        </div>

        <div class="field">
          <div class="field-label">Contact person's Name:</div>
          <input type="text" class="field-input" id="owner-name" name="contact_person_name" value="<?php echo $data['contact_person_name'] ?>" placeholder="Enter contact person's name" >
          <span class="form-input-error"><?php echo $data['contact_person_name_err'];?></span>
        </div>
        
        <input type="hidden" id="logo_path" name="logo_path">

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
  });
</script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>