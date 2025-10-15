<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/profile.style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Content will be loaded here -->
<section class="settings-wrapper">
    <div class="profile-section">
        <div class="profile-circle">
            <img id="profileImage" src="<?php echo URL_ROOT; ?>/img/default-avatar.png" alt="Profile" style="display: none;">
            <i id="profileIcon" class="fas fa-user"></i>
            <input type="file" id="profileImageInput" accept="image/*" style="display: none;">
            
            <!-- Enhanced Change Profile Picture Icon -->
            <div class="profile-overlay" id="profileOverlay">
                <div class="change-picture-icon">
                    <i class="fas fa-camera"></i>
                    <span>Change Picture</span>
                </div>
            </div>
            
            <button class="edit-avatar" id="editAvatar" title="Change Profile Picture">
                <i class="fas fa-edit"></i>
            </button>
        </div>
        
        <!-- Change Profile Picture Button -->
       
    

                <div class="settings-card">
                    <div class="setting-row">
                        <label>Care Taker Name</label>
                        <div class="value" id="nameValue">- Abesekara</div>
                        <button class="ghost-btn" data-edit="name"><span>Change Name</span></button>
                        <button class="icon-btn" data-edit="name" title="Edit"><i class="fas fa-pen"></i></button>
                    </div>

                    <div class="setting-row">
                        <label>Password</label>
                        <div class="value" id="passwordValue">- ••••••••</div>
                        <button class="ghost-btn" data-edit="password"><span>Change Password</span></button>
                        <button class="icon-btn" data-edit="password" title="Edit"><i class="fas fa-pen"></i></button>
                    </div>

                    <div class="setting-row">
                        <label>Contact Number</label>
                        <div class="value" id="contactValue">- 0112 112 112</div>
                        <button class="danger-btn" data-edit="contact"><span>Change Contact No</span></button>
                        <button class="icon-btn" data-edit="contact" title="Edit"><i class="fas fa-pen"></i></button>
                    </div>

                    <div class="setting-row">
                        <label>Email</label>
                        <div class="value" id="emailValue">- abesekara@hotmail.com</div>
                        <button class="primary-btn" data-edit="email"><span>Change Email</span></button>
                        <button class="icon-btn" data-edit="email" title="Edit"><i class="fas fa-pen"></i></button>
                    </div>

                    <div class="setting-row read-only">
                        <label>Address</label>
                        <div class="value" id="addressValue">- 123 Main Street, Colombo 01, Sri Lanka</div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <div id="toast" class="toast" role="status" aria-live="polite"></div>
    <div class="backdrop" id="backdrop" hidden></div>

    <!-- Profile Image Modal -->
    <div class="modal-overlay" id="profileImageModal" hidden>
        <div class="modal">
            <div class="modal-header">
                <h2>Change Profile Picture</h2>
                <button type="button" class="modal-close" id="closeModalBtn" title="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content">
                <div class="upload-options">
                    <button class="upload-option" id="uploadImageBtn">
                        <i class="fas fa-upload"></i>
                        <span>Upload Image</span>
                    </button>
                    <button class="upload-option" id="useDefaultBtn">
                        <i class="fas fa-user"></i>
                        <span>Use Default Icon</span>
                    </button>
                    <button class="upload-option" id="removeImageBtn">
                        <i class="fas fa-trash"></i>
                        <span>Remove Image</span>
                    </button>
                </div>
                <div class="image-preview" id="imagePreview" style="display: none;">
                    <img id="previewImage" src="" alt="Preview">
                    <div class="preview-actions">
                        <button class="btn-save" id="saveImageBtn">Save</button>
                        <button class="btn-cancel" id="cancelImageBtn">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
    <script src="<?php echo URL_ROOT; ?>/js/supervisor/profile.js"></script>
