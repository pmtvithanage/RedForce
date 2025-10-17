// ==============================
// PROFILE PAGE FUNCTIONALITY
// ==============================

// Global variables
let currentField = '';
let currentValue = '';

// DOM Elements
const editModal = document.getElementById('editModal');
const profileImageModal = document.getElementById('profileImageModal');
const editForm = document.getElementById('editForm');
const profileImageForm = document.getElementById('profileImageForm');
const modalTitle = document.getElementById('modalTitle');
const fieldLabel = document.getElementById('fieldLabel');
const fieldInput = document.getElementById('fieldInput');
const passwordInput = document.getElementById('passwordInput');
const confirmPasswordInput = document.getElementById('confirmPasswordInput');
const profileImageInput = document.getElementById('profileImageInput');
const imagePreview = document.getElementById('imagePreview');
const previewImg = document.getElementById('previewImg');

// ==============================
// MODAL FUNCTIONS
// ==============================

// Open edit modal for different fields
function openEditModal(field, value) {
    currentField = field;
    currentValue = value;
    
    // Reset form
    editForm.reset();
    fieldInput.style.display = 'block';
    passwordInput.style.display = 'none';
    confirmPasswordInput.style.display = 'none';
    
    // Configure modal based on field type
    switch(field) {
        case 'name':
            modalTitle.textContent = 'Change Name';
            fieldLabel.textContent = 'Full Name';
            fieldInput.value = value;
            fieldInput.type = 'text';
            fieldInput.placeholder = 'Enter your full name';
            break;
            
        case 'password':
            modalTitle.textContent = 'Change Password';
            fieldLabel.textContent = 'New Password';
            fieldInput.style.display = 'none';
            passwordInput.style.display = 'block';
            confirmPasswordInput.style.display = 'block';
            passwordInput.placeholder = 'Enter new password';
            confirmPasswordInput.placeholder = 'Confirm new password';
            break;
            
        case 'contact':
            modalTitle.textContent = 'Change Contact Number';
            fieldLabel.textContent = 'Contact Number';
            fieldInput.value = value;
            fieldInput.type = 'tel';
            fieldInput.placeholder = 'Enter contact number';
            break;
            
        case 'email':
            modalTitle.textContent = 'Change Email';
            fieldLabel.textContent = 'Email Address';
            fieldInput.value = value;
            fieldInput.type = 'email';
            fieldInput.placeholder = 'Enter email address';
            break;
    }
    
    // Show modal
    editModal.style.display = 'flex';
}

// Close edit modal
function closeEditModal() {
    editModal.style.display = 'none';
    editForm.reset();
}

// Open profile image modal
function openProfileImageModal() {
    profileImageModal.style.display = 'flex';
    imagePreview.style.display = 'none';
}

// Close profile image modal
function closeProfileImageModal() {
    profileImageModal.style.display = 'none';
    profileImageForm.reset();
    imagePreview.style.display = 'none';
}

// ==============================
// FORM HANDLERS
// ==============================

// Handle edit form submission
editForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    let newValue = '';
    let isValid = true;
    let errorMessage = '';
    
    // Validate based on field type
    switch(currentField) {
        case 'name':
            newValue = fieldInput.value.trim();
            if (newValue.length < 2) {
                isValid = false;
                errorMessage = 'Name must be at least 2 characters long';
            }
            break;
            
        case 'password':
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (password.length < 6) {
                isValid = false;
                errorMessage = 'Password must be at least 6 characters long';
            } else if (password !== confirmPassword) {
                isValid = false;
                errorMessage = 'Passwords do not match';
            } else {
                newValue = password;
            }
            break;
            
        case 'contact':
            newValue = fieldInput.value.trim();
            const phoneRegex = /^[0-9+\-\s()]+$/;
            if (!phoneRegex.test(newValue) || newValue.length < 10) {
                isValid = false;
                errorMessage = 'Please enter a valid contact number';
            }
            break;
            
        case 'email':
            newValue = fieldInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(newValue)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address';
            }
            break;
    }
    
    if (!isValid) {
        showNotification(errorMessage, 'error');
        return;
    }
    
    // Simulate saving (replace with actual AJAX call)
    updateProfileField(currentField, newValue);
});

// Handle profile image form submission
profileImageForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const file = profileImageInput.files[0];
    if (!file) {
        showNotification('Please select an image', 'error');
        return;
    }
    
    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!allowedTypes.includes(file.type)) {
        showNotification('Please select a valid image file (JPEG, PNG, GIF)', 'error');
        return;
    }
    
    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
        showNotification('Image size must be less than 5MB', 'error');
        return;
    }
    
    // Simulate upload (replace with actual AJAX call)
    uploadProfileImage(file);
});

// ==============================
// PROFILE UPDATE FUNCTIONS
// ==============================

// Update profile field
function updateProfileField(field, value) {
    // Show loading
    showNotification('Updating...', 'info');
    
    // Simulate API call
    setTimeout(() => {
        // Update the display value
        const infoRows = document.querySelectorAll('.info-row');
        infoRows.forEach(row => {
            const label = row.querySelector('.info-label').textContent.toLowerCase();
            
            if ((field === 'name' && label.includes('name')) ||
                (field === 'password' && label.includes('password')) ||
                (field === 'contact' && label.includes('contact')) ||
                (field === 'email' && label.includes('email'))) {
                
                const valueElement = row.querySelector('.info-value');
                if (field === 'password') {
                    valueElement.textContent = '- ••••••••';
                } else {
                    valueElement.textContent = '- ' + value;
                }
            }
        });
        
        // Close modal and show success
        closeEditModal();
        showNotification('Profile updated successfully!', 'success');
    }, 1000);
}

// Upload profile image
function uploadProfileImage(file) {
    // Show loading
    showNotification('Uploading image...', 'info');
    
    // Create FormData for file upload
    const formData = new FormData();
    formData.append('profile_image', file);
    
    // Simulate upload
    setTimeout(() => {
        // Update profile picture (in real app, use the returned URL)
        const reader = new FileReader();
        reader.onload = function(e) {
            const profilePicture = document.querySelector('.profile-picture');
            profilePicture.innerHTML = `<img src="${e.target.result}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
        };
        reader.readAsDataURL(file);
        
        // Close modal and show success
        closeProfileImageModal();
        showNotification('Profile image updated successfully!', 'success');
    }, 1500);
}

// ==============================
// EVENT LISTENERS
// ==============================

// Change Profile Image button
document.getElementById('changeProfileImageBtn').addEventListener('click', openProfileImageModal);

// Image preview
profileImageInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    if (e.target === editModal) {
        closeEditModal();
    }
    if (e.target === profileImageModal) {
        closeProfileImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
        closeProfileImageModal();
    }
});

// ==============================
// NOTIFICATION SYSTEM
// ==============================

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;
    
    // Style notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: ${getNotificationColor(type)};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        max-width: 400px;
        animation: slideIn 0.3s ease-out;
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Close button functionality
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.remove();
    });
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Get notification color based on type
function getNotificationColor(type) {
    switch (type) {
        case 'success':
            return '#28a745';
        case 'error':
            return '#dc3545';
        case 'warning':
            return '#ffc107';
        default:
            return '#17a2b8';
    }
}

// Add CSS for notification animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .notification-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        margin-left: 10px;
    }
`;
document.head.appendChild(style);

// ==============================
// INITIALIZATION
// ==============================

document.addEventListener('DOMContentLoaded', function() {
    console.log('Profile page loaded successfully!');
    
    // Add any initialization code here
    // For example, load user data from server
});

// Export functions for global access
window.openEditModal = openEditModal;
window.closeEditModal = closeEditModal;
window.openProfileImageModal = openProfileImageModal;
window.closeProfileImageModal = closeProfileImageModal;