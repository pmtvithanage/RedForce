
<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/caretaker/profile_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<main class="main-content">
    <div class="profile-container">
        <!-- Profile Header -->
        

        <!-- Profile Content -->
        <div class="profile-content">
            <!-- Profile Picture Section -->
            <div class="profile-picture-section">
                <div class="profile-picture">
                    <i class="fas fa-user profile-icon"></i>
                </div>
                <button class="change-profile-btn" id="changeProfileImageBtn">
                    <i class="fas fa-camera"></i>
                    Change Profile Image
                </button>
            </div>

            <!-- Profile Information -->
            <div class="profile-info-section">
                <!-- Care Taker Name -->
                <div class="info-row">
                    <div class="info-label">Care Taker Name</div>
                    <div class="info-value">- Abesekara</div>
                    <button class="change-btn" onclick="openEditModal('name', 'Abesekara')">
                        Change Name
                    </button>
                    <button class="edit-icon-btn">
                        <i class="fas fa-pen"></i>
                    </button>
                </div>

                <!-- Password -->
                <div class="info-row">
                    <div class="info-label">Password</div>
                    <div class="info-value">- ••••••••</div>
                    <button class="change-btn" onclick="openEditModal('password', '')">
                        Change Password
                    </button>
                    <button class="edit-icon-btn">
                        <i class="fas fa-pen"></i>
                    </button>
                </div>

                <!-- Contact Number -->
                <div class="info-row">
                    <div class="info-label">Contact Number</div>
                    <div class="info-value">- 0112 112 112</div>
                    <button class="change-btn" onclick="openEditModal('contact', '0112 112 112')">
                        Change Contact No
                    </button>
                    <button class="edit-icon-btn">
                        <i class="fas fa-pen"></i>
                    </button>
                </div>

                <!-- Email -->
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">- abesekara@hotmail.com</div>
                    <button class="change-btn" onclick="openEditModal('email', 'abesekara@hotmail.com')">
                        Change Email
                    </button>
                    <button class="edit-icon-btn">
                        <i class="fas fa-pen"></i>
                    </button>
                </div>

                <!-- Address -->
                <div class="info-row">
                    <div class="info-label">Address</div>
                    <div class="info-value">- 123 Main Street, Colombo 01, Sri Lanka</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Edit Information</h3>
                <button class="close-btn" onclick="closeEditModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label id="fieldLabel">Field</label>
                        <input type="text" id="fieldInput" required>
                        <input type="password" id="passwordInput" style="display: none;">
                        <input type="password" id="confirmPasswordInput" style="display: none;" placeholder="Confirm Password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Profile Image Modal -->
    <div id="profileImageModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Change Profile Image</h3>
                <button class="close-btn" onclick="closeProfileImageModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="profileImageForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select New Image</label>
                        <input type="file" id="profileImageInput" accept="image/*" required>
                        <div class="image-preview" id="imagePreview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeProfileImageModal()">Cancel</button>
                    <button type="submit" class="btn-save">Upload Image</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
<script src="<?php echo URL_ROOT; ?>/js/caretaker/profile.js"></script>