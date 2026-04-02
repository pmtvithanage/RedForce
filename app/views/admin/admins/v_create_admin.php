<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

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

.due-badge {
  background-color: var(--primary-color);
  color: var(--secondary-color);
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 14px;
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

.imagePlaceholder{
 
  height: 180px;
  border: 2px solid var(--border-color);
  border-radius: 12px;
  margin: 0 auto 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background-color: #fff;
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
  width: 120px;
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

/* ---------- Permissions Section ---------- */
.permissions-section {
  margin-top: 30px;
  padding: 20px;
  background-color: #fafafa;
  border-radius: 12px;
  border: 1px solid var(--border-color);
}

.permissions-title {
  font-size: 16px;
  font-weight: 600;
  color: var(--primary-color);
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.permissions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.permission-item {
  display: flex;
  align-items: center;
  gap: 10px;
}

.permission-checkbox {
  width: 18px;
  height: 18px;
  cursor: pointer;
  accent-color: var(--primary-color);
  flex-shrink: 0;
}

.permission-label {
  font-size: 14px;
  color: var(--text-color);
  cursor: pointer;
  user-select: none;
  font-weight: 500;
}

.permission-item:hover {
  opacity: 0.8;
}

</style>
    <!-- Content will be loaded here -->
    <button class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center;" onclick="history.back()"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>
    <main class="page">
  <section class="card-section">
    <div class="section-head">
      <h2>Enter Admin Details</h2>
    </div>

    <div class="form-and-photo">

      <!-- Company Info Form -->
      <form class="application-form" id="serviceForm" method="POST" action="<?php echo URL_ROOT; ?>/admin/addadmin/" enctype="multipart/form-data">
        <!-- Logo Upload Section -->
        <div class="photo-upload">
          
        <img class="imagePlaceholder" src="<?php echo URL_ROOT; ?>/public/img/photo.png" 
         alt="Uploaded logo preview" 
         id="imagePlaceholder"
         data-default-src="<?php echo URL_ROOT; ?>/public/img/photo.png" />
         <span class="form-input-error"><?php echo $data['image_err'];?></span>

          
          <div class="btn-upload" id="addImageBtn" onClick="toggleBrowse()">Add Image</div>
          <div class="btn-upload" id="removeImageBtn" style="display: none;" onClick="removeImage()">Remove</div>
          <input type="file" name="image" id="image" accept="image/*" hidden />
        </div>

        <div class="field">
          <div class="field-label">Name:</div>
          <input type="text" class="field-input" id="site-name" name="name" value="<?php echo $data['name']; ?>" placeholder="Enter name">
          <span class="form-input-error"><?php echo $data['name_err'];?></span>
        </div>

        <div class="field">
          <div class="field-label">Email:</div>
          <input type="text" class="field-input" id="email" name="email" value="<?php echo $data['email']; ?>" placeholder="Enter address">
          <span class="form-input-error"><?php echo $data['email_err'];?></span>
        </div>

        <div class="field">
          <div class="field-label">Phone Number:</div>
          <input type="tel" class="field-input" id="phone" name="phone_number" value="<?php echo $data['phone_number'] ?>" placeholder="Enter phone number">
          <span class="form-input-error"><?php echo $data['phone_number_err'];?></span>
        </div>

        
        
        <input type="hidden" id="logo_path" name="logo_path">

        <!-- Permissions Section -->
        <div class="permissions-section">
          <div class="permissions-title">
            <span class="material-symbols-outlined" style="font-size: 20px;">security</span>
            Assign Permissions
          </div>
          
          <div class="permissions-grid">
            <div class="permission-item">
              <input type="checkbox" id="perm_add_officers" name="permissions[]" value="add_officers" class="permission-checkbox">
              <label for="perm_add_officers" class="permission-label">Add Officers</label>
            </div>
            
            <div class="permission-item">
              <input type="checkbox" id="perm_add_clients" name="permissions[]" value="add_clients" class="permission-checkbox">
              <label for="perm_add_clients" class="permission-label">Add Clients</label>
            </div>
            
            <div class="permission-item">
              <input type="checkbox" id="perm_accept_client_requests" name="permissions[]" value="accept_client_requests" class="permission-checkbox">
              <label for="perm_accept_client_requests" class="permission-label">Accept Client Requests</label>
            </div>
            
            <div class="permission-item">
              <input type="checkbox" id="perm_assign_officers" name="permissions[]" value="assign_officers" class="permission-checkbox">
              <label for="perm_assign_officers" class="permission-label">Assign Officers</label>
            </div>
            
            <div class="permission-item">
              <input type="checkbox" id="perm_accept_leave_requests" name="permissions[]" value="accept_leave_requests" class="permission-checkbox">
              <label for="perm_accept_leave_requests" class="permission-label">Accept Leave Requests</label>
            </div>
            
            <div class="permission-item">
              <input type="checkbox" id="perm_create_advertisements" name="permissions[]" value="create_advertisements" class="permission-checkbox">
              <label for="perm_create_advertisements" class="permission-label">Create Advertisements</label>
            </div>
            
            <div class="permission-item">
              <input type="checkbox" id="perm_view_payments" name="permissions[]" value="view_payments" class="permission-checkbox">
              <label for="perm_view_payments" class="permission-label">View Client Payments</label>
            </div>
            
            <div class="permission-item">
              <input type="checkbox" id="perm_handle_incidents" name="permissions[]" value="handle_incidents" class="permission-checkbox">
              <label for="perm_handle_incidents" class="permission-label">Handle Incident Reports</label>
            </div>
            
            <div class="permission-item">
              <input type="checkbox" id="perm_create_routes" name="permissions[]" value="create_routes" class="permission-checkbox">
              <label for="perm_create_routes" class="permission-label">Create Routes</label>
            </div>
            
            <div class="permission-item">
              <input type="checkbox" id="perm_edit_officer_profiles" name="permissions[]" value="edit_officer_profiles" class="permission-checkbox">
              <label for="perm_edit_officer_profiles" class="permission-label">Edit Officer Profiles</label>
            </div>
          </div>
        </div>

        <input type="hidden" id="logo_path" name="logo_path">

        <div class="form-actions">
          <button type="button" class="btn btn-light" onclick="window.history.back()">Cancel</button>
          <input type="submit" value="Create" class="btn btn-primary">
        </div>
      </form>
    </div>
  </section>

  
</main>

    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    
    

<script>
  const addImageBtn = document.getElementById("addImageBtn");
  const removeImageBtn = document.getElementById("removeImageBtn");
  const imagePlaceholder = document.getElementById("imagePlaceholder");

  let inputPath = document.querySelector("#image");
  let file;

  // Get the default image path from data attribute
  const defaultImagePath = imagePlaceholder.getAttribute('data-default-src');

  function toggleBrowse(){
      inputPath.click();
  }

  function removeImage(){
      addImageBtn.style.display = "block";
      removeImageBtn.style.display = "none";
      imagePlaceholder.style.display = "block"; // Changed to "block" to show default image

      // Reset to default image
      imagePlaceholder.setAttribute('src', defaultImagePath);

      inputPath.value = null;
      file = null;
  }

  inputPath.addEventListener('change', function(){
      file = this.files[0];

      if (file) {
          addImageBtn.style.display = "none";
          removeImageBtn.style.display = "block";
          imagePlaceholder.style.display = "block";
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
          alert('This is not a valid image file'); // Fixed typo
          removeImage();
      }
  }

  document.addEventListener('DOMContentLoaded', function() {
      // Initialize with default image visible
      imagePlaceholder.style.display = "block";
      imagePlaceholder.setAttribute('src', defaultImagePath);
  });
</script>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>