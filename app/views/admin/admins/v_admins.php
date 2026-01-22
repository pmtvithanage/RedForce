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
                        <img src="<?php echo URL_ROOT; ?>/uploads/image/<?php echo $admin->profile_image; ?>"
                             alt="<?php echo htmlspecialchars($admin->name); ?>" 
                             class="admin-avatar">
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
                                <span class="admin-status status-active"><?php echo htmlspecialchars($admin->status); ?></span>
                            </span>
                        </div>
                    </div>
                    
                    <div class="admin-actions">
                        <button class="action-btn btn-edit" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/editadmin/<?php echo $admin->admin_id; ?>'">
                            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                            Edit
                        </button>
                        <button class="action-btn btn-delete" onclick="confirmDelete(<?php echo $admin->admin_id; ?>)">
                            <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                            Delete
                        </button>
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

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script>
function confirmDelete(adminId) {
    if (confirm('Are you sure you want to delete this admin? This action cannot be undone.')) {
        window.location.href = '<?php echo URL_ROOT; ?>/admin/deleteadmin/' + adminId;
    }
}
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>