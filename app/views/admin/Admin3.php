<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<link rel="stylesheet" href="<?= URL_ROOT ?>/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />


<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    .content-container {
        max-width: 60vw;
        margin-left: 10vw;
        padding: 20px 0;
    }
    
    .tabs {
        display: flex; 
        gap: 10px; 
        margin-bottom: 30px; 
        margin-left: 20px;
        flex-wrap: wrap;
    }
    
    .profile-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin: 25px 0;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }
    
    .profile-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }
    
    .card-header {
        padding: 40px 30px;
        text-align: center;
        background: linear-gradient(135deg, #f5f5f5 0%, #fafafa 100%);
        border-bottom: 2px solid #f0f0f0;
    }
    
    .logo-container {
        margin-bottom: 25px;
    }
    
    .imagePlaceholder {
        height: 200px;
        width: 200px;
        border: 3px solid #e0e0e0;
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: linear-gradient(135deg, #fff 0%, #f9f9f9 100%);
        object-fit: cover;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .imagePlaceholder:hover {
        border-color: #a40000;
        box-shadow: 0 6px 20px rgba(164, 0, 0, 0.15);
    }
    
    .role-label {
        display: inline-block;
        background: #e8e8e8;
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
    }
    
    .company-name {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
        letter-spacing: -0.5px;
    }
    
    .card-body {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        padding: 40px;
        background: white;
    }
    
    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        transition: all 0.2s ease;
        padding: 12px;
        border-radius: 8px;
    }
    
    .info-row:hover {
        background: #f9f9f9;
    }
    
    .info-row:nth-last-child(-n+2) {
        grid-column: span 2;
    }
    
    .info-icon {
        font-size: 24px;
        color: #a40000;
        min-width: 28px;
        flex-shrink: 0;
    }
    
    .info-content {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    .info-label {
        font-size: 12px;
        color: #888;
        margin-bottom: 6px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-value {
        font-size: 15px;
        color: #222;
        font-weight: 500;
        word-break: break-word;
    }
    
    .info-value a {
        color: #a40000;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .info-value a:hover {
        color: #d00000;
        text-decoration: underline;
    }
    
    .card-footer {
        padding: 25px 40px;
        background: linear-gradient(135deg, #f9f9f9 0%, #f5f5f5 100%);
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        border-top: 1px solid #f0f0f0;
    }
    
    .btn-primary, .btn-secondary {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        letter-spacing: 0.3px;
    }
    
    .btn-primary {
        background: #a40000;
        color: white;
    }
    
    .btn-primary:hover {
        background: #d00000;
        box-shadow: 0 4px 12px rgba(164, 0, 0, 0.3);
        transform: translateY(-1px);
    }
    
    .btn-secondary {
        background: white;
        color: #a40000;
        border: 2px solid #a40000;
    }
    
    .btn-secondary:hover {
        background: #a40000;
        color: white;
    }
    
    .filter-container {
        position: relative;
        display: inline-block;
    }
    
    .filter-button {
        background: linear-gradient(135deg, #a40000 0%, #d00000 100%);
        color: white;
        padding: 11px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        min-width: 180px;
        text-align: left;
        position: relative;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(164, 0, 0, 0.2);
    }
    
    .filter-button:hover {
        box-shadow: 0 4px 15px rgba(164, 0, 0, 0.3);
        transform: translateY(-1px);
    }
    
    .filter-button::after {
        content: '▼';
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 11px;
        transition: transform 0.3s ease;
    }
    
    .filter-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        display: none;
        flex-direction: column;
        min-width: 220px;
        margin-top: 8px;
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }
    
    .filter-dropdown.show {
        display: flex;
        animation: slideDown 0.25s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .filter-option {
        background: none;
        border: none;
        padding: 13px 20px;
        text-align: left;
        cursor: pointer;
        font-size: 14px;
        color: #333;
        transition: all 0.2s;
        font-weight: 500;
    }
    
    .filter-option:hover {
        background: #f5f5f5;
        padding-left: 24px;
    }
    
    .filter-option.active {
        background: #fff0f0;
        color: #a40000;
        font-weight: 700;
        border-left: 4px solid #a40000;
        padding-left: 24px;
    }
    
    .filter-selected {
        margin-top: 10px;
        font-size: 13px;
        color: #666;
        padding: 8px 12px;
        background: linear-gradient(135deg, #fff5f5 0%, #ffefef 100%);
        border-radius: 6px;
        display: none;
        font-weight: 600;
        border-left: 3px solid #a40000;
    }
    
    @media (max-width: 1024px) {
        .content-container {
            max-width: 80vw;
            margin-left: 5vw;
        }
        
        .card-body {
            grid-template-columns: 1fr;
        }
        
        .info-row:nth-last-child(-n+2) {
            grid-column: span 1;
        }
    }
    
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }
        
        .content-container {
            max-width: 100%;
            margin-left: 0;
            padding: 15px;
        }
        
        .tabs {
            flex-direction: column;
            margin-left: 0;
        }
        
        .card-header {
            padding: 25px 20px;
        }
        
        .imagePlaceholder {
            height: 150px;
            width: 150px;
        }
        
        .company-name {
            font-size: 22px;
        }
        
        .card-body {
            gap: 20px;
            padding: 25px;
        }
        
        .card-footer {
            flex-direction: column;
            padding: 20px 25px;
        }
        
        .btn-primary, .btn-secondary {
            width: 100%;
            justify-content: center;
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<button class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/officers'"> 
    <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
    Back
  </button>

<div class="main-content">
    <div class="tabs">
    <button class="tab secondary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/pending_officer_applications/all'">Pending Requests</button>
    <button class="tab primary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/accepted_officer_applications/all'">Approved Requests</button>
    <button class="tab secondary-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/rejected_officer_applications/all'">Rejected Requests</button>
   
   
   
   
    <div style="display:flex; align-items:center; gap:12px; margin-left:8px; padding:8px 12px; border:1px solid #e5e7eb; border-radius:8px; background:#fff;">
        <span style="font-size:13px; font-weight:600; color:#374151;">Filter:</span>
        <label style="display:flex; align-items:center; gap:6px; font-size:13px; color:#111827;">
            <input type="radio" name="roleFilter" class="role-radio" value="all" checked>
            All
        </label>
        <label style="display:flex; align-items:center; gap:6px; font-size:13px; color:#111827;">
            <input type="radio" name="roleFilter" class="role-radio" value="po">
            Premise Officer
        </label>
        <label style="display:flex; align-items:center; gap:6px; font-size:13px; color:#111827;">
            <input type="radio" name="roleFilter" class="role-radio" value="ct">
            Care Taker
        </label>
        <label style="display:flex; align-items:center; gap:6px; font-size:13px; color:#111827;">
            <input type="radio" name="roleFilter" class="role-radio" value="mr">
            Mobile Rider
        </label>
    </div>

</div>
    
    <div class="content-container">
        <?php if(empty($data['officer'])){
            echo('No result found !');
        }
        ?>
        <?php foreach($data['officer'] as $officer) : ?>
        <!-- Profile Card Container -->
        <div class="profile-card" data-role="<?php echo strtolower($officer->role ?? ''); ?>">

            <p class="role-label" style="margin-left:20px; margin-top:10px; font-weight:bold; color:#555;">
            <?php 
            if($officer->role == 'po') {
                echo 'Premise Officer';
            } else if($officer->role == 'mr') {
                echo 'Mobile Rider';
            } else if($officer->role == 'ct') {
                echo 'Care Taker';
            }
            ?>
            </p>

            <div class="card-header">
                <div class="logo-container">
                    <img class="imagePlaceholder" src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo $officer->photo; ?>" id="photoPreview" alt="Uploaded logo preview"  />
                </div>
                <h2 class="company-name" id="companyName"><?php echo $officer -> name?></h2>
            </div>
            
            <div class="card-body">
                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">mail</span>
                    <div class="info-content">
                        <span class="info-label">Email</span>
                        <span class="info-value" id="companyEmail"><?php echo $officer -> email?></span>
                    </div>
                </div>
                
                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">call</span>
                    <div class="info-content">
                        <span class="info-label">Phone</span>
                        <span class="info-value" id="companyPhone"><?php echo $officer -> phone_number?></span>
                    </div>
                </div>

                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">cake</span>
                    <div class="info-content">
                        <span class="info-label">Date of Birth</span>
                        <span class="info-value"><?php echo $officer -> date_of_birth ?? 'N/A'?></span>
                    </div>
                </div>

                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">badge</span>
                    <div class="info-content">
                        <span class="info-label">National ID</span>
                        <span class="info-value"><?php echo $officer -> national_id ?? 'N/A'?></span>
                    </div>
                </div>

                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">wc</span>
                    <div class="info-content">
                        <span class="info-label">Gender</span>
                        <span class="info-value"><?php echo ucfirst($officer -> gender ?? 'N/A')?></span>
                    </div>
                </div>

                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">home</span>
                    <div class="info-content">
                        <span class="info-label">Permanent Address</span>
                        <span class="info-value"><?php echo $officer -> address ?? 'N/A'?></span>
                    </div>
                </div>

                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">location_on</span>
                    <div class="info-content">
                        <span class="info-label">District</span>
                        <span class="info-value"><?php echo $officer -> district ?? 'N/A'?></span>
                    </div>
                </div>

                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">location_city</span>
                    <div class="info-content">
                        <span class="info-label">City</span>
                        <span class="info-value"><?php echo $officer -> city ?? 'N/A'?></span>
                    </div>
                </div>
                
                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">description</span>
                    <div class="info-content">
                        <span class="info-label">CV File</span>
                        <a href="<?php echo URL_ROOT; ?>/uploads/applicantCVs/<?php echo $officer -> cv ?>" 
                            target="_blank" 
                            style="color: #9a0000ff; text-decoration: none; margin-left: 5px; display: inline-flex; align-items: center;">
                            <span class="material-symbols-outlined" style="margin-right: 5px; font-size: 18px;">
                                picture_as_pdf
                            </span>
                            <?php
                            // Extract just the original filename (remove timestamp prefix)
                            $originalFileName = substr($officer -> cv, strpos($officer -> cv, '_') + 1);
                            echo $originalFileName;
                            ?>
                        </a>
                    </div>
                </div>

                <div class="info-row">
                    <span class="material-symbols-outlined info-icon">event</span>
                    <div class="info-content">
                        <span class="info-label">Applied at</span>
                        <span class="info-value" id="contactPerson"><?php echo time_convert($officer -> submitted_at)?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>



<div class="backdrop" id="backdrop" hidden></div>


<script>
    (function () {
        const roleRadios = document.querySelectorAll('.role-radio');
        const cards = document.querySelectorAll('.profile-card[data-role]');

        if (roleRadios.length === 0 || cards.length === 0) {
            return;
        }

        const applyRoleFilter = () => {
            const selectedRadio = document.querySelector('.role-radio:checked');
            const selectedRole = selectedRadio ? selectedRadio.value : 'all';

            cards.forEach((card) => {
                const role = (card.dataset.role || '').toLowerCase();
                const show = selectedRole === 'all' || role === selectedRole;
                card.style.display = show ? '' : 'none';
            });
        };

        roleRadios.forEach((radio) => {
            radio.addEventListener('change', applyRoleFilter);
        });
    })();
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>