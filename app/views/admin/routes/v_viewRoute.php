<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<style>
.page-container {
    margin: 0 50px;
}

.route-header {
    background: linear-gradient(135deg, #ffffff 0%, #f5f6f7 50%, #eff0f2 100%);
    padding: 50px;
    border-radius: 16px;
    box-shadow: 0 12px 35px rgba(164, 0, 0, 0.12), 0 1px 3px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;

    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
}



.route-header::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(164, 0, 0, 0.05) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
}

.route-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 25px;
    position: relative;
    z-index: 1;
    flex-wrap: wrap;
}

.route-description {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 28px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(249, 250, 251, 0.95) 100%);
    border-radius: 12px;
    border: 1px solid rgba(229, 231, 235, 0.6);
    margin-bottom: 30px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
    position: relative;
    z-index: 1;
}

.route-description .material-symbols-outlined {
    font-size: 32px;
    color: #a40000;
    flex-shrink: 0;
    margin-top: 2px;
    opacity: 0.85;
    transition: all 0.3s ease;
}

.description-content {
    flex: 1;
}

.description-label {
    font-size: 12px;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    font-weight: 700;
    margin-bottom: 8px;
}

.description-value {
    font-size: 17px;
    color: #1f2937;
    font-weight: 500;
    line-height: 1.7;
}

.route-title {
    font-size: 36px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    letter-spacing: -0.5px;
    line-height: 1.2;
    position: relative;
    z-index: 1;
}

.route-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
    position: relative;
    z-index: 1;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 16px 18px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(249, 250, 251, 0.9) 100%);
    border-radius: 10px;
    border: 1px solid rgba(229, 231, 235, 0.8);
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.meta-item:hover {
    box-shadow: 0 8px 20px rgba(164, 0, 0, 0.12);
    transform: translateY(-4px);
    border-color: rgba(164, 0, 0, 0.2);
    background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(255, 250, 250, 0.95) 100%);
}

.meta-icon {
    font-size: 26px;
    color: #a40000;
    flex-shrink: 0;
    opacity: 0.9;
    transition: all 0.3s ease;
}

.meta-item:hover .meta-icon {
    color: #d32f2f;
    opacity: 1;
    transform: scale(1.1);
}

.meta-content {
    flex: 1;
}

.meta-label {
    font-size: 12px;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    font-weight: 700;
    margin-bottom: 6px;
}

.meta-value {
    font-size: 16px;
    color: #1f2937;
    font-weight: 600;
}

.route-status {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    position: relative;
    z-index: 1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.status-active {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border: 1px solid #6ee7b7;
}

.status-inactive {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.sites-section {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.section-title {
    font-size: 24px;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.sites-count {
    background: #a40000;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
}

.sites-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.site-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 20px;
    transition: all 0.3s ease;
    background: #fff;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
    min-height: 250px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.site-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.4) 100%);
    border-radius: 8px;
    z-index: 1;
    pointer-events: none;
}

.site-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.site-name {
    font-size: 18px;
    font-weight: bold;
    color: #fff;
    margin-bottom: 10px;
    position: relative;
    z-index: 2;
}

.site-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    position: relative;
    z-index: 2;
}

.site-info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.site-label {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
}

.site-value {
    font-size: 14px;
    color: #fff;
    font-weight: 600;
}

.no-sites {
    text-align: center;
    padding: 40px 20px;
    color: #6b7280;
}

.no-sites .material-symbols-outlined {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.map-section {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

#route-map {
    width: 100%;
    height: 400px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.action-buttons {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.btn {
    display: inline-block;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.3s ease;
    text-decoration: none;
    text-align: center;
}

.btn-primary {
    background: #a40000;
    color: white;
}

.btn-primary:hover {
    background: #bd0909;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
}

.btn-secondary:hover {
    background: #e5e7eb;
    transform: translateY(-2px);
}

.btn-danger {
    background: #dc2626;
    color: white;
}

.btn-danger:hover {
    background: #b91c1c;
    transform: translateY(-2px);
}

.map-legend {
    display: flex;
    gap: 20px;
    margin-top: 15px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 6px;
    justify-content: center;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #374151;
}

.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 3px;
}

.legend-marker {
    display: flex;
    align-items: center;
    justify-content: center;
}

.map-controls {
    display: flex;
    gap: 10px;
    margin-left: auto;
}

.btn-small {
    padding: 8px 16px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.edit-notice {
    background: #fff3cd;
    color: #856404;
    padding: 12px 16px;
    border-radius: 6px;
    margin-top: 10px;
    border: 1px solid #ffeaa7;
    display: flex;
    align-items: center;
    gap: 8px;
}

.edit-notice .material-symbols-outlined {
    font-size: 18px;
}

.location-search-box {
    margin-bottom: 15px;
}

#location-search {
    width: 100%;
    padding: 12px 45px 12px 15px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s ease;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="%23666" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>');
    background-repeat: no-repeat;
    background-position: right 12px center;
}

#location-search:focus {
    outline: none;
    border-color: #a40000;
    box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.1);
}

#location-search::placeholder {
    color: #999;
}

.pac-container {
    border-radius: 8px;
    margin-top: 5px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    font-family: inherit;
}

.confirm-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.confirm-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.confirm-modal-content {
    position: relative;
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    max-width: 500px;
    width: 90%;
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.confirm-modal-header {
    padding: 30px 30px 20px;
    text-align: center;
    border-bottom: 1px solid #e5e7eb;
}

.confirm-modal-header h2 {
    margin: 15px 0 0 0;
    color: #1f2937;
    font-size: 24px;
    font-weight: 600;
}

.confirm-modal-body {
    padding: 30px;
}

.confirm-modal-body p {
    margin: 0;
    color: #4b5563;
    font-size: 16px;
    line-height: 1.6;
}

.confirm-modal-body strong {
    color: #1f2937;
}

.confirm-modal-footer {
    padding: 20px 30px 30px;
    display: flex;
    gap: 15px;
    justify-content: flex-end;
}

.confirm-modal-footer .btn {
    min-width: 120px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.rider-section {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.rider-assign-container {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.current-rider {
    flex: 1;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    border-left: 4px solid #a40000;
}

.current-rider-title {
    font-size: 14px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-bottom: 10px;
}

.current-rider-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.rider-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #a40000;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 18px;
}

.rider-details h4 {
    margin: 0 0 4px 0;
    font-size: 18px;
    color: #1f2937;
    font-weight: 600;
}

.rider-details p {
    margin: 0;
    font-size: 14px;
    color: #6b7280;
}

.assign-rider-form {
    flex: 1;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 2px dashed #cbd5e1;
}

.assign-rider-form h4 {
    margin: 0 0 15px 0;
    font-size: 16px;
    color: #1f2937;
    font-weight: 600;
}

.rider-select-group {
    display: flex;
    gap: 10px;
    align-items: flex-start;
}

.rider-select {
    flex: 1;
    padding: 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.rider-select:focus {
    outline: none;
    border-color: #a40000;
    box-shadow: 0 0 0 3px rgba(164, 0, 0, 0.1);
}

.btn-assign-rider {
    background: #a40000;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-assign-rider:hover {
    background: #bd0909;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(164, 0, 0, 0.3);
}

.btn-assign-rider:disabled {
    background: #9ca3af;
    cursor: not-allowed;
    transform: none;
}

.no-rider {
    color: #6b7280;
    font-style: italic;
}
</style>

<?php require_once APP_ROOT . '/views/components/showNotification.php'; ?>

<div class="page-container">
    <!-- Back Button -->
    <button class="tertiary-btn" style="display:flex; width:100px; margin-bottom: 20px; align-items:center;" onclick="history.back()">
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Back
    </button>

    <!-- Route Header -->
    <div class="route-header">
        <div class="route-title-row">
            <div class="route-title"><?php echo htmlspecialchars($data['route']->route_name); ?></div>
            <span class="route-status status-<?php echo strtolower($data['route']->status); ?>">
                <?php echo $data['route']->status; ?>
            </span>
        </div>
        <div class="route-description">
            <span class="material-symbols-outlined">description</span>
            <div class="description-content">
                <div class="description-label">Description</div>
                <div class="description-value"><?php echo htmlspecialchars($data['route']->description ?? 'No description'); ?></div>
            </div>
        </div>
        <div class="route-meta">
            <div class="meta-item">
                <span class="material-symbols-outlined meta-icon">person</span>
                <div class="meta-content">
                    <div class="meta-label">Created By</div>
                    <div class="meta-value">
                        <?php echo htmlspecialchars($data['creator'] ? $data['creator']->name : 'Unknown'); ?>
                    </div>
                </div>
            </div>
            <div class="meta-item">
                <span class="material-symbols-outlined meta-icon">calendar_today</span>
                <div class="meta-content">
                    <div class="meta-label">Created Date</div>
                    <div class="meta-value"><?php echo date('M d, Y', strtotime($data['route']->created_at)); ?></div>
                </div>
            </div>
            <div class="meta-item">
                <span class="material-symbols-outlined meta-icon">location_on</span>
                <div class="meta-content">
                    <div class="meta-label">Number of Sites</div>
                    <div class="meta-value"><?php echo count($data['routeSites']); ?> sites</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Sites Section -->
    <div class="sites-section">
        <div class="section-title">
            <span class="material-symbols-outlined">location_on</span>
            Assigned Sites
            <span class="sites-count"><?php echo count($data['routeSites']); ?></span>
        </div>

        <?php if (empty($data['routeSites'])): ?>
            <div class="no-sites">
                <span class="material-symbols-outlined">location_off</span>
                <h3>No sites assigned</h3>
                <p>This route doesn't have any sites assigned to it yet.</p>
            </div>
        <?php else: ?>
            <div class="sites-grid">
                <?php foreach ($data['routeSites'] as $site): ?>
                    <div class="site-card" style="background-image: url('<?php echo URL_ROOT; ?>/uploads/siteImages/<?php echo htmlspecialchars($site->image ?? ''); ?>')">
                        <div class="site-name"><?php echo htmlspecialchars($site->site_name); ?></div>
                        <div class="site-info">
                            <div class="site-info-item">
                                <span class="site-label">Location:</span>
                                <span class="site-value"><?php echo htmlspecialchars($site->city ?? 'N/A'); ?></span>
                            </div>
                            <div class="site-info-item">
                                <span class="site-label">Address:</span>
                                <span class="site-value"><?php echo htmlspecialchars($site->address ?? 'N/A'); ?></span>
                            </div>
                            <div class="site-info-item">
                                <span class="site-label">Phone:</span>
                                <span class="site-value"><?php echo htmlspecialchars($site->phone_number ?? 'N/A'); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Mobile Rider Assignment Section -->
    <div class="rider-section">
        <div class="section-title">
            <span class="material-symbols-outlined">two_wheeler</span>
            Assigned Mobile Rider
        </div>

        <div class="rider-assign-container">
            <!-- Current Assigned Rider -->
            <div class="current-rider">
                <div class="current-rider-title">Current Rider</div>
                <?php if (!empty($data['assignedRider'])): ?>
                    <div class="current-rider-info">
                        <div class="rider-avatar">
                            <?php if (!empty($data['assignedRider']->profile_image)): ?>
                                <img src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo $data['assignedRider']->profile_image; ?>" 
                                     alt="<?php echo htmlspecialchars($data['assignedRider']->name); ?>" 
                                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            <?php else: ?>
                                <?php echo strtoupper(substr($data['assignedRider']->name, 0, 1)); ?>
                            <?php endif; ?>
                        </div>
                        <div class="rider-details">
                            <h4><?php echo htmlspecialchars($data['assignedRider']->name); ?></h4>
                            <p><?php echo htmlspecialchars($data['assignedRider']->email ?? 'No email'); ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-rider">
                        <span class="material-symbols-outlined" style="font-size: 24px; opacity: 0.5; display: block; margin-bottom: 8px;">person_off</span>
                        No mobile rider assigned to this route yet.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Assign New Rider Form -->
            <div class="assign-rider-form">
                <h4><?php echo !empty($data['assignedRider']) ? 'Change Assigned Rider' : 'Assign Mobile Rider'; ?></h4>
                <div class="rider-select-group">
                    <select id="riderSelect" class="rider-select">
                        <option value="">Select a mobile rider...</option>
                        <?php if (!empty($data['mobileRiders'])): ?>
                            <?php foreach ($data['mobileRiders'] as $rider): ?>
                                <?php 
                                    // Skip the currently assigned rider
                                    if (!empty($data['assignedRider']) && $data['assignedRider']->user_id == $rider->user_id) {
                                        continue;
                                    }
                                ?>
                                <option value="<?php echo $rider->user_id; ?>">
                                    <?php echo htmlspecialchars($rider->name); ?>
                                    <?php echo !empty($rider->email) ? ' (' . htmlspecialchars($rider->email) . ')' : ''; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <button class="btn-assign-rider" onclick="assignRiderToRoute()">
                        <span class="material-symbols-outlined" style="font-size: 16px;">person_add</span>
                        Assign
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Route Map Section -->
    <div class="map-section">
        <div class="section-title">
            <span class="material-symbols-outlined">map</span>
            Route Map
            <div class="map-controls">
                <button id="editRouteBtn" class="btn btn-secondary btn-small">
                    <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                    Edit Area
                </button>
                <button id="saveRouteBtn" class="btn btn-primary btn-small" style="display: none;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">save</span>
                    Save Changes
                </button>
                <button id="cancelEditBtn" class="btn btn-light btn-small" style="display: none;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">cancel</span>
                    Cancel
                </button>
            </div>
        </div>
        <div class="location-search-box">
            <input id="location-search" type="text" placeholder="Search for a location...">
        </div>
        <div id="route-map"></div>
        <div id="editInstructions" class="edit-notice" style="display: none;">
            <span class="material-symbols-outlined">info</span>
            Click and drag polygon points to adjust the route area. Use the drawing tools to add/remove points.
        </div>
        <div class="map-legend">
            <div class="legend-item">
                <div class="legend-color" style="background-color: rgba(255, 0, 0, 0.3); border: 2px solid #FF0000;"></div>
                <span>Route Area</span>
            </div>
            <div class="legend-item">
                <div class="legend-marker">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="#a40000"/>
                    </svg>
                </div>
                <span>Assigned Sites</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="<?php echo URL_ROOT; ?>/admin/routes" class="btn btn-secondary">
            <span class="material-symbols-outlined" style="font-size: 18px;">list</span>
            Back to Routes
        </a>
        <button id="deleteRouteBtn" class="btn btn-danger" onclick="confirmDeleteRoute()">
            <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
            Delete Route
        </button>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="confirm-modal" style="display: none;">
    <div class="confirm-modal-overlay" onclick="closeDeleteModal()"></div>
    <div class="confirm-modal-content">
        <div class="confirm-modal-header">
            <span class="material-symbols-outlined" style="color: #dc2626; font-size: 48px;">warning</span>
            <h2>Delete Route</h2>
        </div>
        <div class="confirm-modal-body">
            <p>Are you sure you want to delete the route <strong id="routeNameToDelete"></strong>?</p>
            <p style="color: #dc2626; font-weight: 500; margin-top: 10px;">This action cannot be undone and will also remove all site assignments for this route.</p>
        </div>
        <div class="confirm-modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteModal()">
                <span class="material-symbols-outlined" style="font-size: 16px;">close</span>
                Cancel
            </button>
            <button class="btn btn-danger" onclick="proceedWithDelete()">
                <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                Delete Route
            </button>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGwijY64zQTmizwDN6omOoI9nzxb1MQog&libraries=drawing,geometry,places"></script>
<script>
let map;
let routePolygon = null;
let drawingManager = null;
let isEditing = false;
let originalPath = null;

function initRouteMap() {
    // Initialize map centered on Sri Lanka
    map = new google.maps.Map(document.getElementById('route-map'), {
        center: { lat: 7.8731, lng: 80.7718 },
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    // Initialize Places Autocomplete
    const input = document.getElementById('location-search');
    const autocomplete = new google.maps.places.Autocomplete(input, {
        componentRestrictions: { country: 'lk' }, // Restrict to Sri Lanka
        fields: ['geometry', 'name', 'formatted_address']
    });

    // Bind autocomplete to map
    autocomplete.bindTo('bounds', map);

    // Listen for place selection
    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();

        if (!place.geometry || !place.geometry.location) {
            console.log('No details available for: ' + place.name);
            return;
        }

        // If the place has a viewport, fit the map to it
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(15);
        }

        // Optional: Add a temporary marker at the searched location
        const searchMarker = new google.maps.Marker({
            map: map,
            position: place.geometry.location,
            title: place.name,
            animation: google.maps.Animation.DROP,
            icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
            }
        });

        // Remove the search marker after 3 seconds
        setTimeout(() => {
            searchMarker.setMap(null);
        }, 3000);
    });

    // Prevent form submission when Enter is pressed in search box
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    let hasBounds = false;
    const bounds = new google.maps.LatLngBounds();

    // Try to load route area if it exists
    const locationData = <?php echo json_encode($data['route']->location); ?>;
    if (locationData) {
        try {
            const coordinates = JSON.parse(locationData);
            if (coordinates && coordinates.length > 0) {
                // Create polygon for route area
                routePolygon = new google.maps.Polygon({
                    paths: coordinates,
                    fillColor: '#FF0000',
                    fillOpacity: 0.3,
                    strokeWeight: 2,
                    strokeColor: '#FF0000',
                    clickable: false,
                    editable: false,
                    zIndex: 1
                });

                routePolygon.setMap(map);

                // Store original path for cancel functionality
                originalPath = coordinates.map(coord => ({ lat: coord.lat, lng: coord.lng }));

                // Extend bounds to include polygon
                coordinates.forEach(coord => {
                    bounds.extend(new google.maps.LatLng(coord.lat, coord.lng));
                });
                hasBounds = true;
            }
        } catch (e) {
            console.error('Error loading route area:', e);
        }
    }

    // Initialize drawing manager for editing
    drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: null,
        drawingControl: false,
        polygonOptions: {
            fillColor: '#FF0000',
            fillOpacity: 0.3,
            strokeWeight: 2,
            strokeColor: '#FF0000',
            clickable: true,
            editable: true,
            zIndex: 1
        }
    });
    drawingManager.setMap(map);

    // Add markers for assigned sites
    const sites = <?php echo json_encode($data['routeSites']); ?>;
    if (sites && sites.length > 0) {
        sites.forEach(site => {
            if (site.latitude && site.longitude) {
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(site.latitude), lng: parseFloat(site.longitude) },
                    map: map,
                    title: site.site_name,
                    icon: {
                        url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="#a40000"/>
                            </svg>
                        `),
                        scaledSize: new google.maps.Size(32, 32)
                    }
                });

                // Extend bounds to include marker
                bounds.extend(new google.maps.LatLng(parseFloat(site.latitude), parseFloat(site.longitude)));
                hasBounds = true;

                // Add info window
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="max-width: 250px;">
                            <h4 style="margin: 0 0 8px 0; color: #1f2937; font-size: 16px;">${site.site_name}</h4>
                            <div style="font-size: 14px; color: #6b7280;">
                                <p style="margin: 4px 0;"><strong>Address:</strong> ${site.address || 'No address available'}</p>
                                <p style="margin: 4px 0;"><strong>City:</strong> ${site.city || 'N/A'}</p>
                                
                                <p style="margin: 4px 0;"><strong>Phone:</strong> ${site.phone_number || 'N/A'}</p>
                            </div>
                        </div>
                    `
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });
            }
        });
    }

    // Fit map to bounds if we have any markers or polygons
    if (hasBounds) {
        map.fitBounds(bounds);
        
        // Ensure minimum zoom level
        google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
            if (map.getZoom() > 15) {
                map.setZoom(15);
            }
        });
    }

    // Setup edit controls
    setupEditControls();
}

function setupEditControls() {
    const editBtn = document.getElementById('editRouteBtn');
    const saveBtn = document.getElementById('saveRouteBtn');
    const cancelBtn = document.getElementById('cancelEditBtn');
    const instructions = document.getElementById('editInstructions');

    editBtn.addEventListener('click', function() {
        startEditing();
    });

    saveBtn.addEventListener('click', function() {
        saveChanges();
    });

    cancelBtn.addEventListener('click', function() {
        cancelEditing();
    });

    function startEditing() {
        if (!routePolygon) {
            showNotification('No route area to edit. Please create a route area first.', 'warning');
            return;
        }

        isEditing = true;

        // Make polygon editable
        routePolygon.setEditable(true);
        routePolygon.setOptions({ clickable: true });

        // Show editing controls
        editBtn.style.display = 'none';
        saveBtn.style.display = 'inline-flex';
        cancelBtn.style.display = 'inline-flex';
        instructions.style.display = 'flex';

        // Add polygon event listeners for real-time updates
        google.maps.event.addListener(routePolygon.getPath(), 'set_at', updatePolygonBounds);
        google.maps.event.addListener(routePolygon.getPath(), 'insert_at', updatePolygonBounds);
        google.maps.event.addListener(routePolygon.getPath(), 'remove_at', updatePolygonBounds);
    }

    function cancelEditing() {
        if (!routePolygon || !originalPath) return;

        isEditing = false;

        // Restore original path
        routePolygon.setPath(originalPath);
        routePolygon.setEditable(false);
        routePolygon.setOptions({ clickable: false });

        // Reset UI
        editBtn.style.display = 'inline-flex';
        saveBtn.style.display = 'none';
        cancelBtn.style.display = 'none';
        instructions.style.display = 'none';
    }

    function saveChanges() {
        if (!routePolygon) return;

        // Get updated coordinates
        const path = routePolygon.getPath();
        const coordinates = [];

        for (let i = 0; i < path.getLength(); i++) {
            const point = path.getAt(i);
            coordinates.push({
                lat: point.lat(),
                lng: point.lng()
            });
        }

        // Send to server
        const routeId = '<?php echo $data['route']->id; ?>';
        const locationData = JSON.stringify(coordinates);

        fetch('<?php echo URL_ROOT; ?>/admin/updateRouteLocation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `route_id=${routeId}&location=${encodeURIComponent(locationData)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update original path
                originalPath = coordinates.map(coord => ({ lat: coord.lat, lng: coord.lng }));

                // Exit edit mode
                isEditing = false;
                routePolygon.setEditable(false);
                routePolygon.setOptions({ clickable: false });

                // Reset UI
                editBtn.style.display = 'inline-flex';
                saveBtn.style.display = 'none';
                cancelBtn.style.display = 'none';
                instructions.style.display = 'none';

                // Show success message
                showNotification('Route area updated successfully!', 'success');
            } else {
                showNotification('Failed to update route area: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to update route area. Please try again.', 'error');
        });
    }

    function updatePolygonBounds() {
        // Optional: Update map bounds when polygon changes
        if (routePolygon && isEditing) {
            const bounds = new google.maps.LatLngBounds();
            const path = routePolygon.getPath();

            for (let i = 0; i < path.getLength(); i++) {
                bounds.extend(path.getAt(i));
            }

            // Only fit bounds if polygon is reasonably sized
            if (path.getLength() > 2) {
                map.fitBounds(bounds);
            }
        }
    }
}

function assignRiderToRoute() {
    const riderSelect = document.getElementById('riderSelect');
    const riderId = riderSelect.value;
    const routeId = '<?php echo $data['route']->id; ?>';
    
    if (!riderId) {
        showNotification('Please select a mobile rider', 'warning');
        return;
    }
    
    const riderName = riderSelect.options[riderSelect.selectedIndex].text;
    
    console.log('Assigning rider:', { rider_id: riderId, route_id: routeId });
    
    fetch('<?php echo URL_ROOT; ?>/admin/assignRiderToRoute', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            rider_id: riderId,
            route_id: routeId
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response from server:', data);
        if (data.success) {
            showNotification('Mobile rider assigned successfully!', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            const errorMsg = data.message || 'Unknown error';
            const debugInfo = data.debug ? JSON.stringify(data.debug) : '';
            console.error('Assignment failed:', errorMsg, debugInfo);
            showNotification('Failed: ' + errorMsg + (debugInfo ? ' | Debug: ' + debugInfo : ''), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to assign rider. Please try again.', 'error');
    });
}

function confirmDeleteRoute() {
    const routeName = '<?php echo addslashes($data['route']->route_name); ?>';
    document.getElementById('routeNameToDelete').textContent = `"${routeName}"`;
    document.getElementById('deleteConfirmModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteConfirmModal').style.display = 'none';
}

function proceedWithDelete() {
    const routeId = '<?php echo $data['route']->id; ?>';
    closeDeleteModal();
    deleteRoute(routeId);
}

function deleteRoute(routeId) {
    // Show loading state
    const deleteBtn = document.getElementById('deleteRouteBtn');
    const originalText = deleteBtn.innerHTML;
    deleteBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">hourglass_empty</span> Deleting...';
    deleteBtn.disabled = true;

    fetch('<?php echo URL_ROOT; ?>/admin/deleteRoute', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `route_id=${routeId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Route deleted successfully!', 'success');
            // Redirect to routes list after a short delay
            setTimeout(() => {
                window.location.href = '<?php echo URL_ROOT; ?>/admin/routes';
            }, 1500);
        } else {
            showNotification('Failed to delete route: ' + (data.message || 'Unknown error'), 'error');
            // Restore button
            deleteBtn.innerHTML = originalText;
            deleteBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to delete route. Please try again.', 'error');
        // Restore button
        deleteBtn.innerHTML = originalText;
        deleteBtn.disabled = false;
    });
}

// Initialize map when page loads
google.maps.event.addDomListener(window, 'load', initRouteMap);
</script>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>