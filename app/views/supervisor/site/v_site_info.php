<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_supervisor_sidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
  :root {
    --primary: #a40000;
    --primary-dark: #7a0000;
    --secondary: #f5f5f5;
    --text-dark: #333;
    --text-muted: #666;
    --border: #e0e0e0;
    --shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  .site-info-container {
    padding: 24px;
    max-width: 1400px;
    margin: 0 auto;
  }

  .page-header {
    margin-bottom: 32px;
  }

  .page-header h1 {
    font-size: 28px;
    color: var(--text-dark);
    margin-bottom: 8px;
  }

  .breadcrumb {
    color: var(--text-muted);
    font-size: 14px;
  }

  .breadcrumb a {
    color: var(--primary);
    text-decoration: none;
  }

  .site-details-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: var(--shadow);
    margin-bottom: 24px;
  }

  .site-details-card h2 {
    font-size: 20px;
    color: var(--text-dark);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .site-detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
  }

  .site-detail-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .site-detail-label {
    font-size: 13px;
    color: var(--text-muted);
    font-weight: 500;
  }

  .site-detail-value {
    font-size: 15px;
    color: var(--text-dark);
    font-weight: 600;
  }

  .officers-section {
    margin-bottom: 32px;
  }

  .section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .section-title {
    font-size: 22px;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .officer-count {
    background: var(--primary);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
  }

  .officers-table-container {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    overflow: hidden;
  }

  .officers-table {
    width: 100%;
    border-collapse: collapse;
  }

  .officers-table thead {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    color: white;
  }

  .officers-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .officers-table td {
    padding: 16px;
    border-bottom: 1px solid var(--border);
    font-size: 14px;
  }

  .officers-table tbody tr {
    transition: background-color 0.2s;
  }

  .officers-table tbody tr:hover {
    background-color: #f9f9f9;
  }

  .officer-info {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .officer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), #ff6b6b);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
  }

  .officer-details {
    display: flex;
    flex-direction: column;
  }

  .officer-name {
    font-weight: 600;
    color: var(--text-dark);
  }

  .officer-id {
    font-size: 12px;
    color: var(--text-muted);
  }

  .rank-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .rank-supervisor {
    background: #e3f2fd;
    color: #1976d2;
  }

  .rank-oic {
    background: #fff3e0;
    color: #f57c00;
  }

  .rank-sso {
    background: #f3e5f5;
    color: #7b1fa2;
  }

  .rank-jso {
    background: #e8f5e9;
    color: #388e3c;
  }

  .rank-lso {
    background: #fce4ec;
    color: #c2185b;
  }

  .shift-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
  }

  .shift-day {
    background: #fff8e1;
    color: #f57f17;
  }

  .shift-night {
    background: #e0f2f1;
    color: #00695c;
  }

  .shift-full {
    background: #e8eaf6;
    color: #3f51b5;
  }

  .shift-flexible {
    background: #f3e5f5;
    color: #6a1b9a;
  }

  .status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
  }

  .status-active {
    background: #e8f5e9;
    color: #2e7d32;
  }

  .status-inactive {
    background: #ffebee;
    color: #c62828;
  }

  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-muted);
  }

  .empty-state .material-icons {
    font-size: 64px;
    color: #ddd;
    margin-bottom: 16px;
  }

  .empty-state h3 {
    font-size: 18px;
    color: var(--text-dark);
    margin-bottom: 8px;
  }

  .contact-info {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 14px;
    color: var(--text-dark);
  }

  .contact-info .material-icons {
    font-size: 16px;
    color: var(--text-muted);
  }

  .view-requests-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
  }

  .view-requests-btn:hover {
    background: linear-gradient(135deg, #ee5a6f, #ff6b6b);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
  }

  .view-requests-btn .material-icons {
    font-size: 18px;
  }

  /* Modal Styles */
  .modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 9998;
    backdrop-filter: blur(4px);
  }

  .modal-overlay.active {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .equipment-modal {
    background: white;
    border-radius: 16px;
    width: 90%;
    max-width: 900px;
    max-height: 85vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease;
  }

  @keyframes modalSlideIn {
    from {
      opacity: 0;
      transform: translateY(-30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .modal-header {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    color: white;
    padding: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .modal-header h2 {
    margin: 0;
    font-size: 22px;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .modal-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: background 0.2s;
  }

  .modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
  }

  .modal-close .material-icons {
    font-size: 28px;
  }

  .modal-body {
    padding: 24px;
    max-height: calc(85vh - 160px);
    overflow-y: auto;
  }

  .request-card {
    background: #f9f9f9;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 16px;
    border-left: 4px solid var(--primary);
  }

  .request-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 16px;
  }

  .request-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 4px;
  }

  .request-date {
    font-size: 13px;
    color: var(--text-muted);
  }

  .request-priority {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .priority-high {
    background: #ffebee;
    color: #c62828;
  }

  .priority-medium {
    background: #fff8e1;
    color: #f57f17;
  }

  .priority-low {
    background: #e8f5e9;
    color: #2e7d32;
  }

  .request-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
  }

  .detail-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .detail-label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
  }

  .detail-value {
    font-size: 15px;
    color: var(--text-dark);
    font-weight: 600;
  }

  .request-reason {
    background: white;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 16px;
  }

  .request-reason label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    display: block;
    margin-bottom: 8px;
  }

  .request-reason p {
    margin: 0;
    color: var(--text-dark);
    line-height: 1.6;
  }

  .request-actions {
    display: flex;
    gap: 12px;
    margin-top: 16px;
  }

  .approve-btn, .reject-btn {
    flex: 1;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .approve-btn {
    background: linear-gradient(135deg, #4caf50, #66bb6a);
    color: white;
  }

  .approve-btn:hover {
    background: linear-gradient(135deg, #66bb6a, #4caf50);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
  }

  .reject-btn {
    background: linear-gradient(135deg, #f44336, #e57373);
    color: white;
  }

  .reject-btn:hover {
    background: linear-gradient(135deg, #e57373, #f44336);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
  }

  .approve-btn:disabled, .reject-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
  }

  .no-requests {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-muted);
  }

  .no-requests .material-icons {
    font-size: 48px;
    color: #ddd;
    margin-bottom: 12px;
  }

  .loading-spinner {
    text-align: center;
    padding: 40px;
  }

  .spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid var(--primary);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  /* Toast Notification */
  .toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 16px 24px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 10000;
    animation: slideInRight 0.3s ease;
    max-width: 400px;
  }

  .toast-notification.success {
    border-left: 4px solid #4caf50;
  }

  .toast-notification.error {
    border-left: 4px solid #f44336;
  }

  .toast-notification.warning {
    border-left: 4px solid #ff9800;
  }

  .toast-notification .material-icons {
    font-size: 24px;
  }

  .toast-notification.success .material-icons {
    color: #4caf50;
  }

  .toast-notification.error .material-icons {
    color: #f44336;
  }

  .toast-notification.warning .material-icons {
    color: #ff9800;
  }

  .toast-content {
    flex: 1;
  }

  .toast-title {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
  }

  .toast-message {
    font-size: 14px;
    color: var(--text-muted);
  }

  @keyframes slideInRight {
    from {
      opacity: 0;
      transform: translateX(100px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  @keyframes slideOutRight {
    from {
      opacity: 1;
      transform: translateX(0);
    }
    to {
      opacity: 0;
      transform: translateX(100px);
    }
  }

  @media (max-width: 768px) {
    .site-detail-grid {
      grid-template-columns: 1fr;
    }

    .officers-table {
      font-size: 12px;
    }

    .officers-table th,
    .officers-table td {
      padding: 12px 8px;
    }

    .equipment-modal {
      width: 95%;
      max-height: 95vh;
    }

    .request-details {
      grid-template-columns: 1fr;
    }

    .request-actions {
      flex-direction: column;
    }
  }
</style>

<div class="site-info-container">
  <!-- Page Header -->
  <div class="page-header">
    <h1>Site Information</h1>
    <div class="breadcrumb">
      <a href="<?php echo URL_ROOT; ?>/supervisor/dashboard">Dashboard</a> / Site Information
    </div>
  </div>

  <?php if ($data['site']): ?>
    <!-- Site Details Card -->
    <div class="site-details-card">
      <h2>
        <span class="material-icons">business</span>
        <?php echo htmlspecialchars($data['site']->site_name); ?>
      </h2>
      <div class="site-detail-grid">
        <div class="site-detail-item">
          <span class="site-detail-label">Address</span>
          <span class="site-detail-value"><?php echo htmlspecialchars($data['site']->address); ?></span>
        </div>
        <div class="site-detail-item">
          <span class="site-detail-label">City</span>
          <span class="site-detail-value"><?php echo htmlspecialchars($data['site']->city); ?></span>
        </div>
        <?php if (!empty($data['site']->district)): ?>
        <div class="site-detail-item">
          <span class="site-detail-label">District</span>
          <span class="site-detail-value"><?php echo htmlspecialchars($data['site']->district); ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($data['site']->phone_number)): ?>
        <div class="site-detail-item">
          <span class="site-detail-label">Phone Number</span>
          <span class="site-detail-value"><?php echo htmlspecialchars($data['site']->phone_number); ?></span>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Supervisors Section -->
    <div class="officers-section">
      <div class="section-header">
        <h3 class="section-title">
          <span class="material-icons">supervisor_account</span>
          Supervisors
          <span class="officer-count"><?php echo count($data['supervisors']); ?></span>
        </h3>
      </div>

      <div class="officers-table-container">
        <?php if (!empty($data['supervisors'])): ?>
          <table class="officers-table">
            <thead>
              <tr>
                <th>Officer</th>
                <th>Officer ID</th>
                <th>Rank</th>
                <th>Shift Type</th>
                <th>Employment Status</th>
                <th>Contact</th>
                <th>Assignment Start</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data['supervisors'] as $supervisor): ?>
                <tr>
                  <td>
                    <div class="officer-info">
                      <div class="officer-avatar">
                        <?php if (!empty($supervisor->profile_image)): ?>
                          <img src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo htmlspecialchars($supervisor->profile_image); ?>" 
                               alt="<?php echo htmlspecialchars($supervisor->name); ?>"
                               style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        <?php else: ?>
                          <?php echo strtoupper(substr($supervisor->name, 0, 1)); ?>
                        <?php endif; ?>
                      </div>
                      <div class="officer-details">
                        <span class="officer-name"><?php echo htmlspecialchars($supervisor->name); ?></span>
                        <span class="officer-id"><?php echo htmlspecialchars($supervisor->email); ?></span>
                      </div>
                    </div>
                  </td>
                  <td><?php echo htmlspecialchars($supervisor->officerID ?? 'N/A'); ?></td>
                  <td><span class="rank-badge rank-supervisor"><?php echo htmlspecialchars($supervisor->rank); ?></span></td>
                  <td>
                    <span class="shift-badge shift-<?php echo strtolower(str_replace(' ', '-', $supervisor->shift_type)); ?>">
                      <?php echo htmlspecialchars($supervisor->shift_type); ?>
                    </span>
                  </td>
                  <td>
                    <span class="status-badge status-<?php echo strtolower($supervisor->employment_status); ?>">
                      <?php echo htmlspecialchars($supervisor->employment_status); ?>
                    </span>
                  </td>
                  <td>
                    <div class="contact-info">
                      <span class="material-icons">phone</span>
                      <?php echo htmlspecialchars($supervisor->phone_number ?? 'N/A'); ?>
                    </div>
                  </td>
                  <td><?php echo date('M d, Y', strtotime($supervisor->assignment_start)); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="empty-state">
            <span class="material-icons">supervisor_account</span>
            <h3>No Supervisors Assigned</h3>
            <p>There are currently no supervisors assigned to this site.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Officers Section -->
    <div class="officers-section">
      <div class="section-header">
        <h3 class="section-title">
          <span class="material-icons">badge</span>
          Security Officers
          <span class="officer-count"><?php echo count($data['officers']); ?></span>
        </h3>
      </div>

      <div class="officers-table-container">
        <?php if (!empty($data['officers'])): ?>
          <table class="officers-table">
            <thead>
              <tr>
                <th>Officer</th>
                <th>Officer ID</th>
                <th>Rank</th>
                <th>Shift Type</th>
                <th>Employment Status</th>
                <th>Contact</th>
                <th>Assignment Start</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data['officers'] as $officer): ?>
                <tr>
                  <td>
                    <div class="officer-info">
                      <div class="officer-avatar">
                        <?php if (!empty($officer->profile_image)): ?>
                          <img src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo htmlspecialchars($officer->profile_image); ?>" 
                               alt="<?php echo htmlspecialchars($officer->name); ?>"
                               style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        <?php else: ?>
                          <?php echo strtoupper(substr($officer->name, 0, 1)); ?>
                        <?php endif; ?>
                      </div>
                      <div class="officer-details">
                        <span class="officer-name"><?php echo htmlspecialchars($officer->name); ?></span>
                        <span class="officer-id"><?php echo htmlspecialchars($officer->email); ?></span>
                      </div>
                    </div>
                  </td>
                  <td><?php echo htmlspecialchars($officer->officerID ?? 'N/A'); ?></td>
                  <td>
                    <span class="rank-badge rank-<?php echo strtolower($officer->rank); ?>">
                      <?php echo htmlspecialchars($officer->rank); ?>
                    </span>
                  </td>
                  <td>
                    <span class="shift-badge shift-<?php echo strtolower(str_replace(' ', '-', $officer->shift_type)); ?>">
                      <?php echo htmlspecialchars($officer->shift_type); ?>
                    </span>
                  </td>
                  <td>
                    <span class="status-badge status-<?php echo strtolower($officer->employment_status); ?>">
                      <?php echo htmlspecialchars($officer->employment_status); ?>
                    </span>
                  </td>
                  <td>
                    <div class="contact-info">
                      <span class="material-icons">phone</span>
                      <?php echo htmlspecialchars($officer->phone_number ?? 'N/A'); ?>
                    </div>
                  </td>
                  <td><?php echo date('M d, Y', strtotime($officer->assignment_start)); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="empty-state">
            <span class="material-icons">badge</span>
            <h3>No Officers Assigned</h3>
            <p>There are currently no security officers assigned to this site.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Mobile Riders Section -->
    <div class="officers-section">
      <div class="section-header">
        <h3 class="section-title">
          <span class="material-icons">two_wheeler</span>
          Mobile Riders
          <span class="officer-count"><?php echo count($data['mobile_riders'] ?? []); ?></span>
        </h3>
      </div>

      <div class="officers-table-container">
        <?php if (!empty($data['mobile_riders'])): ?>
          <table class="officers-table">
            <thead>
              <tr>
                <th>Rider</th>
                <th>Contact</th>
                <th>Assigned Routes</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data['mobile_riders'] as $rider): ?>
                <tr>
                  <td>
                    <div class="officer-info">
                      <div class="officer-avatar">
                        <?php if (!empty($rider->profile_image)): ?>
                          <img src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo htmlspecialchars($rider->profile_image); ?>" 
                               alt="<?php echo htmlspecialchars($rider->name); ?>"
                               style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        <?php else: ?>
                          <?php echo strtoupper(substr($rider->name, 0, 1)); ?>
                        <?php endif; ?>
                      </div>
                      <div class="officer-details">
                        <span class="officer-name"><?php echo htmlspecialchars($rider->name); ?></span>
                        <span class="officer-id"><?php echo htmlspecialchars($rider->email); ?></span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="contact-info">
                      <span class="material-icons">phone</span>
                      <?php echo htmlspecialchars($rider->phone_number ?? 'N/A'); ?>
                    </div>
                  </td>
                  <td>
                    <span style="display: inline-block; padding: 6px 12px; background: #f0f4ff; color: #2563eb; border-radius: 6px; font-size: 13px;">
                      <span class="material-icons" style="font-size: 16px; vertical-align: middle;">route</span>
                      Active Route
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="empty-state">
            <span class="material-icons">two_wheeler</span>
            <h3>No Mobile Riders Assigned</h3>
            <p>There are currently no mobile riders assigned to this site.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Caretakers Section -->
    <div class="officers-section">
      <div class="section-header">
        <h3 class="section-title">
          <span class="material-icons">home_repair_service</span>
          Caretakers
          <span class="officer-count"><?php echo count($data['caretakers'] ?? []); ?></span>
        </h3>
      </div>

      <div class="officers-table-container">
        <?php if (!empty($data['caretakers'])): ?>
          <table class="officers-table">
            <thead>
              <tr>
                <th>Caretaker</th>
                <th>Contact</th>
                <th>Assignment Start</th>
                <th>Assignment End</th>
                <th>Status</th>
                <th>Equipment Requests</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data['caretakers'] as $caretaker): ?>
                <tr>
                  <td>
                    <div class="officer-info">
                      <div class="officer-avatar">
                        <?php if (!empty($caretaker->profile_image)): ?>
                          <img src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo htmlspecialchars($caretaker->profile_image); ?>" 
                               alt="<?php echo htmlspecialchars($caretaker->name); ?>"
                               style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        <?php else: ?>
                          <?php echo strtoupper(substr($caretaker->name, 0, 1)); ?>
                        <?php endif; ?>
                      </div>
                      <div class="officer-details">
                        <span class="officer-name"><?php echo htmlspecialchars($caretaker->name); ?></span>
                        <span class="officer-id"><?php echo htmlspecialchars($caretaker->email); ?></span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="contact-info">
                      <span class="material-icons">phone</span>
                      <?php echo htmlspecialchars($caretaker->phone_number ?? 'N/A'); ?>
                    </div>
                  </td>
                  <td><?php echo date('M d, Y', strtotime($caretaker->assignment_start)); ?></td>
                  <td>
                    <?php 
                      if ($caretaker->assignment_end) {
                        echo date('M d, Y', strtotime($caretaker->assignment_end));
                      } else {
                        echo '<span style="color: var(--text-muted);">Ongoing</span>';
                      }
                    ?>
                  </td>
                  <td>
                    <span class="status-badge status-<?php echo strtolower($caretaker->status); ?>">
                      <?php echo htmlspecialchars($caretaker->status); ?>
                    </span>
                  </td>
                  <td>
                    <?php if ($caretaker->pending_requests_count > 0): ?>
                      <button class="view-requests-btn" 
                              data-caretaker-id="<?php echo $caretaker->user_id; ?>"
                              data-caretaker-name="<?php echo htmlspecialchars($caretaker->name); ?>">
                        <span class="material-icons">notification_important</span>
                        <?php echo $caretaker->pending_requests_count; ?> Pending
                      </button>
                    <?php else: ?>
                      <span style="color: var(--text-muted); font-size: 13px;">No pending requests</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php 
                      if (!empty($caretaker->notes)) {
                        echo '<span title="' . htmlspecialchars($caretaker->notes) . '">' . 
                             htmlspecialchars(substr($caretaker->notes, 0, 50)) . 
                             (strlen($caretaker->notes) > 50 ? '...' : '') . '</span>';
                      } else {
                        echo '<span style="color: var(--text-muted);">-</span>';
                      }
                    ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="empty-state">
            <span class="material-icons">home_repair_service</span>
            <h3>No Caretakers Assigned</h3>
            <p>There are currently no caretakers assigned to this site.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

  <?php else: ?>
    <!-- No Site Assigned -->
    <div class="site-details-card">
      <div class="empty-state">
        <span class="material-icons">business_center</span>
        <h3>No Site Assigned</h3>
        <p>You are not currently assigned to any site. Please contact your administrator.</p>
      </div>
    </div>
  <?php endif; ?>
</div>


    </main>
    </div>

    <!-- Equipment Requests Modal -->
    <div class="modal-overlay" id="equipmentModal">
      <div class="equipment-modal">
        <div class="modal-header">
          <h2>
            <span class="material-icons">inventory_2</span>
            <span id="modalCaretakerName">Equipment Requests</span>
          </h2>
          <button class="modal-close" onclick="closeEquipmentModal()">
            <span class="material-icons">close</span>
          </button>
        </div>
        <div class="modal-body" id="modalBody">
          <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Loading requests...</p>
          </div>
        </div>
      </div>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
    <script>
      const URL_ROOT = '<?php echo URL_ROOT; ?>';

      // Open equipment modal
      document.querySelectorAll('.view-requests-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const caretakerId = this.dataset.caretakerId;
          const caretakerName = this.dataset.caretakerName;
          openEquipmentModal(caretakerId, caretakerName);
        });
      });

      function openEquipmentModal(caretakerId, caretakerName) {
        const modal = document.getElementById('equipmentModal');
        const modalBody = document.getElementById('modalBody');
        const modalCaretakerName = document.getElementById('modalCaretakerName');
        
        modalCaretakerName.textContent = `${caretakerName}'s Equipment Requests`;
        modal.classList.add('active');
        
        // Show loading spinner
        modalBody.innerHTML = `
          <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Loading requests...</p>
          </div>
        `;
        
        // Fetch equipment requests
        fetch(`${URL_ROOT}/supervisor/getCaretakerEquipmentRequests`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `caretaker_id=${caretakerId}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success && data.requests.length > 0) {
            displayRequests(data.requests);
          } else {
            modalBody.innerHTML = `
              <div class="no-requests">
                <span class="material-icons">inventory_2</span>
                <h3>No Equipment Requests</h3>
                <p>This caretaker has no equipment requests.</p>
              </div>
            `;
          }
        })
        .catch(error => {
          console.error('Error:', error);
          modalBody.innerHTML = `
            <div class="no-requests">
              <span class="material-icons">error_outline</span>
              <h3>Error Loading Requests</h3>
              <p>Failed to load equipment requests. Please try again.</p>
            </div>
          `;
        });
      }

      function displayRequests(requests) {
        const modalBody = document.getElementById('modalBody');
        let html = '';
        
        requests.forEach(request => {
          const statusBadge = getStatusBadge(request.status);
          const priorityClass = `priority-${request.priority.toLowerCase()}`;
          const isPending = request.status === 'Pending';
          
          html += `
            <div class="request-card">
              <div class="request-header">
                <div>
                  <div class="request-title">${escapeHtml(request.equipment_name)}</div>
                  <div class="request-date">
                    <span class="material-icons" style="font-size: 14px; vertical-align: middle;">calendar_today</span>
                    Requested on ${formatDate(request.requested_date)}
                  </div>
                </div>
                <div style="text-align: right;">
                  <span class="request-priority ${priorityClass}">${request.priority}</span>
                  <div style="margin-top: 8px;">${statusBadge}</div>
                </div>
              </div>
              
              <div class="request-details">
                <div class="detail-item">
                  <span class="detail-label">Quantity</span>
                  <span class="detail-value">${request.quantity}</span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Estimated Cost</span>
                  <span class="detail-value">Rs. ${parseFloat(request.estimated_cost).toFixed(2)}</span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Total Estimated</span>
                  <span class="detail-value">Rs. ${(request.quantity * parseFloat(request.estimated_cost)).toFixed(2)}</span>
                </div>
              </div>
              
              <div class="request-reason">
                <label>Reason</label>
                <p>${escapeHtml(request.reason)}</p>
              </div>
              
              ${isPending ? `
                <div class="request-actions">
                  <button class="approve-btn" onclick="approveRequest(${request.id}, this)">
                    <span class="material-icons">check_circle</span>
                    Approve Request
                  </button>
                  <button class="reject-btn" onclick="rejectRequest(${request.id}, this)">
                    <span class="material-icons">cancel</span>
                    Reject Request
                  </button>
                </div>
              ` : ''}
            </div>
          `;
        });
        
        modalBody.innerHTML = html;
      }

      function getStatusBadge(status) {
        const statusMap = {
          'Pending': '<span class="status-badge" style="background: #fff8e1; color: #f57f17;">Pending</span>',
          'Supervisor Approved': '<span class="status-badge" style="background: #e3f2fd; color: #1976d2;">Supervisor Approved</span>',
          'Approved': '<span class="status-badge status-active">Approved</span>',
          'Rejected': '<span class="status-badge status-inactive">Rejected</span>'
        };
        return statusMap[status] || status;
      }

      function approveRequest(requestId, button) {
        button.disabled = true;
        button.innerHTML = '<span class="material-icons">hourglass_empty</span> Processing...';
        
        console.log('Approving request ID:', requestId);
        
        fetch(`${URL_ROOT}/supervisor/approveEquipmentRequest`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `request_id=${requestId}&supervisor_notes=`
        })
        .then(response => {
          console.log('Response status:', response.status);
          return response.text().then(text => {
            console.log('Raw response:', text);
            try {
              return JSON.parse(text);
            } catch (e) {
              console.error('JSON parse error:', e);
              console.error('Response text:', text);
              throw new Error('Server returned invalid response. Check console for details.');
            }
          });
        })
        .then(data => {
          console.log('Response data:', data);
          if (data.success) {
            showToast('success', 'Success', 'Equipment request approved successfully!');
            setTimeout(() => {
              closeEquipmentModal();
              location.reload();
            }, 1500);
          } else {
            showToast('error', 'Error', data.message || 'Failed to approve request');
            button.disabled = false;
            button.innerHTML = '<span class="material-icons">check_circle</span> Approve Request';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('error', 'Error', error.message || 'Failed to approve request. Please try again.');
          button.disabled = false;
          button.innerHTML = '<span class="material-icons">check_circle</span> Approve Request';
        });
      }

      function rejectRequest(requestId, button) {
        // Find the request card and add an input field for rejection reason
        const requestCard = button.closest('.request-card');
        const existingInput = requestCard.querySelector('.rejection-input-container');
        
        if (existingInput) {
          // If already showing, validate and submit
          const reasonInput = requestCard.querySelector('.rejection-reason-input');
          const reason = reasonInput.value.trim();
          
          if (!reason) {
            showToast('warning', 'Required', 'Rejection reason is required.');
            reasonInput.focus();
            return;
          }
          
          // Proceed with rejection
          button.disabled = true;
          button.innerHTML = '<span class="material-icons">hourglass_empty</span> Processing...';
          
          fetch(`${URL_ROOT}/supervisor/rejectEquipmentRequest`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `request_id=${requestId}&rejection_reason=${encodeURIComponent(reason)}`
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              showToast('success', 'Rejected', 'Equipment request has been rejected.');
              setTimeout(() => {
                closeEquipmentModal();
                location.reload();
              }, 1500);
            } else {
              showToast('error', 'Error', data.message);
              button.disabled = false;
              button.innerHTML = '<span class="material-icons">cancel</span> Reject Request';
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Error', 'Failed to reject request. Please try again.');
            button.disabled = false;
            button.innerHTML = '<span class="material-icons">cancel</span> Reject Request';
          });
        } else {
          // Show input field for rejection reason
          const actionsContainer = requestCard.querySelector('.request-actions');
          const inputContainer = document.createElement('div');
          inputContainer.className = 'rejection-input-container';
          inputContainer.innerHTML = `
            <div style="background: #fff3e0; padding: 16px; border-radius: 8px; margin-bottom: 12px; border-left: 4px solid #ff9800;">
              <label style="display: block; font-size: 13px; font-weight: 600; color: #f57f17; margin-bottom: 8px;">
                <span class="material-icons" style="font-size: 16px; vertical-align: middle;">info</span>
                Please provide a reason for rejection:
              </label>
              <textarea class="rejection-reason-input" 
                        placeholder="Enter rejection reason..."
                        rows="3"
                        style="width: 100%; padding: 10px; border: 2px solid #ffb74d; border-radius: 6px; font-size: 14px; font-family: inherit; resize: vertical;">
              </textarea>
            </div>
          `;
          
          actionsContainer.insertBefore(inputContainer, actionsContainer.firstChild);
          
          // Update button text
          button.innerHTML = '<span class="material-icons">send</span> Submit Rejection';
          
          // Focus on the input
          setTimeout(() => {
            requestCard.querySelector('.rejection-reason-input').focus();
          }, 100);
        }
      }

      function closeEquipmentModal() {
        document.getElementById('equipmentModal').classList.remove('active');
      }

      // Close modal when clicking outside
      document.getElementById('equipmentModal').addEventListener('click', function(e) {
        if (e.target === this) {
          closeEquipmentModal();
        }
      });

      function formatDate(dateString) {
        const date = new Date(dateString);
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return date.toLocaleDateString('en-US', options);
      }

      function escapeHtml(text) {
        const map = {
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
      }

      function showToast(type, title, message) {
        const iconMap = {
          'success': 'check_circle',
          'error': 'error',
          'warning': 'warning'
        };
        
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
          <span class="material-icons">${iconMap[type]}</span>
          <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
          </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
          toast.style.animation = 'slideOutRight 0.3s ease';
          setTimeout(() => {
            document.body.removeChild(toast);
          }, 300);
        }, 3000);
      }
    </script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>