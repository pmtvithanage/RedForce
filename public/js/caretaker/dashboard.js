// Profile Image Management (View Only)
let selectedImageFile = null;

// Profile Image functionality (Read-only for dashboard)
function initializeProfileImage() {
    console.log('Initializing read-only profile image functionality...');
    
    const profileOverlay = document.getElementById('profileOverlay');
    const profileImage = document.getElementById('profileImage');
    const profileIcon = document.getElementById('profileIcon');

    // Handle click on readonly profile overlay - disabled (no-op)
    if (profileOverlay) {
        profileOverlay.addEventListener('click', (e) => {
            // Prevent any action when clicking the profile overlay on dashboard
            e.stopPropagation();
            console.log('Profile overlay click on dashboard is disabled.');
            return;
        });
    }

    // Load saved profile image from localStorage (if any)
    const savedImage = localStorage.getItem('caretakerProfileImage');
    if (savedImage) {
        profileImage.src = savedImage;
        profileImage.style.display = 'block';
        profileIcon.style.display = 'none';
    }
}

// File upload functionality
document.addEventListener('DOMContentLoaded', function() {
    // File upload button functionality
    const uploadBtn = document.querySelector('.upload-btn');
    const submitBtn = document.querySelector('.submit-btn');
    const descriptionArea = document.querySelector('.description-area');
    
    // Create hidden file input
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.multiple = true;
    fileInput.accept = 'image/*';
    fileInput.style.display = 'none';
    document.body.appendChild(fileInput);
    
    // Upload button click handler
    uploadBtn.addEventListener('click', function() {
        fileInput.click();
    });
    
    // File input change handler
    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const filePreviews = document.querySelector('.file-previews');
        
        files.forEach(file => {
            if (file.type.startsWith('image/')) {
                const filePreview = document.createElement('div');
                filePreview.className = 'file-preview';
                filePreview.innerHTML = `
                    <span class="file-name">${file.name}</span>
                    <button class="remove-file" onclick="removeFile(this)">×</button>
                `;
                filePreviews.appendChild(filePreview);
            }
        });
        
        // Show submit button if files are uploaded
        if (filePreviews.children.length > 0) {
            submitBtn.style.display = 'block';
        }
    });
    
    // Submit button click handler
    submitBtn.addEventListener('click', function() {
        const description = descriptionArea.value.trim();
        const fileCount = document.querySelectorAll('.file-preview').length;
        
        if (!description) {
            showNotification('Please add a description', 'error');
            return;
        }
        
        if (fileCount === 0) {
            showNotification('Please upload at least one photo', 'error');
            return;
        }
        
        // Simulate form submission
        showNotification('Report submitted successfully!', 'success');
        
        // Reset form
        descriptionArea.value = '';
        fileInput.value = '';
        document.querySelector('.file-previews').innerHTML = '';
        submitBtn.style.display = 'none';
    });
});

// Remove file function
function removeFile(button) {
    const filePreview = button.parentElement;
    filePreview.remove();
    
    // Hide submit button if no files remain
    const filePreviews = document.querySelector('.file-previews');
    const submitBtn = document.querySelector('.submit-btn');
    
    if (filePreviews.children.length === 0) {
        submitBtn.style.display = 'none';
    }
}

// Notification system
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
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: ${getNotificationColor(type)};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        max-width: 400px;
        animation: slideIn 0.3s ease-out;
    `;
    
    // Add animation styles
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
    `;
    document.head.appendChild(style);
    
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

// Sidebar navigation functionality
document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.menu-item a');
    
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all items
            menuItems.forEach(menuItem => {
                menuItem.parentElement.classList.remove('active');
            });
            
            // Add active class to clicked item
            this.parentElement.classList.add('active');
            
            // Show notification for navigation
            const pageName = this.querySelector('span').textContent;
            showNotification(`Navigating to ${pageName}`, 'info');
        });
    });
});

// Profile section interactions
document.addEventListener('DOMContentLoaded', function() {
    const profileSection = document.querySelector('.profile-section');
    
    if (profileSection) {
        profileSection.addEventListener('click', function() {
            this.style.transform = 'scale(1.02)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 200);
        });
    }
});

// Instructions section interactions
document.addEventListener('DOMContentLoaded', function() {
    const instructionsContent = document.querySelector('.instructions-content');
    
    if (instructionsContent) {
        instructionsContent.addEventListener('click', function() {
            this.style.backgroundColor = '#fce4ec';
            setTimeout(() => {
                this.style.backgroundColor = '#fef2f2';
            }, 300);
        });
    }
});

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    console.log('RED FORCE Care Taker Dashboard loaded successfully!');
    
    // Initialize profile image functionality
    initializeProfileImage();
    
    // Add some interactive features
    const sections = document.querySelectorAll('section');
    sections.forEach(section => {
        section.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.15)';
        });
        
        section.addEventListener('mouseleave', function() {
            this.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        });
    });
});
