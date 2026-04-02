<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<style>
    .container {
        --primary-color: #a40000;
        --success-color: #2e7d32;
        --danger-color: #c62828;
        --info-color: #1976d2;
        --radius: 8px;
    }

    .container {
        width: 90%;
        margin: 40px auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }

    /* Table Card */
    .table-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        overflow-x: auto;
    }

    /* Search Bar */
    .search-box {
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #dadada;
        padding: 10px 14px;
        border-radius: 6px;
        margin-bottom: 16px;
        max-width: 400px;
    }

    .search-box:focus-within {
        border-color: #a40000;
    }

    .search-box input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 15px;
    }

    /* Table */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 12px;
        font-size: 14px;
        text-align: left;
    }

    thead th {
        background: #f9e9e9;
        font-weight: 600;
    }

    tbody tr {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-bottom: 1px solid #f0f0f0;
    }

    tbody tr:hover {
        background-color: #f9f9f9;
    }

    /* Status Badges */
    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        color: #374151;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        border: 1px solid transparent;
    }

    .badge.active {
        background: #e8f5e9;
        color: #2e7d32;
        border-color: #c8e6c9;
    }

    .badge.inactive {
        background: #ffebee;
        color: #c62828;
        border-color: #ffcdd2;
    }

    /* Advertisement Image */
    .ad-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #e0e0e0;
    }

    .ad-image-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #e0e0e0;
    }

    /* Description truncation */
    .description {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Target Roles */
    .roles {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .role-tag {
        background: #e8f4f8;
        color: #0066cc;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
    }

    /* Action Buttons */
    .actions {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 38px;
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    .edit-btn {
        background: #e3f2fd;
        color: var(--info-color);
    }

    .edit-btn:hover {
        background: var(--info-color);
        color: #fff;
    }

    .delete-btn {
        background: #ffebee;
        color: var(--danger-color);
    }

    .delete-btn:hover {
        background: var(--danger-color);
        color: #fff;
    }

    .toggle-btn {
        background: #e3f2fd;
        color: var(--info-color);
    }

    .toggle-btn:hover {
        background: var(--info-color);
        color: #fff;
    }

    .create-btn {
        background: var(--success-color);
        color: #fff;
        border: none;
        border-radius: var(--radius);
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .create-btn:hover {
        background: #1b5e20;
        transform: translateY(-1px);
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #666;
    }

    .empty-state .material-symbols-outlined {
        font-size: 64px;
        color: #ccc;
        margin-bottom: 16px;
    }

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
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
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
        padding: 10px 18px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .modal-btn-cancel {
        background: #f3f4f6;
        color: #4b5563;
    }

    .modal-btn-cancel:hover {
        background: #e5e7eb;
    }

    .modal-btn-confirm {
        background: #ffebee;
        color: var(--danger-color);
    }

    .modal-btn-confirm:hover {
        background: var(--danger-color);
        color: white;
        transform: translateY(-1px);
    }

    .modal-btn-confirm.warning {
        background: #e3f2fd;
        color: var(--info-color);
    }

    .modal-btn-confirm.warning:hover {
        background: var(--info-color);
        color: #fff;
    }
</style>

<div class="container">
    <div class="page-header">
        <h1></h1>
        <button class="primary-btn create-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/createAdvertisement'">
            <span class="material-symbols-outlined">add</span>
            Create Advertisement
        </button>
    </div>

    <div class="table-card">
        <div class="search-box">
            <span class="material-symbols-outlined">search</span>
            <input type="text" id="searchInput" placeholder="Search advertisements...">
        </div>

        <?php if (empty($data['advertisements'])): ?>
            <div class="empty-state">
                <div>
                    <span class="material-symbols-outlined">campaign</span>
                </div>
                <h3>No Advertisements Yet</h3>
                <p>Create your first advertisement to get started.</p>
            </div>
        <?php else: ?>
            <table id="advertisementsTable">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Target Roles</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['advertisements'] as $ad): ?>
                        <tr data-id="<?php echo $ad->id; ?>" onclick="viewAdvertisement(<?php echo $ad->id; ?>)" style="cursor: pointer;">
                            <td>
                                <?php if (!empty($ad->image_path)): ?>
                                    <img src="<?php echo URL_ROOT . $ad->image_path; ?>"
                                        alt="<?php echo htmlspecialchars($ad->title); ?>"
                                        class="ad-image">
                                <?php else: ?>
                                    <div class="ad-image-placeholder">
                                        <span class="material-symbols-outlined">image</span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($ad->title); ?></strong></td>
                            <td>
                                <div class="description" title="<?php echo htmlspecialchars($ad->description ?? ''); ?>">
                                    <?php echo htmlspecialchars($ad->description ?? 'No description'); ?>
                                </div>
                            </td>
                            <td>
                                <div class="roles">
                                    <?php
                                    $roles = json_decode($ad->target_roles);
                                    if (is_array($roles)) {
                                        foreach ($roles as $role):
                                    ?>
                                            <span class="role-tag"><?php echo htmlspecialchars($role); ?></span>
                                        <?php
                                        endforeach;
                                    } else {
                                        // Handle comma-separated roles as fallback
                                        $rolesArray = explode(',', $ad->target_roles);
                                        foreach ($rolesArray as $role):
                                        ?>
                                            <span class="role-tag"><?php echo htmlspecialchars(trim($role)); ?></span>
                                    <?php
                                        endforeach;
                                    }
                                    ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge <?php echo $ad->status; ?>">
                                    <?php echo ucfirst($ad->status); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($ad->creator_name ?? 'Unknown'); ?></td>
                            <td><?php echo date('M d, Y', strtotime($ad->created_at)); ?></td>
                            <td>
                                <div class="actions">
                                    <button class="action-btn toggle-btn"
                                        onclick="event.stopPropagation(); toggleStatus(<?php echo $ad->id; ?>, '<?php echo $ad->status; ?>')"
                                        title="Toggle Status">
                                        <span class="material-symbols-outlined">toggle_<?php echo $ad->status === 'active' ? 'off' : 'on'; ?></span>
                                    </button>
                                    <button class="action-btn delete-btn"
                                        onclick="event.stopPropagation(); deleteAdvertisement(<?php echo $ad->id; ?>)"
                                        title="Delete">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
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

    // Search functionality
    document.getElementById('searchInput')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const table = document.getElementById('advertisementsTable');
        if (!table) return;

        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (let row of rows) {
            const title = row.cells[1].textContent.toLowerCase();
            const description = row.cells[2].textContent.toLowerCase();
            const roles = row.cells[3].textContent.toLowerCase();

            if (title.includes(searchTerm) || description.includes(searchTerm) || roles.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
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
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete advertisement'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
    }

    // View advertisement
    function viewAdvertisement(id) {
        window.location.href = '<?php echo URL_ROOT; ?>/admin/viewAdvertisement/' + id;
    }
</script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>