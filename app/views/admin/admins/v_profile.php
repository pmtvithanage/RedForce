<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Profile CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/components/profile_style.css">

<style>
    .avatar-circle-large {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f0f0f0; /* Fallback color if image fails to load */
    border: 4px solid #ffffff; /* Optional: white border */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Optional: subtle shadow */
}

.profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures the image covers the circle without distortion */
    object-position: center; /* Centers the image focus */
}

/* Optional: Hover effect */
.avatar-circle-large:hover .profile-image {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

/* Modal Styles */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    animation: fadeIn 0.2s ease;
}

.modal-overlay.active {
    display: flex;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from { transform: translateY(50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-header {
    background: var(--primary-color);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 12px 12px 0 0;
}

.modal-header h2 {
    margin: 0;
    font-size: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background 0.2s;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.modal-body {
    padding: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
    font-size: 14px;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s;
    box-sizing: border-box;
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary-color);
}

.modal-footer {
    padding: 20px 25px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.modal-btn {
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-cancel {
    background: #f5f5f5;
    color: #666;
}

.btn-cancel:hover {
    background: #e0e0e0;
}

.btn-confirm {
    background: var(--primary-color);
    color: white;
}

.btn-confirm:hover {
    background: #8b0000;
}

.btn-confirm:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Toast Notification */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 16px 24px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 10000;
    animation: slideInRight 0.3s ease;
    min-width: 300px;
}

@keyframes slideInRight {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.toast.success {
    border-left: 4px solid #4caf50;
}

.toast.error {
    border-left: 4px solid #f44336;
}

.toast-icon {
    font-size: 24px;
}

.toast.success .toast-icon {
    color: #4caf50;
}

.toast.error .toast-icon {
    color: #f44336;
}

.toast-message {
    flex: 1;
    color: #333;
    font-size: 14px;
}

.toast-close {
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    font-size: 20px;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.toast-close:hover {
    color: #333;
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
</style>

<div class="main-content">
    <div class="profile-container">
        


        <div class="profile-grid" style="margin-top:80px;">
            <!-- Profile Card -->
            <div class="profile-card">
                <div class="card-header">
                    <span class="material-icons">account_circle</span>
                    <h3>Profile Information</h3>
                </div>
                <div class="card-body">
                    <div class="profile-avatar-large">
                        <div class="avatar-circle-large">
                            <img class="profile-image" src="<?php echo URL_ROOT; ?>/uploads/image/<?php echo $data['admin']->profile_image; ?>" alt="Profile Image"> 
                        </div>
                        <button class="edit-avatar-btn" onclick="openImageModal()">
                            <span class="material-icons">photo_camera</span>
                        </button>
                    </div>
                    <div class="profile-name">
                        <?php echo $data['admin']->name; ?>
                    </div>
                    <div class="profile-role">Admin</div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="info-card">
                <div class="card-header">
                    <span class="material-icons">contact_phone</span>
                    <h3>Contact Information</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-icon">
                            <span class="material-icons">phone</span>
                        </div>
                        <div class="info-details">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value"><?php echo $data['admin']->phone_number; ?></div>
                        </div>
                        <button onclick="openPhoneModal()" class="action-btn" style="border: none; background: none; cursor: pointer;">
                            <span class="material-icons">edit</span>
                        </button>
                    </div>

                    <div class="info-row">
                        <div class="info-icon">
                            <span class="material-icons">email</span>
                        </div>
                        <div class="info-details">
                            <div class="info-label">Email Address</div>
                            <div class="info-value"><?php echo $data['admin']->email; ?></div>
                        </div>
                        <button onclick="openEmailModal()" class="action-btn" style="border: none; background: none; cursor: pointer;">
                            <span class="material-icons">edit</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Security Card -->
            <div class="security-card">
                <div class="card-header">
                    <span class="material-icons">security</span>
                    <h3>Security Settings</h3>
                </div>
                <div class="card-body">
                    <div class="security-item">
                        <div class="security-icon">
                            <span class="material-icons">lock</span>
                        </div>
                        <div class="security-details">
                            <div class="security-title">Password</div>
                            <div class="security-desc">Change your account password</div>
                        </div>
                        <button onclick="openPasswordModal()" class="btn-primary" style="border: none; cursor: pointer;">
                            Change Password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Phone Modal -->
<div class="modal-overlay" id="phoneModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                <span class="material-icons">phone</span>
                Update Phone Number
            </h2>
            <button class="modal-close" onclick="closePhoneModal()">
                <span class="material-icons">close</span>
            </button>
        </div>
        <form id="phoneForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="new_phone">New Phone Number</label>
                    <input type="tel" id="new_phone" name="phone_number" placeholder="Enter new phone number" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn btn-cancel" onclick="closePhoneModal()">
                    <span class="material-icons">close</span>
                    Cancel
                </button>
                <button type="submit" class="modal-btn btn-confirm" id="phoneBtn">
                    <span class="material-icons">save</span>
                    Update Phone
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Change Email Modal -->
<div class="modal-overlay" id="emailModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                <span class="material-icons">email</span>
                Update Email Address
            </h2>
            <button class="modal-close" onclick="closeEmailModal()">
                <span class="material-icons">close</span>
            </button>
        </div>
        <form id="emailForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="new_email">New Email Address</label>
                    <input type="email" id="new_email" name="email" placeholder="Enter new email address" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn btn-cancel" onclick="closeEmailModal()">
                    <span class="material-icons">close</span>
                    Cancel
                </button>
                <button type="submit" class="modal-btn btn-confirm" id="emailBtn">
                    <span class="material-icons">save</span>
                    Update Email
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal-overlay" id="passwordModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                <span class="material-icons">lock</span>
                Change Password
            </h2>
            <button class="modal-close" onclick="closePasswordModal()">
                <span class="material-icons">close</span>
            </button>
        </div>
        <form id="passwordForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" placeholder="Enter current password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn btn-cancel" onclick="closePasswordModal()">
                    <span class="material-icons">close</span>
                    Cancel
                </button>
                <button type="submit" class="modal-btn btn-confirm" id="passwordBtn">
                    <span class="material-icons">save</span>
                    Change Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Change Profile Image Modal -->
<div class="modal-overlay" id="imageModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                <span class="material-icons">photo_camera</span>
                Update Profile Picture
            </h2>
            <button class="modal-close" onclick="closeImageModal()">
                <span class="material-icons">close</span>
            </button>
        </div>
        <form id="imageForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="profile_image">Choose New Profile Picture</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn btn-cancel" onclick="closeImageModal()">
                    <span class="material-icons">close</span>
                    Cancel
                </button>
                <button type="submit" class="modal-btn btn-confirm" id="imageBtn">
                    <span class="material-icons">upload</span>
                    Upload Photo
                </button>
            </div>
        </form>
    </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script>
console.log('Profile page script loading...');
console.log('Checking for modal elements...');
console.log('phoneModal exists:', document.getElementById('phoneModal'));
console.log('emailModal exists:', document.getElementById('emailModal'));
console.log('passwordModal exists:', document.getElementById('passwordModal'));
console.log('imageModal exists:', document.getElementById('imageModal'));

// Toast Notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <span class="material-icons toast-icon">${type === 'success' ? 'check_circle' : 'error'}</span>
        <span class="toast-message">${message}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <span class="material-icons">close</span>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideInRight 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Phone Modal
function openPhoneModal() {
    console.log('openPhoneModal called!');
    const modal = document.getElementById('phoneModal');
    console.log('Phone modal element:', modal);
    if (modal) {
        console.log('Adding active class to modal');
        modal.classList.add('active');
        console.log('Modal classes after add:', modal.className);
    } else {
        console.error('Phone modal not found!');
    }
}

console.log('openPhoneModal function defined:', typeof openPhoneModal);

function closePhoneModal() {
    document.getElementById('phoneModal').classList.remove('active');
    document.getElementById('phoneForm').reset();
}

// Email Modal
function openEmailModal() {
    document.getElementById('emailModal').classList.add('active');
}

function closeEmailModal() {
    document.getElementById('emailModal').classList.remove('active');
    document.getElementById('emailForm').reset();
}

// Password Modal
function openPasswordModal() {
    document.getElementById('passwordModal').classList.add('active');
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.remove('active');
    document.getElementById('passwordForm').reset();
}

// Image Modal
function openImageModal() {
    document.getElementById('imageModal').classList.add('active');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.remove('active');
    document.getElementById('imageForm').reset();
}

// Handle Phone Update
document.getElementById('phoneForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const phoneBtn = document.getElementById('phoneBtn');
    phoneBtn.disabled = true;
    phoneBtn.innerHTML = '<span class="material-icons">hourglass_empty</span> Updating...';
    
    const formData = new FormData(this);
    
    fetch('<?php echo URL_ROOT; ?>/admin/updateProfilePhone', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closePhoneModal();
            showToast('Phone number updated successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Failed to update phone number', 'error');
            phoneBtn.disabled = false;
            phoneBtn.innerHTML = '<span class="material-icons">save</span> Update Phone';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while updating phone number', 'error');
        phoneBtn.disabled = false;
        phoneBtn.innerHTML = '<span class="material-icons">save</span> Update Phone';
    });
});

// Handle Email Update
document.getElementById('emailForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const emailBtn = document.getElementById('emailBtn');
    emailBtn.disabled = true;
    emailBtn.innerHTML = '<span class="material-icons">hourglass_empty</span> Updating...';
    
    const formData = new FormData(this);
    
    fetch('<?php echo URL_ROOT; ?>/admin/updateProfileEmail', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeEmailModal();
            showToast('Email address updated successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Failed to update email', 'error');
            emailBtn.disabled = false;
            emailBtn.innerHTML = '<span class="material-icons">save</span> Update Email';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while updating email', 'error');
        emailBtn.disabled = false;
        emailBtn.innerHTML = '<span class="material-icons">save</span> Update Email';
    });
});

// Handle Password Update
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        showToast('New passwords do not match', 'error');
        return;
    }
    
    if (newPassword.length < 6) {
        showToast('Password must be at least 6 characters long', 'error');
        return;
    }
    
    const passwordBtn = document.getElementById('passwordBtn');
    passwordBtn.disabled = true;
    passwordBtn.innerHTML = '<span class="material-icons">hourglass_empty</span> Updating...';
    
    const formData = new FormData(this);
    
    fetch('<?php echo URL_ROOT; ?>/admin/updateProfilePassword', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closePasswordModal();
            showToast('Password changed successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Failed to change password', 'error');
            passwordBtn.disabled = false;
            passwordBtn.innerHTML = '<span class="material-icons">save</span> Change Password';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while changing password', 'error');
        passwordBtn.disabled = false;
        passwordBtn.innerHTML = '<span class="material-icons">save</span> Change Password';
    });
});

// Handle Profile Image Update
document.getElementById('imageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const imageBtn = document.getElementById('imageBtn');
    imageBtn.disabled = true;
    imageBtn.innerHTML = '<span class="material-icons">hourglass_empty</span> Uploading...';
    
    const formData = new FormData(this);
    
    fetch('<?php echo URL_ROOT; ?>/admin/updateProfileImage', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeImageModal();
            showToast('Profile picture updated successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Failed to update profile picture', 'error');
            imageBtn.disabled = false;
            imageBtn.innerHTML = '<span class="material-icons">upload</span> Upload Photo';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while uploading image', 'error');
        imageBtn.disabled = false;
        imageBtn.innerHTML = '<span class="material-icons">upload</span> Upload Photo';
    });
});

// Close modals when clicking outside
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhoneModal();
        closeEmailModal();
        closePasswordModal();
        closeImageModal();
    }
});
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>       