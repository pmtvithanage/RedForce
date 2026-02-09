<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_premiseofficer_sidebar.php'; ?>


<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">


<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Dashboard CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/premiseOfficer/dashboard_style.css">
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/components/advertisement_view.css">

<!-- Dashboard Content -->
<div class="main-content">
    <!-- Stats Cards Section -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">
                <span class="material-icons">location_on</span>
            </div>
            <div class="stat-info">
                <h3>1</h3>
                <p>Site</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <span class="material-icons">event_busy</span>
            </div>
            <div class="stat-info">
                <h3>3</h3>
                <p>No of Leaves</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <span class="material-icons">schedule</span>
            </div>
            <div class="stat-info">
                <h3>24</h3>
                <p>Overtime Hours</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <span class="material-icons">work</span>
            </div>
            <div class="stat-info">
                <h3>48</h3>
                <p>Total Shifts</p>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="content-grid">
        <!-- Messages Section - LEFT SIDE -->
        <div class="section">
            <div class="section-header">
                <h3 class="section-title">Messages</h3>
            </div>
            <div class="section-content">
                <div class="search-container">
                    <input type="text" placeholder="Search" class="search-input">
                </div>
                
                <div class="message-item" onclick="openChatModal(1)">
                    <div class="message-avatar">A</div>
                    <div class="message-content">
                        <div class="message-header">
                            <div class="message-sender">Admin - Red Force</div>
                            <div class="message-time">14:32</div>
                        </div>
                        <div class="message-text">Dear Officer, kindly note that we are assigning you to the night shift tonight...</div>
                    </div>
                </div>

                <div class="message-item" onclick="openChatModal(2)">
                    <div class="message-avatar">J</div>
                    <div class="message-content">
                        <div class="message-header">
                            <div class="message-sender">John Silva</div>
                            <div class="message-time">12:32</div>
                        </div>
                        <div class="message-text">Your shift schedule has been updated...</div>
                        <div class="message-subtitle">Supervisor</div>
                    </div>
                </div>

                <div class="message-item" onclick="openChatModal(3)">
                    <div class="message-avatar">N</div>
                    <div class="message-content">
                        <div class="message-header">
                            <div class="message-sender">Naduni Senanayake</div>
                            <div class="message-time">01:42</div>
                        </div>
                        <div class="message-text">Please submit your monthly report...</div>
                        <div class="message-subtitle">HR Officer</div>
                    </div>
                </div>

                <div class="message-item" onclick="openChatModal(4)">
                    <div class="message-avatar">M</div>
                    <div class="message-content">
                        <div class="message-header">
                            <div class="message-sender">Manager</div>
                            <div class="message-time">01:22</div>
                        </div>
                        <div class="message-text">Team meeting scheduled for tomorrow...</div>
                        <div class="message-subtitle">Site Manager</div>
                    </div>
                </div>
            </div>
        </div>

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

<!-- Chat Modal -->
<div id="chatModal" class="modal chat-modal">
    <div class="modal-content chat-modal-content">
        <div class="chat-header">
            <div class="chat-info">
                <h3 id="chatTitle">Messages</h3>
                <p id="chatParticipant">Admin - Red Force</p>
            </div>
            <span class="close" onclick="closeModal('chatModal')">&times;</span>
        </div>
        
        <div class="chat-body">
            <div id="chatContent" class="chat-messages">
                <!-- Messages will be loaded here dynamically -->
            </div>
        </div>
        
        <div class="chat-footer">
            <div class="message-input-container">
                <input type="text" id="messageInput" placeholder="Type a message" onkeypress="handleMessageKeyPress(event)">
                <button onclick="sendMessage()" class="send-btn">
                    <span class="material-icons">send</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/premiseofficer/dashboard.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/components/advertisements_view.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>