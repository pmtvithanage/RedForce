<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<!-- Import global stylesheet -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">

<!-- Import Google Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<style>
    /* ---------- Form Section ---------- */
    .form-and-photo {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
    }

    .photo-upload {
        flex: 1 1 200px;
        text-align: center;
    }

    .imagePlaceholder {
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

    .field-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 15px;
    }

    .field-input:focus {
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

    .preview-description {
        font-size: 14px;
        color: #555;
        margin: 15px 0;
        text-align: left;
        line-height: 1.6;
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
        width: 120px;
        margin: 5px auto;
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
    
    /* ---------- Preview Section ---------- */
    .preview-section {
        flex: 1 1 300px;
        background-color: #fff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        min-height: 400px;
    }

    .preview-section h3 {
        color: var(--primary-color);
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 18px;
    }

    .preview-content {
        text-align: center;
    }

    .preview-image {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        border-radius: 8px;
        margin-bottom: 15px;
        border: 2px solid var(--border-color);
    }

    .preview-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-color);
        margin: 15px 0;
    }

    .preview-roles {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
        margin-top: 10px;
    }

    .role-badge {
        background-color: var(--primary-color);
        color: var(--secondary-color);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 13px;
    }

    .checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 8px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .checkbox-item input[type="checkbox"] {
        width: auto;
        cursor: pointer;
    }

    .checkbox-item label {
        cursor: pointer;
        margin: 0;
        font-weight: normal;
    }
</style>

<div class="back-btn-container">
    <button class="tertiary-btn" style="display:flex; width:100px; align-items:center;" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/advertisements'"> 
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>
</div>

<main class="page">
    <form class="form-and-photo" action="<?php echo URL_ROOT; ?>/admin/createAdvertisement" method="POST" enctype="multipart/form-data">
        
        <!-- Left Side: Preview -->
       <!-- <div class="preview-section">
            <h3>Advertisement Preview</h3>
            <div class="preview-content">
                <img id="previewImage" class="preview-image" 
                     src="<?php echo URL_ROOT; ?>/public/img/photo.png" 
                     alt="Preview" />
                <div class="preview-title" id="previewTitle">Advertisement Title</div>
                <div class="preview-description" id="previewDescription">No description provided</div>
                <div class="preview-roles" id="previewRoles">
                    <span class="role-badge">No roles selected</span>
                </div>
            </div>
        </div>-->

        <!-- Right Side: Input Form -->
        <div class="application-form" style="flex: 2 1 500px;">
            <h3 style="color: var(--primary-color); margin-top: 0;">Advertisement Details</h3>
            
            <div class="field">
                <div class="field-label">Advertisement Title:</div>
                <input class="field-input" type="text" id="title" name="title" 
                       value="<?php echo isset($data['title_value']) ? $data['title_value'] : ''; ?>" 
                       placeholder="Enter advertisement title" />
                <span class="form-input-error"><?php echo isset($data['title_err']) ? $data['title_err'] : ''; ?></span>
            </div>

            <div class="field">
                <div class="field-label">Description:</div>
                <textarea class="field-textarea" id="description" name="description" 
                          placeholder="Enter advertisement description"><?php echo isset($data['description_value']) ? $data['description_value'] : ''; ?></textarea>
                <span class="form-input-error"><?php echo isset($data['description_err']) ? $data['description_err'] : ''; ?></span>
            </div>

            <div class="field">
                <div class="field-label">Advertisement Image:</div>
                <div class="photo-upload">
                    <img class="imagePlaceholder" src="<?php echo URL_ROOT; ?>/public/img/photo.png" 
                        alt="Advertisement image preview" 
                        id="imagePlaceholder"
                        data-default-src="<?php echo URL_ROOT; ?>/public/img/photo.png" />
                    
                    <input type="file" id="image" name="image" accept="image/*" hidden />
                    <span class="form-input-error"><?php echo isset($data['image_err']) ? $data['image_err'] : ''; ?></span>
                    <div class="btn-upload" id="addImageBtn" onClick="toggleBrowse()">Upload Image</div>
                    <div class="btn-upload" id="removeImageBtn" style="display: none;" onClick="removeImage()">Remove</div>
                </div>
            </div>

            <div class="field">
                <div class="field-label">Target Roles:</div>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" id="role_all" name="target_roles[]" value="all" />
                        <label for="role_all">All Roles</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="role_po" name="target_roles[]" value="premise officer" />
                        <label for="role_po">Premise Officer</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="role_ct" name="target_roles[]" value="caretaker" />
                        <label for="role_ct">Caretaker</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="role_mr" name="target_roles[]" value="mobile rider" />
                        <label for="role_mr">Mobile Rider</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="role_supervisor" name="target_roles[]" value="supervisor" />
                        <label for="role_supervisor">Supervisor</label>
                    </div>
                </div>
                <span class="form-input-error"><?php echo isset($data['roles_err']) ? $data['roles_err'] : ''; ?></span>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-light" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/advertisements'">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Advertisement</button>
            </div>
        </div>
    </form>
</main>

<script>
// Image upload functionality
function toggleBrowse() {
    document.getElementById('image').click();
}

function removeImage() {
    const imagePlaceholder = document.getElementById('imagePlaceholder');
    const previewImage = document.getElementById('previewImage');
    const defaultSrc = imagePlaceholder.getAttribute('data-default-src');
    
    imagePlaceholder.src = defaultSrc;
    previewImage.src = defaultSrc;
    document.getElementById('image').value = '';
    document.getElementById('addImageBtn').style.display = 'block';
    document.getElementById('removeImageBtn').style.display = 'none';
}

// Handle image selection
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePlaceholder = document.getElementById('imagePlaceholder');
            const previewImage = document.getElementById('previewImage');
            imagePlaceholder.src = e.target.result;
            previewImage.src = e.target.result;
            document.getElementById('addImageBtn').style.display = 'none';
            document.getElementById('removeImageBtn').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

// Live preview for title
document.getElementById('title').addEventListener('input', function(e) {
    const previewTitle = document.getElementById('previewTitle');
    previewTitle.textContent = e.target.value || 'Advertisement Title';
});

// Live preview for description
document.getElementById('description').addEventListener('input', function(e) {
    const previewDescription = document.getElementById('previewDescription');
    previewDescription.textContent = e.target.value || 'No description provided';
});

// Live preview for roles
const roleCheckboxes = document.querySelectorAll('input[name="target_roles[]"]');
roleCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateRolesPreview);
});

function updateRolesPreview() {
    const previewRoles = document.getElementById('previewRoles');
    const checkedRoles = Array.from(roleCheckboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.labels[0].textContent.trim());
    
    if (checkedRoles.length === 0) {
        previewRoles.innerHTML = '<span class="role-badge">No roles selected</span>';
    } else {
        previewRoles.innerHTML = checkedRoles
            .map(role => `<span class="role-badge">${role}</span>`)
            .join('');
    }
}
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
