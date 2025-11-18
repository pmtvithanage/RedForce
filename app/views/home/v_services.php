<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<!-- External Styles -->
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
      <!-- Logo Upload Section -->
      <div class="photo-upload">
        <div class="photo-frame" id="photoFrame">
          <img id="photoPreview" alt="Uploaded logo preview" hidden />
        </div>
        <input type="file" id="photoInput" accept="image/*" hidden />
        <label for="photoInput" class="btn-upload">Upload Logo / Photo</label>
      </div>

      <!-- Company Info Form -->
      <form class="application-form" id="serviceForm">
        <div class="field">
          <div class="field-label">Company Name:</div>
          <input type="text" class="field-input" id="company-name" placeholder="Enter company name">
        </div>

        <div class="field">
          <div class="field-label">Email:</div>
          <input type="email" class="field-input" id="email" placeholder="Enter email address">
        </div>

        <div class="field">
          <div class="field-label">Phone Number:</div>
          <input type="tel" class="field-input" id="phone" placeholder="Enter phone number">
        </div>

        <div class="field">
          <div class="field-label">Owner's Name:</div>
          <input type="text" class="field-input" id="owner-name" placeholder="Enter owner's name">
        </div>
      </form>
    </div>
  </section>


  <div class="form-actions">
    <button class="btn btn-light" onclick="cancel()">Cancel</button>
    <button class="btn btn-primary" onclick="submitForm()">Submit</button>
  </div>
</main>

<script src="<?php echo URL_ROOT; ?>/js/home/getservice.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>