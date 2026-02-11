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
        margin-left: 200px;
        margin-right: 200px;
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

    .form-card {
        background: white;
        border-radius: var(--radius);
        padding: 32px;
        box-shadow: var(--shadow);
        border: 2px solid #e0e0e0;
    }

    .form-section {
        margin-bottom: 32px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .form-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section-title .material-symbols-outlined {
        color: var(--accent);
        font-size: 24px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group label .required {
        color: var(--accent);
        margin-left: 4px;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-group small {
        display: block;
        margin-top: 6px;
        color: #666;
        font-size: 13px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .btn-group {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #f0f0f0;
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(164, 0, 0, 0.2);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(164, 0, 0, 0.3);
    }

    .btn-cancel {
        background: #f0f0f0;
        color: #333;
        border: none;
        padding: 14px 32px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-cancel:hover {
        background: #e0e0e0;
        transform: translateY(-2px);
    }

    .image-upload-container {
        border: 2px dashed #e0e0e0;
        border-radius: 8px;
        padding: 24px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        background: #f8f9fa;
    }

    .image-upload-container:hover {
        border-color: var(--accent);
        background: #fff5f5;
    }

    .image-upload-container.has-image {
        padding: 0;
        border-style: solid;
    }

    .image-preview {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
        border-radius: 6px;
        display: none;
    }

    .image-preview.active {
        display: block;
    }

    .upload-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .upload-placeholder .material-symbols-outlined {
        font-size: 48px;
        color: #999;
    }

    .upload-placeholder p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }

    .btn-clear-image {
        margin-top: 12px;
        background: #dc3545;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-clear-image:hover {
        background: #c82333;
        transform: scale(1.05);
    }

    /* Package Preview Styles */
    .preview-section {
        margin-top: 40px;
        padding-top: 32px;
        border-top: 3px solid #f0f0f0;
    }

    .preview-card-container {
        max-width: 500px;
        margin: 20px auto 0;
    }

    .preview-package-card {
        height: 450px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: var(--radius);
        padding: 50px 30px;
        box-shadow: var(--shadow);
        border: 2px solid var(--accent);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .preview-package-card::before {
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

    .preview-package-card > * {
        position: relative;
        z-index: 1;
    }

    .preview-package-name {
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

    .preview-package-officers {
        font-size: 15px;
        color: #ffffff;
        margin-bottom: 16px;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        padding: 10px 20px;
        border-radius: 8px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
    }

    .preview-package-description {
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

    .preview-package-price {
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

    .preview-price-period {
        font-size: 16px;
        color: #ffffff;
        font-weight: 400;
    }

    .preview-empty {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .preview-empty .material-symbols-outlined {
        font-size: 64px;
        color: #ddd;
        margin-bottom: 16px;
    }

    #previewContent {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    .current-image {
        max-width: 100%;
        max-height: 200px;
        margin-bottom: 12px;
        border-radius: 8px;
    }
</style>

<a href="<?php echo URL_ROOT; ?>/admin/viewPackages" class="tertiary-btn" style="display:flex; width:100px; margin: 20px;align-items:center; text-decoration:none;"> 
    <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
    Back
</a>

<div class="main-content">
    <div class="page-header">
        <h1>Edit Package</h1>
    </div>

    <div class="form-card">
        <form method="POST" action="<?php echo URL_ROOT; ?>/admin/updatePackage" enctype="multipart/form-data">
            <input type="hidden" name="package_id" value="<?php echo $data['package']->id; ?>">
            <input type="hidden" name="current_image" value="<?php echo $data['package']->background_image ?? ''; ?>">
            
            <!-- Basic Information -->
            <div class="form-section">
                <div class="form-section-title">
                    <span class="material-symbols-outlined">info</span>
                    Basic Information
                </div>

                <div class="form-group">
                    <label for="package_name">
                        Package Name
                        <span class="required">*</span>
                        <?php if (isset($data['package']->is_default) && $data['package']->is_default == 1): ?>
                        <span style="color: #666; font-size: 12px; font-weight: normal;">(System Default - Cannot be changed)</span>
                        <?php endif; ?>
                    </label>
                    <input type="text" id="package_name" name="package_name" required placeholder="e.g., Premium Package" 
                           value="<?php echo isset($data['data']['package_name']) ? htmlspecialchars($data['data']['package_name']) : htmlspecialchars($data['package']->package_name); ?>"
                           <?php echo (isset($data['package']->is_default) && $data['package']->is_default == 1) ? 'readonly style="background: #f5f5f5; cursor: not-allowed;"' : ''; ?>>
                    <small><?php echo (isset($data['package']->is_default) && $data['package']->is_default == 1) ? 'Default package name cannot be modified' : 'Give your package a descriptive name'; ?></small>
                    <?php if (isset($data['data']['package_name_err']) && !empty($data['data']['package_name_err'])): ?>
                        <span style="color: red; font-size: 13px; display: block; margin-top: 4px;"><?php echo $data['data']['package_name_err']; ?></span>
                    <?php endif; ?>
                </div>

                <?php 
                $isCustomPackage = (isset($data['package']->package_name) && $data['package']->package_name == 'Custom Package');
                $isDefaultPackage = (isset($data['package']->is_default) && $data['package']->is_default == 1);
                ?>
                
                <?php if ($isCustomPackage): ?>
                    <!-- Custom Package: Show pricing fields instead of quantity -->
                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="price_per_officer">
                                Price per Officer (LKR)
                                <span class="required">*</span>
                            </label>
                            <input type="number" id="price_per_officer" name="price_per_officer" min="0" step="0.01" required placeholder="e.g., 15000" 
                                   value="<?php echo isset($data['data']['price_per_officer']) ? $data['data']['price_per_officer'] : ($data['package']->price_per_officer ?? 15000); ?>">
                            <small>Price charged per security officer</small>
                        </div>

                        <div class="form-group">
                            <label for="price_per_supervisor">
                                Price per Supervisor (LKR)
                                <span class="required">*</span>
                            </label>
                            <input type="number" id="price_per_supervisor" name="price_per_supervisor" min="0" step="0.01" required placeholder="e.g., 20000" 
                                   value="<?php echo isset($data['data']['price_per_supervisor']) ? $data['data']['price_per_supervisor'] : ($data['package']->price_per_supervisor ?? 20000); ?>">
                            <small>Price charged per supervisor</small>
                        </div>

                        <div class="form-group">
                            <label for="price_per_caretaker">
                                Price per Caretaker (LKR)
                                <span class="required">*</span>
                            </label>
                            <input type="number" id="price_per_caretaker" name="price_per_caretaker" min="0" step="0.01" required placeholder="e.g., 12000" 
                                   value="<?php echo isset($data['data']['price_per_caretaker']) ? $data['data']['price_per_caretaker'] : ($data['package']->price_per_caretaker ?? 12000); ?>">
                            <small>Price charged per caretaker</small>
                        </div>
                    </div>
                    <!-- Hidden fields to keep personnel count at 0 for custom package -->
                    <input type="hidden" name="number_of_officers" value="0">
                    <input type="hidden" name="number_of_supervisors" value="0">
                    <input type="hidden" name="number_of_caretakers" value="0">
                <?php else: ?>
                    <!-- Regular Package: Show quantity fields -->
                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="number_of_officers">
                                Number of Officers
                                <span class="required">*</span>
                                <?php if ($isDefaultPackage): ?>
                                <span style="color: #666; font-size: 12px; font-weight: normal;">(Fixed)</span>
                                <?php endif; ?>
                            </label>
                            <input type="number" id="number_of_officers" name="number_of_officers" min="0" required placeholder="e.g., 5" 
                                   value="<?php echo isset($data['data']['number_of_officers']) ? $data['data']['number_of_officers'] : $data['package']->number_of_officers; ?>"
                                   <?php echo $isDefaultPackage ? 'readonly style="background: #f5f5f5; cursor: not-allowed;"' : ''; ?>>
                            <small><?php echo $isDefaultPackage ? 'Cannot be changed' : 'How many security officers'; ?></small>
                            <?php if (isset($data['data']['number_of_officers_err']) && !empty($data['data']['number_of_officers_err'])): ?>
                                <span style="color: red; font-size: 13px; display: block; margin-top: 4px;"><?php echo $data['data']['number_of_officers_err']; ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="number_of_supervisors">
                                Number of Supervisors
                                <?php if ($isDefaultPackage): ?>
                                <span style="color: #666; font-size: 12px; font-weight: normal;">(Fixed)</span>
                                <?php endif; ?>
                            </label>
                            <input type="number" id="number_of_supervisors" name="number_of_supervisors" min="0" 
                                   value="<?php echo isset($data['data']['number_of_supervisors']) ? $data['data']['number_of_supervisors'] : $data['package']->number_of_supervisors; ?>" 
                                   placeholder="e.g., 2"
                                   <?php echo $isDefaultPackage ? 'readonly style="background: #f5f5f5; cursor: not-allowed;"' : ''; ?>>
                            <small><?php echo $isDefaultPackage ? 'Cannot be changed' : 'How many supervisors'; ?></small>
                        </div>

                        <div class="form-group">
                            <label for="number_of_caretakers">
                                Number of Caretakers
                                <?php if ($isDefaultPackage): ?>
                                <span style="color: #666; font-size: 12px; font-weight: normal;">(Fixed)</span>
                                <?php endif; ?>
                            </label>
                            <input type="number" id="number_of_caretakers" name="number_of_caretakers" min="0" 
                                   value="<?php echo isset($data['data']['number_of_caretakers']) ? $data['data']['number_of_caretakers'] : $data['package']->number_of_caretakers; ?>" 
                                   placeholder="e.g., 1"
                                   <?php echo $isDefaultPackage ? 'readonly style="background: #f5f5f5; cursor: not-allowed;"' : ''; ?>>
                            <small><?php echo $isDefaultPackage ? 'Cannot be changed' : 'How many caretakers'; ?></small>
                        </div>
                    </div>
                    <!-- Hidden fields for pricing (not used in regular packages) -->
                    <input type="hidden" name="price_per_officer" value="0">
                    <input type="hidden" name="price_per_supervisor" value="0">
                    <input type="hidden" name="price_per_caretaker" value="0">
                <?php endif; ?>
            </div>
            </div>

            <!-- Pricing -->
            <?php if (!$isCustomPackage): ?>
            <div class="form-section">
                <div class="form-section-title">
                    <span class="material-symbols-outlined">payments</span>
                    Pricing
                </div>

                <div class="form-group">
                    <label for="package_price">
                        Monthly Price (LKR)
                        <span class="required">*</span>
                    </label>
                    <input type="number" id="package_price" name="package_price" min="0" step="0.01" required placeholder="e.g., 75000" value="<?php echo isset($data['data']['package_price']) ? $data['data']['package_price'] : $data['package']->package_price; ?>">
                    <small>Total monthly price for this package in LKR</small>
                    <?php if (isset($data['data']['package_price_err']) && !empty($data['data']['package_price_err'])): ?>
                        <span style="color: red; font-size: 13px; display: block; margin-top: 4px;"><?php echo $data['data']['package_price_err']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <!-- Custom Package has no fixed price -->
            <input type="hidden" name="package_price" value="0">
            <?php endif; ?>

            <!-- Background Image -->
            <div class="form-section">
                <div class="form-section-title">
                    <span class="material-symbols-outlined">image</span>
                    Background Image
                </div>

                <div class="form-group">
                    <label for="package_image">Package Background Image</label>
                    
                    <?php if (!empty($data['package']->background_image)): ?>
                        <div style="margin-bottom: 16px;">
                            <p style="font-size: 14px; color: #666; margin-bottom: 8px;">Current Image:</p>
                            <img src="<?php echo URL_ROOT; ?>/uploads/packages/<?php echo $data['package']->background_image; ?>" class="current-image" alt="Current package image">
                        </div>
                    <?php endif; ?>
                    
                    <div class="image-upload-container" onclick="document.getElementById('package_image').click()" id="imageUploadContainer">
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            <span class="material-symbols-outlined">cloud_upload</span>
                            <p>Click to upload new background image</p>
                            <p style="font-size: 12px; color: #999;">Recommended: 800x600px, JPG or PNG</p>
                        </div>
                        <img id="imagePreview" class="image-preview" alt="Package background preview">
                    </div>
                    <input type="file" id="package_image" name="package_image" accept="image/*" style="display: none;" onchange="previewImage(event)">
                    <button type="button" id="clearImageBtn" class="btn-clear-image" onclick="clearImage()" style="display: none;">Clear Image</button>
                    <small>Upload a new background image to replace the current one (optional)</small>
                    <?php if (isset($data['data']['image_err']) && !empty($data['data']['image_err'])): ?>
                        <span style="color: red; font-size: 13px; display: block; margin-top: 4px;"><?php echo $data['data']['image_err']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Description -->
            <div class="form-section">
                <div class="form-section-title">
                    <span class="material-symbols-outlined">description</span>
                    Description
                </div>

                <div class="form-group">
                    <label for="description">Package Description</label>
                    <textarea id="description" name="description" placeholder="Describe what makes this package unique..."><?php echo isset($data['data']['description']) ? htmlspecialchars($data['data']['description']) : htmlspecialchars($data['package']->description ?? ''); ?></textarea>
                    <small>Optional additional details about the package</small>
                </div>
            </div>

            <!-- Package Preview -->
            <div class="form-section preview-section">
                <div class="form-section-title">
                    <span class="material-symbols-outlined">visibility</span>
                    Package Preview
                </div>

                <div class="preview-card-container">
                    <div class="preview-package-card" id="previewCard">
                        <?php if (!empty($data['package']->background_image)): ?>
                            <style id="initial-bg-style">
                                #previewCard::before {
                                    background: url('<?php echo URL_ROOT; ?>/uploads/packages/<?php echo $data['package']->background_image; ?>') no-repeat center !important;
                                    background-size: cover !important;
                                }
                            </style>
                        <?php endif; ?>
                        <div id="previewContent">
                            <div class="preview-package-name" id="previewName"><?php echo htmlspecialchars($data['package']->package_name); ?></div>
                            <div class="preview-package-officers" id="previewOfficers">
                                <?php 
                                    if ($isCustomPackage) {
                                        // Show per-unit pricing for Custom Package
                                        $pricing = [];
                                        if (isset($data['package']->price_per_officer) && $data['package']->price_per_officer > 0) {
                                            $pricing[] = 'Officer: LKR ' . number_format($data['package']->price_per_officer, 0);
                                        }
                                        if (isset($data['package']->price_per_supervisor) && $data['package']->price_per_supervisor > 0) {
                                            $pricing[] = 'Supervisor: LKR ' . number_format($data['package']->price_per_supervisor, 0);
                                        }
                                        if (isset($data['package']->price_per_caretaker) && $data['package']->price_per_caretaker > 0) {
                                            $pricing[] = 'Caretaker: LKR ' . number_format($data['package']->price_per_caretaker, 0);
                                        }
                                        echo !empty($pricing) ? implode(' • ', $pricing) : 'Per-Unit Pricing';
                                    } else {
                                        // Show personnel counts for regular packages
                                        $personnel = [];
                                        if ($data['package']->number_of_officers > 0) {
                                            $personnel[] = $data['package']->number_of_officers . ' Security Officer' . ($data['package']->number_of_officers != 1 ? 's' : '');
                                        }
                                        if ($data['package']->number_of_supervisors > 0) {
                                            $personnel[] = $data['package']->number_of_supervisors . ' Supervisor' . ($data['package']->number_of_supervisors != 1 ? 's' : '');
                                        }
                                        if ($data['package']->number_of_caretakers > 0) {
                                            $personnel[] = $data['package']->number_of_caretakers . ' Caretaker' . ($data['package']->number_of_caretakers != 1 ? 's' : '');
                                        }
                                        echo !empty($personnel) ? implode(', ', $personnel) : 'Personnel Details';
                                    }
                                ?>
                            </div>
                            <div class="preview-package-description" id="previewDescription" style="<?php echo empty($data['package']->description) ? 'display: none;' : ''; ?>">
                                <?php echo htmlspecialchars($data['package']->description ?? ''); ?>
                            </div>
                            <div class="preview-package-price">
                                <span id="previewPrice"><?php echo $isCustomPackage ? 'Customizable' : 'LKR ' . number_format($data['package']->package_price, 0); ?></span>
                                <?php if (!$isCustomPackage): ?>
                                <span class="preview-price-period">/month</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="btn-group">
                <a href="<?php echo URL_ROOT; ?>/admin/viewPackages" class="btn-cancel">
                    <span class="material-symbols-outlined">close</span>
                    Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <span class="material-symbols-outlined">save</span>
                    Update Package
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            const container = document.getElementById('imageUploadContainer');
            const clearBtn = document.getElementById('clearImageBtn');
            
            preview.src = e.target.result;
            preview.classList.add('active');
            placeholder.style.display = 'none';
            container.classList.add('has-image');
            clearBtn.style.display = 'inline-block';
            
            // Update preview card background
            const style = document.createElement('style');
            style.id = 'preview-bg-style';
            style.textContent = `#previewCard::before { background: url(${e.target.result}) no-repeat center !important; background-size: cover !important; filter: grayscale(0%) brightness(0.8) !important; }`;
            
            // Remove old styles
            const oldStyle = document.getElementById('preview-bg-style');
            if (oldStyle) oldStyle.remove();
            const initialStyle = document.getElementById('initial-bg-style');
            if (initialStyle) initialStyle.remove();
            
            document.head.appendChild(style);
        };
        reader.readAsDataURL(file);
    }
}

function clearImage() {
    const fileInput = document.getElementById('package_image');
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('uploadPlaceholder');
    const container = document.getElementById('imageUploadContainer');
    const clearBtn = document.getElementById('clearImageBtn');
    
    fileInput.value = '';
    preview.src = '';
    preview.classList.remove('active');
    placeholder.style.display = 'flex';
    container.classList.remove('has-image');
    clearBtn.style.display = 'none';
    
    // Remove preview card background style change and restore initial if exists
    const oldStyle = document.getElementById('preview-bg-style');
    if (oldStyle) oldStyle.remove();
}

// Update preview in real-time
function updatePreview() {
    const name = document.getElementById('package_name').value;
    const isCustomPackage = (name.trim() === 'Custom Package');
    const officers = document.getElementById('number_of_officers') ? document.getElementById('number_of_officers').value : '';
    const supervisors = document.getElementById('number_of_supervisors') ? document.getElementById('number_of_supervisors').value : '';
    const caretakers = document.getElementById('number_of_caretakers') ? document.getElementById('number_of_caretakers').value : '';
    const priceInput = document.getElementById('package_price');
    const price = priceInput ? priceInput.value : '';
    const description = document.getElementById('description').value;
    
    const previewContent = document.getElementById('previewContent');
    
    // Update name
    document.getElementById('previewName').textContent = name || 'Package Name';
    
    // Build personnel/pricing details text
    let personnelText = [];
    if (isCustomPackage) {
        // Show per-unit pricing for Custom Package
        const pricePerOfficer = document.getElementById('price_per_officer') ? document.getElementById('price_per_officer').value : '';
        const pricePerSupervisor = document.getElementById('price_per_supervisor') ? document.getElementById('price_per_supervisor').value : '';
        const pricePerCaretaker = document.getElementById('price_per_caretaker') ? document.getElementById('price_per_caretaker').value : '';
        
        if (pricePerOfficer && parseFloat(pricePerOfficer) > 0) {
            personnelText.push('Officer: LKR ' + parseFloat(pricePerOfficer).toLocaleString());
        }
        if (pricePerSupervisor && parseFloat(pricePerSupervisor) > 0) {
            personnelText.push('Supervisor: LKR ' + parseFloat(pricePerSupervisor).toLocaleString());
        }
        if (pricePerCaretaker && parseFloat(pricePerCaretaker) > 0) {
            personnelText.push('Caretaker: LKR ' + parseFloat(pricePerCaretaker).toLocaleString());
        }
        
        document.getElementById('previewOfficers').textContent = personnelText.length > 0 ? personnelText.join(' • ') : 'Per-Unit Pricing';
    } else {
        // Show personnel counts for regular packages
        if (officers && parseInt(officers) > 0) {
            personnelText.push(officers + ' Security Officer' + (parseInt(officers) !== 1 ? 's' : ''));
        }
        if (supervisors && parseInt(supervisors) > 0) {
            personnelText.push(supervisors + ' Supervisor' + (parseInt(supervisors) !== 1 ? 's' : ''));
        }
        if (caretakers && parseInt(caretakers) > 0) {
            personnelText.push(caretakers + ' Caretaker' + (parseInt(caretakers) !== 1 ? 's' : ''));
        }
        
        document.getElementById('previewOfficers').textContent = personnelText.length > 0 ? personnelText.join(', ') : 'Personnel Details';
    }
    
    // Update description
    const descElement = document.getElementById('previewDescription');
    if (description && description.trim()) {
        descElement.textContent = description;
        descElement.style.display = 'block';
    } else {
        descElement.style.display = 'none';
    }
    
    // Update price
    if (isCustomPackage) {
        document.getElementById('previewPrice').textContent = 'Customizable';
    } else if (price && parseFloat(price) > 0) {
        document.getElementById('previewPrice').textContent = 'LKR ' + parseFloat(price).toLocaleString();
    } else {
        document.getElementById('previewPrice').textContent = 'LKR 0';
    }
}

// Add event listeners for real-time preview
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('package_name').addEventListener('input', updatePreview);
    
    const officersInput = document.getElementById('number_of_officers');
    const supervisorsInput = document.getElementById('number_of_supervisors');
    const caretakersInput = document.getElementById('number_of_caretakers');
    const priceInput = document.getElementById('package_price');
    
    // Quantity field listeners
    if (officersInput) officersInput.addEventListener('input', updatePreview);
    if (supervisorsInput) supervisorsInput.addEventListener('input', updatePreview);
    if (caretakersInput) caretakersInput.addEventListener('input', updatePreview);
    if (priceInput) priceInput.addEventListener('input', updatePreview);
    
    // Pricing field listeners
    const pricePerOfficer = document.getElementById('price_per_officer');
    const pricePerSupervisor = document.getElementById('price_per_supervisor');
    const pricePerCaretaker = document.getElementById('price_per_caretaker');
    
    if (pricePerOfficer) pricePerOfficer.addEventListener('input', updatePreview);
    if (pricePerSupervisor) pricePerSupervisor.addEventListener('input', updatePreview);
    if (pricePerCaretaker) pricePerCaretaker.addEventListener('input', updatePreview);
    
    document.getElementById('description').addEventListener('input', updatePreview);
});
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
