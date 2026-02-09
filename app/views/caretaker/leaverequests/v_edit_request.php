<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>

<!-- Import global stylesheet -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">

<!-- Import Google Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<style>
    /* ---------- Form Section ---------- */
    .form-and-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
    }

    .file-upload {
        flex: 1 1 200px;
        text-align: center;
    }

    .filePlaceholder {
        height: 180px;
        border: 2px dashed var(--border-color);
        border-radius: 12px;
        margin: 0 auto 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f9f9f9;
        transition: all 0.3s;
    }

    .filePlaceholder:hover {
        border-color: var(--primary-color);
        background-color: #fff;
    }

    .filePlaceholder .material-symbols-outlined {
        font-size: 48px;
        color: #999;
    }

    .filePlaceholder p {
        margin: 10px 0 0 0;
        color: #666;
        font-size: 14px;
    }

    .application-form {
        flex: 2 1 500px;
        background-color: #fff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
    }

    .field {
        margin-bottom: 15px;
    }

    .field-label {
        margin-bottom: 6px;
        font-weight: 500;
        color: var(--text-color);
    }

    .field-input, .field-select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 15px;
    }

    .field-input:focus, .field-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(164, 0, 0, 0.2);
    }

    .field-textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 15px;
        min-height: 100px;
        resize: vertical;
        font-family: inherit;
    }

    .field-textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(164, 0, 0, 0.2);
    }

    .btn-upload {
        background-color: var(--primary-color);
        color: var(--secondary-color);
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-upload:hover {
        background-color: #b50000;
    }

    .btn-upload:active {
        background-color: #800000;
        transform: scale(0.97);
    }

    /* ---------- Buttons ---------- */
    .btn {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: var(--secondary-color);
        box-shadow: 0 4px 10px rgba(164, 0, 0, 0.3);
    }

    .btn-primary:hover {
        background-color: #b50000;
    }

    .btn-primary:active {
        background-color: #800000;
        transform: scale(0.97);
    }

    .btn-light {
        background-color: #f1f1f1;
        color: #333;
    }

    .btn-light:hover {
        background-color: #e0e0e0;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .form-input-error {
        color: red;
        font-size: 13px;
        margin-top: 4px;
    }

    /* ---------- Page Layout ---------- */
    .page {
        max-width: 1200px;
        margin-right: 15vw;
        margin-left: 15vw;
        padding: 0 20px;
    }

    .back-btn-container {
        margin: 20px;
    }

    .date-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .file-name {
        margin-top: 10px;
        font-size: 13px;
        color: #666;
        word-break: break-word;
    }

    .current-file {
        padding: 12px;
        background: #f5f5f5;
        border-radius: 6px;
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .current-file-info {
        flex: 1;
    }

    .current-file-name {
        font-size: 14px;
        color: #333;
        margin-bottom: 4px;
    }

    .current-file-link {
        font-size: 13px;
        color: var(--primary-color);
        text-decoration: none;
    }

    .current-file-link:hover {
        text-decoration: underline;
    }

    .remove-current-file {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
    }

    .remove-current-file:hover {
        background: #c0392b;
    }
</style>

<div class="back-btn-container">
    <button class="tertiary-btn" style="display:flex; width:100px; align-items:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/caretaker/leaverequests'"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>
</div>

<main class="page">
    <form class="form-and-preview" action="<?php echo URL_ROOT; ?>/caretaker/editLeaveRequest/<?php echo $data['leaveRequest']->id; ?>" method="POST" enctype="multipart/form-data">
        
        <!-- Input Form -->
        <div class="application-form" style="flex: 1 1 100%; max-width: 800px; margin: 0 auto;">
            <h3 style="color: var(--primary-color); margin-top: 0;">Edit Leave Request</h3>
            
            <div class="field">
                <div class="field-label">Leave Type: <span style="color: red;">*</span></div>
                <select class="field-select" id="leave_type" name="leave_type" required>
                    <option value="">Select Leave Type</option>
                    <option value="Sick Leave" <?php echo (isset($data['leave_type_value']) && $data['leave_type_value'] == 'Sick Leave') ? 'selected' : ''; ?>>Sick Leave</option>
                    <option value="Annual Leave" <?php echo (isset($data['leave_type_value']) && $data['leave_type_value'] == 'Annual Leave') ? 'selected' : ''; ?>>Annual Leave</option>
                    <option value="Emergency Leave" <?php echo (isset($data['leave_type_value']) && $data['leave_type_value'] == 'Emergency Leave') ? 'selected' : ''; ?>>Emergency Leave</option>
                    <option value="Maternity Leave" <?php echo (isset($data['leave_type_value']) && $data['leave_type_value'] == 'Maternity Leave') ? 'selected' : ''; ?>>Maternity Leave</option>
                    <option value="Paternity Leave" <?php echo (isset($data['leave_type_value']) && $data['leave_type_value'] == 'Paternity Leave') ? 'selected' : ''; ?>>Paternity Leave</option>
                    <option value="Other" <?php echo (isset($data['leave_type_value']) && $data['leave_type_value'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
                <span class="form-input-error"><?php echo isset($data['leave_type_err']) ? $data['leave_type_err'] : ''; ?></span>
            </div>

            <div class="date-grid">
                <div class="field">
                    <div class="field-label">Start Date: <span style="color: red;">*</span></div>
                    <input class="field-input" type="date" id="start_date" name="start_date" 
                           value="<?php echo isset($data['start_date_value']) ? $data['start_date_value'] : ''; ?>" 
                           min="<?php echo date('Y-m-d'); ?>"
                           required />
                    <span class="form-input-error"><?php echo isset($data['start_date_err']) ? $data['start_date_err'] : ''; ?></span>
                </div>

                <div class="field">
                    <div class="field-label">End Date: <span style="color: red;">*</span></div>
                    <input class="field-input" type="date" id="end_date" name="end_date" 
                           value="<?php echo isset($data['end_date_value']) ? $data['end_date_value'] : ''; ?>" 
                           min="<?php echo date('Y-m-d'); ?>"
                           required />
                    <span class="form-input-error"><?php echo isset($data['end_date_err']) ? $data['end_date_err'] : ''; ?></span>
                </div>
            </div>

            <div class="field">
                <div class="field-label">Reason: <span style="color: red;">*</span></div>
                <textarea class="field-textarea" id="reason" name="reason" 
                          placeholder="Please provide a detailed reason for your leave request" required><?php echo isset($data['reason_value']) ? $data['reason_value'] : ''; ?></textarea>
                <span class="form-input-error"><?php echo isset($data['reason_err']) ? $data['reason_err'] : ''; ?></span>
            </div>

            <div class="field">
                <div class="field-label">Proof/Supporting Document: <span style="font-size: 12px; color: #666;">(Optional - PDF, JPG, PNG, GIF)</span></div>
                
                <?php if (!empty($data['current_file'])): ?>
                <div class="current-file" id="currentFileDisplay">
                    <span class="material-symbols-outlined" style="color: var(--primary-color);">attach_file</span>
                    <div class="current-file-info">
                        <div class="current-file-name"><?php echo basename($data['current_file']); ?></div>
                        <a href="<?php echo URL_ROOT . $data['current_file']; ?>" target="_blank" class="current-file-link">View current file</a>
                    </div>
                    <button type="button" class="remove-current-file" onclick="removeCurrentFile()">Remove</button>
                </div>
                <input type="hidden" name="remove_file" id="removeFileFlag" value="0">
                <?php endif; ?>
                
                <div class="file-upload">
                    <div class="filePlaceholder" id="filePlaceholder" onclick="toggleFileBrowse()">
                        <span class="material-symbols-outlined">upload_file</span>
                        <p><?php echo !empty($data['current_file']) ? 'Click to replace file' : 'Click to upload file'; ?></p>
                    </div>
                    
                    <input type="file" id="proof_file" name="proof_file" accept=".pdf,.jpg,.jpeg,.png,.gif" hidden />
                    <span class="form-input-error"><?php echo isset($data['proof_file_err']) ? $data['proof_file_err'] : ''; ?></span>
                    <div class="file-name" id="fileName"></div>
                    <div class="btn-upload" id="removeFileBtn" style="display: none;" onClick="removeFile()">Remove File</div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-light" onclick="window.location.href='<?php echo URL_ROOT; ?>/caretaker/leaverequests'">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Request</button>
            </div>
        </div>
    </form>
</main>

<script>
// File upload functionality
function toggleFileBrowse() {
    document.getElementById('proof_file').click();
}

function removeFile() {
    const filePlaceholder = document.getElementById('filePlaceholder');
    document.getElementById('proof_file').value = '';
    document.getElementById('fileName').textContent = '';
    document.getElementById('removeFileBtn').style.display = 'none';
    filePlaceholder.innerHTML = '<span class="material-symbols-outlined">upload_file</span><p><?php echo !empty($data['current_file']) ? 'Click to replace file' : 'Click to upload file'; ?></p>';
}

function removeCurrentFile() {
    document.getElementById('currentFileDisplay').style.display = 'none';
    document.getElementById('removeFileFlag').value = '1';
    const filePlaceholder = document.getElementById('filePlaceholder');
    filePlaceholder.innerHTML = '<span class="material-symbols-outlined">upload_file</span><p>Click to upload file</p>';
}

// Handle file selection
document.getElementById('proof_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const filePlaceholder = document.getElementById('filePlaceholder');
        const fileName = document.getElementById('fileName');
        
        // Get file icon based on type
        let iconName = 'description';
        if (file.type.startsWith('image/')) {
            iconName = 'image';
        } else if (file.type === 'application/pdf') {
            iconName = 'picture_as_pdf';
        }
        
        filePlaceholder.innerHTML = `<span class="material-symbols-outlined" style="font-size: 64px; color: var(--primary-color);">${iconName}</span>`;
        fileName.textContent = file.name;
        document.getElementById('removeFileBtn').style.display = 'block';
    }
});

// Update end date min value when start date changes
document.getElementById('start_date').addEventListener('change', function(e) {
    document.getElementById('end_date').min = e.target.value;
});
</script>

<?php flash('msg')?>

<script>
  // Flash message auto-remove
  document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.getElementById('msg-flash');
    
    if (flashMessage) {
      setTimeout(function() {
        flashMessage.classList.add('fade-out');
        
        setTimeout(function() {
          if (flashMessage.parentNode) {
            flashMessage.parentNode.removeChild(flashMessage);
          }
        }, 300);
      }, 5000);
    }
  });
</script>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
