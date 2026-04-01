<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_premiseofficer_sidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
  
/* ---------- Page Layout ---------- */
.page {
  width:900px;
  margin: 40px auto;
  margin-top:0;
  padding: 0 20px;
}

.card-section {
  margin-bottom: 40px;
}

.section-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 15px;
}

.section-head h2 {
  font-size: 20px;
  color: var(--primary-color);
  margin: 0;
}

/* ---------- Cards ---------- */
.card {
  background-color: #fff;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(164, 0, 0, 0.1);
}

.large-card {
  min-height: 120px;
}

.placeholder {
  color: #555;
  font-size: 15px;
}

/* ---------- Form Section ---------- */
.form-and-photo {
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
}

.photo-upload {
  flex: 1 1 200px;
  text-align: center;
  justify-items: center;
}

.photo-frame {
  width: 180px;
  height: 180px;
  border: 2px dashed var(--border-color);
  border-radius: 12px;
  margin: 0 auto 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background-color: #fff;
}

.avatar-circle-large {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f0f0f0;
  border: 4px solid #ffffff;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  margin: 0 auto 10px;
}

.profile-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

.avatar-circle-large:hover .profile-image {
  transform: scale(1.05);
  transition: transform 0.3s ease;
}

.application-form {
  flex: 2 1 400px;
  background-color: #fff;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
}

.field {
  margin-bottom: 15px;
}

.field-label {
  margin-bottom: 6px;
  font-weight: 500;
  color: var(--text-color);
}

.field-input {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  font-size: 15px;
}

.field-input:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px rgba(164, 0, 0, 0.2);
}

/* ---------- Custom File Upload ---------- */
.custom-file {
  display: flex;
  align-items: center;
  gap: 10px;
}

.btn-upload {
  background-color: var(--primary-color);
  color: var(--secondary-color);
  border: none;
  width: 140px;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-upload:hover {
  background-color: #b50000;
}

.btn-upload:active {
  background-color: #800000;
  transform: scale(0.97);
}

.file-name {
  font-size: 14px;
  color: #555;
  font-style: italic;
}

/* ---------- Buttons ---------- */
.btn {
  display: inline-block;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  border: none;
  transition: all 0.3s ease;
}

.btn-primary {
  background-color: var(--primary-color);
  color: var(--secondary-color);
  box-shadow: 0 4px 10px rgba(164, 0, 0, 0.3);
}

.btn-primary:hover {
  background-color: #b50000;
}

.btn-primary:active {
  background-color: #800000;
  transform: scale(0.97);
}

.btn-light {
  background-color: #f1f1f1;
  color: #333;
}

.btn-light:hover {
  background-color: #e0e0e0;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
}

.form-input-error {
  color: red;
  font-size: 13px;
}

.edit-avatar-btn {
  position: absolute;
  bottom: 10px;
  right: 10px;
  background: var(--primary-color);
  color: white;
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  transition: transform 0.2s;
}

.edit-avatar-btn:hover {
  transform: scale(1.1);
}

.profile-avatar-large {
  position: relative;
  width: fit-content;
  margin: 0 auto;
}

.profile-name {
  text-align: center;
  font-size: 18px;
  font-weight: 600;
  margin-top: 15px;
}

.profile-role {
  text-align: center;
  font-size: 14px;
  color: #666;
  margin-top: 5px;
}
</style>

    <!-- Back Button -->
    <button class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center;" onclick="history.back()"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>

    <main class="page">
  <section class="card-section">
    <div class="section-head">
      <h2>Edit Profile Information</h2>
    </div>

    <div class="form-and-photo">

      <!-- Profile Form -->
      <form class="application-form" id="profileForm" method="POST" action="<?php echo URL_ROOT; ?>/premiseOfficer/editProfile" enctype="multipart/form-data">
        
        <!-- Avatar Upload Section -->
        <div class="photo-upload">
          <div class="profile-avatar-large">
            <div class="avatar-circle-large">
              <img class="profile-image" src="<?php echo URL_ROOT; ?>/uploads/image/<?php echo isset($data['premiseofficer']) ? $data['premiseofficer']->profile_image : 'default.png'; ?>" 
                   alt="Profile Image" 
                   id="imagePlaceholder"
                   data-default-src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo isset($data['premiseofficer']) ? $data['premiseofficer']->profile_image : 'default.png'; ?>" />
            </div>
            <button type="button" class="edit-avatar-btn" id="addImageBtn" onclick="toggleBrowse()">
              <span class="material-icons">photo_camera</span>
            </button>
          </div>
          <span class="form-input-error" id="imageError"><?php echo isset($data['image_err']) ? $data['image_err'] : ''; ?></span>
          <input type="file" name="profile_image" id="image" accept="image/*" hidden />
        </div>

        <!-- Profile Details Form -->
        <div>
          <div class="field">
            <div class="field-label">Name:</div>
            <input type="text" class="field-input" id="premiseofficer-name" name="name" value="<?php echo isset($data['premiseofficer']) ? $data['premiseofficer']->name : ''; ?>" placeholder="Enter your name">
            <span class="form-input-error" id="nameError"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>
          </div>

          <div class="field">
            <div class="field-label">Phone Number:</div>
            <input type="tel" class="field-input" id="phone" name="phone_number" value="<?php echo isset($data['premiseofficer']) ? $data['premiseofficer']->phone_number : ''; ?>" placeholder="Enter phone number">
            <span class="form-input-error" id="phoneError"><?php echo isset($data['phone_number_err']) ? $data['phone_number_err'] : ''; ?></span>
          </div>

          <div class="field">
            <div class="field-label">Email Address:</div>
            <input type="email" class="field-input" id="email" name="email" value="<?php echo isset($data['premiseofficer']) ? $data['premiseofficer']->email : ''; ?>" placeholder="Enter email address">
            <span class="form-input-error" id="emailError"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>
          </div>

          <button type="button" class="btn btn-primary" id="changePasswordBtn" onclick="togglePasswordFields()" style="margin-top: 15px;">
            Change Password
          </button>

          <div id="passwordFields" style="display: none; margin-top: 20px;">
            <hr style="margin: 20px 0; border: none; border-top: 1px solid var(--border-color);">

            <div class="field">
              <div class="field-label">Current Password:</div>
              <input type="password" class="field-input" id="current-password" name="current_password" placeholder="Enter current password">
              <span class="form-input-error" id="currentPasswordError"><?php echo isset($data['current_password_err']) ? $data['current_password_err'] : ''; ?></span>
            </div>

            <div class="field">
              <div class="field-label">New Password:</div>
              <input type="password" class="field-input" id="new-password" name="new_password" placeholder="Enter new password">
              <span class="form-input-error" id="newPasswordError"><?php echo isset($data['new_password_err']) ? $data['new_password_err'] : ''; ?></span>
            </div>

            <div class="field">
              <div class="field-label">Confirm Password:</div>
              <input type="password" class="field-input" id="confirm-password" name="confirm_password" placeholder="Confirm new password">
              <span class="form-input-error" id="confirmPasswordError"><?php echo isset($data['confirm_password_err']) ? $data['confirm_password_err'] : ''; ?></span>
            </div>
          </div>

          <div class="form-actions">
            <button type="button" class="btn btn-light" onclick="window.history.back()">Cancel</button>
            <input type="submit" value="Save Changes" class="btn btn-primary">
          </div>
        </div>
      </form>
    </div>
  </section>
</main>

</div>

<div class="backdrop" id="backdrop" hidden></div>

<script>
  const addImageBtn = document.getElementById("addImageBtn");
  const imagePlaceholder = document.getElementById("imagePlaceholder");
  let inputPath = document.querySelector("#image");
  let file;

  // Get the default image path from data attribute
  const defaultImagePath = imagePlaceholder.getAttribute('data-default-src');

  function toggleBrowse(){
      inputPath.click();
  }

  function removeImage(){
      imagePlaceholder.setAttribute('src', defaultImagePath);
      inputPath.value = null;
      file = null;
  }

  inputPath.addEventListener('change', function(){
      file = this.files[0];

      if (file) {
          showImage();
      } else {
          // If user cancels file selection, reset to default
          removeImage();
      }
  });

  function showImage(){
      let fileType = file.type;
      let validExtensions = ["image/jpeg", "image/jpg", "image/png"];

      if(validExtensions.includes(fileType)){
          let fileReader = new FileReader();

          fileReader.onload = () => {
              let fileURL = fileReader.result;
              imagePlaceholder.setAttribute('src', fileURL);
          }

          fileReader.onerror = () => {
              alert('Error reading file');
              removeImage();
          }

          fileReader.readAsDataURL(file);
      }
      else{
          alert('This is not a valid image file');
          removeImage();
      }
  }

  function togglePasswordFields(){
      const passwordFields = document.getElementById('passwordFields');
      const changePasswordBtn = document.getElementById('changePasswordBtn');
      
      if (passwordFields.style.display === 'none') {
          passwordFields.style.display = 'block';
          changePasswordBtn.textContent = 'Hide Password';
          changePasswordBtn.style.backgroundColor = '#e0e0e0';
          changePasswordBtn.style.color = '#333';
      } else {
          passwordFields.style.display = 'none';
          changePasswordBtn.textContent = 'Change Password';
          changePasswordBtn.style.backgroundColor = 'var(--primary-color)';
          changePasswordBtn.style.color = 'var(--secondary-color)';
      }
  }

  document.addEventListener('DOMContentLoaded', function() {
      // Initialize with default image visible
      imagePlaceholder.setAttribute('src', defaultImagePath);
      
      // Check URL parameter to auto-show password section
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('show') === 'password') {
          const passwordFields = document.getElementById('passwordFields');
          const changePasswordBtn = document.getElementById('changePasswordBtn');
          
          passwordFields.style.display = 'block';
          changePasswordBtn.textContent = 'Hide Password';
          changePasswordBtn.style.backgroundColor = '#e0e0e0';
          changePasswordBtn.style.color = '#333';
          
          // Scroll to password section
          setTimeout(() => {
              changePasswordBtn.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }, 100);
      }
  });
</script>200413101997

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>       