<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<!-- Import global stylesheet -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">

<!-- Import Google Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>/css/home/jobapplication_style.css">


<header class="topbar">
  <div class="topbar__inner">
    <div class="topbar__left">
      <button class="go-back-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/home/index#jobs'">
        <span class="material-symbols-outlined">arrow_back</span>
        Go Back
      </button>
      <span>
        <?php
          if($data['role'] == 'po'){
            echo "Premise Officer Application";
          } elseif($data['role'] == 'ct'){
            echo "Care Taker Application";
          } elseif($data['role'] == 'mr'){
            echo "Mobile Rider Application";
          }
        ?>
      </span>
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

  <form class="form-and-photo" action="<?php echo URL_ROOT; ?>/home/submit_application/<?php echo $data['role'];?>" method="POST" enctype="multipart/form-data">
    <div class="photo-upload">
      
        <img class="imagePlaceholder" src="<?php echo URL_ROOT; ?>/public/img/photo.png" 
          alt="Uploaded logo preview" 
          id="imagePlaceholder"
          data-default-src="<?php echo URL_ROOT; ?>/public/img/photo.png" />
        
      
      <input type="file" id="image" name="image" accept="image/*" hidden />
      <span class="form-input-error"><?php echo $data['image_err'];?></span>
      <div class="btn-upload" id="addImageBtn" onClick="toggleBrowse()">Upload photo</div>
      <div class="btn-upload" id="removeImageBtn" style="display: none;" onClick="removeImage()">Remove</div>
      
    </div>

    <div class="application-form" id="applicationForm" novalidate>
      <div class="field">
        <div class="field-label">Name:</div>
        <input class="field-input" type="text" id="name" name="name" value="<?php echo $data['name']; ?>" placeholder="Enter name"/>
        <span class="form-input-error"><?php echo $data['name_err'];?></span>
      </div>

      <div class="field">
        <div class="field-label">Email:</div>
        <input class="field-input" type="text" id="email" name="email" value="<?php echo $data['email']; ?>" placeholder="Enter your email" />
        <span class="form-input-error"><?php echo $data['email_err'];?></span>
      </div>

      <div class="field">
        <div class="field-label">Phone Number:</div>
        <input class="field-input" type="text" id="phone" name="phone" value="<?php echo $data['phone']; ?>" placeholder="Enter your phone number"/>
        <span class="form-input-error"><?php echo $data['phone_err'];?></span>
      </div>

      <div class="field file-field">
        <div class="field-label">Attach CV:</div>
        <div class="custom-file">
            <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" hidden>
            <label for="cv" class="btn btn-upload">Choose File</label>
            <span id="selectedFile"></span> 
            <span class="form-input-error"><?php echo $data['cv_err'];?></span>
        </div>
    </div>

      <div class="form-actions">
        <button type="button" class="btn btn-light" id="cancelBtn" onclick="window.location.href='<?php echo URL_ROOT; ?>/home/index#jobs'">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </form>
</main>

<script src="<?php echo URL_ROOT; ?>/js/home/jobapplication.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>