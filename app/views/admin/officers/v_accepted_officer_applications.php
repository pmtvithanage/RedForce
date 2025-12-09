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

.filter-container {
    position: relative;
    display: inline-block;
}

.filter-button {
    background-color: #a40000;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    min-width: 180px;
    text-align: left;
    position: relative;
}

.filter-button::after {
    content: '▼';
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
}

.filter-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    background-color: white;
    border-radius: 5px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1000;
    display: none;
    flex-direction: column;
    min-width: 200px;
    margin-top: 5px;
}

.filter-dropdown.show {
    display: flex;
    animation: fadeIn 0.2s ease;
}

.filter-option {
    background: none;
    border: none;
    padding: 12px 20px;
    text-align: left;
    cursor: pointer;
    font-size: 15px;
    color: #333;
    transition: background-color 0.2s;
}

.filter-option:hover {
    background-color: #f5f5f5;
}

.filter-option.active {
    background-color: #ffeeeeff;
    color: #f95858ff;
    font-weight: 500;
}

.filter-selected {
    margin-top: 10px;
    font-size: 14px;
    color: #666;
    padding: 5px 10px;
    background-color: #f8f9fa;
    border-radius: 4px;
    display: none;
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
    

        <div class="filter-container" id="filterContainer">
      <button class="filter-button" id="filterButton">
          <?php 
          if(isset($data['role'])) {
              switch($data['role']) {
                  case 'all': echo 'All Applications'; break;
                  case 'po': echo 'Premise Officers'; break;
                  case 'mr': echo 'Mobile Riders'; break;
                  case 'ct': echo 'Care Takers'; break;
                  default: echo 'Select Filters';
              }
          } else {
              echo 'Select Filters';
          }
          ?>
      </button>
      
      <div class="filter-dropdown" id="filterDropdown">
          <?php 
          $currentRole = isset($data['role']) ? $data['role'] : '';
          $options = [
              'all' => 'All Applications',
              'po' => 'Premise Officers',
              'mr' => 'Mobile Riders',
              'ct' => 'Care Takers'
          ];
          
          // Show all options except the currently selected one
          foreach($options as $key => $label):
              if($key !== $currentRole):
          ?>
          <button class="filter-option" 
                  data-value="<?php echo URL_ROOT; ?>/admin/accepted_officer_applications/<?php echo $key; ?>"
                  onclick="window.location.href = this.dataset.value">
              <?php echo $label; ?>
          </button>
          <?php 
              endif;
          endforeach;
          ?>
      </div>
      
      <div class="filter-selected" id="filterSelected">
          Current: 
          <?php 
          if(isset($data['role'])) {
              echo htmlspecialchars($options[$data['role']]);
          }
          ?>
      </div>
  </div>
</div>
    
    <div class="content-container">
        <?php if(empty($data['officer'])){
            echo('No result found !');
        }
        ?>
        <?php foreach($data['officer'] as $officer) : ?>
        <!-- Profile Card Container -->
        <div class="profile-card">

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
                    <span class="material-symbols-outlined info-icon">person</span>
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



<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.getElementById('filterButton');
    const filterDropdown = document.getElementById('filterDropdown');
    const filterSelected = document.getElementById('filterSelected');
    
    // Toggle dropdown visibility
    filterButton.addEventListener('click', function(e) {
        e.stopPropagation();
        filterDropdown.classList.toggle('show');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!filterContainer.contains(e.target)) {
            filterDropdown.classList.remove('show');
        }
    });
    
    // Show current selection if not "Select Filters"
    const buttonText = filterButton.textContent.trim();
    if (buttonText !== 'Select Filters') {
        filterSelected.style.display = 'block';
    }
    
    // Add active class to current selection in dropdown
    const currentPath = window.location.pathname;
    const options = document.querySelectorAll('.filter-option');
    options.forEach(option => {
        const optionPath = new URL(option.dataset.value).pathname;
        if (currentPath === optionPath) {
            option.classList.add('active');
        }
    });
});
</script>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>