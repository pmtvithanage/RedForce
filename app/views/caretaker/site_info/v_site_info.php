<?php require_once APP_ROOT . '/views/components/v_caretaker_sidebar.php'; ?>

<style>
    .site-info-container {
        padding: 24px;
        margin: 20px;
    }

    .site-header {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
        box-shadow: 0 8px 24px rgba(20,20,40,0.08);
        border: 1px solid rgba(164, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
    }

    .site-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--accent) 0%, #ff6b6b 100%);
    }

    .site-header-content {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 24px;
        align-items: center;
    }

    .client-logo {
        width: 120px;
        height: 120px;
        border-radius: 16px;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }

    .site-title-section h1 {
        font-size: 28px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }

    .site-title-section p {
        color: #666;
        font-size: 15px;
        margin: 4px 0;
    }

    .assignment-badge {
        padding: 12px 24px;
        background: linear-gradient(135deg, #e7f7ef 0%, #d4f1e0 100%);
        color: #0a8f4e;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(10, 143, 78, 0.2);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
        margin-bottom: 24px;
    }

    .info-card {
        background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 6px 20px rgba(20,20,40,0.08);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(20,20,40,0.15);
        border-color: rgba(164, 0, 0, 0.1);
    }

    .info-card h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 14px;
        border-bottom: 2px solid #f0f0f0;
    }

    .info-card h3 .material-symbols-outlined {
        color: var(--accent);
        font-size: 24px;
    }

    .info-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
        transition: all 0.2s ease;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row:hover {
        padding-left: 8px;
        background: rgba(164, 0, 0, 0.03);
        border-radius: 8px;
    }

    .info-label {
        font-weight: 700;
        color: var(--accent);
        min-width: 140px;
        font-size: 14px;
    }

    .info-value {
        color: #4a5568;
        font-size: 14px;
        flex: 1;
    }

    .site-image-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 8px 24px rgba(20,20,40,0.08);
        border: 1px solid rgba(164, 0, 0, 0.08);
    }

    .site-image-section h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .site-main-image {
        width: 100%;
        height: 400px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        transition: transform 0.3s ease;
    }

    .site-main-image:hover {
        transform: scale(1.02);
    }

    .no-image {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 18px;
        flex-direction: column;
        gap: 12px;
    }

    .supervisors-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: 0 8px 24px rgba(20,20,40,0.08);
        border: 1px solid rgba(164, 0, 0, 0.08);
    }

    .supervisors-section h3 {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 14px;
        border-bottom: 2px solid #f0f0f0;
    }

    .supervisors-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .supervisor-card {
        background: linear-gradient(135deg, #f8f4ff 0%, #f0e7ff 100%);
        border: 2px solid #e1bee7;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(156, 39, 176, 0.1);
    }

    .supervisor-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(156, 39, 176, 0.2);
        border-color: #9c27b0;
    }

    .supervisor-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 14px;
    }

    .supervisor-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #9c27b0;
        box-shadow: 0 4px 12px rgba(156, 39, 176, 0.2);
    }

    .supervisor-avatar-placeholder {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #9c27b0 0%, #ba68c8 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 22px;
        border: 3px solid #9c27b0;
        box-shadow: 0 4px 12px rgba(156, 39, 176, 0.2);
    }

    .supervisor-info h4 {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .supervisor-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: linear-gradient(135deg, #9c27b0 0%, #ba68c8 100%);
        color: white;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .supervisor-details {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid rgba(156, 39, 176, 0.2);
    }

    .supervisor-detail-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #4a5568;
    }

    .supervisor-detail-row .material-symbols-outlined {
        font-size: 18px;
        color: #9c27b0;
    }

    .no-supervisors {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .no-supervisors .material-symbols-outlined {
        font-size: 64px;
        color: #ddd;
        margin-bottom: 12px;
    }

    @media (max-width: 768px) {
        .site-header-content {
            grid-template-columns: 1fr;
            text-align: center;
            justify-items: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-row {
            flex-direction: column;
            gap: 4px;
        }

        .info-label {
            min-width: auto;
        }
    }
</style>

<div class="site-info-container">
    <?php if (empty($data['site'])): ?>
        <!-- No Site Assigned Message -->
        <div class="no-site-message" style="
            background: linear-gradient(135deg, #fff5e6 0%, #ffe8cc 100%);
            border-radius: 16px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 8px 24px rgba(255, 152, 0, 0.15);
            border: 2px solid #ff9800;
            margin: 40px auto;
            max-width: 600px;
        ">
            <span class="material-symbols-outlined" style="font-size: 120px; color: #ff9800; opacity: 0.8; margin-bottom: 20px; display: block;">
                domain_disabled
            </span>
            <h2 style="font-size: 28px; font-weight: 800; color: #1a1a1a; margin-bottom: 12px;">
                No Site Assigned
            </h2>
            <p style="font-size: 16px; color: #666; margin-bottom: 24px; line-height: 1.6;">
                You currently do not have an active site assignment.<br>
                Please contact your administrator for site assignment.
            </p>
            <a href="<?php echo URL_ROOT; ?>/caretaker/dashboard" 
               style="
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: #ff9800;
                color: white;
                padding: 14px 28px;
                border-radius: 10px;
                text-decoration: none;
                font-weight: 700;
                box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
                transition: all 0.3s ease;
            " onmouseover="this.style.background='#f57c00'; this.style.transform='translateY(-2px)';" 
               onmouseout="this.style.background='#ff9800'; this.style.transform='translateY(0)';">
                <span class="material-symbols-outlined">arrow_back</span>
                Back to Dashboard
            </a>
        </div>
    <?php else: ?>
    <!-- Site Header -->
    <div class="site-header">
        <div class="site-header-content">
            <div>
                <?php if (!empty($data['site']->client_logo)): ?>
                    <img src="<?php echo URL_ROOT; ?>/uploads/clientLogos/<?php echo $data['site']->client_logo; ?>" 
                         alt="<?php echo htmlspecialchars($data['site']->client_name); ?>" 
                         class="client-logo">
                <?php else: ?>
                    <div class="client-logo" style="background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%); display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="font-size: 48px; color: #999;">business</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="site-title-section">
                <h1><?php echo htmlspecialchars($data['site']->site_name); ?></h1>
                <p><strong>Client:</strong> <?php echo htmlspecialchars($data['site']->client_name); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($data['site']->address); ?></p>
            </div>

            <div class="assignment-badge">
                <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px;">check_circle</span>
                Active Assignment
            </div>
        </div>
    </div>

    <!-- Information Grid -->
    <div class="info-grid">
        <!-- Site Details -->
        <div class="info-card">
            <h3>
                <span class="material-symbols-outlined">location_on</span>
                Site Details
            </h3>
            <div class="info-row">
                <span class="info-label">Site Name:</span>
                <span class="info-value"><?php echo htmlspecialchars($data['site']->site_name); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value"><?php echo htmlspecialchars($data['site']->address); ?></span>
            </div>
            <?php if (!empty($data['site']->district)): ?>
            <div class="info-row">
                <span class="info-label">District:</span>
                <span class="info-value"><?php echo htmlspecialchars($data['site']->district); ?></span>
            </div>
            <?php endif; ?>
            <div class="info-row">
                <span class="info-label">City:</span>
                <span class="info-value"><?php echo htmlspecialchars($data['site']->city); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value"><?php echo htmlspecialchars($data['site']->phone_number); ?></span>
            </div>
        </div>

        <!-- Client Information -->
        <div class="info-card">
            <h3>
                <span class="material-symbols-outlined">business</span>
                Client Information
            </h3>
            <div class="info-row">
                <span class="info-label">Client Name:</span>
                <span class="info-value"><?php echo htmlspecialchars($data['site']->client_name); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo htmlspecialchars($data['site']->client_email); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value"><?php echo htmlspecialchars($data['site']->client_phone); ?></span>
            </div>
        </div>

        <!-- Assignment Details -->
        <div class="info-card">
            <h3>
                <span class="material-symbols-outlined">assignment</span>
                Assignment Details
            </h3>
            <div class="info-row">
                <span class="info-label">Start Date:</span>
                <span class="info-value"><?php echo date('F d, Y', strtotime($data['site']->assignment_start)); ?></span>
            </div>
            <?php if (!empty($data['site']->assignment_end)): ?>
            <div class="info-row">
                <span class="info-label">End Date:</span>
                <span class="info-value"><?php echo date('F d, Y', strtotime($data['site']->assignment_end)); ?></span>
            </div>
            <?php endif; ?>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value">
                    <span style="color: #0a8f4e; font-weight: 700;">
                        <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px;">check_circle</span>
                        <?php echo htmlspecialchars($data['site']->assignment_status); ?>
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Duration:</span>
                <span class="info-value">
                    <?php 
                    $start = new DateTime($data['site']->assignment_start);
                    $end = !empty($data['site']->assignment_end) ? new DateTime($data['site']->assignment_end) : new DateTime();
                    $diff = $start->diff($end);
                    echo $diff->days . ' days';
                    ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Assigned Supervisors Section -->
    <div class="supervisors-section">
        <h3>
            <span class="material-symbols-outlined">supervisor_account</span>
            Assigned Supervisors
        </h3>
        <?php if (!empty($data['supervisors'])): ?>
            <div class="supervisors-grid">
                <?php foreach ($data['supervisors'] as $supervisor): ?>
                    <div class="supervisor-card">
                        <div class="supervisor-header">
                            <?php if (!empty($supervisor->profile_image)): ?>
                                <img src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo $supervisor->profile_image; ?>" 
                                     alt="<?php echo htmlspecialchars($supervisor->name); ?>" 
                                     class="supervisor-avatar">
                            <?php else: ?>
                                <div class="supervisor-avatar-placeholder">
                                    <?php echo strtoupper(substr($supervisor->name, 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <div class="supervisor-info">
                                <h4><?php echo htmlspecialchars($supervisor->name); ?></h4>
                                <span class="supervisor-badge">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">badge</span>
                                    Supervisor
                                </span>
                            </div>
                        </div>
                        <div class="supervisor-details">
                            <?php if (!empty($supervisor->email)): ?>
                            <div class="supervisor-detail-row">
                                <span class="material-symbols-outlined">email</span>
                                <span><?php echo htmlspecialchars($supervisor->email); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($supervisor->phone_number)): ?>
                            <div class="supervisor-detail-row">
                                <span class="material-symbols-outlined">phone</span>
                                <span><?php echo htmlspecialchars($supervisor->phone_number); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="supervisor-detail-row">
                                <span class="material-symbols-outlined">calendar_today</span>
                                <span>Since <?php echo date('M d, Y', strtotime($supervisor->assignment_start)); ?></span>
                            </div>
                            <div class="supervisor-detail-row">
                                <span class="material-symbols-outlined">check_circle</span>
                                <span style="color: #0a8f4e; font-weight: 600;"><?php echo htmlspecialchars($supervisor->status); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-supervisors">
                <span class="material-symbols-outlined">person_off</span>
                <p style="font-size: 16px; font-weight: 600; margin-top: 8px;">No supervisors assigned to this site</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Site Image -->
    <div class="site-image-section">
        <h3>
            <span class="material-symbols-outlined">photo_camera</span>
            Site Image
        </h3>
        <?php if (!empty($data['site']->image)): ?>
            <img src="<?php echo URL_ROOT; ?>/uploads/siteImages/<?php echo $data['site']->image; ?>" 
                 alt="<?php echo htmlspecialchars($data['site']->site_name); ?>" 
                 class="site-main-image">
        <?php else: ?>
            <div class="no-image">
                <span class="material-symbols-outlined" style="font-size: 64px; color: #ccc;">image</span>
                <span>No site image available</span>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>