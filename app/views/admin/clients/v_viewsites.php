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
          <p><strong>City:</strong> <?php echo $site->city?></p>
          <p><strong>Phone Number:</strong> <?php echo $site->phone_number?></p>
          <p><strong>Last Updated:</strong> <?php echo time_convert($site->updated_at)?> </p>

        </div>
      </div>
    </div>

    <!-- ASSIGN OFFICERS SECTION -->
    <div class="duty-points-section">
      <div class="section-header">
        <h2 class="section-title">Assign Officers</h2>
        <button class="secondary-btn add-duty-btn" id="assignOfficerBtn">
          <span class="material-symbols-outlined" style="font-size:18px;">add</span>
          Assign Officer
        </button>
      </div>

      <!-- Filter Section -->
      <div class="filter-section" style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 15px; color: #333;">Filter Officers</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
          <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Location</label>
            <select id="locationFilter" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
              <option value="same-city">Same City (<?php echo htmlspecialchars($data['site']->city); ?>)</option>
              <option value="same-district">Same District</option>
              <option value="all">All Locations</option>
            </select>
          </div>
          <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Availability</label>
            <select id="availabilityFilter" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
              <option value="available">Available Only</option>
              <option value="assigned">Currently Assigned</option>
              <option value="all">All Officers</option>
            </select>
          </div>
          <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Employment Status</label>
            <select id="statusFilter" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
              <option value="Active">Active</option>
              <option value="On Leave">On Leave</option>
              <option value="all">All Status</option>
            </select>
          </div>
        </div>
        <div style="margin-top: 15px;">
          <button id="applyFilters" class="secondary-btn" style="padding: 8px 20px;">
            <span class="material-symbols-outlined" style="font-size:18px; vertical-align: middle;">filter_alt</span>
            Apply Filters
          </button>
          <button id="resetFilters" class="secondary-btn" style="padding: 8px 20px; margin-left: 10px; background: #6c757d;">
            <span class="material-symbols-outlined" style="font-size:18px; vertical-align: middle;">refresh</span>
            Reset
          </button>
        </div>
      </div>

      <!-- Officers List -->
      <div id="officersList" class="duty-cards-container">
        <!-- Officers will be loaded here via AJAX -->
        <div style="text-align: center; padding: 40px; color: #666;">
          <span class="material-symbols-outlined" style="font-size: 48px;">person_search</span>
          <p style="margin-top: 10px;">Click "Apply Filters" to load officers</p>
        </div>
      </div>

      <!-- Currently Assigned Officers -->
      <div style="margin-top: 40px;">
        <h3 style="margin-bottom: 20px; color: #333;">Currently Assigned Officers</h3>
        <div id="assignedOfficersList" class="duty-cards-container">
          <!-- Assigned officers will be loaded here -->
          <?php if(empty($data['assigned_officers'])): ?>
            <div style="text-align: center; padding: 40px; color: #666; background: white; border-radius: 8px;">
              <span class="material-symbols-outlined" style="font-size: 48px;">badge</span>
              <p style="margin-top: 10px;">No officers assigned to this site yet</p>
            </div>
          <?php else: ?>
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
                  <span class="duty-status status-active"><?php echo htmlspecialchars($officer->shift_type ?? 'Full Time'); ?></span>
                </div>
                <div style="padding: 15px;">
                  <p><strong>Start Date:</strong> <?php echo date('M d, Y', strtotime($officer->assignment_start)); ?></p>
                  <?php if($officer->assignment_end): ?>
                    <p><strong>End Date:</strong> <?php echo date('M d, Y', strtotime($officer->assignment_end)); ?></p>
                  <?php endif; ?>
                  <p><strong>Phone:</strong> <?php echo htmlspecialchars($officer->phone_number); ?></p>
                </div>
                <div class="duty-card-footer">
                  <button class="action-btn" onclick="unassignOfficer(<?php echo $officer->assignment_id; ?>)">
                    <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">person_remove</span>
                    Unassign
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script>
// Filter and assign officer functionality
const siteId = <?php echo $data['site']->id; ?>;
const siteCity = '<?php echo addslashes($data['site']->city); ?>';
const urlRoot = '<?php echo URL_ROOT; ?>';

document.getElementById('applyFilters').addEventListener('click', loadOfficers);
document.getElementById('resetFilters').addEventListener('click', resetFilters);

function loadOfficers() {
    const location = document.getElementById('locationFilter').value;
    const availability = document.getElementById('availabilityFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    // Show loading
    document.getElementById('officersList').innerHTML = '<div style="text-align: center; padding: 40px;"><p>Loading officers...</p></div>';
    
    // Make AJAX call to fetch officers
    fetch(urlRoot + '/admin/getAvailableOfficers', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            site_id: siteId,
            location: location,
            availability: availability,
            status: status,
            city: siteCity
        })
    })
    .then(response => response.json())
    .then(data => {
        displayOfficers(data.officers);
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('officersList').innerHTML = '<div style="text-align: center; padding: 40px; color: red;">Error loading officers</div>';
    });
}

function displayOfficers(officers) {
    const container = document.getElementById('officersList');
    
    if (officers.length === 0) {
        container.innerHTML = '<div style="text-align: center; padding: 40px; color: #666;"><span class="material-symbols-outlined" style="font-size: 48px;">person_off</span><p style="margin-top: 10px;">No officers found matching the filters</p></div>';
        return;
    }
    
    let html = '';
    officers.forEach(officer => {
        html += `
            <div class="duty-card" style="border-left: 4px solid #2196F3;">
                <div class="duty-card-header">
                    <div>
                        <h3 class="duty-location">${officer.name}</h3>
                        <p style="margin: 5px 0; color: #666; font-size: 14px;">
                            ${officer.officerID} • ${officer.city}, ${officer.district}
                        </p>
                    </div>
                    <span class="duty-status ${officer.employment_status === 'Active' ? 'status-active' : 'status-inactive'}">${officer.employment_status}</span>
                </div>
                <div style="padding: 15px;">
                    <p><strong>Rank:</strong> ${officer.rank}</p>
                    <p><strong>Phone:</strong> ${officer.phone_number}</p>
                    <p><strong>Shift Pattern:</strong> ${officer.shift_pattern || 'Flexible'}</p>
                    ${officer.current_assignment ? '<p style="color: #ff9800;"><strong>Currently assigned to another site</strong></p>' : ''}
                </div>
                <div class="duty-card-footer">
                    <button class="action-btn" onclick="assignOfficer(${officer.user_id})" ${officer.current_assignment ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''}>
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
    document.getElementById('locationFilter').value = 'same-city';
    document.getElementById('availabilityFilter').value = 'available';
    document.getElementById('statusFilter').value = 'Active';
    document.getElementById('officersList').innerHTML = '<div style="text-align: center; padding: 40px; color: #666;"><span class="material-symbols-outlined" style="font-size: 48px;">person_search</span><p style="margin-top: 10px;">Click "Apply Filters" to load officers</p></div>';
}

function assignOfficer(officerId) {
    if (!confirm('Are you sure you want to assign this officer to this site?')) {
        return;
    }
    
    fetch(urlRoot + '/admin/assignOfficerToSite', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            site_id: siteId,
            officer_id: officerId
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