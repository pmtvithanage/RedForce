<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>


<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">


<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/supervisor/dashboard.style.css">
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/components/advertisement_view.css">

    <!-- Statistics Cards -->
    <section class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon">
                <span class="material-symbols-outlined">group</span>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['attendanceStats']['total']; ?></div>
                <div class="stat-label">Total Officers</div>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon">
                <span class="material-symbols-outlined">shield</span>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['attendanceStats']['present']; ?></div>
                <div class="stat-label">On Duty</div>
            </div>
        </div>

        <div class="stat-card yellow">
            <div class="stat-icon">
                <span class="material-symbols-outlined">verified</span>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo $data['attendanceStats']['total'] - $data['attendanceStats']['absent']; ?></div>
                <div class="stat-label">Active</div>
            </div>
        </div>

        <div class="stat-card red">
            <div class="stat-icon">
                <span class="material-symbols-outlined">error</span>
            </div>
            <div class="stat-info">
                <div class="stat-value">0</div>
                <div class="stat-label">Incidents</div>
            </div>
        </div>
    </section>

    <!-- Dashboard Content -->
    <section class="content-grid">
        <!-- Attendance Card -->
        <div class="attendance-card">
            <div class="search-bar">
                <span class="material-symbols-outlined">search</span>
                <input id="searchInput" type="text" placeholder="Search">
            </div>
            <div class="table-wrapper">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceBody">
                        <?php if (!empty($data['todayAttendance'])): ?>
                            <?php foreach ($data['todayAttendance'] as $attendance): ?>
                                <tr>
                                    <td>
                                        <a href="#" class="name-link">
                                            <?php echo htmlspecialchars($attendance->officer_name); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php 
                                            $statusClass = '';
                                            switch(strtolower($attendance->status)) {
                                                case 'present':
                                                    $statusClass = 'present';
                                                    break;
                                                case 'absent':
                                                    $statusClass = 'absent';
                                                    break;
                                                case 'late':
                                                    $statusClass = 'late';
                                                    break;
                                                case 'half day':
                                                    $statusClass = 'half-day';
                                                    break;
                                                default:
                                                    $statusClass = 'present';
                                            }
                                        ?>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <?php echo htmlspecialchars($attendance->status); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" style="text-align: center; color: #999; padding: 2rem;">
                                    <span class="material-symbols-outlined" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5; display: block;">assignment</span>
                                    <p style="margin: 0;">No attendance marked for today</p>
                                    <small style="font-size: 0.85rem;">Go to Attendance page to mark attendance</small>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Advertisements Card -->
        <div class="advertisements-card">
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

        </main>
    </div>

    
     

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
    <script src="<?php echo URL_ROOT; ?>/js/components/advertisements_view.js"></script>
    <script src="<?php echo URL_ROOT; ?>/js/supervisor/dashboard.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
