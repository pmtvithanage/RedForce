<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?= URL_ROOT ?>/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />


<style>
    
    
    .content-container {
        max-width: 60vw;
        margin-left: 10vw;
    }
    
    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin: 20px 0;
    }
    
    .card-header {
        padding: 30px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }
    
    .logo-container {
        margin-bottom: 20px;
    }
    
    .company-logo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #f0f0f0;
    }
    
    .company-name {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .card-body {
        padding: 30px;
    }
    
    .info-row {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }
    
    .info-row:last-child {
        margin-bottom: 0;
    }
    
    .icon {
        font-size: 24px;
        color: #666;
        margin-right: 15px;
        width: 30px;
    }
    
    .info-content {
        display: flex;
        flex-direction: column;
    }
    
    .info-label {
        font-size: 14px;
        color: #888;
        margin-bottom: 5px;
    }
    
    .info-value {
        font-size: 16px;
        color: #333;
        font-weight: 500;
    }
    
    .card-footer {
        padding: 20px 30px;
        background-color: #f9f9f9;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }
    
    .btn-primary, .btn-secondary {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
    }
    
    
  
    
    
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }
        
        .card-footer {
            flex-direction: column;
        }
        
        .btn-primary, .btn-secondary {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<button class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center;" onclick="history.back()"> 
    <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
    Back
  </button>

<div class="main-content">
    <div class="content-container">
        
        <!-- Profile Card Container -->
        <div class="profile-card">
            <div class="card-header">
                <div class="logo-container">
                    <img src="<?= URL_ROOT ?>/images/default-company-logo.png" alt="Company Logo" class="company-logo" id="companyLogo">
                </div>
                <h2 class="company-name" id="companyName">Tech Solutions Inc.</h2>
            </div>
            
            <div class="card-body">
                <div class="info-row">
                    <span class="material-symbols-outlined icon">mail</span>
                    <div class="info-content">
                        <span class="info-label">Email</span>
                        <span class="info-value" id="companyEmail">contact@techsolutions.com</span>
                    </div>
                </div>
                
                <div class="info-row">
                    <span class="material-symbols-outlined icon">call</span>
                    <div class="info-content">
                        <span class="info-label">Phone</span>
                        <span class="info-value" id="companyPhone">+1 (555) 123-4567</span>
                    </div>
                </div>
                
                <div class="info-row">
                    <span class="material-symbols-outlined icon">person</span>
                    <div class="info-content">
                        <span class="info-label">Contact Person</span>
                        <span class="info-value" id="contactPerson">John Smith</span>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <button class="btn-primary primary-btn" id="addClientBtn">
                    <span class="material-symbols-outlined">person_add</span>
                    Add Client
                </button>
                <button class="btn-secondary secondary-btn" id="rejectBtn">
                    <span class="material-symbols-outlined">close</span>
                    Reject
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal" id="confirmationModal" hidden>
    <div class="modal-content">
        <h3 id="modalTitle">Confirm Action</h3>
        <p id="modalMessage">Are you sure you want to proceed?</p>
        <div class="modal-actions">
            <button class="btn-secondary" id="modalCancel">Cancel</button>
            <button class="btn-primary" id="modalConfirm">Confirm</button>
        </div>
    </div>
</div>

<div class="backdrop" id="backdrop" hidden></div>




<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>