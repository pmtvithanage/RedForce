document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const uploadArea = document.getElementById('uploadArea');
    const profilePhotoInput = document.getElementById('profilePhoto');
    const previewImage = document.getElementById('previewImage');
    const previewImg = document.getElementById('previewImg');
    const removePhotoBtn = document.getElementById('removePhoto');
    const createAdminForm = document.getElementById('createAdminForm');
    const viewButtons = document.querySelectorAll('.view-btn');

    // Image Upload Functionality
    uploadArea.addEventListener('click', function() {
        profilePhotoInput.click();
    });

    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = '#ff5252';
        uploadArea.style.background = '#ffe6e6';
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = '#ff6b6b';
        uploadArea.style.background = '#fff5f5';
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = '#ff6b6b';
        uploadArea.style.background = '#fff5f5';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleImageUpload(files[0]);
        }
    });

    profilePhotoInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleImageUpload(e.target.files[0]);
        }
    });

    function handleImageUpload(file) {
        if (!file.type.startsWith('image/')) {
            showNotification('Please select an image file', 'error');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            uploadArea.style.display = 'none';
            previewImage.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    removePhotoBtn.addEventListener('click', function() {
        previewImage.style.display = 'none';
        uploadArea.style.display = 'flex';
        profilePhotoInput.value = '';
    });

    // View Button Functionality
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const adminCard = this.closest('.admin-card');
            const adminName = adminCard.querySelector('h3').textContent;
            showAdminDetails(adminName);
        });
    });

    function showAdminDetails(adminName) {
        // Create modal for admin details
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-header">
                    <h2>Admin Profile</h2>
                    <button class="edit-toggle-btn" id="editToggleBtn">
                        <span class="edit-icon">✏️</span>
                        <span class="edit-text">Edit</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="profile-section">
                        <div class="profile-photo-container">
                            <div class="profile-photo">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&h=200&fit=crop&crop=face" alt="${adminName}" id="profileImage">
                            </div>
                            <div class="photo-upload-overlay" id="photoUploadOverlay" style="display: none;">
                                <input type="file" id="profilePhotoUpload" accept="image/*" hidden>
                                <span>Change Photo</span>
                            </div>
                        </div>
                        <h2 class="admin-name" id="adminNameDisplay">${adminName}</h2>
                        <div class="contact-details">
                            <div class="contact-item">
                                <strong>NIC :</strong> 
                                <span class="contact-value" id="nicDisplay">199027881997</span>
                                <input type="text" class="contact-input" id="nicInput" value="199027881997" style="display: none;">
                            </div>
                            <div class="contact-item">
                                <strong>Email :</strong> 
                                <span class="contact-value" id="emailDisplay">adikari11@gmail.com</span>
                                <input type="email" class="contact-input" id="emailInput" value="adikari11@gmail.com" style="display: none;">
                            </div>
                            <div class="contact-item">
                                <strong>Mobile No :</strong> 
                                <span class="contact-value" id="mobileDisplay">0778912342</span>
                                <input type="tel" class="contact-input" id="mobileInput" value="0778912342" style="display: none;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="permissions-section">
                        <h3>Permissions</h3>
                        <div class="permissions-list">
                            <div class="permission-item" data-permission="addOfficers">
                                <span class="permission-text">Add Officers</span>
                                <span class="permission-status granted">✓</span>
                                <input type="checkbox" class="permission-checkbox" checked style="display: none;">
                            </div>
                            <div class="permission-item" data-permission="addClients">
                                <span class="permission-text">Add Clients</span>
                                <span class="permission-status granted">✓</span>
                                <input type="checkbox" class="permission-checkbox" checked style="display: none;">
                            </div>
                            <div class="permission-item" data-permission="scheduling">
                                <span class="permission-text">Scheduling</span>
                                <span class="permission-status granted">✓</span>
                                <input type="checkbox" class="permission-checkbox" checked style="display: none;">
                            </div>
                            <div class="permission-item" data-permission="salaryAdjustment">
                                <span class="permission-text">Salary Adjustment</span>
                                <span class="permission-status granted">✓</span>
                                <input type="checkbox" class="permission-checkbox" checked style="display: none;">
                            </div>
                            <div class="permission-item" data-permission="resolveIncidents">
                                <span class="permission-text">Resolve Incidents</span>
                                <span class="permission-status granted">✓</span>
                                <input type="checkbox" class="permission-checkbox" checked style="display: none;">
                            </div>
                            <div class="permission-item" data-permission="publishAdvertisements">
                                <span class="permission-text">Publish Advertisements</span>
                                <span class="permission-status granted">✓</span>
                                <input type="checkbox" class="permission-checkbox" checked style="display: none;">
                            </div>
                            <div class="permission-item" data-permission="updateProfiles">
                                <span class="permission-text">Update Profiles</span>
                                <span class="permission-status denied">✗</span>
                                <input type="checkbox" class="permission-checkbox" style="display: none;">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button class="save-btn" id="saveBtn" style="display: none;">Save Changes</button>
                    <button class="cancel-btn" id="cancelBtn" style="display: none;">Cancel</button>
                    <button class="remove-btn" id="removeBtn">Remove Admin</button>
                    <button class="close-btn" id="closeBtn">Close</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Get modal elements
        const editToggleBtn = modal.querySelector('#editToggleBtn');
        const saveBtn = modal.querySelector('#saveBtn');
        const cancelBtn = modal.querySelector('#cancelBtn');
        const removeBtn = modal.querySelector('#removeBtn');
        const closeBtn = modal.querySelector('#closeBtn');
        const photoUploadOverlay = modal.querySelector('#photoUploadOverlay');
        const profilePhotoUpload = modal.querySelector('#profilePhotoUpload');
        const profileImage = modal.querySelector('#profileImage');

        // Store original values for cancel functionality
        const originalValues = {
            nic: modal.querySelector('#nicInput').value,
            email: modal.querySelector('#emailInput').value,
            mobile: modal.querySelector('#mobileInput').value,
            permissions: {}
        };

        // Store original permission states
        modal.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            const permissionItem = checkbox.closest('.permission-item');
            const permissionName = permissionItem.dataset.permission;
            originalValues.permissions[permissionName] = checkbox.checked;
        });

        let isEditing = false;

        // Edit toggle functionality
        editToggleBtn.addEventListener('click', function() {
            isEditing = !isEditing;
            
            if (isEditing) {
                // Enter edit mode
                editToggleBtn.classList.add('editing');
                editToggleBtn.querySelector('.edit-text').textContent = 'Cancel Edit';
                editToggleBtn.querySelector('.edit-icon').textContent = '❌';
                
                // Show edit elements
                modal.querySelectorAll('.contact-value').forEach(span => span.style.display = 'none');
                modal.querySelectorAll('.contact-input').forEach(input => input.style.display = 'block');
                modal.querySelectorAll('.permission-status').forEach(status => status.style.display = 'none');
                modal.querySelectorAll('.permission-checkbox').forEach(checkbox => checkbox.style.display = 'block');
                modal.querySelectorAll('.permission-item').forEach(item => item.classList.add('editing'));
                photoUploadOverlay.style.display = 'flex';
                
                // Show save/cancel buttons
                saveBtn.style.display = 'block';
                cancelBtn.style.display = 'block';
                closeBtn.style.display = 'none';
            } else {
                // Exit edit mode
                exitEditMode();
            }
        });

        // Save functionality
        saveBtn.addEventListener('click', function() {
            // Validate inputs
            const nic = modal.querySelector('#nicInput').value.trim();
            const email = modal.querySelector('#emailInput').value.trim();
            const mobile = modal.querySelector('#mobileInput').value.trim();

            if (!nic || !email || !mobile) {
                showNotification('Please fill in all fields', 'error');
                return;
            }

            if (!isValidEmail(email)) {
                showNotification('Please enter a valid email', 'error');
                return;
            }

            if (!isValidMobile(mobile)) {
                showNotification('Please enter a valid mobile number', 'error');
                return;
            }

            // Update display values
            modal.querySelector('#nicDisplay').textContent = nic;
            modal.querySelector('#emailDisplay').textContent = email;
            modal.querySelector('#mobileDisplay').textContent = mobile;

            // Update permission displays
            modal.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                const permissionItem = checkbox.closest('.permission-item');
                const statusElement = permissionItem.querySelector('.permission-status');
                
                if (checkbox.checked) {
                    statusElement.textContent = '✓';
                    statusElement.className = 'permission-status granted';
                } else {
                    statusElement.textContent = '✗';
                    statusElement.className = 'permission-status denied';
                }
            });

            // Exit edit mode
            exitEditMode();
            
            // Show success message
            showNotification('Admin profile updated successfully!', 'success');
        });

        // Cancel functionality
        cancelBtn.addEventListener('click', function() {
            // Restore original values
            modal.querySelector('#nicInput').value = originalValues.nic;
            modal.querySelector('#emailInput').value = originalValues.email;
            modal.querySelector('#mobileInput').value = originalValues.mobile;

            // Restore original permissions
            modal.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                const permissionItem = checkbox.closest('.permission-item');
                const permissionName = permissionItem.dataset.permission;
                checkbox.checked = originalValues.permissions[permissionName];
            });

            exitEditMode();
        });

        function exitEditMode() {
            isEditing = false;
            
            // Reset edit button
            editToggleBtn.classList.remove('editing');
            editToggleBtn.querySelector('.edit-text').textContent = 'Edit';
            editToggleBtn.querySelector('.edit-icon').textContent = '✏️';
            
            // Hide edit elements
            modal.querySelectorAll('.contact-value').forEach(span => span.style.display = 'block');
            modal.querySelectorAll('.contact-input').forEach(input => input.style.display = 'none');
            modal.querySelectorAll('.permission-status').forEach(status => status.style.display = 'flex');
            modal.querySelectorAll('.permission-checkbox').forEach(checkbox => checkbox.style.display = 'none');
            modal.querySelectorAll('.permission-item').forEach(item => item.classList.remove('editing'));
            photoUploadOverlay.style.display = 'none';
            
            // Show close button
            saveBtn.style.display = 'none';
            cancelBtn.style.display = 'none';
            closeBtn.style.display = 'block';
        }

        // Photo upload functionality
        photoUploadOverlay.addEventListener('click', function() {
            profilePhotoUpload.click();
        });

        profilePhotoUpload.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                if (!file.type.startsWith('image/')) {
                    showNotification('Please select an image file', 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Permission toggle functionality
        modal.querySelectorAll('.permission-item').forEach(item => {
            item.addEventListener('click', function() {
                if (isEditing) {
                    const checkbox = this.querySelector('.permission-checkbox');
                    checkbox.checked = !checkbox.checked;
                }
            });
        });

        // Remove admin functionality
        removeBtn.addEventListener('click', function() {
            showConfirmationModal(adminName, modal);
        });

        // Close modal functionality
        closeBtn.addEventListener('click', function() {
            document.body.removeChild(modal);
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                document.body.removeChild(modal);
            }
        });

        // Add modal styles
        const modalStyles = `
            <style>
                .modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 1000;
                }
                .modal-content {
                    background: white;
                    padding: 0;
                    border-radius: 15px;
                    max-width: 600px;
                    width: 90%;
                    position: relative;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                    overflow: hidden;
                }
                .close {
                    position: absolute;
                    top: 15px;
                    right: 20px;
                    font-size: 24px;
                    cursor: pointer;
                    color: #999;
                    z-index: 10;
                }
                .close:hover {
                    color: #333;
                }
                .modal-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 20px 30px;
                    background: #fafafa;
                    border-bottom: 1px solid #eee;
                }
                .modal-header h2 {
                    font-size: 20px;
                    font-weight: 600;
                    color: #333;
                    margin: 0;
                }
                .edit-toggle-btn {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    padding: 8px 16px;
                    background: #ff6b6b;
                    color: white;
                    border: none;
                    border-radius: 20px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: 500;
                    transition: all 0.3s ease;
                }
                .edit-toggle-btn:hover {
                    background: #ff5252;
                    transform: translateY(-1px);
                }
                .edit-toggle-btn.editing {
                    background: #ff5252;
                }
                .modal-body {
                    display: flex;
                    min-height: 400px;
                }
                .profile-section {
                    flex: 1;
                    padding: 30px;
                    background: #fafafa;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                }
                .profile-photo-container {
                    position: relative;
                    margin-bottom: 20px;
                }
                .profile-photo {
                    width: 120px;
                    height: 120px;
                    border-radius: 50%;
                    overflow: hidden;
                    border: 4px solid #ff6b6b;
                }
                .profile-photo img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
                .photo-upload-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(255, 107, 107, 0.8);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    color: white;
                    font-size: 12px;
                    font-weight: 500;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }
                .photo-upload-overlay:hover {
                    opacity: 1;
                }
                .admin-name {
                    font-size: 24px;
                    font-weight: 700;
                    color: #333;
                    margin-bottom: 20px;
                }
                .contact-details {
                    text-align: left;
                    width: 100%;
                }
                .contact-item {
                    padding: 8px 0;
                    font-size: 14px;
                    color: #333;
                    border-bottom: 1px solid #eee;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }
                .contact-item:last-child {
                    border-bottom: none;
                }
                .contact-value {
                    flex: 1;
                }
                .contact-input {
                    flex: 1;
                    padding: 6px 10px;
                    border: 2px solid #ff6b6b;
                    border-radius: 6px;
                    font-size: 14px;
                    background: white;
                }
                .contact-input:focus {
                    outline: none;
                    border-color: #ff5252;
                    box-shadow: 0 0 0 2px rgba(255, 82, 82, 0.1);
                }
                .permissions-section {
                    flex: 1;
                    padding: 30px;
                    background: white;
                }
                .permissions-section h3 {
                    font-size: 18px;
                    font-weight: 600;
                    color: #333;
                    margin-bottom: 20px;
                }
                .permissions-list {
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                }
                .permission-item {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 12px 15px;
                    background: #fff5f5;
                    border-radius: 8px;
                    border: 1px solid #ffe6e6;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }
                .permission-item:hover {
                    background: #ffe6e6;
                }
                .permission-item.editing {
                    background: #ffe6e6;
                    border-color: #ff6b6b;
                }
                .permission-text {
                    font-size: 14px;
                    color: #333;
                    font-weight: 500;
                }
                .permission-status {
                    font-size: 16px;
                    font-weight: bold;
                    width: 20px;
                    height: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                }
                .permission-status.granted {
                    color: #4CAF50;
                    background: #e8f5e8;
                }
                .permission-status.denied {
                    color: #f44336;
                    background: #ffe6e6;
                }
                .permission-checkbox {
                    width: 18px;
                    height: 18px;
                    accent-color: #ff6b6b;
                }
                .modal-actions {
                    display: flex;
                    gap: 15px;
                    justify-content: center;
                    padding: 20px 30px;
                    background: #fafafa;
                    border-top: 1px solid #eee;
                }
                .save-btn, .close-btn, .cancel-btn, .remove-btn {
                    padding: 12px 25px;
                    border: none;
                    border-radius: 25px;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 14px;
                    transition: all 0.3s ease;
                }
                .save-btn {
                    background: #4CAF50;
                    color: white;
                }
                .save-btn:hover {
                    background: #45a049;
                    transform: translateY(-2px);
                }
                .cancel-btn {
                    background: #ff9800;
                    color: white;
                }
                .cancel-btn:hover {
                    background: #f57c00;
                    transform: translateY(-2px);
                }
                .close-btn {
                    background: #ff6b6b;
                    color: white;
                }
                .close-btn:hover {
                    background: #ff5252;
                    transform: translateY(-2px);
                }
                .remove-btn {
                    background: #f44336;
                    color: white;
                }
                .remove-btn:hover {
                    background: #d32f2f;
                    transform: translateY(-2px);
                }
                @media (max-width: 768px) {
                    .modal-body {
                        flex-direction: column;
                    }
                    .profile-section, .permissions-section {
                        padding: 20px;
                    }
                    .modal-actions {
                        flex-direction: column;
                        gap: 10px;
                    }
                    .modal-header {
                        padding: 15px 20px;
                    }
                }
            </style>
        `;
        document.head.insertAdjacentHTML('beforeend', modalStyles);
    }

    // Form Submission
    createAdminForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = {
            name: document.getElementById('adminName').value,
            nic: document.getElementById('adminNIC').value,
            email: document.getElementById('adminEmail').value,
            mobile: document.getElementById('adminMobile').value,
            profilePhoto: profilePhotoInput.files[0],
            permissions: {
                addOfficers: document.getElementById('addOfficers').checked,
                addClients: document.getElementById('addClients').checked,
                scheduling: document.getElementById('scheduling').checked,
                salaryAdjustment: document.getElementById('salaryAdjustment').checked,
                resolveIncidents: document.getElementById('resolveIncidents').checked,
                publishAdvertisements: document.getElementById('publishAdvertisements').checked,
                updateProfiles: document.getElementById('updateProfiles').checked
            }
        };

        // Validate form
        if (!validateForm(formData)) {
            return;
        }

        // Simulate form submission
        submitForm(formData);
    });

    function validateForm(data) {
        if (!data.name.trim()) {
            showNotification('Please enter admin name', 'error');
            return false;
        }
        if (!data.nic.trim()) {
            showNotification('Please enter NIC', 'error');
            return false;
        }
        if (!data.email.trim()) {
            showNotification('Please enter email', 'error');
            return false;
        }
        if (!isValidEmail(data.email)) {
            showNotification('Please enter a valid email', 'error');
            return false;
        }
        if (!data.mobile.trim()) {
            showNotification('Please enter mobile number', 'error');
            return false;
        }
        if (!isValidMobile(data.mobile)) {
            showNotification('Please enter a valid mobile number', 'error');
            return false;
        }
        return true;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidMobile(mobile) {
        const mobileRegex = /^[\+]?[1-9][\d]{0,15}$/;
        return mobileRegex.test(mobile.replace(/\s/g, ''));
    }

    function submitForm(data) {
        // Show loading state
        const submitBtn = createAdminForm.querySelector('.create-btn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Creating...';
        submitBtn.disabled = true;

        // Remove any existing success message
        const existingMessage = createAdminForm.querySelector('.success-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Simulate API call
        setTimeout(() => {
            // Create success message
            const successMessage = document.createElement('div');
            successMessage.className = 'success-message';
            successMessage.innerHTML = `
                <div class="success-content">
                    <span class="success-icon">✅</span>
                    <span class="success-text">Admin created successfully!</span>
                </div>
            `;
            
            // Insert success message after the form actions
            const formActions = createAdminForm.querySelector('.form-actions');
            formActions.parentNode.insertBefore(successMessage, formActions.nextSibling);
            
            // Reset form
            createAdminForm.reset();
            previewImage.style.display = 'none';
            uploadArea.style.display = 'flex';
            
            // Reset button
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            
            // Add new admin to the grid (simulation)
            addNewAdminToGrid(data);
            
            // Remove success message after 5 seconds
            setTimeout(() => {
                if (successMessage.parentNode) {
                    successMessage.style.animation = 'fadeOut 0.5s ease';
                    setTimeout(() => {
                        if (successMessage.parentNode) {
                            successMessage.remove();
                        }
                    }, 500);
                }
            }, 5000);
        }, 2000);
    }

    function addNewAdminToGrid(data) {
        const adminsGrid = document.querySelector('.admins-grid');
        const newAdminCard = document.createElement('div');
        newAdminCard.className = 'admin-card';
        newAdminCard.innerHTML = `
            <div class="admin-photo">
                <img src="${previewImg.src || 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face'}" alt="${data.name}">
            </div>
            <h3>${data.name}</h3>
            <button class="view-btn">View</button>
        `;

        // Add click event to new view button
        const newViewBtn = newAdminCard.querySelector('.view-btn');
        newViewBtn.addEventListener('click', function() {
            showAdminDetails(data.name);
        });

        adminsGrid.appendChild(newAdminCard);
    }

    // Notification System
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;

        // Add notification styles
        const notificationStyles = `
            <style>
                .notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 15px 20px;
                    border-radius: 8px;
                    color: white;
                    font-weight: 500;
                    z-index: 1001;
                    animation: slideIn 0.3s ease;
                }
                .notification.success {
                    background: #4CAF50;
                }
                .notification.error {
                    background: #f44336;
                }
                .notification.info {
                    background: #2196F3;
                }
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
            </style>
        `;
        
        if (!document.querySelector('.notification-styles')) {
            const styleElement = document.createElement('style');
            styleElement.className = 'notification-styles';
            styleElement.textContent = notificationStyles;
            document.head.appendChild(styleElement);
        }

        document.body.appendChild(notification);

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Add slideOut animation
    const slideOutStyles = `
        <style>
                            @keyframes slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
                @keyframes fadeOut {
                    from {
                        opacity: 1;
                        transform: scale(1);
                    }
                    to {
                        opacity: 0;
                        transform: scale(0.8);
                    }
                }
        </style>
    `;
            document.head.insertAdjacentHTML('beforeend', slideOutStyles);
    });

    // Confirmation Modal Function
    function showConfirmationModal(adminName, parentModal) {
        const confirmationModal = document.createElement('div');
        confirmationModal.className = 'confirmation-modal';
        confirmationModal.innerHTML = `
            <div class="confirmation-content">
                <div class="confirmation-header">
                    <h3>⚠️ Remove Admin</h3>
                </div>
                <div class="confirmation-body">
                    <p>Are you sure you want to remove <strong>${adminName}</strong> from the admin panel?</p>
                    <p class="warning-text">This action cannot be undone.</p>
                </div>
                <div class="confirmation-actions">
                    <button class="confirm-remove-btn">Remove Admin</button>
                    <button class="cancel-remove-btn">Cancel</button>
                </div>
            </div>
        `;

        // Add confirmation modal styles
        const confirmationStyles = `
            <style>
                .confirmation-modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.7);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 2000;
                }
                .confirmation-content {
                    background: white;
                    padding: 0;
                    border-radius: 15px;
                    max-width: 400px;
                    width: 90%;
                    position: relative;
                    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
                    overflow: hidden;
                }
                .confirmation-header {
                    background: #f44336;
                    color: white;
                    padding: 20px;
                    text-align: center;
                }
                .confirmation-header h3 {
                    margin: 0;
                    font-size: 18px;
                    font-weight: 600;
                }
                .confirmation-body {
                    padding: 30px 20px;
                    text-align: center;
                }
                .confirmation-body p {
                    margin: 0 0 15px 0;
                    font-size: 16px;
                    color: #333;
                    line-height: 1.5;
                }
                .warning-text {
                    color: #f44336;
                    font-weight: 600;
                    font-size: 14px;
                }
                .confirmation-actions {
                    display: flex;
                    gap: 15px;
                    padding: 20px;
                    background: #fafafa;
                    border-top: 1px solid #eee;
                }
                .confirm-remove-btn, .cancel-remove-btn {
                    flex: 1;
                    padding: 12px 20px;
                    border: none;
                    border-radius: 25px;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 14px;
                    transition: all 0.3s ease;
                }
                .confirm-remove-btn {
                    background: #f44336;
                    color: white;
                }
                .confirm-remove-btn:hover {
                    background: #d32f2f;
                    transform: translateY(-2px);
                }
                .cancel-remove-btn {
                    background: #9e9e9e;
                    color: white;
                }
                .cancel-remove-btn:hover {
                    background: #757575;
                    transform: translateY(-2px);
                }
                @media (max-width: 480px) {
                    .confirmation-actions {
                        flex-direction: column;
                        gap: 10px;
                    }
                }
            </style>
        `;
        document.head.insertAdjacentHTML('beforeend', confirmationStyles);

        document.body.appendChild(confirmationModal);

        // Confirmation modal event handlers
        const confirmBtn = confirmationModal.querySelector('.confirm-remove-btn');
        const cancelBtn = confirmationModal.querySelector('.cancel-remove-btn');

        confirmBtn.addEventListener('click', function() {
            // Remove admin from the grid
            removeAdminFromGrid(adminName);
            
            // Close both modals
            document.body.removeChild(confirmationModal);
            document.body.removeChild(parentModal);
            
            // Show success message
            showNotification(`${adminName} has been removed from the admin panel`, 'success');
        });

        cancelBtn.addEventListener('click', function() {
            document.body.removeChild(confirmationModal);
        });

        // Close confirmation modal when clicking outside
        confirmationModal.addEventListener('click', function(e) {
            if (e.target === confirmationModal) {
                document.body.removeChild(confirmationModal);
            }
        });
    }

    // Function to remove admin from the grid
    function removeAdminFromGrid(adminName) {
        const adminCards = document.querySelectorAll('.admin-card');
        adminCards.forEach(card => {
            const cardName = card.querySelector('h3').textContent;
            if (cardName === adminName) {
                card.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => {
                    card.remove();
                }, 300);
            }
        });
    }
