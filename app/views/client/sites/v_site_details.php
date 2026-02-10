<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<style>
    :root {
        --bg: #f0f2f5;
        --card: #fff;
        --muted: #606770;
        --accent: #a40000;
        --shadow: 0 6px 18px rgba(20,20,40,0.06);
        --radius: 12px;
    }

    .shell {
        padding: 24px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        color: #333;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 24px;
        transition: all 0.2s;
    }

    .back-btn:hover {
        background: #f5f5f5;
        transform: translateX(-4px);
    }

    .site-cover {
        width: 100%;
        height: 280px;
        background-size: cover;
        background-position: center;
        border-radius: var(--radius);
        margin-bottom: 24px;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .site-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .info-card {
        background: white;
        border-radius: var(--radius);
        padding: 28px;
        box-shadow: var(--shadow);
        margin-bottom: 24px;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .info-card h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f0f0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #ffe0e0 0%, #ffd0d0 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-icon .material-symbols-outlined {
        color: var(--accent);
        font-size: 22px;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 16px;
        color: #1a1a1a;
        font-weight: 600;
    }

    .staff-section {
        margin-top: 24px;
    }

    .staff-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .staff-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .staff-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .staff-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .staff-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .staff-header-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .staff-avatar {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #e0e0e0;
    }

    .staff-avatar-placeholder {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #90caf9;
    }

    .staff-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .staff-id {
        font-size: 13px;
        color: var(--muted);
    }

    .staff-details {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #f0f0f0;
    }

    .detail-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #666;
    }

    .detail-row .material-symbols-outlined {
        font-size: 18px;
        color: var(--accent);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 12px;
    }

    .badge-day {
        background: #e7f5e7;
        color: #2e7d32;
    }

    .badge-night {
        background: #e3f2fd;
        color: #1565c0;
    }

    .badge-fulltime {
        background: #f3e5f5;
        color: #6a1b9a;
    }

    .badge-caretaker {
        background: #fff3e0;
        color: #e65100;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fafafa;
        border-radius: var(--radius);
        border: 2px dashed #ddd;
    }

    .empty-icon {
        font-size: 64px;
        color: #ddd;
        margin-bottom: 16px;
    }
</style>

<div class="shell" role="main">
    <a href="<?php echo URL_ROOT; ?>/client/sites" class="back-btn">
        <span class="material-symbols-outlined">arrow_back</span>
        Back to Sites
    </a>

    <!-- Site Cover Image -->
    <div class="site-cover">
        <?php if(!empty($data['site']->image)): ?>
            <img src="<?php echo URL_ROOT; ?>/uploads/siteImages/<?php echo $data['site']->image; ?>" 
                 alt="<?php echo htmlspecialchars($data['site']->site_name); ?>" 
                 class="site-cover-img">
        <?php else: ?>
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%); display: flex; align-items: center; justify-content: center;">
                <span class="material-symbols-outlined" style="font-size: 80px; color: #ccc;">location_city</span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Site Information -->
    <div class="info-card">
        <h2><?php echo htmlspecialchars($data['site']->site_name); ?></h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-icon">
                    <span class="material-symbols-outlined">location_on</span>
                </div>
                <div class="info-content">
                    <div class="info-label">Address</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['site']->address); ?></div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    <span class="material-symbols-outlined">location_city</span>
                </div>
                <div class="info-content">
                    <div class="info-label">City & District</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($data['site']->city); ?>
                        <?php echo !empty($data['site']->district) ? ', ' . htmlspecialchars($data['site']->district) : ''; ?>
                    </div>
                </div>
            </div>

            <?php if(!empty($data['site']->phone_number)): ?>
            <div class="info-item">
                <div class="info-icon">
                    <span class="material-symbols-outlined">call</span>
                </div>
                <div class="info-content">
                    <div class="info-label">Contact Number</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['site']->phone_number); ?></div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!empty($data['site']->supervisor_name)): ?>
            <div class="info-item">
                <div class="info-icon">
                    <span class="material-symbols-outlined">supervisor_account</span>
                </div>
                <div class="info-content">
                    <div class="info-label">Supervisor</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['site']->supervisor_name); ?></div>
                    <?php if(!empty($data['site']->supervisor_phone)): ?>
                        <div style="font-size: 13px; color: #666; margin-top: 4px;">
                            <?php echo htmlspecialchars($data['site']->supervisor_phone); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="info-item">
                <div class="info-icon">
                    <span class="material-symbols-outlined">event</span>
                </div>
                <div class="info-content">
                    <div class="info-label">Created Date</div>
                    <div class="info-value"><?php echo date('M d, Y', strtotime($data['site']->created_at)); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Officers Section -->
    <div class="staff-section">
        <div class="staff-header">
            <h3 class="staff-title">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px;">badge</span>
                Assigned Officers (<?php echo count($data['assigned_officers']); ?>)
            </h3>
        </div>

        <?php if(empty($data['assigned_officers'])): ?>
            <div class="empty-state">
                <span class="material-symbols-outlined empty-icon">badge</span>
                <p style="color: var(--muted); font-size: 16px;">No officers assigned to this site</p>
            </div>
        <?php else: ?>
            <div class="staff-grid">
                <?php foreach($data['assigned_officers'] as $officer): ?>
                    <div class="staff-card">
                        <div class="staff-header-row">
                            <?php if(!empty($officer->profile_image)): ?>
                                <img src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo $officer->profile_image; ?>" 
                                     alt="<?php echo htmlspecialchars($officer->name); ?>" 
                                     class="staff-avatar">
                            <?php else: ?>
                                <div class="staff-avatar-placeholder">
                                    <span style="font-weight: 700; font-size: 20px; color: #1976d2;">
                                        <?php echo strtoupper(substr($officer->name, 0, 1)); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div style="flex: 1;">
                                <div class="staff-name"><?php echo htmlspecialchars($officer->name); ?></div>
                                <div class="staff-id"><?php echo htmlspecialchars($officer->officerID ?? 'N/A'); ?></div>
                            </div>
                        </div>

                        <div class="staff-details">
                            <?php if(!empty($officer->phone_number)): ?>
                            <div class="detail-row">
                                <span class="material-symbols-outlined">call</span>
                                <span><?php echo htmlspecialchars($officer->phone_number); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="detail-row">
                                <span class="material-symbols-outlined">event</span>
                                <span>Started: <?php echo date('M d, Y', strtotime($officer->assignment_start)); ?></span>
                            </div>
                            <?php if(!empty($officer->assignment_end)): ?>
                            <div class="detail-row">
                                <span class="material-symbols-outlined">event_busy</span>
                                <span>Ends: <?php echo date('M d, Y', strtotime($officer->assignment_end)); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php 
                            $shiftClass = match($officer->shift_type ?? 'Full Time') {
                                'Day' => 'badge-day',
                                'Night' => 'badge-night',
                                default => 'badge-fulltime'
                            };
                        ?>
                        <span class="badge <?php echo $shiftClass; ?>">
                            <?php echo htmlspecialchars($officer->shift_type ?? 'Full Time'); ?> Shift
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Assigned Supervisors Section -->
    <div class="staff-section">
        <div class="staff-header">
            <h3 class="staff-title">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px;">supervisor_account</span>
                Assigned Supervisors (<?php echo count($data['assigned_supervisors']); ?>)
            </h3>
        </div>

        <?php if(empty($data['assigned_supervisors'])): ?>
            <div class="empty-state">
                <span class="material-symbols-outlined empty-icon">supervisor_account</span>
                <p style="color: var(--muted); font-size: 16px;">No supervisors assigned to this site</p>
            </div>
        <?php else: ?>
            <div class="staff-grid">
                <?php foreach($data['assigned_supervisors'] as $supervisor): ?>
                    <div class="staff-card">
                        <div class="staff-header-row">
                            <?php if(!empty($supervisor->profile_image)): ?>
                                <img src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo $supervisor->profile_image; ?>" 
                                     alt="<?php echo htmlspecialchars($supervisor->name); ?>" 
                                     class="staff-avatar">
                            <?php else: ?>
                                <div class="staff-avatar-placeholder" style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); border-color: #ff9800;">
                                    <span style="font-weight: 700; font-size: 20px; color: #e65100;">
                                        <?php echo strtoupper(substr($supervisor->name, 0, 1)); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div style="flex: 1;">
                                <div class="staff-name"><?php echo htmlspecialchars($supervisor->name); ?></div>
                                <div class="staff-id"><?php echo htmlspecialchars($supervisor->officerID ?? 'N/A'); ?></div>
                            </div>
                        </div>

                        <div class="staff-details">
                            <?php if(!empty($supervisor->phone_number)): ?>
                            <div class="detail-row">
                                <span class="material-symbols-outlined">call</span>
                                <span><?php echo htmlspecialchars($supervisor->phone_number); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($supervisor->email)): ?>
                            <div class="detail-row">
                                <span class="material-symbols-outlined">email</span>
                                <span><?php echo htmlspecialchars($supervisor->email); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="detail-row">
                                <span class="material-symbols-outlined">event</span>
                                <span>Started: <?php echo date('M d, Y', strtotime($supervisor->assignment_start)); ?></span>
                            </div>
                            <?php if(!empty($supervisor->assignment_end)): ?>
                            <div class="detail-row">
                                <span class="material-symbols-outlined">event_busy</span>
                                <span>Ends: <?php echo date('M d, Y', strtotime($supervisor->assignment_end)); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <span class="badge" style="background: #fff3e0; color: #e65100;">Supervisor</span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Assigned Caretakers Section -->
    <div class="staff-section">
        <div class="staff-header">
            <h3 class="staff-title">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px;">person_check</span>
                Assigned Caretakers (<?php echo count($data['assigned_caretakers']); ?>)
            </h3>
        </div>

        <?php if(empty($data['assigned_caretakers'])): ?>
            <div class="empty-state">
                <span class="material-symbols-outlined empty-icon">person_check</span>
                <p style="color: var(--muted); font-size: 16px;">No caretakers assigned to this site</p>
            </div>
        <?php else: ?>
            <div class="staff-grid">
                <?php foreach($data['assigned_caretakers'] as $caretaker): ?>
                    <div class="staff-card">
                        <div class="staff-header-row">
                            <?php if(!empty($caretaker->profile_image)): ?>
                                <img src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo $caretaker->profile_image; ?>" 
                                     alt="<?php echo htmlspecialchars($caretaker->name); ?>" 
                                     class="staff-avatar">
                            <?php else: ?>
                                <div class="staff-avatar-placeholder" style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); border-color: #ffb74d;">
                                    <span style="font-weight: 700; font-size: 20px; color: #e65100;">
                                        <?php echo strtoupper(substr($caretaker->name, 0, 1)); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div style="flex: 1;">
                                <div class="staff-name"><?php echo htmlspecialchars($caretaker->name); ?></div>
                                <div class="staff-id"><?php echo htmlspecialchars($caretaker->caretakerID ?? 'N/A'); ?></div>
                            </div>
                        </div>

                        <div class="staff-details">
                            <?php if(!empty($caretaker->phone_number)): ?>
                            <div class="detail-row">
                                <span class="material-symbols-outlined">call</span>
                                <span><?php echo htmlspecialchars($caretaker->phone_number); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="detail-row">
                                <span class="material-symbols-outlined">event</span>
                                <span>Started: <?php echo date('M d, Y', strtotime($caretaker->assignment_start)); ?></span>
                            </div>
                            <?php if(!empty($caretaker->assignment_end)): ?>
                            <div class="detail-row">
                                <span class="material-symbols-outlined">event_busy</span>
                                <span>Ends: <?php echo date('M d, Y', strtotime($caretaker->assignment_end)); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <span class="badge badge-caretaker">Caretaker</span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
