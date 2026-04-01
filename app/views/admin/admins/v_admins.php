<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<style>
    .admins-container {
        padding: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .page-title h1 {
        font-size: 28px;
        color: var(--primary-color);
        margin: 0;
    }

    .page-title p {
        color: #666;
        margin-top: 5px;
        font-size: 14px;
    }

    .admins-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .admin-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #eee;
    }

    .admin-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(164, 0, 0, 0.12);
    }

    .admin-header {
        background: var(--primary-color);
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .admin-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .admin-info h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .admin-info p {
        margin: 5px 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .admin-details {
        padding: 20px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f5;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #666;
        font-size: 14px;
        font-weight: 500;
    }

    .detail-value {
        color: #333;
        font-weight: 500;
        text-align: right;
    }

    .admin-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .status-inactive {
        background-color: #ffebee;
        color: #c62828;
    }

    .admin-actions {
        padding: 15px 20px;
        background: #f9f9f9;
        display: flex;
        justify-content: space-between;
        border-top: 1px solid #eee;
    }

    .action-btn {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-edit {
        background-color: #ffebee;
        color: #c62828;
    }

    .btn-edit:hover {
        background-color: #ffcdd2;
    }

    .btn-delete {
        background-color: #ffebee;
        color: #c62828;
    }

    .btn-delete:hover {
        background-color: #ffcdd2;
    }

    .no-admins {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .no-admins-icon {
        font-size: 48px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .admin-count {
        background: var(--primary-color);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    @media (max-width: 1024px) {
        .admins-container {
            margin-left: 0;
            padding: 15px;
        }
        
        .admins-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .admins-grid {
            grid-template-columns: 1fr;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
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

    .delete-warning {
        background: #fff3e0;
        border-left: 4px solid #ff9800;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .delete-warning p {
        margin: 0;
        color: #e65100;
        font-size: 14px;
    }

    .delete-admin-info {
        background: #f5f5f5;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .delete-admin-info h4 {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 16px;
    }

    .delete-admin-info p {
        margin: 5px 0;
        color: #666;
        font-size: 14px;
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
</style>

<div class="admins-container">
    <div class="page-header">
        
        <div class="admin-count">
            <?php echo count($data['admins']); ?> Admins
        </div>
    </div>

    <?php if(empty($data['admins'])): ?>
        <div class="no-admins">
            <div class="no-admins-icon">👨‍💼</div>
            <h3>No Admins Found</h3>
            <p>Start by creating your first admin account</p>
        </div>
    <?php else: ?>
        <div class="admins-grid">
            <?php foreach($data['admins'] as $admin): ?>
                <div class="admin-card">
                    <div class="admin-header">
                        <?php if($admin->userID == 'ADMIN001'): ?>
                            <img src="<?php echo URL_ROOT; ?>/public/img/logo.png"
                                 alt="<?php echo htmlspecialchars($admin->name); ?>" 
                                 class="admin-avatar">
                        <?php else: ?>
                            <img src="<?php echo URL_ROOT; ?>/uploads/image/<?php echo $admin->profile_image; ?>"
                                 alt="<?php echo htmlspecialchars($admin->name); ?>" 
                                 class="admin-avatar">
                        <?php endif; ?>
                        <div class="admin-info">
                            <h3><?php echo htmlspecialchars($admin->name); ?></h3>
                            <p>Admin ID: <?php echo $admin->userID; ?></p>
                        </div>
                    </div>
                    
                    <div class="admin-details">
                        <div class="detail-row">
                            <span class="detail-label">Email</span>
                            <span class="detail-value"><?php echo htmlspecialchars($admin->email); ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value"><?php echo htmlspecialchars($admin->phone_number); ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Joined Date</span>
                            <span class="detail-value"><?php echo date('M d, Y', strtotime($admin->created_at)); ?></span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Status</span>
                            <span class="detail-value">
                                <span class="admin-status status-active">Active</span>
                            </span>
                        </div>
                    </div>
                    
                    <div class="admin-actions">
                        <button class="action-btn btn-edit" 
                                data-admin-id="<?php echo $admin->id; ?>"
                                data-admin-name="<?php echo htmlspecialchars($admin->name, ENT_QUOTES); ?>"
                                data-admin-email="<?php echo htmlspecialchars($admin->email, ENT_QUOTES); ?>"
                                data-admin-phone="<?php echo htmlspecialchars($admin->phone_number, ENT_QUOTES); ?>"
                                onclick="openEditModal(this)">
                            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                            Edit
                        </button>
                        <?php if($admin->userID != 'ADMIN001'): ?>
                        <button class="action-btn btn-delete" onclick="openDeleteModal(<?php echo $admin->id; ?>, '<?php echo htmlspecialchars($admin->name, ENT_QUOTES); ?>', '<?php echo htmlspecialchars($admin->email, ENT_QUOTES); ?>')">
                            <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                            Delete
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<button class="secondary-btn" style="position: fixed; right: 30px; bottom: 40px;display:flex; width:200px; margin: 20px;align-items:center; justify-content:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/addadmin'"> 
    <span class="material-symbols-outlined" style="margin-right:8px;">person_add</span>
    Create Admin
</button>

<!-- Edit Admin Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                <span class="material-symbols-outlined">edit</span>
                Edit Admin
            </h2>
            <button class="modal-close" onclick="closeEditModal()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="editAdminForm">
            <div class="modal-body">
                <input type="hidden" id="edit_admin_id" name="admin_id">
                
                <div class="form-group">
                    <label for="edit_name">Full Name</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_email">Email Address</label>
                    <input type="email" id="edit_email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_phone">Phone Number</label>
                    <input type="tel" id="edit_phone" name="phone_number" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn btn-cancel" onclick="closeEditModal()">
                    <span class="material-symbols-outlined">close</span>
                    Cancel
                </button>
                <button type="submit" class="modal-btn btn-confirm" id="saveBtn">
                    <span class="material-symbols-outlined">save</span>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                <span class="material-symbols-outlined">warning</span>
                Confirm Deletion
            </h2>
            <button class="modal-close" onclick="closeDeleteModal()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="delete-warning">
                <p><strong>Warning:</strong> This action cannot be undone!</p>
            </div>
            
            <div class="delete-admin-info" id="deleteAdminInfo">
                <h4>Admin to be deleted:</h4>
                <p><strong>Name:</strong> <span id="delete_admin_name"></span></p>
                <p><strong>Email:</strong> <span id="delete_admin_email"></span></p>
            </div>
            
            <p style="color: #666; font-size: 14px;">Are you sure you want to delete this admin account? All associated data will be permanently removed.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn btn-cancel" onclick="closeDeleteModal()">
                <span class="material-symbols-outlined">close</span>
                Cancel
            </button>
            <button type="button" class="modal-btn btn-confirm" id="confirmDeleteBtn" onclick="confirmDelete()">
                <span class="material-symbols-outlined">delete</span>
                Delete Admin
            </button>
        </div>
    </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script>
let currentAdminId = null;

// Toast Notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <span class="material-symbols-outlined toast-icon">${type === 'success' ? 'check_circle' : 'error'}</span>
        <span class="toast-message">${message}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <span class="material-symbols-outlined">close</span>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideInRight 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Edit Modal Functions
function openEditModal(button) {
    // Get data from button's data attributes
    const adminId = button.getAttribute('data-admin-id');
    const adminName = button.getAttribute('data-admin-name');
    const adminEmail = button.getAttribute('data-admin-email');
    const adminPhone = button.getAttribute('data-admin-phone');
    
    document.getElementById('edit_admin_id').value = adminId;
    document.getElementById('edit_name').value = adminName;
    document.getElementById('edit_email').value = adminEmail;
    document.getElementById('edit_phone').value = adminPhone;
    
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
    document.getElementById('editAdminForm').reset();
}

// Handle Edit Form Submission
document.getElementById('editAdminForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const saveBtn = document.getElementById('saveBtn');
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="material-symbols-outlined">hourglass_empty</span> Saving...';
    
    const formData = new FormData(this);
    
    fetch('<?php echo URL_ROOT; ?>/admin/updateAdmin', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showToast('Admin updated successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Failed to update admin', 'error');
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<span class="material-symbols-outlined">save</span> Save Changes';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while updating admin', 'error');
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<span class="material-symbols-outlined">save</span> Save Changes';
    });
});

// Delete Modal Functions
function openDeleteModal(adminId, adminName, adminEmail) {
    currentAdminId = adminId;
    document.getElementById('delete_admin_name').textContent = adminName;
    document.getElementById('delete_admin_email').textContent = adminEmail;
    
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    currentAdminId = null;
}

function confirmDelete() {
    if (!currentAdminId) {
        console.error('No admin ID selected');
        return;
    }
    
    console.log('Deleting admin with ID:', currentAdminId);
    
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<span class="material-symbols-outlined">hourglass_empty</span> Deleting...';
    
    fetch('<?php echo URL_ROOT; ?>/admin/deleteAdmin', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'admin_id=' + currentAdminId
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.status === 'success') {
            closeDeleteModal();
            showToast('Admin deleted successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Failed to delete admin', 'error');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<span class="material-symbols-outlined">delete</span> Delete Admin';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while deleting admin', 'error');
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<span class="material-symbols-outlined">delete</span> Delete Admin';
    });
}

// Close modals when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
        closeDeleteModal();
    }
});
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>