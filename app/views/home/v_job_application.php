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
        <input class="field-input" type="text" id="phone" name="phone" value="<?php echo $data['phone']; ?>" placeholder="Enter your phone number" />
        <span class="form-input-error"><?php echo $data['phone_err'];?></span>
      </div>

      <div class="field">
        <div class="field-label">Date Of Birth:</div>
        <input class="field-input" type="date" id="birthday" name="birthday" value="<?php echo $data['birthday']; ?>" />
        <span class="form-input-error"><?php echo $data['birthday_err'];?></span>
      </div>

      <div class="field">
        <div class="field-label">National Identity Number:</div>
        <input class="field-input" type="text" id="national_id" name="national_id" value="<?php echo $data['national_id']; ?>" placeholder="Enter your national ID number" />
        <span class="form-input-error"><?php echo $data['national_id_err'];?></span>
      </div>

      <div class="field">
        <div class="field-label">Gender:</div>
        <select class="field-input" id="gender" name="gender">
          <option value="">Select Gender</option>
          <option value="male" <?php echo ($data['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
          <option value="female" <?php echo ($data['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
          <option value="other" <?php echo ($data['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
        </select>
        <span class="form-input-error"><?php echo $data['gender_err'];?></span>
      </div>

      <div class="field">
        <div class="field-label">Permanent Address:</div>
        <input class="field-input" type="text" id="address" name="address" value="<?php echo $data['address']; ?>" placeholder="Enter your permanent address" />
        <span class="form-input-error"><?php echo $data['address_err'];?></span>
      </div>

      <div class="field">
        <div class="field-label">District:</div> 
        <input class="field-input" type="text" id="district" name="district" value="<?php echo $data['district']; ?>" placeholder="Enter your district" />
        <span class="form-input-error"><?php echo $data['district_err'];?></span>
      </div>

      <div class="field" id="city-field" name="city-filed" style="display: none;">
        <div class="field-label">City:</div> 
        <input class="field-input" type="text" id="city" name="city" value="<?php echo $data['city']; ?>" placeholder="Enter your city" />
        <span class="form-input-error"><?php echo $data['city_err'];?></span>
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
<script src="<?php echo URL_ROOT; ?>/js/components/select_district_city.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>