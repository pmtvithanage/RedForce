<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<!-- External Styles -->
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
      <h3>Submitted Company Information:</h3> <br>
    </div>

    <div class="form-and-photo">

      <!-- Company Info Form -->
      <div class="application-form" >
        <!-- Logo Upload Section -->
        <div class="photo-upload">
          
            <img class="imagePlaceholder" src="<?php echo URL_ROOT; ?>/uploads/clientLogos/<?php echo $data['image_name']; ?>" id="photoPreview" alt="Uploaded logo preview"  />
          
        </div>

        <div class="field">
          <div class="field-label"><strong>Company Name:</strong> <?php echo $data['company_name']; ?></div>
        </div>

        <div class="field">
          <div class="field-label"><strong>Email:</strong> <?php echo $data['email']; ?></div>
          
        </div>

        <div class="field">
          <div class="field-label"><strong>Phone Number:</strong><?php echo $data['phone_number']; ?></div>
          
        </div>

        <div class="field">
          <div class="field-label"><strong>Contact person's Name:</strong><?php echo $data['contact_person_name']; ?></div>
          
        </div>
        
        <input type="hidden" id="logo_path" name="logo_path">

        <div class="form-actions">
          <button type="button" class="btn btn-light" onclick="window.location.href='<?php echo URL_ROOT; ?>/home/index'">Back to Home</button>
        </div>
      </div>
    </div>
  </section>

  
</main>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>