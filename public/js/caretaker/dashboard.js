// Minimal JavaScript for Caretaker Dashboard
// Most functionality handled by PHP

document.addEventListener('DOMContentLoaded', function() {
    console.log('Caretaker Dashboard loaded');
    
    // Update notification count
    updateNotificationCount();
    
    // File upload functionality
    const uploadBtn = document.getElementById('uploadBtn');
    const fileInput = document.getElementById('photoUpload');
    const fileList = document.getElementById('fileList');
    const submitBtn = document.getElementById('submitBtn');
    
    // Upload button triggers file input
    if (uploadBtn && fileInput) {
        uploadBtn.addEventListener('click', function() {
            fileInput.click();
        });
    }
    
    // File input change - show previews
    if (fileInput && fileList && submitBtn) {
        fileInput.addEventListener('change', function(e) {
            fileList.innerHTML = '';
            const files = Array.from(e.target.files);
            
            if (files.length > 0) {
                files.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-preview';
                    fileItem.innerHTML = `
                        <span class="file-name">${file.name}</span>
                        <button type="button" class="remove-file" onclick="removeFile(${index})">×</button>
                    `;
                    fileList.appendChild(fileItem);
                });
                submitBtn.style.display = 'block';
            } else {
                submitBtn.style.display = 'none';
            }
        });
    }
});

// Notification functions
function dismissNotification(button) {
    const notificationItem = button.closest('.notification-item');
    notificationItem.style.opacity = '0';
    notificationItem.style.transform = 'translateX(100px)';
    
    setTimeout(() => {
        notificationItem.remove();
        updateNotificationCount();
        checkEmptyState();
    }, 300);
}

function markAllAsRead() {
    const notifications = document.querySelectorAll('.notification-item');
    notifications.forEach((notification, index) => {
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100px)';
            
            setTimeout(() => {
                notification.remove();
                if (index === notifications.length - 1) {
                    updateNotificationCount();
                    checkEmptyState();
                }
            }, 300);
        }, index * 100);
    });
}

function updateNotificationCount() {
    const badge = document.getElementById('notificationCount');
    const notifications = document.querySelectorAll('.notification-item');
    const count = notifications.length;
    
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }
}

function checkEmptyState() {
    const container = document.querySelector('.notifications-container');
    const notifications = document.querySelectorAll('.notification-item');
    const emptyState = document.querySelector('.notifications-empty');
    const footer = document.querySelector('.notifications-footer');
    
    if (notifications.length === 0 && emptyState) {
        emptyState.style.display = 'block';
        if (footer) footer.style.display = 'none';
    }
}

// Remove file from list
function removeFile(index) {
    const fileInput = document.getElementById('photoUpload');
    const fileList = document.getElementById('fileList');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!fileInput) return;
    
    // Create new FileList without the removed file
    const dt = new DataTransfer();
    const files = Array.from(fileInput.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    fileInput.files = dt.files;
    
    // Update preview
    fileList.innerHTML = '';
    if (fileInput.files.length > 0) {
        Array.from(fileInput.files).forEach((file, i) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-preview';
            fileItem.innerHTML = `
                <span class="file-name">${file.name}</span>
                <button type="button" class="remove-file" onclick="removeFile(${i})">×</button>
            `;
            fileList.appendChild(fileItem);
        });
        submitBtn.style.display = 'block';
    } else {
        submitBtn.style.display = 'none';
    }
}
