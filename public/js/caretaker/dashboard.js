// RED FORCE Care Taker Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the dashboard
    initializeDashboard();
    
    // Add event listeners
    addEventListeners();
});

function initializeDashboard() {
    console.log('RED FORCE Dashboard initialized');
    
    // Set current date and time
    updateDateTime();
    
    // Initialize file upload functionality
    initializeFileUpload();
}

function addEventListeners() {
    // Upload button functionality
    const uploadBtn = document.querySelector('.upload-btn');
    if (uploadBtn) {
        uploadBtn.addEventListener('click', handleUpload);
    }
    
    // Submit button functionality
    const submitBtn = document.querySelector('.submit-btn');
    if (submitBtn) {
        submitBtn.addEventListener('click', handleSubmit);
    }
    
    // Contact button functionality
    const contactBtn = document.querySelector('.contact-btn');
    if (contactBtn) {
        contactBtn.addEventListener('click', handleContact);
    }
    
    // Description area auto-resize
    const descriptionArea = document.querySelector('.description-area');
    if (descriptionArea) {
        descriptionArea.addEventListener('input', autoResizeTextarea);
    }
    
    // Menu item click handlers
    const menuItems = document.querySelectorAll('.menu-item a');
    menuItems.forEach(item => {
        item.addEventListener('click', handleMenuClick);
    });
}

function updateDateTime() {
    const now = new Date();
    const dateString = now.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    const timeString = now.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
    
    // You can add this to the header if needed
    console.log(`Current time: ${dateString} at ${timeString}`);
}

function removeFile(button) {
    const filePreview = button.parentElement;
    
    // Add fade out animation
    filePreview.style.transition = 'all 0.3s ease';
    filePreview.style.opacity = '0';
    filePreview.style.transform = 'scale(0.8)';
    
    setTimeout(() => {
        filePreview.remove();
        
        // Check if no files remain
        const remainingFiles = document.querySelectorAll('.file-preview');
        if (remainingFiles.length === 0) {
            showMessage('All files removed', 'info');
            
            // Hide submit button if no files remain
            const submitBtn = document.querySelector('.submit-btn');
            if (submitBtn) {
                submitBtn.classList.remove('show');
                setTimeout(() => {
                    submitBtn.style.display = 'none';
                }, 300);
            }
        }
    }, 300);
}

function handleUpload() {
    const description = document.querySelector('.description-area').value;
    const files = document.querySelectorAll('.file-preview');
    
    if (files.length === 0) {
        showMessage('Please add at least one file before uploading', 'warning');
        return;
    }
    
    if (!description.trim()) {
        showMessage('Please add a description before uploading', 'warning');
        return;
    }
    
    // Simulate upload process
    const uploadBtn = document.querySelector('.upload-btn');
    const submitBtn = document.querySelector('.submit-btn');
    const originalText = uploadBtn.textContent;
    
    uploadBtn.textContent = 'Uploading...';
    uploadBtn.disabled = true;
    
    setTimeout(() => {
        uploadBtn.textContent = 'Upload Complete!';
        uploadBtn.style.background = 'linear-gradient(135deg, #059669 0%, #10b981 100%)';
        
        showMessage('Files uploaded successfully!', 'success');
        
        // Show submit button after successful upload
        if (submitBtn) {
            submitBtn.style.display = 'block';
            setTimeout(() => {
                submitBtn.classList.add('show');
            }, 100);
        }
        
        // Reset upload button after 2 seconds
        setTimeout(() => {
            uploadBtn.textContent = originalText;
            uploadBtn.disabled = false;
            uploadBtn.style.background = 'linear-gradient(135deg, #dc2626 0%, #991b1b 100%)';
        }, 2000);
    }, 2000);
}

function handleSubmit() {
    const description = document.querySelector('.description-area').value;
    const files = document.querySelectorAll('.file-preview');
    const submitBtn = document.querySelector('.submit-btn');
    
    if (files.length === 0) {
        showMessage('No files to submit. Please upload files first.', 'warning');
        return;
    }
    
    if (!description.trim()) {
        showMessage('Please add a description before submitting', 'warning');
        return;
    }
    
    // Simulate submit process
    const originalText = submitBtn.textContent;
    
    submitBtn.textContent = 'Submitting...';
    submitBtn.disabled = true;
    submitBtn.style.background = 'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)';
    
    setTimeout(() => {
        submitBtn.textContent = 'Submitted Successfully!';
        submitBtn.style.background = 'linear-gradient(135deg, #059669 0%, #10b981 100%)';
        
        showMessage('Report submitted successfully!', 'success');
        
        // Reset form after successful submission
        setTimeout(() => {
            // Clear form
            document.querySelector('.description-area').value = '';
            document.querySelectorAll('.file-preview').forEach(file => file.remove());
            
            // Hide submit button
            submitBtn.classList.remove('show');
            setTimeout(() => {
                submitBtn.style.display = 'none';
            }, 300);
            
            // Reset submit button
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            submitBtn.style.background = 'linear-gradient(135deg, #059669 0%, #10b981 100%)';
        }, 2000);
    }, 2000);
}

function handleContact() {
    showMessage('Contact form will open in a new window', 'info');
    
    // Simulate opening contact form
    setTimeout(() => {
        const contactInfo = {
            phone: '+94 11 2345678',
            email: 'contact@redforce.com',
            address: 'Reid Avenue, Colombo 07, Sri Lanka'
        };
        
        alert(`Contact Information:\n\nPhone: ${contactInfo.phone}\nEmail: ${contactInfo.email}\nAddress: ${contactInfo.address}`);
    }, 500);
}

function handleMenuClick(e) {
    e.preventDefault();
    
    // Remove active class from all menu items
    document.querySelectorAll('.menu-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Add active class to clicked item
    const menuItem = e.target.closest('.menu-item');
    if (menuItem) {
        menuItem.classList.add('active');
    }
    
    // Handle different menu items
    const href = e.target.closest('a').getAttribute('href');
    
    switch(href) {
        case '#dashboard':
            showMessage('Dashboard loaded', 'info');
            break;
        case '#communicate':
            showMessage('Communication module will open', 'info');
            break;
        case '#incidents':
            showMessage('Incidents module will open', 'info');
            break;
        case '#settings':
            showMessage('Settings module will open', 'info');
            break;
    }
}

function initializeFileUpload() {
    // Create a hidden file input
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.multiple = true;
    fileInput.accept = 'image/*,.pdf,.doc,.docx';
    fileInput.style.display = 'none';
    
    // Add click handler to upload button to trigger file selection
    const uploadBtn = document.querySelector('.upload-btn');
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function(e) {
            // Only trigger file selection if no files are already uploaded
            const existingFiles = document.querySelectorAll('.file-preview');
            if (existingFiles.length === 0) {
                fileInput.click();
            }
        });
    }
    
    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        files.forEach(file => {
            addFilePreview(file);
        });
    });
    
    document.body.appendChild(fileInput);
}

function addFilePreview(file) {
    const filePreviews = document.querySelector('.file-previews');
    
    const filePreview = document.createElement('div');
    filePreview.className = 'file-preview';
    
    const fileName = document.createElement('span');
    fileName.className = 'file-name';
    fileName.textContent = file.name;
    
    const removeBtn = document.createElement('button');
    removeBtn.className = 'remove-file';
    removeBtn.textContent = '×';
    removeBtn.onclick = function() {
        removeFile(this);
    };
    
    filePreview.appendChild(fileName);
    filePreview.appendChild(removeBtn);
    
    // Add with animation
    filePreview.style.opacity = '0';
    filePreview.style.transform = 'scale(0.8)';
    filePreviews.appendChild(filePreview);
    
    setTimeout(() => {
        filePreview.style.transition = 'all 0.3s ease';
        filePreview.style.opacity = '1';
        filePreview.style.transform = 'scale(1)';
    }, 10);
}

function autoResizeTextarea(e) {
    const textarea = e.target;
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

function showMessage(message, type = 'info') {
    // Create message element
    const messageDiv = document.createElement('div');
    messageDiv.className = `message message-${type}`;
    messageDiv.textContent = message;
    
    // Style the message
    messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
    `;
    
    // Set background color based on type
    switch(type) {
        case 'success':
            messageDiv.style.background = '#059669';
            break;
        case 'warning':
            messageDiv.style.background = '#d97706';
            break;
        case 'error':
            messageDiv.style.background = '#dc2626';
            break;
        default:
            messageDiv.style.background = '#3b82f6';
    }
    
    // Add to page
    document.body.appendChild(messageDiv);
    
    // Animate in
    setTimeout(() => {
        messageDiv.style.transform = 'translateX(0)';
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        messageDiv.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(messageDiv);
        }, 300);
    }, 3000);
}

// Add some utility functions
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function validateFile(file) {
    const maxSize = 10 * 1024 * 1024; // 10MB
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    
    if (file.size > maxSize) {
        showMessage('File size too large. Maximum size is 10MB.', 'error');
        return false;
    }
    
    if (!allowedTypes.includes(file.type)) {
        showMessage('File type not allowed. Please upload images, PDFs, or Word documents.', 'error');
        return false;
    }
    
    return true;
}

// Export functions for global access
window.removeFile = removeFile;
window.handleUpload = handleUpload;
window.handleContact = handleContact;
window.handleSubmit = handleSubmit;

