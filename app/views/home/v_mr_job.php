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
      <span>Mobile Rider</span>
    </div>
    <div class="topbar__brand">Red Force Security Service</div>
    <div class="topbar__logo" aria-label="Company logo" title="Red Force">
      <img src="<?php echo URL_ROOT; ?>/public/img/logo.png" alt="RED FORCE" class="logo-img">
    </div>
  </div>
</header>

<main class="page">
  <section class="card-section">
    <div class="section-head">
      <h2>Job Description</h2>
      <div class="due-badge" id="dueBadge" title="Application deadline">Due <?php echo $data['due_date'];?></div>
    </div>
    <div class="card large-card" aria-label="Job description">
      <p style="font-family: 'Courier New', monospace; font-size:18px;"class="placeholder"><?php echo $data['description'];?></p>
    </div>
  </section>

  <section class="card-section">
    <div class="section-head">
      <h2>Qualifications</h2>
    </div>
    <div class="card large-card" aria-label="Qualifications">
      <p style="font-family: 'Courier New', monospace; font-size:18px;" class="placeholder"><?php echo nl2br(htmlspecialchars($data['qualifications'])); ?></p>
    </div>
  </section>

  <section class="form-and-photo">
    <div class="photo-upload">
      <div class="photo-frame" id="photoFrame" aria-live="polite">
        <img id="photoPreview" alt="Applicant photo preview" hidden />
      </div>
      <input type="file" id="photoInput" accept="image/*" hidden />
      <label for="photoInput" class="btn btn-light">Upload photo</label>
    </div>

    <form class="application-form" id="applicationForm" novalidate>
      <div class="field">
        <div class="field-label">Name:</div>
        <input class="field-input" type="text" id="name" name="name" autocomplete="name" />
      </div>

      <div class="field">
        <div class="field-label">Email:</div>
        <input class="field-input" type="email" id="email" name="email" autocomplete="email" />
      </div>

      <div class="field">
        <div class="field-label">Phone Number:</div>
        <input class="field-input" type="tel" id="phone" name="phone" autocomplete="tel" />
      </div>

      <div class="field file-field">
        <div class="field-label">Attach CV:</div>
        <div class="custom-file">
          <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" hidden>
          <label for="cv" class="btn btn-upload">Choose File</label>
          <span id="cvFileName" class="file-name">No file chosen</span>
        </div>
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-light" id="cancelBtn" onclick="cancel()">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </section>
</main>

<script src="<?php echo URL_ROOT; ?>/js/home/jobapplication.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>