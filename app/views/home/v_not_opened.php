<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<!-- Import global stylesheet -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">

<!-- Import Google Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>/css/home/jobapplication_style.css">


<header class="topbar">
  <div class="topbar__inner">
    <div class="topbar__left">
      <button class="go-back-btn" onclick="history.back()">
        <span class="material-symbols-outlined">arrow_back</span>
        Go Back
      </button>
    </div>
    <div class="topbar__brand">Red Force Security Service</div>
    <div class="topbar__logo" aria-label="Company logo" title="Red Force">
      <img src="<?php echo URL_ROOT; ?>/public/img/logo.png" alt="RED FORCE" class="logo-img">
    </div>
  </div>
</header>

<main class="page">
  <section style="margin-top: 200px;"class="card-section">
    
    <div style="text-align: center;" class="card large-card" aria-label="Job description">
      <h2 style="color: #a40000;">Application Not Opened</h2>
      <p class="placeholder">Try again later</p>
    </div>
  </section>

  
</main>

<script src="<?php echo URL_ROOT; ?>/js/home/jobapplication.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>