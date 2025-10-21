<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>
<link rel="stylesheet" href="<?= URL_ROOT ?>/css/caretaker/dashboard_style.css">
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/components/advertisement_view.css">
<!-- Google Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Content will be loaded here -->


            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Top Section - User Profile and Instructions -->
                <section class="top-section">
                    <div class="instructions-section">
                        <h2>Instructions from Admin</h2>
                        <div class="instructions-content">
                            <p>Good morning!</p>
                            <p>Please ensure that all security officers at Site A have submitted their attendance by 9:00 AM.</p>
                            <p>Also, don't forget to update the incident report if there were any issues during the night shift.</p>
                            <p>Let me know once it's done.</p>
                            <p>Thank you.</p>
                        </div>
                    </div>

                    <!-- Right-side stat cards -->
                    <div class="stats-column">
                        <div class="stat-card purple">
                            <div class="stat-icon"><span class="material-icons">groups</span></div>
                            <div class="stat-text">
                                <div class="stat-value">0</div>
                                <div class="stat-label">Total Officers</div>
                            </div>
                        </div>
                        <div class="stat-card green">
                            <div class="stat-icon"><span class="material-icons">person</span></div>
                            <div class="stat-text">
                                <div class="stat-value">0</div>
                                <div class="stat-label">On Duty</div>
                            </div>
                        </div>
                        <div class="stat-card yellow">
                            <div class="stat-icon"><span class="material-icons">verified_user</span></div>
                            <div class="stat-text">
                                <div class="stat-value">0</div>
                                <div class="stat-label">Active</div>
                            </div>
                        </div>
                        <div class="stat-card red">
                            <div class="stat-icon"><span class="material-icons">report_problem</span></div>
                            <div class="stat-text">
                                <div class="stat-value">0</div>
                                <div class="stat-label">Incidents</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Bottom Section - Upload and Advertisements -->
                <section class="bottom-section">
                    <div class="upload-section">
                        <h2>Upoload Evidence/Photo</h2>
                        <div class="upload-content">
                            <textarea placeholder="Description" class="description-area"></textarea>
                            <div class="file-previews">
                                <div class="file-preview">
                                    <span class="file-name">2023.8.2.1.jpg</span>
                                    <button class="remove-file" onclick="removeFile(this)">×</button>
                                </div>
                                <div class="file-preview">
                                    <span class="file-name">2023.8.2.2.jpg</span>
                                    <button class="remove-file" onclick="removeFile(this)">×</button>
                                </div>
                                <div class="file-preview">
                                    <span class="file-name">2023.8.2.3.jpg</span>
                                    <button class="remove-file" onclick="removeFile(this)">×</button>
                                </div>
                                <div class="file-preview">
                                    <span class="file-name">2023.8.2.4.jpg</span>
                                    <button class="remove-file" onclick="removeFile(this)">×</button>
                                </div>
                            </div>
                            <div class="upload-actions">
                                <button class="upload-btn">Upload Photos</button>
                                <button class="submit-btn" style="display: none;">Submit Report</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="advertisements-section">
                         <!-- ==========================
     Advertisements Section (RIGHT SIDE)
     ========================== -->
<div class="section">
    <div class="section-header">
        <h3 class="section-title">Advertisements</h3>
        <div class="section-actions">
            <button 
                class="refresh-btn" 
                type="button" 
                onclick="refreshAdvertisements()" 
                title="Refresh advertisements" 
                aria-label="Refresh advertisements"
            >
                <span class="material-icons" aria-hidden="true">refresh</span>
            </button>
        </div>
    </div>

    <div class="section-content">
        <?php if (!empty($data['advertisements'])): ?>
            <div class="advertisements-container">
                <?php foreach ($data['advertisements'] as $ad): ?>
                    <?php 
                        // Safely handle nulls to avoid PHP 8.2 warnings
                        $adTitle = htmlspecialchars($ad->title ?? 'Untitled Advertisement', ENT_QUOTES, 'UTF-8');
                        $adCreator = htmlspecialchars($ad->creator_name ?? '', ENT_QUOTES, 'UTF-8');
                        $adImage = !empty($ad->image_path) ? URL_ROOT . '/' . htmlspecialchars($ad->image_path, ENT_QUOTES, 'UTF-8') : '';
                        $adRoles = !empty($ad->target_roles) ? array_map('trim', explode(',', $ad->target_roles)) : [];
                        $adContent = htmlspecialchars($ad->content ?? '', ENT_QUOTES, 'UTF-8');
                    ?>

                    <!-- Visible Advertisement Item -->
                    <button 
                        type="button"
                        class="advertisement-item"
                        data-ad-id="<?php echo (int) $ad->id; ?>"
                        onclick="viewAdvertisement(<?php echo (int) $ad->id; ?>)"
                        aria-label="View advertisement: <?php echo $adTitle; ?>"
                    >
                        <?php if (!empty($adImage)): ?>
                            <div class="ad-image">
                                <img 
                                    src="<?php echo $adImage; ?>" 
                                    alt="<?php echo $adTitle; ?>"
                                    onerror="this.closest('.ad-image').style.display='none'"
                                >
                            </div>
                        <?php endif; ?>

                        <div class="ad-content">
                            <h4 class="ad-title"><?php echo $adTitle; ?></h4>

                            <div class="ad-meta">
                                <span class="ad-date">
                                    <span class="material-icons" aria-hidden="true">event</span>
                                    <?php echo date('M j, Y', strtotime($ad->created_at)); ?>
                                </span>

                                <?php if (!empty($adCreator)): ?>
                                    <span class="ad-creator">
                                        <span class="material-icons" aria-hidden="true">person</span>
                                        By <?php echo $adCreator; ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($adRoles) && $ad->target_roles !== 'all'): ?>
                                <div class="ad-target">
                                    <span class="material-icons" aria-hidden="true">groups</span>
                                    <?php echo htmlspecialchars(implode(', ', $adRoles), ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="ad-actions" aria-hidden="true">
                            <span class="material-icons">chevron_right</span>
                        </div>
                    </button>

                    <!-- Hidden full ad content for modal -->
                    <div id="full-ad-<?php echo (int) $ad->id; ?>" class="hidden-full-ad" hidden>
                        <?php if (!empty($adImage)): ?>
                            <img 
                                src="<?php echo $adImage; ?>" 
                                class="ad-full-image"
                                alt="<?php echo $adTitle; ?>"
                                onerror="this.remove()"
                            >
                        <?php endif; ?>

                        <div class="ad-full-content">
                            <?php echo nl2br($adContent); ?>
                        </div>

                        <div class="ad-modal-meta">
                            <span>
                                <span class="material-icons" aria-hidden="true">event</span>
                                <?php echo date('F j, Y \a\t g:i A', strtotime($ad->created_at)); ?>
                            </span>

                            <?php if (!empty($adCreator)): ?>
                                <span>
                                    <span class="material-icons" aria-hidden="true">person</span>
                                    By <?php echo $adCreator; ?>
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($adRoles) && $ad->target_roles !== 'all'): ?>
                                <span>
                                    <span class="material-icons" aria-hidden="true">groups</span>
                                    For: <?php echo htmlspecialchars(implode(', ', $adRoles), ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="advertisement-empty" role="alert">
                <div class="empty-ad-message">
                    <span class="material-icons" aria-hidden="true">campaign</span>
                    <p>No advertisements available</p>
                    <small>Check back later for updates</small>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ==========================
     Advertisement Modal
     ========================== -->
<div 
    id="adModal" 
    class="modal ad-modal" 
    role="dialog" 
    aria-modal="true" 
    aria-labelledby="adModalTitle"
    aria-hidden="true"
>
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="ad-modal-header">
            <h3 id="adModalTitle">Advertisement</h3>
            <button 
                type="button" 
                class="close" 
                onclick="closeModal('adModal')" 
                aria-label="Close advertisement modal"
            >
                &times;
            </button>
        </div>

        <!-- Modal Body -->
        <div class="ad-modal-body" id="adModalBody">
            <!-- 
                Content will be dynamically loaded here by JS.
                Structure inside should be:
                <img class="ad-full-image" ... >
                <div class="ad-modal-meta">
                    <span>Date info</span>
                    <span>Creator info</span>
                    <span>Target roles</span>
                </div>
                <div class="ad-full-content">Ad text content</div>
            -->
        </div>
    </div>
</div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/advertisements_view.js"></script>
    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
    <script src="<?= URL_ROOT ?>/js/caretaker/dashboard.js"></script>
