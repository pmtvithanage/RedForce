<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<style>
.container {
    margin: 20px ;
    padding: 0 20px;
}

.back-btn-container {
    margin-bottom: 20px;
}

.ad-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.08);
}

.ad-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.ad-title-section h1 {
    font-size: 32px;
    font-weight: 700;
    color: #333;
    margin: 0 0 10px 0;
}

.ad-status {
    display: flex;
    align-items: center;
    gap: 10px;
}

.badge {
    padding: 8px 16px;
    border-radius: 20px;
    color: white;
    font-size: 14px;
    font-weight: 600;
    display: inline-block;
}

.badge.active {
    background: #4caf50;
}

.badge.inactive {
    background: #e74c3c;
}

.ad-content {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
}

.ad-image-section {
    flex: 1 1 300px;
}

.ad-image-large {
    width: 100%;
    max-width: 500px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.ad-details-section {
    flex: 2 1 400px;
}

.detail-row {
    margin-bottom: 20px;
}

.detail-label {
    font-weight: 600;
    color: #666;
    font-size: 14px;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    font-size: 16px;
    color: #333;
    line-height: 1.6;
}

.roles-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 5px;
}

.role-tag {
    background: #e8f4f8;
    color: #0066cc;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
}

.ad-actions {
    display: flex;
    gap: 12px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.action-button {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.edit-button {
    background: #2196F3;
    color: white;
}

.delete-button {
    background: #e74c3c;
    color: white;
}

.toggle-button {
    background: #ff9800;
    color: white;
}

.action-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.meta-info {
    display: flex;
    gap: 30px;
    margin-top: 20px;
    padding: 15px;
    background: #f9f9f9;
    border-radius: 8px;
}

.meta-item {
    display: flex;
    flex-direction: column;
}

.meta-label {
    font-size: 12px;
    color: #666;
    font-weight: 600;
    text-transform: uppercase;
}

.meta-value {
    font-size: 14px;
    color: #333;
    margin-top: 3px;
}

.hidden { display: none; }

/* Confirmation Modal */
.confirm-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 10000;
    justify-content: center;
    align-items: center;
}

.confirm-modal.active {
    display: flex;
}

.confirm-modal-content {
    background: white;
    padding: 30px;
    border-radius: 12px;
    max-width: 450px;
    width: 90%;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.confirm-modal-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.confirm-modal-icon {
    font-size: 32px;
    color: #ff9800;
}

.confirm-modal-title {
    font-size: 22px;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.confirm-modal-message {
    font-size: 16px;
    color: #666;
    line-height: 1.6;
    margin-bottom: 25px;
}

.confirm-modal-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.modal-btn {
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-btn-cancel {
    background: #f1f1f1;
    color: #333;
}

.modal-btn-cancel:hover {
    background: #e0e0e0;
}

.modal-btn-confirm {
    background: #e74c3c;
    color: white;
}

.modal-btn-confirm.warning {
    background: #ff9800;
}

.modal-btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

</style>

<div class="container">
    <div class="back-btn-container">
        <button class="tertiary-btn" style="display:flex; width:100px; align-items:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/advertisements'"> 
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
            Back
        </button>
    </div>

    <div class="ad-card">
        <div class="ad-header">
            <div class="ad-title-section">
                <h1><?php echo htmlspecialchars($data['advertisement']->title); ?></h1>
            </div>
            <div class="ad-status">
                <span class="badge <?php echo $data['advertisement']->status; ?>">
                    <?php echo ucfirst($data['advertisement']->status); ?>
                </span>
            </div>
        </div>

        <div class="ad-content">
            <div class="ad-image-section">
                <?php if (!empty($data['advertisement']->image_path)): ?>
                    <img src="<?php echo URL_ROOT . $data['advertisement']->image_path; ?>" 
                         alt="<?php echo htmlspecialchars($data['advertisement']->title); ?>" 
                         class="ad-image-large">
                <?php else: ?>
                    <div style="width: 100%; height: 300px; background: #f0f0f0; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="font-size: 64px; color: #ccc;">image</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="ad-details-section">
                <div class="detail-row">
                    <div class="detail-label">Description</div>
                    <div class="detail-value">
                        <?php echo nl2br(htmlspecialchars($data['advertisement']->description ?? 'No description provided')); ?>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Target Roles</div>
                    <div class="roles-list">
                        <?php 
                        $roles = json_decode($data['advertisement']->target_roles);
                        if (is_array($roles)) {
                            foreach ($roles as $role): 
                        ?>
                            <span class="role-tag"><?php echo htmlspecialchars($role); ?></span>
                        <?php 
                            endforeach;
                        } else {
                            // Handle comma-separated roles as fallback
                            $rolesArray = explode(',', $data['advertisement']->target_roles);
                            foreach ($rolesArray as $role):
                        ?>
                            <span class="role-tag"><?php echo htmlspecialchars(trim($role)); ?></span>
                        <?php 
                            endforeach;
                        }
                        ?>
                    </div>
                </div>

                <div class="meta-info">
                    <div class="meta-item">
                        <span class="meta-label">Created By</span>
                        <span class="meta-value"><?php echo htmlspecialchars($data['advertisement']->creator_name ?? 'Unknown'); ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Created Date</span>
                        <span class="meta-value"><?php echo date('F d, Y \a\t h:i A', strtotime($data['advertisement']->created_at)); ?></span>
                    </div>
                    <?php if ($data['advertisement']->updated_at != $data['advertisement']->created_at): ?>
                    <div class="meta-item">
                        <span class="meta-label">Last Updated</span>
                        <span class="meta-value"><?php echo date('F d, Y \a\t h:i A', strtotime($data['advertisement']->updated_at)); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="ad-actions">
                    <button  class="action-button edit-button hidden" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/editAdvertisement/<?php echo $data['advertisement']->id; ?>'">
                        <span class="material-symbols-outlined">edit</span>
                        Edit
                    </button>
                    <button class="action-button toggle-button" onclick="toggleStatus(<?php echo $data['advertisement']->id; ?>, '<?php echo $data['advertisement']->status; ?>')">
                        <span class="material-symbols-outlined">toggle_<?php echo $data['advertisement']->status === 'active' ? 'off' : 'on'; ?></span>
                        <?php echo $data['advertisement']->status === 'active' ? 'Deactivate' : 'Activate'; ?>
                    </button>
                    <button class="action-button delete-button" onclick="deleteAdvertisement(<?php echo $data['advertisement']->id; ?>)">
                        <span class="material-symbols-outlined">delete</span>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-modal-content">
        <div class="confirm-modal-header">
            <span class="material-symbols-outlined confirm-modal-icon" id="modalIcon">warning</span>
            <h3 class="confirm-modal-title" id="modalTitle">Confirm Action</h3>
        </div>
        <p class="confirm-modal-message" id="modalMessage">Are you sure you want to proceed?</p>
        <div class="confirm-modal-actions">
            <button class="modal-btn modal-btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="modal-btn modal-btn-confirm" id="modalConfirmBtn" onclick="confirmAction()">Confirm</button>
        </div>
    </div>
</div>

<script>
let currentAction = null;
let actionData = null;

// Show modal
function showModal(title, message, icon, confirmBtnText, confirmBtnClass, action, data) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalMessage').textContent = message;
    document.getElementById('modalIcon').textContent = icon;
    
    const confirmBtn = document.getElementById('modalConfirmBtn');
    confirmBtn.textContent = confirmBtnText;
    confirmBtn.className = 'modal-btn modal-btn-confirm ' + (confirmBtnClass || '');
    
    currentAction = action;
    actionData = data;
    
    document.getElementById('confirmModal').classList.add('active');
}

// Close modal
function closeModal() {
    document.getElementById('confirmModal').classList.remove('active');
    currentAction = null;
    actionData = null;
}

// Confirm action
function confirmAction() {
    if (currentAction) {
        currentAction(actionData);
    }
    closeModal();
}

// Close modal on background click
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Toggle advertisement status
function toggleStatus(id, currentStatus) {
    const action = currentStatus === 'active' ? 'deactivate' : 'activate';
    showModal(
        action.charAt(0).toUpperCase() + action.slice(1) + ' Advertisement',
        `Are you sure you want to ${action} this advertisement?`,
        'toggle_' + (currentStatus === 'active' ? 'off' : 'on'),
        action.charAt(0).toUpperCase() + action.slice(1),
        'warning',
        performToggleStatus,
        id
    );
}

function performToggleStatus(id) {
    fetch('<?php echo URL_ROOT; ?>/admin/toggleAdvertisementStatus/' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to toggle status'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

// Delete advertisement
function deleteAdvertisement(id) {
    showModal(
        'Delete Advertisement',
        'Are you sure you want to delete this advertisement? This action cannot be undone.',
        'delete',
        'Delete',
        '',
        performDeleteAdvertisement,
        id
    );
}

function performDeleteAdvertisement(id) {
    fetch('<?php echo URL_ROOT; ?>/admin/deleteAdvertisement/' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '<?php echo URL_ROOT; ?>/admin/advertisements';
        } else {
            alert('Error: ' + (data.message || 'Failed to delete advertisement'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>