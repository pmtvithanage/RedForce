<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?= URL_ROOT ?>/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />


<style>
    
    
    .content-container {
        max-width: 60vw;
        margin-left: 10vw;
    }
    
    .tabs{
        display:flex; 
        gap:10px; 
        margin-bottom:20px; 
        margin-left:20px;
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
    
    .imagePlaceholder{
        height: 180px;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        margin: 0 auto 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #fff;
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
    
    .info-icon {
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
        border-radius: 8px;
        text-decoration: none;
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

<button class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/clients'""> 
    <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
    Back
  </button>

<div class="main-content">
    <div class="tabs">
        <button class="tab secondary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/addclients'">Pending Requests</button>
        <button class="tab secondary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/accepted'">Approved Requests</button>
        <button class="tab primary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/rejected'">Rejected Requests</button>
    </div>
    
    <div class="content-container">
        <?php if(empty($data['clients'])){
            echo('No result found !');
        }
        ?>
        <?php foreach($data['clients'] as $clients) : ?>
        <!-- Profile Card Container -->
        <div class="profile-card">
            
            <div class="card-header">
                <div class="logo-container">
                    <img class="imagePlaceholder" src="<?php echo URL_ROOT; ?>/uploads/clientLogos/<?php echo $clients->logo_path; ?>" id="photoPreview" alt="Uploaded logo preview"  />
                </div>
                <h2 class="company-name" id="companyName"><?php echo $clients -> legal_company_name ?? $clients -> company_name?></h2>
                <p style="color: #666; font-size: 14px; margin-top: 5px;"><?php echo $clients -> company_type ?? 'N/A'?></p>
            </div>
            
            <div class="card-body">
                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">badge</span>
                    <div class="info-content">
                        <span class="info-label">Business Registration Number</span>
                        <span class="info-value" id="registrationNumber"><?php echo $clients -> business_registration_number ?? 'Not provided'?></span>
                    </div>
                </div>

                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">location_on</span>
                    <div class="info-content">
                        <span class="info-label">Registered Business Address</span>
                        <span class="info-value" id="registeredAddress"><?php echo nl2br($clients -> registered_address ?? 'Not provided')?></span>
                    </div>
                </div>
                
                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">mail</span>
                    <div class="info-content">
                        <span class="info-label">Primary Business Email</span>
                        <span class="info-value" id="companyEmail"><?php echo $clients -> email?></span>
                    </div>
                </div>
                
                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">call</span>
                    <div class="info-content">
                        <span class="info-label">Primary Business Phone</span>
                        <span class="info-value" id="companyPhone"><?php echo $clients -> phone_number?></span>
                    </div>
                </div>
                
                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">person</span>
                    <div class="info-content">
                        <span class="info-label">Contact Person</span>
                        <span class="info-value" id="contactPerson"><?php echo $clients -> contact_person_name?></span>
                    </div>
                </div>

                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">description</span>
                    <div class="info-content">
                        <span class="info-label">Business Registration Document</span>
                        <?php if(!empty($clients -> business_document)): ?>
                            <a href="<?php echo URL_ROOT; ?>/uploads/businessDocuments/<?php echo $clients -> business_document; ?>" target="_blank" style="color: #a30f0f; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">open_in_new</span>
                                View Document
                            </a>
                        <?php else: ?>
                            <span class="info-value" style="color: #999;">No document uploaded</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">event</span>
                    <div class="info-content">
                        <span class="info-label">Applied at</span>
                        <span class="info-value" id="contactPerson"><?php echo time_convert($clients -> created_at)?></span>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <a href="<?php echo URL_ROOT; ?>/admin/deleterequest/<?php echo $clients -> id?>" class="btn-primary primary-btn" id="editClientBtn">
                    Delete
                </a>
                
            </div>
        </div>
        <?php endforeach; ?>
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