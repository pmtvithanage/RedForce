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

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>