<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<style>
    :root {
        --accent: #a40000;
        --accent-light: #c41e1e;
        --shadow: 0 6px 18px rgba(20,20,40,0.06);
        --radius: 12px;
    }

    .main-content {
        padding: 24px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f0f0;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .back-btn {
        background: #f0f0f0;
        color: #333;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .back-btn:hover {
        background: #e0e0e0;
        transform: translateY(-2px);
    }

    .btn-create-package {
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(164, 0, 0, 0.2);
    }

    .btn-create-package:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(164, 0, 0, 0.3);
    }

    .packages-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 24px;
        max-width: 1400px;
    }

    .package-card {
        height: 450px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: var(--radius);
        padding: 50px 30px;
        box-shadow: var(--shadow);
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .package-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        background: url('<?php echo URL_ROOT; ?>/img/SecurityOfficer.png') no-repeat center;
        background-size: cover;
        filter: grayscale(0%) brightness(0.8);
        z-index: 0;
    }

    .package-card > * {
        position: relative;
        z-index: 1;
    }

    .package-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(164, 0, 0, 0.15);
        border-color: var(--accent);
    }

    .package-name {
        font-size: 20px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 16px;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        padding: 12px 24px;
        border-radius: 8px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .package-personnel {
        font-size: 15px;
        color: #ffffff;
        margin-bottom: 16px;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        padding: 10px 20px;
        border-radius: 8px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
    }

    .package-description {
        font-size: 13px;
        color: #ffffff;
        margin-bottom: 16px;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        padding: 10px 20px;
        border-radius: 8px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        max-width: 80%;
        text-align: center;
        line-height: 1.5;
    }

    .package-price {
        font-size: 26px;
        font-weight: 700;
        color: #ffffff;
        margin-top: 24px;
        background: rgba(164, 0, 0, 0.7);
        backdrop-filter: blur(10px);
        padding: 14px 28px;
        border-radius: 8px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
    }

    .package-price-period {
        font-size: 16px;
        color: #ffffff;
        font-weight: 400;
    }

    .custom-package::before {
        filter: grayscale(30%) brightness(0.7);
    }

    .package-actions {
        position: absolute;
        top: 12px;
        right: 12px;
        display: flex;
        gap: 8px;
        z-index: 2;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .package-status-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        z-index: 2;
        backdrop-filter: blur(10px);
    }

    .package-status-badge.active {
        background: rgba(40, 167, 69, 0.9);
        color: white;
    }

    .package-status-badge.inactive {
        background: rgba(220, 53, 69, 0.9);
        color: white;
    }

    .package-card:hover .package-actions {
        opacity: 1;
    }

    .btn-edit, .btn-delete {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(10px);
    }

    .btn-edit {
        color: white;
    }

    .btn-edit:hover {
        background: rgba(40, 167, 69, 0.9);
        transform: scale(1.1);
    }

    .btn-delete {
        color: white;
    }

    .btn-delete:hover {
        background: rgba(220, 53, 69, 0.9);
        transform: scale(1.1);
    }

    .btn-edit .material-symbols-outlined,
    .btn-delete .material-symbols-outlined {
        font-size: 20px;
    }

    .btn-toggle-status {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(10px);
        color: white;
    }

    .btn-toggle-status.active:hover {
        background: rgba(255, 193, 7, 0.9);
        transform: scale(1.1);
    }

    .btn-toggle-status.inactive:hover {
        background: rgba(40, 167, 69, 0.9);
        transform: scale(1.1);
    }

    .btn-toggle-status .material-symbols-outlined {
        font-size: 20px;
    }

    .package-card.inactive {
        opacity: 0.6;
        filter: grayscale(50%);
    }

    .package-card.inactive::after {
        content: 'INACTIVE';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(220, 53, 69, 0.9);
        color: white;
        padding: 8px 24px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 2px;
        z-index: 10;
        backdrop-filter: blur(10px);
    }

    .info-section {
        background: white;
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--shadow);
        margin-bottom: 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
    }

    .info-section-content {
        flex: 1;
    }

    .info-section h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 12px;
    }

    .info-section p {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
        margin: 0;
    }

    /* Delete Confirmation Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-content {
        background: white;
        border-radius: var(--radius);
        padding: 32px;
        max-width: 480px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    .modal-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .modal-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-icon .material-symbols-outlined {
        color: white;
        font-size: 32px;
    }

    .modal-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .modal-body {
        margin-bottom: 28px;
    }

    .modal-text {
        font-size: 15px;
        color: #666;
        line-height: 1.6;
        margin-bottom: 12px;
    }

    .modal-package-name {
        font-size: 16px;
        font-weight: 600;
        color: var(--accent);
        background: #fff5f5;
        padding: 12px 16px;
        border-radius: 8px;
        margin-top: 12px;
    }

    .modal-warning {
        font-size: 13px;
        color: #dc3545;
        margin-top: 12px;
        font-weight: 500;
    }

    .modal-footer {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .btn-modal {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-modal-cancel {
        background: #f0f0f0;
        color: #333;
    }

    .btn-modal-cancel:hover {
        background: #e0e0e0;
        transform: translateY(-2px);
    }

    .btn-modal-delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    .btn-modal-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
    }
</style>

<a href="<?php echo URL_ROOT; ?>/admin/clientRequests" class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center; text-decoration:none;"> 
    <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
    Back
</a>

<div class="main-content">
    <?php flash('msg'); ?>

    <div class="info-section">
        <div class="info-section-content">
            <h3>Available Packages</h3>
            <p>These are the standard security packages offered to clients. Clients can choose from predefined packages or create a custom package with specific requirements.</p>
        </div>
        <a href="<?php echo URL_ROOT; ?>/admin/createPackage" class="btn-create-package">
            <span class="material-symbols-outlined">add</span>
            Create Package
        </a>
    </div>

    <div class="packages-grid">
        <?php if (!empty($data['packages'])): ?>
            <?php foreach ($data['packages'] as $index => $package): ?>
                <?php if (!empty($package->background_image)): ?>
                    <style>
                        .package-card-<?php echo $package->id; ?>::before {
                            background: url('<?php echo URL_ROOT; ?>/uploads/packages/<?php echo $package->background_image; ?>') no-repeat center !important;
                            background-size: cover !important;
                        }
                    </style>
                <?php endif; ?>
                
                <div class="package-card package-card-<?php echo $package->id; ?> <?php echo strtolower($package->package_name) === 'custom package' ? 'custom-package' : ''; ?> <?php echo $package->status !== 'Active' ? 'inactive' : ''; ?>">                    <div class="package-status-badge <?php echo $package->status === 'Active' ? 'active' : 'inactive'; ?>">
                        <?php echo $package->status; ?>
                    </div>                    <div class="package-actions">
                        <button type="button" 
                                class="btn-toggle-status <?php echo $package->status === 'Active' ? 'active' : 'inactive'; ?>" 
                                onclick="togglePackageStatus(<?php echo $package->id; ?>, '<?php echo htmlspecialchars($package->package_name, ENT_QUOTES); ?>', '<?php echo $package->status; ?>')" 
                                title="<?php echo $package->status === 'Active' ? 'Deactivate Package' : 'Activate Package'; ?>">
                            <span class="material-symbols-outlined"><?php echo $package->status === 'Active' ? 'toggle_on' : 'toggle_off'; ?></span>
                        </button>
                        <a href="<?php echo URL_ROOT; ?>/admin/editPackage/<?php echo $package->id; ?>" class="btn-edit" title="Edit Package">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                        <?php if (!isset($package->is_default) || $package->is_default != 1): ?>
                        <button type="button" class="btn-delete" onclick="confirmDelete(<?php echo $package->id; ?>, '<?php echo htmlspecialchars($package->package_name, ENT_QUOTES); ?>')" title="Delete Package">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                        <?php else: ?>
                        <button type="button" class="btn-delete" style="opacity: 0.5; cursor: not-allowed;" title="Default packages cannot be deleted" disabled>
                            <span class="material-symbols-outlined">lock</span>
                        </button>
                        <?php endif; ?>
                    </div>
                    
                    <div class="package-name"><?php echo htmlspecialchars($package->package_name); ?></div>
                    
                    <div class="package-personnel">
                        <?php 
                            $personnel = [];
                            if ($package->number_of_officers > 0) {
                                $personnel[] = $package->number_of_officers . ' Security Officer' . ($package->number_of_officers != 1 ? 's' : '');
                            }
                            if ($package->number_of_supervisors > 0) {
                                $personnel[] = $package->number_of_supervisors . ' Supervisor' . ($package->number_of_supervisors != 1 ? 's' : '');
                            }
                            if ($package->number_of_caretakers > 0) {
                                $personnel[] = $package->number_of_caretakers . ' Caretaker' . ($package->number_of_caretakers != 1 ? 's' : '');
                            }
                            echo !empty($personnel) ? implode(', ', $personnel) : 'Personnel Details';
                        ?>
                    </div>
                    
                    <?php if (!empty($package->description)): ?>
                        <div class="package-description">
                            <?php echo htmlspecialchars($package->description); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="package-price">
                        <?php if ($package->package_price > 0): ?>
                            LKR <?php echo number_format($package->package_price, 0); ?>
                        <?php else: ?>
                            Customizable
                        <?php endif; ?>
                        <span class="package-price-period">
                            <?php echo $package->package_price > 0 ? '/month' : ''; ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px; grid-column: 1 / -1;">
                <span class="material-symbols-outlined" style="font-size: 64px; color: #ddd;">package_2</span>
                <p style="color: #999; margin-top: 16px;">No packages available. Create your first package!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-icon">
                <span class="material-symbols-outlined">warning</span>
            </div>
            <h2 class="modal-title">Delete Package?</h2>
        </div>
        <div class="modal-body">
            <p class="modal-text">Are you sure you want to delete this package?</p>
            <div class="modal-package-name" id="modalPackageName"></div>
            <p class="modal-warning">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeDeleteModal()">
                <span class="material-symbols-outlined">close</span>
                Cancel
            </button>
            <button type="button" class="btn-modal btn-modal-delete" id="confirmDeleteBtn">
                <span class="material-symbols-outlined">delete</span>
                Delete Package
            </button>
        </div>
    </div>
</div>

<!-- Toggle Status Confirmation Modal -->
<div class="modal-overlay" id="toggleStatusModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-icon" id="toggleModalIcon" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                <span class="material-symbols-outlined">info</span>
            </div>
            <h2 class="modal-title" id="toggleModalTitle">Toggle Package Status?</h2>
        </div>
        <div class="modal-body">
            <p class="modal-text" id="toggleModalText"></p>
            <div class="modal-package-name" id="toggleModalPackageName"></div>
            <p class="modal-warning" id="toggleModalWarning" style="color: #ff9800;"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeToggleModal()">
                <span class="material-symbols-outlined">close</span>
                Cancel
            </button>
            <button type="button" class="btn-modal" id="confirmToggleBtn" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: white; box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);">
                <span class="material-symbols-outlined" id="toggleBtnIcon">toggle_on</span>
                <span id="toggleBtnText">Confirm</span>
            </button>
        </div>
    </div>
</div>

<script>
let packageToDelete = null;
let packageToToggle = null;
let newStatus = null;

function togglePackageStatus(packageId, packageName, currentStatus) {
    packageToToggle = packageId;
    newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';
    
    document.getElementById('toggleModalPackageName').textContent = packageName;
    
    if (newStatus === 'Inactive') {
        document.getElementById('toggleModalTitle').textContent = 'Deactivate Package?';
        document.getElementById('toggleModalText').textContent = 'Are you sure you want to deactivate this package?';
        document.getElementById('toggleModalWarning').textContent = 'Clients will no longer be able to select this package.';
        document.getElementById('toggleBtnIcon').textContent = 'toggle_off';
        document.getElementById('toggleBtnText').textContent = 'Deactivate';
    } else {
        document.getElementById('toggleModalTitle').textContent = 'Activate Package?';
        document.getElementById('toggleModalText').textContent = 'Are you sure you want to activate this package?';
        document.getElementById('toggleModalWarning').textContent = 'This package will become available for clients to select.';
        document.getElementById('toggleBtnIcon').textContent = 'toggle_on';
        document.getElementById('toggleBtnText').textContent = 'Activate';
    }
    
    document.getElementById('toggleStatusModal').classList.add('active');
}

function closeToggleModal() {
    document.getElementById('toggleStatusModal').classList.remove('active');
    packageToToggle = null;
    newStatus = null;
}

document.getElementById('confirmToggleBtn').addEventListener('click', function() {
    if (packageToToggle && newStatus) {
        window.location.href = '<?php echo URL_ROOT; ?>/admin/togglePackageStatus/' + packageToToggle + '/' + newStatus;
    }
});

function confirmDelete(packageId, packageName) {
    packageToDelete = packageId;
    document.getElementById('modalPackageName').textContent = packageName;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    packageToDelete = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (packageToDelete) {
        window.location.href = '<?php echo URL_ROOT; ?>/admin/deletePackage/' + packageToDelete;
    }
});

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

document.getElementById('toggleStatusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeToggleModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
        closeToggleModal();
    }
});
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
