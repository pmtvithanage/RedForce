<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/admin/settings_style.css">

<div class="container">
  <!-- Current Admins Section -->
  <div class="section current-admins">
    <h2>Current Admins</h2>
    <div class="admins-grid">
      <?php
      // Dynamic admin cards from database
      if (!empty($data['admins'])) {
        foreach ($data['admins'] as $admin): ?>
          <div class="admin-card">
            <div class="admin-photo">
              <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face" alt="<?= htmlspecialchars($admin->name) ?>">
            </div>
            <h3><?= htmlspecialchars($admin->name) ?></h3>
            <p class="admin-email"><?= htmlspecialchars($admin->email) ?></p>
            <p class="admin-userid">ID: <?= htmlspecialchars($admin->userID) ?></p>
            <button class="view-btn" data-userid="<?= htmlspecialchars($admin->userID) ?>">View</button>
          </div>
        <?php endforeach;
      } else {
        echo '<p>No admins found.</p>';
      }
      ?>
    </div>
  </div>

  <!-- Create User Section -->
  <div class="section create-user">
    <h2>Create User</h2>

    <!-- Success Message (hidden by default) -->
    <div id="successMessage" class="success-message" style="display: none;">
      <span class="success-icon">✅</span>
      <span class="success-text">User created successfully!</span>
    </div>

    <form id="createUserForm" method="POST" action="<?php echo URL_ROOT; ?>/admin/createUser">
      <div class="form-content">
        <div class="photo-upload">
          <div class="upload-area" id="uploadArea" tabindex="0" role="button" aria-label="Upload profile photo">
            <span>Upload Profile Photo</span>
            <input type="file" id="profilePhoto" accept="image/*" hidden>
          </div>
          <div class="preview-image" id="previewImage" style="display: none;">
            <img id="previewImg" src="" alt="Profile preview">
            <button type="button" class="remove-photo" id="removePhoto" aria-label="Remove photo">×</button>
          </div>
        </div>

        <div class="form-fields">
          <div class="input-group">
            <input type="text" id="userName" name="name" placeholder="Full Name" required>
            <span class="error-message" id="nameError"></span>
          </div>
          <div class="input-group">
            <input type="text" id="userNIC" name="nic" placeholder="NIC" required>
          </div>
          <div class="input-group">
            <input type="email" id="userEmail" name="email" placeholder="Email" required>
            <span class="error-message" id="emailError"></span>
          </div>
          <div class="input-group">
            <input type="tel" id="userMobile" name="mobile" placeholder="Mobile Number" required>
          </div>
          <div class="input-group">
            <input type="text" id="userAddress" name="address" placeholder="Address">
          </div>
          <div class="input-group">
            <select id="userRole" name="role" required>
              <option value="">Select Role</option>
              <option value="admin">Admin</option>
              <option value="client">Client</option>
              <option value="mobile rider">Mobile Rider</option>
              <option value="caretaker">Caretaker</option>
              <option value="supervisor">Supervisor</option>
              <option value="premise officer">Premise Officer</option>
            </select>
            <span class="error-message" id="roleError"></span>
          </div>
          <div class="input-group">
            <input type="password" id="userPassword" name="password" placeholder="Password" required>
            <span class="error-message" id="passwordError"></span>
          </div>
          <div class="input-group">
            <input type="password" id="confirmPassword" name="confirm_password" placeholder="Confirm Password" required>
            <span class="error-message" id="confirmPasswordError"></span>
          </div>
        </div>

        <!-- Additional Fields for Specific Roles -->
        <div class="role-specific-fields" id="roleSpecificFields" style="display: none;">
          <h4>Additional Information</h4>
          <div class="additional-fields" id="additionalFields">
            <!-- Additional fields will be dynamically populated based on role -->
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="create-btn">Create User</button>
      </div>
    </form>
  </div>
</div>

<!-- Cleanup stray closing tags -->
</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/admin/settings.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>