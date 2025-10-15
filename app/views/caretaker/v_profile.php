
<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/caretaker/profile_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Profile Content -->
<div class="profile-content">
    <section class="settings-wrapper">
        <div class="profile-circle" id="profileOverlay">
            <img id="profileImage" class="profile-img" style="display: none;" alt="Profile Image">
            <i id="profileIcon" class="fas fa-user"></i>
        </div>
        
        <button class="change-profile-btn" id="changeProfileImageBtn">
            <i class="fas fa-camera"></i>
            Change Profile Image
        </button>

        <div class="settings-card">
            <div class="setting-row">
                <label>Care Taker Name</label>
                <div class="value" id="nameValue">- Abesekara</div>
                <button class="danger-btn" data-edit="name">Change Name</button>
                <button class="icon-btn" data-edit="name" title="Edit"><i class="fas fa-pen"></i></button>
            </div>
            <div class="setting-row">
                <label>Password</label>
                <div class="value" id="passwordValue">- ••••••••</div>
                <button class="danger-btn" data-edit="password">Change Password</button>
                <button class="icon-btn" data-edit="password" title="Edit"><i class="fas fa-pen"></i></button>
            </div>
            <div class="setting-row">
                <label>Contact Number</label>
                <div class="value" id="contactValue">- 0112 112 112</div>
                <button class="danger-btn" data-edit="contact">Change Contact No</button>
                <button class="icon-btn" data-edit="contact" title="Edit"><i class="fas fa-pen"></i></button>
            </div>
            <div class="setting-row">
                <label>Email</label>
                <div class="value" id="emailValue">- abesekara@hotmail.com</div>
                <button class="primary-btn" data-edit="email">Change Email</button>
                <button class="icon-btn" data-edit="email" title="Edit"><i class="fas fa-pen"></i></button>
            </div>
            <div class="setting-row read-only">
                <label>Address</label>
                <div class="value" id="addressValue" style="font-style:italic;color:#888;">- 123 Main Street, Colombo 01, Sri Lanka</div>
            </div>
        </div>
    </section>
</div>
</main>
</div>

<div id="toast" class="toast" role="status" aria-live="polite"></div>

<!-- Profile Image Modal -->
<div id="profileImageModal" class="profile-modal" hidden>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Change Profile Picture</h3>
            <button class="close-btn" id="closeModalBtn">&times;</button>
        </div>
        <div class="modal-body">
            <div class="upload-section">
                <button class="upload-btn" id="uploadImageBtn">
                    <i class="fas fa-upload"></i>
                    Upload New Image
                </button>
                <input type="file" id="profileImageInput" accept="image/*" style="display: none;">
                <p class="upload-hint">Select a JPG, PNG, or GIF image (max 5MB)</p>
            </div>
            
            <div class="image-preview" id="imagePreview" style="display: none;">
                <img id="previewImage" alt="Preview">
                <div class="preview-actions">
                    <button class="save-btn" id="saveImageBtn">Save Image</button>
                    <button class="cancel-btn" id="cancelImageBtn">Cancel</button>
                </div>
            </div>
            
            <div class="default-options">
                <button class="default-btn" id="useDefaultBtn">
                    <i class="fas fa-user"></i>
                    Use Default Icon
                </button>
                <button class="remove-btn" id="removeImageBtn">
                    <i class="fas fa-trash"></i>
                    Remove Current Image
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
<script src="<?php echo URL_ROOT; ?>/js/caretaker/profile.js"></script>