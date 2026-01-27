<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<style>
    :root{
      --bg: #f0f2f5;
      --card: #fff;
      --muted: #606770;
      --accent: #a40000;
      --accent-plain: #e7f0ff;
      --container-padding: 18px;
      --shadow: 0 6px 18px rgba(20,20,40,0.06);
      --glass: rgba(255,255,255,0.8);
    }

    /* COVER + PROFILE */
    .cover {
      width: calc(100% - 24px);
      margin: 12px;
      height: 240px;
      background-size: cover;
      background-position: center;
      background: #ffff;
      border-radius: var(--radius);
      position: relative;
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .site-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      position: absolute;
      top: 0;
      left: 0;
      z-index: 0;
    }

    .cover > button,
    .cover > div {
      position: relative;
      z-index: 1;
    }

    .left-column, .right-column {
      align-self: start;
    }

    .center-column{
      background: transparent;
    }

    /* Profile card (left) */
    .profile-card{
      background: #fff;
      border-radius: 20px;
      padding: 18px;
      box-shadow: var(--shadow);
      position: relative;
      margin:12px;
    }

    .avatar-wrap{
      position: absolute;
      top: -64px;
      left: 22px;
      display:flex;
      align-items:center;
      gap:14px;
    }

    .avatar-image {
      height: 128px;
      border-radius: 12px;
      object-fit:cover;
      border:2px solid var(--card);
      background: #eee;
      box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }

    .profile-info{
      margin-left: 160px;
    }
    .profile-name{
      font-size: 20px;
      font-weight:700;
      margin: 12px 0 4px 0;
    }
    .profile-meta{
      color:var(--muted);
      font-size: 14px;
    }

    .btn {
      display:inline-flex;
      align-items:center;
      gap:8px;
      background: var(--accent);
      color:#fff;
      border:none;
      padding:8px 12px;
      border-radius: 8px;
      font-weight:600;
      cursor:pointer;
      font-size:14px;
    }
    .btn.secondary {
      background: #fff;
      color: #111827;
      border:1px solid #e6e6e6;
      box-shadow:none;
      font-weight:600;
    }

    .tertiary-btn{
      background: #fff;
    }

    /* Duty Points Cards Styles */
    .duty-points-section {
      padding: 0 12px;
      margin-top: 24px;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .section-title {
      font-size: 22px;
      font-weight: 700;
      color: #1a1a1a;
    }

    .add-duty-btn {
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .duty-cards-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
    }

    .duty-card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: var(--shadow);
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .duty-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(20,20,40,0.12);
    }

    .duty-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
      padding-bottom: 12px;
      border-bottom: 1px solid #f0f0f0;
    }

    .duty-location {
      font-size: 18px;
      font-weight: 700;
      color: #1a1a1a;
    }

    .duty-status {
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .status-active {
      background: #e7f7ef;
      color: #0a8f4e;
    }

    .status-inactive {
      background: #fef2f2;
      color: #dc2626;
    }

    .supervisor-info {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 12px 0;
      padding: 10px;
      background: #f8f9fa;
      border-radius: 8px;
      border-left: 3px solid #a40000;
    }

    .supervisor-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
    }

    .supervisor-details {
      flex: 1;
    }

    .supervisor-name {
      font-weight: 600;
      font-size: 14px;
    }

    .supervisor-role {
      font-size: 12px;
      color: var(--muted);
    }

    .duty-shifts {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .shift {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 12px;
    }

    .shift-header {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 8px;
    }

    .shift-icon {
      font-size: 16px;
    }

    .shift-title {
      font-weight: 600;
      font-size: 14px;
    }

    .officer-list {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .officer-item {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 4px 0;
    }

    .officer-avatar {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      object-fit: cover;
    }

    .officer-name {
      font-size: 13px;
    }

    .duty-card-footer {
      display: flex;
      justify-content: flex-end;
      margin-top: 16px;
      padding-top: 12px;
      border-top: 1px solid #f0f0f0;
    }

    .action-btn {
      background: transparent;
      border: none;
      color: #a40000;
      font-weight: 600;
      cursor: pointer;
      padding: 6px 12px;
      border-radius: 6px;
      transition: background 0.2s;
    }

    .action-btn:hover {
      background: #ffededff;
    }

    .empty-state {
      grid-column: 1 / -1;
      text-align: center;
      padding: 40px 20px;
      color: var(--muted);
    }

    .empty-icon {
      font-size: 48px;
      margin-bottom: 16px;
      opacity: 0.5;
    }

    .empty-text {
      font-size: 16px;
    }

    /* Tab styles */
    .tab-btn.active {
      color: var(--accent) !important;
      border-bottom-color: var(--accent) !important;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
</style>

<div class="shell" role="main">

    <!-- COVER -->
    <div class="cover" aria-hidden="true">
      <button class="tertiary-btn" style="display:flex; width:100px; margin:20px;align-items:center;" onClick="window.location.href='<?php echo URL_ROOT; ?>/admin/clientprofile/<?php echo $data['client']->id; ?>'"> 
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
            Back
        </button>
      <img class="site-img" src="<?php echo URL_ROOT; ?>/uploads/siteImages/<?php echo $data['site']->image; ?>" alt="Cover Image"> 
      <div style="position:absolute;right:12px;bottom:12px">
        <button class="tertiary-btn" onClick="window.location.href='<?php echo URL_ROOT; ?>/admin/editSite/<?php echo $data['site']->id; ?>'">Edit Site</button>
        <button class="primary-btn" style="float: right; margin-left: 10px; padding: 12px 20px;" onClick="window.location.href='<?php echo URL_ROOT; ?>/admin/deleteSite/<?php echo $data['site']->id; ?>'">Remove Site</button>
      </div>
    </div>

    <!-- MAIN GRID -->
    <div class="profile-main" style="margin-top:18px;">
      <!-- LEFT: Profile summary -->
      <div class="left-column">
        <div class="profile-card" aria-label="Profile summary">
          <div class="avatar-wrap">
            <img class="avatar-image" src="<?php echo URL_ROOT; ?>/uploads/clientLogos/<?php echo $data['client']->client_profile; ?>" alt="Client Logo">
          </div>

          <div style="height:86px"></div> <!-- spacer to accommodate absolute avatar -->
          


          <h1><?php echo $site->site_name?></h1>
          <p><strong>Location:</strong> <?php echo $site->address?></p>
          <?php if(!empty($site->district)): ?>
          <p><strong>District:</strong> <?php echo $site->district?></p>
          <?php endif; ?>
          <p><strong>City:</strong> <?php echo $site->city?></p>
          <p><strong>Phone Number:</strong> <?php echo $site->phone_number?></p>
          <p><strong>Last Updated:</strong> <?php echo time_convert($site->updated_at)?> </p>

        </div>
      </div>
    </div>

    <!-- ASSIGN OFFICERS SECTION -->
    <div class="duty-points-section">
      <div class="section-header">
        <h2 class="section-title">Officer Management</h2>
      </div>

      <!-- Tab Navigation -->
      <div style="background: white; border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; margin-bottom: 24px;">
        <div class="tab-navigation" style="display: flex; border-bottom: 2px solid #f0f0f0;">
          <button class="tab-btn active" onclick="switchTab('assigned')" id="assignedTab" style="flex: 1; padding: 16px 24px; background: transparent; border: none; font-weight: 600; font-size: 15px; color: #666; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s;">
            <span class="material-symbols-outlined" style="font-size:20px; vertical-align: middle; margin-right: 8px;">badge</span>
            Currently Assigned (<?php echo count($data['assigned_officers']); ?>)
          </button>
          <button class="tab-btn" onclick="switchTab('available')" id="availableTab" style="flex: 1; padding: 16px 24px; background: transparent; border: none; font-weight: 600; font-size: 15px; color: #666; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s;">
            <span class="material-symbols-outlined" style="font-size:20px; vertical-align: middle; margin-right: 8px;">person_search</span>
            Find Officers
          </button>
        </div>

        <!-- Tab Content: Currently Assigned Officers -->
        <div id="assignedContent" class="tab-content" style="padding: 24px;">
          <?php if(empty($data['assigned_officers'])): ?>
            <div style="text-align: center; padding: 60px 20px; color: #666;">
              <span class="material-symbols-outlined" style="font-size: 64px; color: #ddd;">badge</span>
              <h3 style="margin: 16px 0 8px; font-size: 18px; font-weight: 600;">No Officers Assigned</h3>
              <p style="color: #999; margin-bottom: 24px;">This site currently has no officers assigned to it.</p>
              <button class="secondary-btn" onclick="switchTab('available')" style="padding: 10px 24px;">
                <span class="material-symbols-outlined" style="font-size:18px; vertical-align: middle;">add</span>
                Assign Officers
              </button>
            </div>
          <?php else: ?>
            <div class="duty-cards-container" style="margin-top: 0;">
              <?php foreach($data['assigned_officers'] as $officer): ?>
                <div class="duty-card" style="border-left: 4px solid #4caf50;">
                  <div class="duty-card-header">
                    <div>
                      <h3 class="duty-location"><?php echo htmlspecialchars($officer->name); ?></h3>
                      <p style="margin: 5px 0; color: #666; font-size: 14px;">
                        <?php echo htmlspecialchars($officer->officerID); ?> • 
                        <?php echo htmlspecialchars($officer->city); ?>
                      </p>
                    </div>
                    <?php 
                      $shift = $officer->shift_type ?? 'Full Time';
                      $badgeColor = match($shift) {
                        'Day' => '#4caf50',
                        'Night' => '#2196f3',
                        'Full Time' => '#9c27b0',
                        'Flexible' => '#ff9800',
                        default => '#4caf50'
                      };
                    ?>
                    <span class="duty-status" style="background-color: <?php echo $badgeColor; ?>; color: white; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                      <?php echo htmlspecialchars($shift); ?>
                    </span>
                  </div>
                  <div style="padding: 15px; background: #fafafa; border-radius: 8px; margin: 12px 0;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                      <div>
                        <p style="font-size: 12px; color: #999; margin-bottom: 4px;">Start Date</p>
                        <p style="font-weight: 600; font-size: 14px;"><?php echo date('M d, Y', strtotime($officer->assignment_start)); ?></p>
                      </div>
                      <?php if($officer->assignment_end): ?>
                        <div>
                          <p style="font-size: 12px; color: #999; margin-bottom: 4px;">End Date</p>
                          <p style="font-weight: 600; font-size: 14px;"><?php echo date('M d, Y', strtotime($officer->assignment_end)); ?></p>
                        </div>
                      <?php endif; ?>
                    </div>
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e0e0e0;">
                      <p style="font-size: 12px; color: #999; margin-bottom: 4px;">Contact</p>
                      <p style="font-weight: 600; font-size: 14px;">
                        <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle; color: #4caf50;">phone</span>
                        <?php echo htmlspecialchars($officer->phone_number); ?>
                      </p>
                    </div>
                  </div>
                  <div class="duty-card-footer">
                    <button class="action-btn" onclick="unassignOfficer(<?php echo $officer->assignment_id; ?>)">
                      <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">person_remove</span>
                      Unassign
                    </button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Tab Content: Find Officers -->
        <div id="availableContent" class="tab-content" style="display: none; padding: 24px;">
          <!-- Filter Section -->
          <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #e9ecef;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
              <h3 style="margin: 0; color: #333; font-size: 16px; font-weight: 600;">
                <span class="material-symbols-outlined" style="font-size:20px; vertical-align: middle; margin-right: 8px; color: var(--accent);">filter_alt</span>
                Filter Officers
              </h3>
              <button id="resetFilters" onclick="resetFilters()" style="padding: 6px 16px; background: transparent; color: #666; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;">
                <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">refresh</span>
                Reset
              </button>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 16px;">
              <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: #555;">
                  <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle; margin-right: 4px;">map</span>
                  District
                </label>
                <select id="districtFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                  <option value="same-district">Same District (<?php echo htmlspecialchars($data['site']->district ?? 'N/A'); ?>)</option>
                  <option value="all">All Districts</option>
                </select>
              </div>
              <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: #555;">
                  <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle; margin-right: 4px;">location_city</span>
                  City
                </label>
                <select id="cityFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                  <option value="same-city">Same City (<?php echo htmlspecialchars($data['site']->city); ?>)</option>
                  <option value="all">All Cities</option>
                </select>
              </div>
              <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: #555;">
                  <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle; margin-right: 4px;">event_available</span>
                  Availability
                </label>
                <select id="availabilityFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                  <option value="available">Available Only</option>
                  <option value="assigned">Currently Assigned</option>
                  <option value="all">All Officers</option>
                </select>
              </div>
              <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: #555;">
                  <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle; margin-right: 4px;">work</span>
                  Employment Status
                </label>
                <select id="statusFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                  <option value="Active">Active</option>
                  <option value="On Leave">On Leave</option>
                  <option value="all">All Status</option>
                </select>
              </div>
            </div>
            <button id="applyFilters" onclick="loadOfficers()" style="width: 100%; padding: 12px; background: var(--accent); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">
              <span class="material-symbols-outlined" style="font-size:18px; vertical-align: middle; margin-right: 6px;">search</span>
              Search Officers
            </button>
          </div>

          <!-- Officers List -->
          <div id="officersList" class="duty-cards-container">
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #666;">
              <span class="material-symbols-outlined" style="font-size: 64px; color: #ddd;">person_search</span>
              <h3 style="margin: 16px 0 8px; font-size: 18px; font-weight: 600;">Search for Officers</h3>
              <p style="color: #999;">Use the filters above to find officers available for assignment</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
</div>

<!-- Shift Selection Modal -->
<div id="shiftModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
  <div style="background: white; border-radius: 12px; padding: 30px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
    <h3 style="margin: 0 0 10px 0; color: #333;">Assign Officer to Site</h3>
    <p id="officerNameDisplay" style="color: #666; margin-bottom: 20px; font-size: 14px;"></p>
    
    <div style="margin-bottom: 20px;">
      <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Select Shift Type:</label>
      <select id="shiftTypeSelect" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
        <option value="Day">Day Shift</option>
        <option value="Night">Night Shift</option>
        <option value="Full Time">Full Time</option>
        <option value="Flexible">Flexible</option>
      </select>
    </div>
    
    <div style="display: flex; gap: 10px; justify-content: flex-end;">
      <button onclick="closeShiftModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px;">
        Cancel
      </button>
      <button onclick="confirmAssignment()" style="padding: 10px 20px; background: #a40000; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px;">
        <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">check</span>
        Confirm Assignment
      </button>
    </div>
  </div>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script>
// Filter and assign officer functionality
const siteId = <?php echo $data['site']->id; ?>;
const siteCity = '<?php echo addslashes($data['site']->city); ?>';
const siteDistrict = '<?php echo addslashes($data['site']->district ?? ''); ?>';
const urlRoot = '<?php echo URL_ROOT; ?>';

// Tab switching functionality
function switchTab(tab) {
    const assignedTab = document.getElementById('assignedTab');
    const availableTab = document.getElementById('availableTab');
    const assignedContent = document.getElementById('assignedContent');
    const availableContent = document.getElementById('availableContent');
    
    if (tab === 'assigned') {
        assignedTab.classList.add('active');
        availableTab.classList.remove('active');
        assignedTab.style.color = '#a40000';
        assignedTab.style.borderBottomColor = '#a40000';
        availableTab.style.color = '#666';
        availableTab.style.borderBottomColor = 'transparent';
        assignedContent.style.display = 'block';
        availableContent.style.display = 'none';
    } else {
        availableTab.classList.add('active');
        assignedTab.classList.remove('active');
        availableTab.style.color = '#a40000';
        availableTab.style.borderBottomColor = '#a40000';
        assignedTab.style.color = '#666';
        assignedTab.style.borderBottomColor = 'transparent';
        availableContent.style.display = 'block';
        assignedContent.style.display = 'none';
    }
}

// Initialize first tab as active
document.addEventListener('DOMContentLoaded', function() {
    switchTab('assigned');
});

function loadOfficers() {
    const districtFilter = document.getElementById('districtFilter').value;
    const cityFilter = document.getElementById('cityFilter').value;
    const availability = document.getElementById('availabilityFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    // Show loading
    document.getElementById('officersList').innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 60px;"><div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #a40000; border-radius: 50%; animation: spin 1s linear infinite;"></div><p style="margin-top: 16px; color: #666;">Loading officers...</p></div>';
    
    // Make AJAX call to fetch officers
    fetch(urlRoot + '/admin/getAvailableOfficers', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            site_id: siteId,
            district_filter: districtFilter,
            city_filter: cityFilter,
            availability: availability,
            status: status,
            district: siteDistrict,
            city: siteCity
        })
    })
    .then(response => response.json())
    .then(data => {
        displayOfficers(data.officers);
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('officersList').innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #dc2626;"><span class="material-symbols-outlined" style="font-size: 64px;">error</span><p style="margin-top: 16px; font-weight: 600;">Error loading officers</p></div>';
    });
}

function displayOfficers(officers) {
    const container = document.getElementById('officersList');
    
    if (officers.length === 0) {
        container.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #666;"><span class="material-symbols-outlined" style="font-size: 64px; color: #ddd;">person_off</span><h3 style="margin: 16px 0 8px; font-size: 18px; font-weight: 600;">No Officers Found</h3><p style="color: #999;">No officers match your current filter criteria</p></div>';
        return;
    }
    
    let html = '';
    officers.forEach(officer => {
        const isAssigned = officer.current_assignment;
        html += `
            <div class="duty-card" style="border-left: 4px solid ${isAssigned ? '#ff9800' : '#2196F3'};">
                <div class="duty-card-header">
                    <div>
                        <h3 class="duty-location">${officer.name}</h3>
                        <p style="margin: 5px 0; color: #666; font-size: 14px;">
                            <span class="material-symbols-outlined" style="font-size:14px; vertical-align: middle;">badge</span>
                            ${officer.officerID} • 
                            <span class="material-symbols-outlined" style="font-size:14px; vertical-align: middle;">location_on</span>
                            ${officer.city}, ${officer.district}
                        </p>
                    </div>
                    <span class="duty-status ${officer.employment_status === 'Active' ? 'status-active' : 'status-inactive'}">${officer.employment_status}</span>
                </div>
                <div style="background: #fafafa; border-radius: 8px; padding: 16px; margin: 12px 0;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                        <div>
                            <p style="font-size: 12px; color: #999; margin-bottom: 4px;">Rank</p>
                            <p style="font-weight: 600; font-size: 14px;">${officer.rank}</p>
                        </div>
                        <div>
                            <p style="font-size: 12px; color: #999; margin-bottom: 4px;">Shift Pattern</p>
                            <p style="font-weight: 600; font-size: 14px;">${officer.shift_pattern || 'Flexible'}</p>
                        </div>
                    </div>
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e0e0e0;">
                        <p style="font-size: 12px; color: #999; margin-bottom: 4px;">Contact</p>
                        <p style="font-weight: 600; font-size: 14px;">
                            <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle; color: #4caf50;">phone</span>
                            ${officer.phone_number}
                        </p>
                    </div>
                    ${isAssigned ? '<div style="margin-top: 12px; padding: 8px 12px; background: #fff3e0; border-radius: 6px; border-left: 3px solid #ff9800;"><p style="margin: 0; font-size: 13px; color: #e65100; font-weight: 600;"><span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">info</span> Currently assigned to another site</p></div>' : ''}
                </div>
                <div class="duty-card-footer">
                    <button class="action-btn" onclick="openShiftModal(${officer.user_id}, '${officer.name.replace(/'/g, "\\'")}')" ${isAssigned ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''}>
                        <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">person_add</span>
                        Assign to Site
                    </button>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function resetFilters() {
    document.getElementById('districtFilter').value = 'same-district';
    document.getElementById('cityFilter').value = 'same-city';
    document.getElementById('availabilityFilter').value = 'available';
    document.getElementById('statusFilter').value = 'Active';
    document.getElementById('officersList').innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #666;"><span class="material-symbols-outlined" style="font-size: 64px; color: #ddd;">person_search</span><h3 style="margin: 16px 0 8px; font-size: 18px; font-weight: 600;">Search for Officers</h3><p style="color: #999;">Use the filters above to find officers available for assignment</p></div>';
}

// Global variable to store officer ID for assignment
let selectedOfficerId = null;

function openShiftModal(officerId, officerName) {
    selectedOfficerId = officerId;
    document.getElementById('officerNameDisplay').textContent = `Officer: ${officerName}`;
    document.getElementById('shiftModal').style.display = 'flex';
}

function closeShiftModal() {
    selectedOfficerId = null;
    document.getElementById('shiftModal').style.display = 'none';
    document.getElementById('shiftTypeSelect').value = 'Day';
}

function confirmAssignment() {
    const shiftType = document.getElementById('shiftTypeSelect').value;
    assignOfficer(selectedOfficerId, shiftType);
    closeShiftModal();
}

function assignOfficer(officerId, shiftType) {
    fetch(urlRoot + '/admin/assignOfficerToSite', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            site_id: siteId,
            officer_id: officerId,
            shift_type: shiftType
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Officer assigned successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error assigning officer');
    });
}

function unassignOfficer(assignmentId) {
    if (!confirm('Are you sure you want to unassign this officer from this site?')) {
        return;
    }
    
    fetch(urlRoot + '/admin/unassignOfficerFromSite', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            assignment_id: assignmentId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Officer unassigned successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error unassigning officer');
    });
}
</script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
```