<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<style>
    :root {
        --bg: #f0f2f5;
        --card: #fff;
        --muted: #606770;
        --accent: #a40000;
        --shadow: 0 6px 18px rgba(20, 20, 40, 0.06);
        --radius: 12px;
    }

    .shell {
        width: 90%;
        margin: 24px auto;
        padding: 0;
    }

    .sites-header {
        margin-bottom: 24px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        flex-shrink: 0;
    }

    .stat-icon.primary {
        background: #ffebee;
        color: #d32f2f;
    }

    .stat-icon.success {
        background: #e8f5e9;
        color: #388e3c;
    }

    .stat-icon.info {
        background: #e3f2fd;
        color: #1976d2;
    }

    .stat-value {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }

    .stat-label {
        margin: 5px 0 0 0;
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .sites-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 24px;
    }

    .site-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #ececec;
        display: flex;
        flex-direction: column;
    }

    .site-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .site-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
    }

    .site-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .site-name {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 12px;
    }

    .site-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: auto;
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #666;
    }

    .info-row .material-symbols-outlined {
        font-size: 18px;
        color: var(--accent);
    }

    .site-stats {
        display: flex;
        gap: 16px;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #ececec;
        margin-bottom: 16px;
        margin-top: auto;
    }

    .stat-item {
        flex: 1;
        text-align: center;
    }

    .stat-item-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--accent);
    }

    .stat-item-label {
        font-size: 12px;
        color: var(--muted);
        margin-top: 4px;
    }

    .site-actions {
        display: flex;
        gap: 8px;
        margin-bottom: 0;
    }

    .btn {
        flex: 1;
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--accent);
        color: white;
    }

    .btn-primary:hover {
        background: #8b0000;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #333;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }

    .empty-icon {
        font-size: 80px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .empty-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 12px;
    }

    .empty-text {
        font-size: 16px;
        color: var(--muted);
        margin-bottom: 24px;
    }
</style>

<div class="shell" role="main">
    <div class="sites-header">
        <h1 style="font-size: 28px; font-weight: 700; color: #1a1a1a; margin-bottom: 8px;">My Sites</h1>
        <p style="color: var(--muted); font-size: 16px;">Manage and monitor your security sites</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <span class="material-symbols-outlined">location_city</span>
            </div>
            <div>
                <div class="stat-value"><?php echo count($data['sites']); ?></div>
                <div class="stat-label">Total Sites</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <span class="material-symbols-outlined">badge</span>
            </div>
            <div>
                <div class="stat-value">
                    <?php
                    $totalOfficers = 0;
                    foreach ($data['sites'] as $site) {
                        $totalOfficers += $site->assigned_officers;
                    }
                    echo $totalOfficers;
                    ?>
                </div>
                <div class="stat-label">Assigned Officers</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <span class="material-symbols-outlined">supervisor_account</span>
            </div>
            <div>
                <div class="stat-value">
                    <?php
                    $totalSupervisors = 0;
                    foreach ($data['sites'] as $site) {
                        $totalSupervisors += $site->assigned_supervisors;
                    }
                    echo $totalSupervisors;
                    ?>
                </div>
                <div class="stat-label">Assigned Supervisors</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <span class="material-symbols-outlined">person_check</span>
            </div>
            <div>
                <div class="stat-value">
                    <?php
                    $totalCaretakers = 0;
                    foreach ($data['sites'] as $site) {
                        $totalCaretakers += $site->assigned_caretakers;
                    }
                    echo $totalCaretakers;
                    ?>
                </div>
                <div class="stat-label">Assigned Caretakers</div>
            </div>
        </div>
    </div>

    <?php if (empty($data['sites'])): ?>
        <!-- Empty State -->
        <div class="empty-state">
            <span class="material-symbols-outlined empty-icon">location_city</span>
            <h2 class="empty-title">No Sites Yet</h2>
            <p class="empty-text">You don't have any active security sites.<br>Submit a package request to get started.</p>
            <a href="<?php echo URL_ROOT; ?>/client/requests" class="btn btn-primary" style="display: inline-flex; width: auto;">
                <span class="material-symbols-outlined">add</span>
                Request Security Service
            </a>
        </div>
    <?php else: ?>
        <!-- Sites Grid -->
        <div class="sites-grid">
            <?php foreach ($data['sites'] as $site): ?>
                <div class="site-card">
                    <?php if (!empty($site->image)): ?>
                        <img src="<?php echo URL_ROOT; ?>/uploads/siteImages/<?php echo $site->image; ?>"
                            alt="<?php echo htmlspecialchars($site->site_name); ?>"
                            class="site-image">
                    <?php else: ?>
                        <div class="site-image" style="display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 64px; color: #ccc;">location_city</span>
                        </div>
                    <?php endif; ?>

                    <div class="site-content">
                        <h3 class="site-name"><?php echo htmlspecialchars($site->site_name); ?></h3>

                        <div class="site-info">
                            <div class="info-row">
                                <span class="material-symbols-outlined">location_on</span>
                                <span><?php echo htmlspecialchars($site->address); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="material-symbols-outlined">location_city</span>
                                <span><?php echo htmlspecialchars($site->city); ?><?php echo !empty($site->district) ? ', ' . htmlspecialchars($site->district) : ''; ?></span>
                            </div>
                            <?php if (!empty($site->phone_number)): ?>
                                <div class="info-row">
                                    <span class="material-symbols-outlined">call</span>
                                    <span><?php echo htmlspecialchars($site->phone_number); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($site->supervisor_name)): ?>
                                <div class="info-row">
                                    <span class="material-symbols-outlined">supervisor_account</span>
                                    <span>Supervisor: <?php echo htmlspecialchars($site->supervisor_name); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="site-stats">
                            <div class="stat-item">
                                <div class="stat-item-value"><?php echo $site->assigned_officers; ?></div>
                                <div class="stat-item-label">Officers</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-item-value"><?php echo $site->assigned_supervisors; ?></div>
                                <div class="stat-item-label">Supervisors</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-item-value"><?php echo $site->assigned_caretakers; ?></div>
                                <div class="stat-item-label">Caretakers</div>
                            </div>
                        </div>

                        <div class="site-actions">
                            <a href="<?php echo URL_ROOT; ?>/client/viewSite/<?php echo $site->id; ?>" class="btn btn-primary">
                                <span class="material-symbols-outlined">visibility</span>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>