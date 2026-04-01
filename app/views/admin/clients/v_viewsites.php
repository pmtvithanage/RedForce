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
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      border-radius: 20px;
      padding: 28px;
      box-shadow: 0 8px 24px rgba(20,20,40,0.08), 0 2px 8px rgba(0,0,0,0.04);
      position: relative;
      margin: 12px;
      border: 1px solid rgba(0,0,0,0.05);
      transition: all 0.3s ease;
    }

    .profile-card:hover {
      box-shadow: 0 12px 32px rgba(20,20,40,0.12), 0 4px 12px rgba(0,0,0,0.06);
      transform: translateY(-2px);
    }

    .profile-card h1 {
      color: #1a1a1a;
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 16px;
      padding-bottom: 12px;
      border-bottom: 2px solid #f0f0f0;
    }

    .profile-card p {
      color: #4a5568;
      font-size: 15px;
      line-height: 1.8;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
    }

    .profile-card p strong {
      color: var(--accent);
      font-weight: 600;
      min-width: 140px;
      display: inline-block;
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

    .schedule-section {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: var(--shadow);
      border: 1px solid rgba(0,0,0,0.05);
      padding: 24px;
    }

    .schedule-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 24px;
    }

    .calendar-panel {
      background: #ffffff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .schedule-title {
      margin: 0 0 20px 0;
      font-size: 40px;
      font-weight: 700;
      color: #a40000;
    }

    .calendar-toolbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
      background: #f8f9fa;
      border-radius: 8px;
      padding: 10px 15px;
    }

    .month-label {
      font-size: 36px;
      font-weight: 700;
      line-height: 1;
      color: #1f2937;
      text-align: center;
    }

    .nav-btn {
      width: 38px;
      height: 38px;
      border: none;
      border-radius: 999px;
      background: var(--accent);
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
    }

    .nav-btn:hover {
      background: #b50000;
      transform: scale(1.05);
    }

    .nav-btn .material-symbols-outlined {
      font-size: 20px;
    }

    .weekdays,
    .calendar-body {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
    }

    .weekdays {
      background: #f8f9fa;
      border-radius: 8px;
      margin-bottom: 10px;
    }

    .weekday {
      text-align: center;
      font-weight: 600;
      font-size: 14px;
      color: #666;
      padding: 15px 10px;
      border-right: 1px solid #e9ecef;
    }

    .weekday:last-child {
      border-right: none;
    }

    .calendar-day {
      min-height: 80px;
      background: #ffffff;
      border: 0;
      border-right: 1px solid #e9ecef;
      border-bottom: 1px solid #e9ecef;
      border-radius: 0;
      padding: 8px;
      font-size: 14px;
      font-weight: 400;
      color: #2f2f2f;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: flex-start;
      justify-content: flex-start;
      position: relative;
    }

    .calendar-day:hover {
      background: #f8fbff;
      transform: scale(1.02);
    }

    .calendar-day.other-month {
      color: #c3c7cc;
      background: #f8f9fa;
      font-weight: 400;
      cursor: default;
    }

    .calendar-day.other-month:hover {
      background: #f8f9fa;
      transform: scale(1);
    }

    .calendar-day.today {
      background: #e8f4ff;
      box-shadow: inset 0 0 0 2px #2196f3;
      font-weight: 700;
    }

    .calendar-day.selected {
      background: #cfe3f5;
      box-shadow: inset 0 0 0 2px #2196f3;
    }

    .calendar-day.service-period {
      background: #e7f7ef;
      box-shadow: inset 0 0 0 2px #41a863;
      color: #166534;
      font-weight: 700;
    }

    .calendar-day.service-period.selected {
      background: #d7f0e1;
      box-shadow: inset 0 0 0 2px #2f9e56;
    }

    .day-number {
      font-weight: 600;
      font-size: 14px;
      color: inherit;
    }

    .schedule-panel {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }

    .schedule-panel h3 {
      margin: 0;
      color: #333;
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 5px;
    }

    .selected-date {
      font-size: 14px;
      color: #666;
      margin: 0;
      margin-bottom: 16px;
    }

    .schedule-separator {
      border: 0;
      border-top: 1px solid #f0f0f0;
      margin: 0 0 18px;
    }

    .schedule-empty {
      text-align: center;
      padding: 40px 20px;
      color: #999;
    }

    .schedule-empty .material-symbols-outlined {
      font-size: 48px;
      color: #ddd;
      display: block;
      margin-bottom: 12px;
    }

    @media (max-width: 1024px) {
      .schedule-grid {
        grid-template-columns: 1fr;
      }
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    @keyframes progressBar {
      0% { width: 0%; }
      100% { width: 100%; }
    }
</style>

<div class="shell" role="main">

    <?php if ($data['site']->is_draft == 1): ?>
    <!-- Draft Site Notice -->
    <div style="margin: 12px; padding: 16px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 8px;">
      <h3 style="margin: 0 0 8px 0; color: #856404; font-size: 18px;">
        <span class="material-symbols-outlined" style="vertical-align: middle;">info</span>
        Draft Site - Review in Progress
      </h3>
      <p style="margin: 0; color: #856404;">
        This is a temporary draft site. You must assign exactly <strong><?php echo $data['package_request']->number_of_guards ?? 0; ?> officer(s)</strong> before you can approve and create the official site.
        <?php 
          $assignedCount = count($data['assigned_officers'] ?? []);
          $requiredCount = $data['package_request']->number_of_guards ?? 0;
        ?>
        Currently assigned: <strong><?php echo $assignedCount; ?>/<?php echo $requiredCount; ?></strong>
      </p>
    </div>
    <?php endif; ?>

    <!-- COVER -->
    <div class="cover" aria-hidden="true">
      <button class="tertiary-btn" style="display:flex; width:100px; margin:20px;align-items:center;" onClick="window.location.href='<?php echo URL_ROOT; ?>/admin/clientprofile/<?php echo $data['client']->id; ?>'"> 
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
            Back
        </button>
      <img class="site-img" src="<?php echo URL_ROOT; ?>/uploads/siteImages/<?php echo $data['site']->image; ?>" alt="Cover Image"> 
      <div style="position:absolute;right:12px;bottom:12px">
        <?php if ($data['site']->is_draft == 0): ?>
          <!-- Normal site - show Edit/Remove buttons -->
          <button class="tertiary-btn" onClick="window.location.href='<?php echo URL_ROOT; ?>/admin/editSite/<?php echo $data['site']->id; ?>'">Edit Site</button>
          <button class="primary-btn" style="float: right; margin-left: 10px; padding: 12px 20px;" onClick="window.location.href='<?php echo URL_ROOT; ?>/admin/deleteSite/<?php echo $data['site']->id; ?>'">Remove Site</button>
        <?php endif; ?>
      </div>
    </div>

    <!-- MAIN GRID -->
    <div class="profile-main" style="margin-top:18px;">
      <div class="profile-card" aria-label="Profile summary" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: start;">
        <!-- LEFT: Profile Info -->
        <div>
          <div class="avatar-wrap">
            <?php if (!empty($data['client']->client_profile)): ?>
              <img class="avatar-image" src="<?php echo URL_ROOT; ?>/uploads/clientLogos/<?php echo $data['client']->client_profile; ?>" alt="Client Logo">
            <?php else: ?>
              <div class="avatar-image" style="display:flex;align-items:center;justify-content:center;background:#eee;color:#666;">No Logo</div>
            <?php endif; ?>
          </div>

          <div style="height:86px"></div> <!-- spacer to accommodate absolute avatar -->
          
          <h1><?php echo $site->site_name?></h1>
          <p><strong>Location:</strong> <?php echo $site->address?></p>
          <?php if(!empty($site->district)): ?>
          <p><strong>District:</strong> <?php echo $site->district?></p>
          <?php endif; ?>
          <p><strong>City:</strong> <?php echo $site->city?></p>
          <p><strong>Phone Number:</strong> <?php echo $site->phone_number?></p>
          <?php if(!empty($data['package_request'])): ?>
          <p><strong>Package:</strong> <?php echo $data['package_request']->package_name?></p>
          <p><strong>Officers Requested:</strong> <?php echo $data['package_request']->number_of_guards?></p>
          <p><strong>Service Period:</strong> <?php echo date('d M Y', strtotime($data['package_request']->start_date))?> - <?php echo date('d M Y', strtotime($data['package_request']->end_date))?></p>
          <?php endif; ?>
          <p><strong>Last Updated:</strong> <?php echo time_convert($site->updated_at)?> </p>
        </div>

        <!-- RIGHT: Site Statistics Chart -->
        <div style="background: #fff; border-radius: 12px; box-shadow: var(--shadow); padding: 20px;">
          <canvas id="siteStatsChart" style="max-height: 280px;"></canvas>
        </div>
      </div>
    </div>

    <!-- Calendar Section -->
    <div style="margin: 24px 12px;">
      <div class="schedule-section">
        <h2 class="schedule-title">Site Schedule Calendar</h2>
        <div class="schedule-grid">
          <div class="calendar-panel">
            <div class="calendar-toolbar">
              <button class="nav-btn" id="prevMonth" type="button" aria-label="Previous month">
                <span class="material-symbols-outlined">chevron_left</span>
              </button>
              <div class="month-label" id="currentMonthYear">April 2026</div>
              <button class="nav-btn" id="nextMonth" type="button" aria-label="Next month">
                <span class="material-symbols-outlined">chevron_right</span>
              </button>
            </div>

            <div class="weekdays">
              <div class="weekday">MON</div>
              <div class="weekday">TUE</div>
              <div class="weekday">WED</div>
              <div class="weekday">THU</div>
              <div class="weekday">FRI</div>
              <div class="weekday">SAT</div>
              <div class="weekday">SUN</div>
            </div>

            <div class="calendar-body" id="calendarBody"></div>
          </div>

          <div class="schedule-panel">
            <h3>Schedule Details</h3>
            <div class="selected-date" id="selectedDate">Select a date</div>
            <hr class="schedule-separator">

            <div id="shiftContent" class="schedule-empty">
              <span class="material-symbols-outlined">calendar_month</span>
              <p>Click on a date to view details</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ASSIGN OFFICERS SECTION -->
    <div class="duty-points-section">
      <div class="section-header">
        <h2 class="section-title">Officer Assignment</h2>
      </div>

      <!-- Tab Navigation -->
      <div style="background: white; border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; margin-bottom: 24px;">
        <div class="tab-navigation" style="display: flex; border-bottom: 2px solid #f0f0f0;">
          <button class="tab-btn active" onclick="switchTab('assigned')" id="assignedTab" style="flex: 1; padding: 16px 24px; background: transparent; border: none; font-weight: 600; font-size: 15px; color: #666; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s;">
            <span class="material-symbols-outlined" style="font-size:20px; vertical-align: middle; margin-right: 8px;">badge</span>
            Currently Assigned (<?php echo count($data['assigned_officers']) + count($data['assigned_caretakers']); ?>)
          </button>
          <button class="tab-btn" onclick="switchTab('available')" id="availableTab" style="flex: 1; padding: 16px 24px; background: transparent; border: none; font-weight: 600; font-size: 15px; color: #666; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s;">
            <span class="material-symbols-outlined" style="font-size:20px; vertical-align: middle; margin-right: 8px;">person_search</span>
            Find Officers
          </button>
          <button class="tab-btn" onclick="switchTab('supervisor')" id="supervisorTab" style="flex: 1; padding: 16px 24px; background: transparent; border: none; font-weight: 600; font-size: 15px; color: #666; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s;">
            <span class="material-symbols-outlined" style="font-size:20px; vertical-align: middle; margin-right: 8px;">supervisor_account</span>
            Find Supervisor
          </button>
          <button class="tab-btn" onclick="switchTab('caretaker')" id="caretakerTab" style="flex: 1; padding: 16px 24px; background: transparent; border: none; font-weight: 600; font-size: 15px; color: #666; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s;">
            <span class="material-symbols-outlined" style="font-size:20px; vertical-align: middle; margin-right: 8px;">person_check</span>
            Find Caretaker
          </button>
        </div>

        <!-- Tab Content: Currently Assigned Officers -->
        <div id="assignedContent" class="tab-content" style="padding: 24px;">
          <?php if(empty($data['assigned_officers']) && empty($data['assigned_caretakers'])): ?>
            <div style="text-align: center; padding: 60px 20px; color: #666;">
              <span class="material-symbols-outlined" style="font-size: 64px; color: #ddd;">badge</span>
              <h3 style="margin: 16px 0 8px; font-size: 18px; font-weight: 600;">No Staff Assigned</h3>
              <p style="color: #999; margin-bottom: 24px;">This site currently has no officers or caretakers assigned to it.</p>
              <button class="secondary-btn" onclick="switchTab('available')" style="padding: 10px 24px;">
                <span class="material-symbols-outlined" style="font-size:18px; vertical-align: middle;">add</span>
                Assign Staff
              </button>
            </div>
          <?php else: ?>
            <div class="duty-cards-container" style="margin-top: 0;">
              <?php if(!empty($data['assigned_officers'])): ?>
                <?php foreach($data['assigned_officers'] as $officer): ?>
                  <div class="duty-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fffe 100%); border: 1px solid #e8f5e9; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.08);">
                    <div class="duty-card-header">
                      <div style="display: flex; align-items: center; gap: 12px;">
                        <?php if(!empty($officer->profile_image)): ?>
                          <img src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo $officer->profile_image; ?>" 
                               alt="<?php echo htmlspecialchars($officer->name); ?>" 
                               style="width: 48px; height: 48px; border-radius: 12px; object-fit: cover; border: 2px solid #c8e6c9;">
                        <?php else: ?>
                          <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 2px solid #c8e6c9;">
                            <span style="font-weight: 700; font-size: 18px; color: #4caf50;"><?php echo strtoupper(substr($officer->name, 0, 1)); ?></span>
                          </div>
                        <?php endif; ?>
                        <div>
                          <h3 class="duty-location"><?php echo htmlspecialchars($officer->name); ?></h3>
                          <p style="margin: 5px 0; color: #666; font-size: 14px;">
                            <?php echo htmlspecialchars($officer->officerID); ?> • 
                            <?php echo htmlspecialchars($officer->city); ?>
                          </p>
                        </div>
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
                      <button class="action-btn" onclick="unassignOfficer(<?php echo $officer->assignment_id; ?>, '<?php echo addslashes($officer->name); ?>')">
                        <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">person_remove</span>
                        Unassign
                      </button>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>

              <?php if(!empty($data['assigned_caretakers'])): ?>
                <?php foreach($data['assigned_caretakers'] as $caretaker): ?>
                  <div class="duty-card" style="background: linear-gradient(135deg, #ffffff 0%, #f5f9ff 100%); border: 1px solid #e3f2fd; box-shadow: 0 4px 12px rgba(33, 150, 243, 0.08);">
                    <div class="duty-card-header">
                      <div style="display: flex; align-items: center; gap: 12px;">
                        <?php if(!empty($caretaker->profile_image)): ?>
                          <img src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo $caretaker->profile_image; ?>" 
                               alt="<?php echo htmlspecialchars($caretaker->name); ?>" 
                               style="width: 48px; height: 48px; border-radius: 12px; object-fit: cover; border: 2px solid #bbdefb;">
                        <?php else: ?>
                          <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 2px solid #bbdefb;">
                            <span style="font-weight: 700; font-size: 18px; color: #2196f3;"><?php echo strtoupper(substr($caretaker->name, 0, 1)); ?></span>
                          </div>
                        <?php endif; ?>
                        <div>
                          <h3 class="duty-location"><?php echo htmlspecialchars($caretaker->name); ?></h3>
                          <p style="margin: 5px 0; color: #666; font-size: 14px;">
                            <?php echo htmlspecialchars($caretaker->caretakerID); ?> • 
                            <?php echo htmlspecialchars($caretaker->city); ?>
                          </p>
                        </div>
                      </div>
                      <span class="duty-status" style="background-color: #2196f3; color: white; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                        Caretaker
                      </span>
                    </div>
                    <div style="padding: 15px; background: #fafafa; border-radius: 8px; margin: 12px 0;">
                      <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                        <div>
                          <p style="font-size: 12px; color: #999; margin-bottom: 4px;">Start Date</p>
                          <p style="font-weight: 600; font-size: 14px;"><?php echo date('M d, Y', strtotime($caretaker->assignment_start)); ?></p>
                        </div>
                        <?php if($caretaker->assignment_end): ?>
                          <div>
                            <p style="font-size: 12px; color: #999; margin-bottom: 4px;">End Date</p>
                            <p style="font-weight: 600; font-size: 14px;"><?php echo date('M d, Y', strtotime($caretaker->assignment_end)); ?></p>
                          </div>
                        <?php endif; ?>
                      </div>
                      <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e0e0e0;">
                        <p style="font-size: 12px; color: #999; margin-bottom: 4px;">Contact</p>
                        <p style="font-weight: 600; font-size: 14px;">
                          <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle; color: #4caf50;">phone</span>
                          <?php echo htmlspecialchars($caretaker->phone_number); ?>
                        </p>
                      </div>
                    </div>
                    <div class="duty-card-footer">
                      <button class="action-btn" onclick="unassignCaretaker(<?php echo $caretaker->assignment_id; ?>, '<?php echo addslashes($caretaker->name); ?>')">
                        <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">person_remove</span>
                        Unassign
                      </button>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
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

        <!-- Tab Content: Find Supervisor -->
        <div id="supervisorContent" class="tab-content" style="display: none; padding: 24px;">
          <!-- Filter Section -->
          <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #e9ecef;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
              <h3 style="margin: 0; color: #333; font-size: 16px; font-weight: 600;">
                <span class="material-symbols-outlined" style="font-size:20px; vertical-align: middle; margin-right: 8px; color: var(--accent);">filter_alt</span>
                Filter Supervisors
              </h3>
              <button onclick="resetSupervisorFilters()" style="padding: 6px 16px; background: transparent; color: #666; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;">
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
                <select id="supervisorDistrictFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                  <option value="same-district">Same District (<?php echo htmlspecialchars($data['site']->district ?? 'N/A'); ?>)</option>
                  <option value="all">All Districts</option>
                </select>
              </div>
              <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: #555;">
                  <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle; margin-right: 4px;">location_city</span>
                  City
                </label>
                <select id="supervisorCityFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                  <option value="same-city">Same City (<?php echo htmlspecialchars($data['site']->city); ?>)</option>
                  <option value="all">All Cities</option>
                </select>
              </div>
              <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: #555;">
                  <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle; margin-right: 4px;">event_available</span>
                  Availability
                </label>
                <select id="supervisorAvailabilityFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                  <option value="available">Available Only</option>
                  <option value="all">All Supervisors</option>
                </select>
              </div>
            </div>
            <button onclick="loadSupervisors()" style="width: 100%; padding: 12px; background: var(--accent); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">
              <span class="material-symbols-outlined" style="font-size:18px; vertical-align: middle; margin-right: 6px;">search</span>
              Search Supervisors
            </button>
          </div>

          <!-- Supervisors List -->
          <div id="supervisorsList" class="duty-cards-container">
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #666;">
              <span class="material-symbols-outlined" style="font-size: 64px; color: #ddd;">supervisor_account</span>
              <h3 style="margin: 16px 0 8px; font-size: 18px; font-weight: 600;">Search for Supervisors</h3>
              <p style="color: #999;">Use the filters above to find supervisors available for assignment</p>
            </div>
          </div>
        </div>

        <!-- Tab Content: Find Caretaker -->
        <div id="caretakerContent" class="tab-content" style="display: none; padding: 24px;">
          <!-- Filter Section -->
          <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #e9ecef;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
              <h3 style="margin: 0; color: #333; font-size: 16px; font-weight: 600;">
                <span class="material-symbols-outlined" style="font-size:20px; vertical-align: middle; margin-right: 8px; color: var(--accent);">filter_alt</span>
                Filter Caretakers
              </h3>
              <button onclick="resetCaretakerFilters()" style="padding: 6px 16px; background: transparent; color: #666; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600;">
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
                <select id="caretakerDistrictFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                  <option value="same-district">Same District (<?php echo htmlspecialchars($data['site']->district ?? 'N/A'); ?>)</option>
                  <option value="all">All Districts</option>
                </select>
              </div>
              <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: #555;">
                  <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle; margin-right: 4px;">location_city</span>
                  City
                </label>
                <select id="caretakerCityFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                  <option value="same-city">Same City (<?php echo htmlspecialchars($data['site']->city); ?>)</option>
                  <option value="all">All Cities</option>
                </select>
              </div>
              <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: #555;">
                  <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle; margin-right: 4px;">event_available</span>
                  Availability
                </label>
                <select id="caretakerAvailabilityFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                  <option value="available">Available Only</option>
                  <option value="all">All Caretakers</option>
                </select>
              </div>
            </div>
            <button onclick="loadCaretakers()" style="width: 100%; padding: 12px; background: var(--accent); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">
              <span class="material-symbols-outlined" style="font-size:18px; vertical-align: middle; margin-right: 6px;">search</span>
              Search Caretakers
            </button>
          </div>

          <!-- Caretakers List -->
          <div id="caretakersList" class="duty-cards-container">
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #666;">
              <span class="material-symbols-outlined" style="font-size: 64px; color: #ddd;">person_check</span>
              <h3 style="margin: 16px 0 8px; font-size: 18px; font-weight: 600;">Search for Caretakers</h3>
              <p style="color: #999;">Use the filters above to find caretakers available for assignment</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Draft Site Action Buttons (after officer assignment section) -->
    <?php if ($data['site']->is_draft == 1): ?>
    <div style="margin: 24px 12px; padding: 20px; background: white; border-radius: 12px; box-shadow: var(--shadow);">
      <div style="margin-bottom: 16px;">
        <h3 style="margin: 0 0 8px 0; color: #333; font-size: 18px;">
          <span class="material-symbols-outlined" style="vertical-align: middle; color: #a40000;">task_alt</span>
          Review & Approval
        </h3>
        <?php 
          $assignedCount = count($data['assigned_officers'] ?? []);
          $requiredCount = $data['package_request']->number_of_guards ?? 0;
          $canApprove = ($assignedCount == $requiredCount);
        ?>
        <p style="margin: 0; color: #666; font-size: 14px;">
          Officers assigned: <strong style="color: <?php echo $canApprove ? '#28a745' : '#dc3545'; ?>"><?php echo $assignedCount; ?>/<?php echo $requiredCount; ?></strong>
          <?php if (!$canApprove): ?>
            - Assign exactly <?php echo $requiredCount; ?> officer(s) to approve this request.
          <?php else: ?>
            - All required officers assigned. You can now approve or reject this request.
          <?php endif; ?>
        </p>
      </div>
      <div style="display: flex; gap: 12px;">
        <?php if ($canApprove): ?>
          <button class="primary-btn" style="padding: 14px 24px; background: #28a745; flex: 1;" 
                  onclick="openApproveModal()">
            <span class="material-symbols-outlined" style="font-size:18px; vertical-align: middle;">check_circle</span>
            Approve & Create Site
          </button>
        <?php else: ?>
          <button class="secondary-btn" style="padding: 14px 24px; opacity: 0.6; cursor: not-allowed; flex: 1;" disabled title="Assign exactly <?php echo $requiredCount; ?> officer(s) to approve">
            <span class="material-symbols-outlined" style="font-size:18px; vertical-align: middle;">check_circle</span>
            Approve (Requires <?php echo $requiredCount; ?> officers)
          </button>
        <?php endif; ?>
        <button class="tertiary-btn" style="padding: 14px 24px; flex: 1;" 
                onclick="openRejectModal()">
          <span class="material-symbols-outlined" style="font-size:18px; vertical-align: middle;">cancel</span>
          Reject Request
        </button>
      </div>
    </div>
    <?php endif; ?>
    
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

<!-- Supervisor Assignment Modal -->
<div id="supervisorModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
  <div style="background: white; border-radius: 12px; padding: 30px; max-width: 450px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
    <div style="text-align: center; margin-bottom: 20px;">
      <div style="width: 64px; height: 64px; background: #f3e5f5; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
        <span class="material-symbols-outlined" style="font-size: 32px; color: #a40000;">supervisor_account</span>
      </div>
      <h3 style="margin: 0 0 8px 0; color: #333; font-size: 20px;">Assign Supervisor</h3>
      <p id="supervisorNameDisplay" style="color: #666; margin: 0; font-size: 15px; font-weight: 500;"></p>
    </div>
    
    <div style="background: #f9fafb; border-left: 4px solid #a40000; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
      <p style="margin: 0; font-size: 14px; color: #555; line-height: 1.6;">
        <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle; color: #a40000;">info</span>
        This supervisor will be assigned to oversee and manage operations at this site.
      </p>
    </div>
    
    <div style="display: flex; gap: 12px; justify-content: flex-end;">
      <button onclick="closeSupervisorModal()" style="padding: 12px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
        Cancel
      </button>
      <button onclick="confirmSupervisorAssignment()" style="padding: 12px 24px; background: #a40000; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#a40000'" onmouseout="this.style.background='#a40000'">
        <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">check_circle</span>
        Confirm Assignment
      </button>
    </div>
  </div>
</div>

<!-- Approve Confirmation Modal -->
<div id="approveModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
  <div style="background: white; border-radius: 12px; padding: 30px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
    <div style="text-align: center; margin-bottom: 20px;">
      <div style="width: 64px; height: 64px; background: #d4edda; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
        <span class="material-symbols-outlined" style="font-size: 32px; color: #28a745;">check_circle</span>
      </div>
      <h3 style="margin: 0 0 8px 0; color: #333; font-size: 20px;">Approve Package Request?</h3>
      <p style="color: #666; margin: 0; font-size: 14px;">Create the official site and activate assignments</p>
    </div>
    
    <div style="background: #f0f8ff; border-left: 4px solid #28a745; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
      <p style="margin: 0 0 12px 0; font-size: 14px; color: #333; font-weight: 600;">This action will:</p>
      <ul style="margin: 0; padding-left: 20px; font-size: 14px; color: #555; line-height: 1.8;">
        <li>Convert the draft to an official site</li>
        <li>Notify the client of approval</li>
        <li>Activate all officer assignments</li>
      </ul>
    </div>
    
    <div style="display: flex; gap: 12px; justify-content: flex-end;">
      <button onclick="closeApproveModal()" style="padding: 12px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">
        Cancel
      </button>
      <button onclick="confirmApprove()" style="padding: 12px 24px; background: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">
        <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">check_circle</span>
        Approve & Create
      </button>
    </div>
  </div>
</div>

<!-- Reject Confirmation Modal -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
  <div style="background: white; border-radius: 12px; padding: 30px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
    <div style="text-align: center; margin-bottom: 20px;">
      <div style="width: 64px; height: 64px; background: #f8d7da; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
        <span class="material-symbols-outlined" style="font-size: 32px; color: #dc3545;">cancel</span>
      </div>
      <h3 style="margin: 0 0 8px 0; color: #333; font-size: 20px;">Reject Package Request?</h3>
      <p style="color: #666; margin: 0; font-size: 14px;">This action cannot be undone</p>
    </div>
    
    <div style="background: #fff3cd; border-left: 4px solid #dc3545; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
      <p style="margin: 0 0 12px 0; font-size: 14px; color: #333; font-weight: 600;">⚠️ This will:</p>
      <ul style="margin: 0; padding-left: 20px; font-size: 14px; color: #555; line-height: 1.8;">
        <li>Delete the draft site permanently</li>
        <li>Remove all officer assignments</li>
        <li>Notify the client of rejection</li>
      </ul>
    </div>
    
    <div style="display: flex; gap: 12px; justify-content: flex-end;">
      <button onclick="closeRejectModal()" style="padding: 12px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">
        Cancel
      </button>
      <button onclick="confirmReject()" style="padding: 12px 24px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">
        <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">delete</span>
        Reject Request
      </button>
    </div>
  </div>
</div>

<!-- Caretaker Assignment Modal -->
<div id="caretakerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
  <div style="background: white; border-radius: 12px; padding: 30px; max-width: 450px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
    <div style="text-align: center; margin-bottom: 20px;">
      <div style="width: 64px; height: 64px; background: #e3f2fd; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
        <span class="material-symbols-outlined" style="font-size: 32px; color: #a40000;">person_check</span>
      </div>
      <h3 style="margin: 0 0 8px 0; color: #333; font-size: 20px;">Assign Caretaker</h3>
      <p id="caretakerNameDisplay" style="color: #666; margin: 0; font-size: 15px; font-weight: 500;"></p>
    </div>
    
    <div style="background: #f9fafb; border-left: 4px solid #a40000; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
      <p style="margin: 0; font-size: 14px; color: #555; line-height: 1.6;">
        <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle; color: #a40000;">info</span>
        This caretaker will manage equipment requests and officer attendance at this site.
      </p>
    </div>
    
    <div style="display: flex; gap: 12px; justify-content: flex-end;">
      <button onclick="closeCaretakerModal()" style="padding: 12px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
        Cancel
      </button>
      <button onclick="confirmCaretakerAssignment()" style="padding: 12px 24px; background: #a40000; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#8b0000'" onmouseout="this.style.background='#a40000'">
        <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">check_circle</span>
        Confirm Assignment
      </button>
    </div>
  </div>
</div>

<div class="backdrop" id="backdrop" hidden></div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

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
    const supervisorTab = document.getElementById('supervisorTab');
    const caretakerTab = document.getElementById('caretakerTab');
    const assignedContent = document.getElementById('assignedContent');
    const availableContent = document.getElementById('availableContent');
    const supervisorContent = document.getElementById('supervisorContent');
    const caretakerContent = document.getElementById('caretakerContent');
    
    // Reset all tabs
    [assignedTab, availableTab, supervisorTab, caretakerTab].forEach(t => {
        t.classList.remove('active');
        t.style.color = '#666';
        t.style.borderBottomColor = 'transparent';
    });
    
    // Hide all content
    assignedContent.style.display = 'none';
    availableContent.style.display = 'none';
    supervisorContent.style.display = 'none';
    caretakerContent.style.display = 'none';
    
    // Show selected tab
    if (tab === 'assigned') {
        assignedTab.classList.add('active');
        assignedTab.style.color = '#a40000';
        assignedTab.style.borderBottomColor = '#a40000';
        assignedContent.style.display = 'block';
    } else if (tab === 'available') {
        availableTab.classList.add('active');
        availableTab.style.color = '#a40000';
        availableTab.style.borderBottomColor = '#a40000';
        availableContent.style.display = 'block';
    } else if (tab === 'supervisor') {
        supervisorTab.classList.add('active');
        supervisorTab.style.color = '#a40000';
        supervisorTab.style.borderBottomColor = '#a40000';
        supervisorContent.style.display = 'block';
    } else if (tab === 'caretaker') {
        caretakerTab.classList.add('active');
        caretakerTab.style.color = '#a40000';
        caretakerTab.style.borderBottomColor = '#a40000';
        caretakerContent.style.display = 'block';
    }
}

// Initialize first tab as active
document.addEventListener('DOMContentLoaded', function() {
    switchTab('assigned');
    initializeSiteChart();
});

// Approve/Reject Modal Functions
function openApproveModal() {
    const modal = document.getElementById('approveModal');
    modal.style.display = 'flex';
}

function closeApproveModal() {
    const modal = document.getElementById('approveModal');
    modal.style.display = 'none';
}

function confirmApprove() {
    window.location.href = '<?php echo URL_ROOT; ?>/admin/approveDraftSite/<?php echo $data['site']->id; ?>';
}

function openRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.style.display = 'flex';
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.style.display = 'none';
}

function confirmReject() {
    window.location.href = '<?php echo URL_ROOT; ?>/admin/rejectDraftSite/<?php echo $data['site']->id; ?>';
}

// Initialize Site Statistics Chart
function initializeSiteChart() {
    const ctx = document.getElementById('siteStatsChart');
    if (!ctx) return;

    <?php 
    $regularOfficersCount = 0;
    $supervisorsCount = 0;
    foreach($data['assigned_officers'] as $officer) {
        if($officer->shift_type === 'Supervisor') {
            $supervisorsCount++;
        } else {
            $regularOfficersCount++;
        }
    }
    $caretakersCount = count($data['assigned_caretakers']);
    ?>

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Officers', 'Supervisors', 'Caretakers'],
            datasets: [{
                label: 'Assigned Staff',
                data: [<?php echo $regularOfficersCount; ?>, <?php echo $supervisorsCount; ?>, <?php echo $caretakersCount; ?>],
                backgroundColor: [
                    'rgba(76, 175, 80, 0.85)',  // Green for officers
                    'rgba(156, 39, 176, 0.85)',  // Purple for supervisors
                    'rgba(33, 150, 243, 0.85)'   // Blue for caretakers
                ],
                borderColor: [
                    'rgba(76, 175, 80, 1)',
                    'rgba(156, 39, 176, 1)',
                    'rgba(33, 150, 243, 1)'
                ],
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12,
                            weight: '600'
                        },
                        color: '#666'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 13,
                            weight: '600'
                        },
                        color: '#333'
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.85)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: '700'
                    },
                    bodyFont: {
                        size: 13,
                        weight: '600'
                    },
                    borderColor: 'rgba(255, 255, 255, 0.2)',
                    borderWidth: 1,
                    displayColors: true,
                    boxWidth: 8,
                    boxHeight: 8,
                    boxPadding: 4,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed.y;
                            if (context.parsed.y === 1) {
                                label += ' person';
                            } else {
                                label += ' people';
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
}

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
        const cardStyle = isAssigned 
            ? 'background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%); border: 1px solid #ffe0b2; box-shadow: 0 4px 12px rgba(255, 152, 0, 0.08);'
            : 'background: linear-gradient(135deg, #ffffff 0%, #f8fffe 100%); border: 1px solid #e8f5e9; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.08);';
        const avatarBorder = isAssigned ? '#ffe0b2' : '#c8e6c9';
        const avatarBg = isAssigned 
            ? 'background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);'
            : 'background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);';
        const avatarColor = isAssigned ? '#ff9800' : '#4caf50';
        
        const profileImg = officer.profile_image 
            ? `<img src="${urlRoot}/uploads/applicantPhotos/${officer.profile_image}" alt="${officer.name}" style="width: 48px; height: 48px; border-radius: 12px; object-fit: cover; border: 2px solid ${avatarBorder};">`
            : `<div style="width: 48px; height: 48px; ${avatarBg} border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 2px solid ${avatarBorder};"><span style="font-weight: 700; font-size: 18px; color: ${avatarColor};">${officer.name.charAt(0).toUpperCase()}</span></div>`;
        
        html += `
            <div class="duty-card" style="${cardStyle}">
                <div class="duty-card-header">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        ${profileImg}
                        <div>
                            <h3 class="duty-location">${officer.name}</h3>
                            <p style="margin: 5px 0; color: #666; font-size: 14px;">
                                ${officer.officerID} • ${officer.city}, ${officer.district}
                            </p>
                        </div>
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
                    ${isAssigned ? '<div style="margin-top: 12px; padding: 8px 12px; background: #fff3e0; border-radius: 6px;"><p style="margin: 0; font-size: 13px; color: #e65100; font-weight: 600;"><span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">info</span> Currently assigned to another site</p></div>' : ''}
                </div>
                <div class="duty-card-footer">
                    ${isAssigned ? 
                        '<button class="action-btn" disabled style="opacity: 0.5; cursor: not-allowed; background: #e0e0e0; color: #999;"><span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">block</span> Already Assigned</button>' :
                        `<button class="action-btn" onclick="openShiftModal(${officer.user_id}, '${officer.name.replace(/'/g, "\\'")}')"><span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">person_add</span> Assign to Site</button>`
                    }
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
let selectedOfficerName = null;

function openShiftModal(officerId, officerName) {
    selectedOfficerId = officerId;
    selectedOfficerName = officerName;
    document.getElementById('officerNameDisplay').textContent = `Officer: ${officerName}`;
    document.getElementById('shiftModal').style.display = 'flex';
}

function closeShiftModal() {
    selectedOfficerId = null;
    selectedOfficerName = null;
    document.getElementById('shiftModal').style.display = 'none';
    document.getElementById('shiftTypeSelect').value = 'Day';
}

function confirmAssignment() {
    const shiftType = document.getElementById('shiftTypeSelect').value;
    assignOfficer(selectedOfficerId, shiftType, selectedOfficerName);
    closeShiftModal();
}

function assignOfficer(officerId, shiftType, officerName) {
    // Get service period end date from package request
    const servicePeriodEnd = '<?php echo $data['package_request']->end_date ?? ''; ?>';
    
    // Create a temporary modal to show progress
    const progressModal = document.createElement('div');
    progressModal.style.cssText = 'display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;';
    progressModal.innerHTML = `
        <div style="background: white; border-radius: 12px; padding: 30px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
            <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #a40000; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            <p style="margin-top: 16px; color: #666;">Assigning officer...</p>
        </div>
    `;
    document.body.appendChild(progressModal);
    
    fetch(urlRoot + '/admin/assignOfficerToSite', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            site_id: siteId,
            officer_id: officerId,
            shift_type: shiftType,
            assignment_end: servicePeriodEnd
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            progressModal.innerHTML = `
                <div style="background: white; border-radius: 12px; padding: 50px 40px; max-width: 450px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);">
                        <span class="material-symbols-outlined" style="font-size: 48px; color: #28a745;">check_circle</span>
                    </div>
                    <h3 style="margin: 0 0 12px 0; color: #28a745; font-size: 24px; font-weight: 700;">Assignment Successful!</h3>
                    <p style="color: #666; margin: 0 0 8px 0; font-size: 16px; line-height: 1.5;">
                        <strong style="color: #333;">${officerName}</strong> has been assigned
                    </p>
                    <p style="color: #999; margin: 0 0 4px 0; font-size: 14px;">
                        Shift Type: <strong style="color: #333;">${shiftType}</strong>
                    </p>
                    <p style="color: #999; margin: 0; font-size: 14px;">
                        Redirecting to updated site view...
                    </p>
                    <div style="margin-top: 20px; width: 100%; height: 4px; background: #e9ecef; border-radius: 2px; overflow: hidden;">
                        <div style="height: 100%; background: linear-gradient(90deg, #28a745, #20c997); border-radius: 2px; animation: progressBar 1.5s ease-out;"></div>
                    </div>
                </div>
            `;
            setTimeout(() => location.reload(), 1500);
        } else {
            // Show error message
            progressModal.innerHTML = `
                <div style="background: white; border-radius: 12px; padding: 40px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                    <div style="width: 64px; height: 64px; background: #f8d7da; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <span class="material-symbols-outlined" style="font-size: 32px; color: #dc3545;">error</span>
                    </div>
                    <h3 style="margin: 0 0 8px 0; color: #dc3545; font-size: 20px;">Error</h3>
                    <p style="color: #666; margin: 0 0 20px 0;">${data.message}</p>
                    <button onclick="this.closest('div[style*=z-index]').remove()" style="padding: 10px 24px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">Close</button>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        progressModal.innerHTML = `
            <div style="background: white; border-radius: 12px; padding: 40px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                <div style="width: 64px; height: 64px; background: #f8d7da; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                    <span class="material-symbols-outlined" style="font-size: 32px; color: #dc3545;">error</span>
                </div>
                <h3 style="margin: 0 0 8px 0; color: #dc3545; font-size: 20px;">Error</h3>
                <p style="color: #666; margin: 0 0 20px 0;">Failed to assign officer</p>
                <button onclick="this.closest('div[style*=z-index]').remove()" style="padding: 10px 24px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">Close</button>
            </div>
        `;
    });
}

function unassignOfficer(assignmentId, officerName) {
    // Create confirmation modal
    const confirmModal = document.createElement('div');
    confirmModal.style.cssText = 'display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;';
    confirmModal.innerHTML = `
        <div style="background: white; border-radius: 12px; padding: 30px; max-width: 450px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="width: 64px; height: 64px; background: #fff3e0; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                    <span class="material-symbols-outlined" style="font-size: 32px; color: #ff9800;">warning</span>
                </div>
                <h3 style="margin: 0 0 8px 0; color: #333; font-size: 20px;">Confirm Unassignment</h3>
                <p style="color: #666; margin: 0; font-size: 15px; line-height: 1.5;">
                    Are you sure you want to unassign <strong style="color: #333;">${officerName}</strong> from this site?
                </p>
            </div>
            
            <div style="background: #f9fafb; border-left: 4px solid #ff9800; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                <p style="margin: 0; font-size: 14px; color: #555; line-height: 1.6;">
                    <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle; color: #ff9800;">info</span>
                    This action will remove the officer's current assignment from this site.
                </p>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button onclick="this.closest('div[style*=z-index]').remove()" style="padding: 12px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    Cancel
                </button>
                <button onclick="confirmUnassignment(${assignmentId}, this)" style="padding: 12px 24px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'">
                    <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">person_remove</span>
                    Unassign Officer
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(confirmModal);
}

function confirmUnassignment(assignmentId, buttonElement) {
    const modal = buttonElement.closest('div[style*="z-index"]');
    
    // Show loading
    modal.innerHTML = `
        <div style="background: white; border-radius: 12px; padding: 30px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
            <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #a40000; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            <p style="margin-top: 16px; color: #666;">Unassigning officer...</p>
        </div>
    `;
    
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
            // Show success message
            modal.innerHTML = `
                <div style="background: white; border-radius: 12px; padding: 50px 40px; max-width: 450px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);">
                        <span class="material-symbols-outlined" style="font-size: 48px; color: #28a745;">check_circle</span>
                    </div>
                    <h3 style="margin: 0 0 12px 0; color: #28a745; font-size: 24px; font-weight: 700;">Unassignment Successful!</h3>
                    <p style="color: #666; margin: 0 0 8px 0; font-size: 16px; line-height: 1.5;">
                        Officer has been successfully removed from this site
                    </p>
                    <p style="color: #999; margin: 0; font-size: 14px;">
                        Redirecting to updated site view...
                    </p>
                    <div style="margin-top: 20px; width: 100%; height: 4px; background: #e9ecef; border-radius: 2px; overflow: hidden;">
                        <div style="height: 100%; background: linear-gradient(90deg, #28a745, #20c997); border-radius: 2px; animation: progressBar 1.5s ease-out;"></div>
                    </div>
                </div>
            `;
            setTimeout(() => location.reload(), 1500);
        } else {
            // Show error message
            modal.innerHTML = `
                <div style="background: white; border-radius: 12px; padding: 40px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                    <div style="width: 64px; height: 64px; background: #f8d7da; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <span class="material-symbols-outlined" style="font-size: 32px; color: #dc3545;">error</span>
                    </div>
                    <h3 style="margin: 0 0 8px 0; color: #dc3545; font-size: 20px;">Error</h3>
                    <p style="color: #666; margin: 0 0 20px 0;">${data.message}</p>
                    <button onclick="this.closest('div[style*=z-index]').remove()" style="padding: 10px 24px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">Close</button>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        modal.innerHTML = `
            <div style="background: white; border-radius: 12px; padding: 40px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                <div style="width: 64px; height: 64px; background: #f8d7da; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                    <span class="material-symbols-outlined" style="font-size: 32px; color: #dc3545;">error</span>
                </div>
                <h3 style="margin: 0 0 8px 0; color: #dc3545; font-size: 20px;">Error</h3>
                <p style="color: #666; margin: 0 0 20px 0;">Failed to unassign officer</p>
                <button onclick="this.closest('div[style*=z-index]').remove()" style="padding: 10px 24px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">Close</button>
            </div>
        `;
    });
}

function unassignCaretaker(assignmentId, caretakerName) {
    // Create confirmation modal
    const confirmModal = document.createElement('div');
    confirmModal.style.cssText = 'display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;';
    confirmModal.innerHTML = `
        <div style="background: white; border-radius: 12px; padding: 30px; max-width: 450px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="width: 64px; height: 64px; background: #fff3e0; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                    <span class="material-symbols-outlined" style="font-size: 32px; color: #ff9800;">warning</span>
                </div>
                <h3 style="margin: 0 0 8px 0; color: #333; font-size: 20px;">Confirm Unassignment</h3>
                <p style="color: #666; margin: 0; font-size: 15px; line-height: 1.5;">
                    Are you sure you want to unassign <strong style="color: #333;">${caretakerName}</strong> from this site?
                </p>
            </div>
            
            <div style="background: #f9fafb; border-left: 4px solid #ff9800; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                <p style="margin: 0; font-size: 14px; color: #555; line-height: 1.6;">
                    <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle; color: #ff9800;">info</span>
                    This action will remove the caretaker's current assignment from this site.
                </p>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button onclick="this.closest('div[style*=z-index]').remove()" style="padding: 12px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    Cancel
                </button>
                <button onclick="confirmCaretakerUnassignment(${assignmentId}, this)" style="padding: 12px 24px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'">
                    <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">person_remove</span>
                    Unassign Caretaker
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(confirmModal);
}

function confirmCaretakerUnassignment(assignmentId, buttonElement) {
    const modal = buttonElement.closest('div[style*="z-index"]');
    
    // Show loading
    modal.innerHTML = `
        <div style="background: white; border-radius: 12px; padding: 30px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
            <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #a40000; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            <p style="margin-top: 16px; color: #666;">Unassigning caretaker...</p>
        </div>
    `;
    
    fetch(urlRoot + '/admin/unassignCaretakerFromSite', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            assignment_id: assignmentId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            modal.innerHTML = `
                <div style="background: white; border-radius: 12px; padding: 30px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                    <div style="width: 64px; height: 64px; background: #d4edda; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <span class="material-symbols-outlined" style="font-size: 32px; color: #28a745;">check_circle</span>
                    </div>
                    <h3 style="margin: 0 0 8px 0; color: #28a745; font-size: 20px;">Success!</h3>
                    <p style="color: #666; margin: 0;">Caretaker unassigned successfully. Page will reload...</p>
                </div>
            `;
            setTimeout(() => location.reload(), 1500);
        } else {
            modal.innerHTML = `
                <div style="background: white; border-radius: 12px; padding: 30px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                    <div style="width: 64px; height: 64px; background: #f8d7da; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <span class="material-symbols-outlined" style="font-size: 32px; color: #dc3545;">error</span>
                    </div>
                    <h3 style="margin: 0 0 8px 0; color: #dc3545; font-size: 20px;">Error</h3>
                    <p style="color: #666; margin: 0 0 20px 0;">${data.message || 'Failed to unassign caretaker'}</p>
                    <button onclick="this.closest('div[style*=z-index]').remove()" style="padding: 10px 24px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">Close</button>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        modal.innerHTML = `
            <div style="background: white; border-radius: 12px; padding: 30px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                <div style="width: 64px; height: 64px; background: #f8d7da; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                    <span class="material-symbols-outlined" style="font-size: 32px; color: #dc3545;">error</span>
                </div>
                <h3 style="margin: 0 0 8px 0; color: #dc3545; font-size: 20px;">Error</h3>
                <p style="color: #666; margin: 0 0 20px 0;">Failed to unassign caretaker</p>
                <button onclick="this.closest('div[style*=z-index]').remove()" style="padding: 10px 24px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">Close</button>
            </div>
        `;
    });
}

// Supervisor search functionality
function loadSupervisors() {
    const districtFilter = document.getElementById('supervisorDistrictFilter').value;
    const cityFilter = document.getElementById('supervisorCityFilter').value;
    const availability = document.getElementById('supervisorAvailabilityFilter').value;
    
    // Show loading
    document.getElementById('supervisorsList').innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 60px;"><div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #a40000; border-radius: 50%; animation: spin 1s linear infinite;"></div><p style="margin-top: 16px; color: #666;">Loading supervisors...</p></div>';
    
    // Make AJAX call to fetch supervisors
    fetch(urlRoot + '/admin/getAvailableSupervisors', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            site_id: siteId,
            district_filter: districtFilter,
            city_filter: cityFilter,
            availability: availability,
            district: siteDistrict,
            city: siteCity
        })
    })
    .then(response => response.json())
    .then(data => {
        displaySupervisors(data.supervisors);
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('supervisorsList').innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #dc2626;"><span class="material-symbols-outlined" style="font-size: 64px;">error</span><p style="margin-top: 16px; font-weight: 600;">Error loading supervisors</p></div>';
    });
}

function displaySupervisors(supervisors) {
    const container = document.getElementById('supervisorsList');
    
    if (supervisors.length === 0) {
        container.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #666;"><span class="material-symbols-outlined" style="font-size: 64px; color: #ddd;">person_off</span><h3 style="margin: 16px 0 8px; font-size: 18px; font-weight: 600;">No Supervisors Found</h3><p style="color: #999;">No supervisors match your current filter criteria</p></div>';
        return;
    }
    
    let html = '';
    supervisors.forEach(supervisor => {
        const isAssigned = supervisor.current_assignment_count > 0;
        const cardStyle = isAssigned 
            ? 'background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%); border: 1px solid #ffe0b2; box-shadow: 0 4px 12px rgba(255, 152, 0, 0.08);'
            : 'background: linear-gradient(135deg, #ffffff 0%, #faf5ff 100%); border: 1px solid #e1bee7; box-shadow: 0 4px 12px rgba(156, 39, 176, 0.08);';
        const avatarBorder = isAssigned ? '#ffe0b2' : '#ce93d8';
        const avatarBg = isAssigned 
            ? 'background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);'
            : 'background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);';
        const avatarColor = isAssigned ? '#ff9800' : '#9c27b0';
        
        const profileImg = supervisor.profile_image 
            ? `<img src="${urlRoot}/uploads/applicantPhotos/${supervisor.profile_image}" alt="${supervisor.name}" style="width: 48px; height: 48px; border-radius: 12px; object-fit: cover; border: 2px solid ${avatarBorder};">`
            : `<div style="width: 48px; height: 48px; ${avatarBg} border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 2px solid ${avatarBorder};"><span style="font-weight: 700; font-size: 18px; color: ${avatarColor};">${supervisor.name.charAt(0).toUpperCase()}</span></div>`;
        
        html += `
            <div class="duty-card" style="${cardStyle}">
                <div class="duty-card-header">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        ${profileImg}
                        <div>
                            <h3 class="duty-location">${supervisor.name}</h3>
                            <p style="margin: 5px 0; color: #666; font-size: 14px;">
                                ${supervisor.userID} • ${supervisor.city || 'N/A'}
                            </p>
                        </div>
                    </div>
                    <span class="duty-status" style="background-color: ${isAssigned ? '#ff9800' : '#9c27b0'}; color: white; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                        ${isAssigned ? 'Assigned' : 'Supervisor'}
                    </span>
                </div>
                <div style="background: #fafafa; border-radius: 8px; padding: 16px; margin: 12px 0;">
                    <div style="display: grid; grid-template-columns: 1fr; gap: 12px;">
                        <div>
                            <p style="font-size: 12px; color: #999; margin-bottom: 4px;">Email</p>
                            <p style="font-weight: 600; font-size: 14px;">${supervisor.email || 'N/A'}</p>
                        </div>
                    </div>
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e0e0e0;">
                        <p style="font-size: 12px; color: #999; margin-bottom: 4px;">Contact</p>
                        <p style="font-weight: 600; font-size: 14px;">
                            <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle; color: #4caf50;">phone</span>
                            ${supervisor.contact || 'N/A'}
                        </p>
                    </div>
                    ${isAssigned ? '<div style="margin-top: 12px; padding: 8px 12px; background: #fff3e0; border-radius: 6px;"><p style="margin: 0; font-size: 13px; color: #e65100; font-weight: 600;"><span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">info</span> Currently assigned to another site</p></div>' : ''}
                </div>
                <div class="duty-card-footer">
                    ${isAssigned ? 
                        '<button class="action-btn" disabled style="opacity: 0.5; cursor: not-allowed; background: #e0e0e0; color: #999;"><span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">block</span> Already Assigned</button>' :
                        `<button class="action-btn" onclick="assignSupervisor(${supervisor.id}, '${supervisor.name.replace(/'/g, "\\'")}')"><span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">person_add</span> Assign Supervisor</button>`
                    }
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function resetSupervisorFilters() {
    document.getElementById('supervisorDistrictFilter').value = 'same-district';
    document.getElementById('supervisorCityFilter').value = 'same-city';
    document.getElementById('supervisorAvailabilityFilter').value = 'available';
    document.getElementById('supervisorsList').innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #666;"><span class="material-symbols-outlined" style="font-size: 64px; color: #ddd;">supervisor_account</span><h3 style="margin: 16px 0 8px; font-size: 18px; font-weight: 600;">Search for Supervisors</h3><p style="color: #999;">Use the filters above to find supervisors available for assignment</p></div>';
}

// Global variable for supervisor assignment
let selectedSupervisorId = null;
let selectedSupervisorName = null;

function assignSupervisor(supervisorId, supervisorName) {
    selectedSupervisorId = supervisorId;
    selectedSupervisorName = supervisorName;
    document.getElementById('supervisorNameDisplay').textContent = supervisorName;
    document.getElementById('supervisorModal').style.display = 'flex';
}

function closeSupervisorModal() {
    selectedSupervisorId = null;
    selectedSupervisorName = null;
    document.getElementById('supervisorModal').style.display = 'none';
}

function confirmSupervisorAssignment() {
    if (!selectedSupervisorId) return;
    
    // Show loading state
    const modal = document.getElementById('supervisorModal');
    modal.style.pointerEvents = 'none';
    modal.style.opacity = '0.7';
    
    fetch(urlRoot + '/admin/assignSupervisorToSite', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            site_id: siteId,
            supervisor_id: selectedSupervisorId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reset opacity and show success message
            modal.style.opacity = '1';
            modal.style.pointerEvents = 'auto';
            modal.innerHTML = `
                <div style="background: white; border-radius: 12px; padding: 50px 40px; max-width: 450px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);">
                        <span class="material-symbols-outlined" style="font-size: 48px; color: #28a745;">check_circle</span>
                    </div>
                    <h3 style="margin: 0 0 12px 0; color: #28a745; font-size: 24px; font-weight: 700;">Assignment Successful!</h3>
                    <p style="color: #666; margin: 0 0 8px 0; font-size: 16px; line-height: 1.5;">
                        <strong style="color: #333;">${selectedSupervisorName}</strong> has been assigned as supervisor
                    </p>
                    <p style="color: #999; margin: 0; font-size: 14px;">
                        Redirecting to updated site view...
                    </p>
                    <div style="margin-top: 20px; width: 100%; height: 4px; background: #e9ecef; border-radius: 2px; overflow: hidden;">
                        <div style="height: 100%; background: linear-gradient(90deg, #28a745, #20c997); border-radius: 2px; animation: progressBar 1.5s ease-out;"></div>
                    </div>
                </div>
            `;
            setTimeout(() => location.reload(), 1500);
        } else {
            // Show error message
            modal.style.pointerEvents = 'auto';
            modal.style.opacity = '1';
            modal.innerHTML = `
                <div style="background: white; border-radius: 12px; padding: 40px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                    <div style="width: 64px; height: 64px; background: #f8d7da; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <span class="material-symbols-outlined" style="font-size: 32px; color: #dc3545;">error</span>
                    </div>
                    <h3 style="margin: 0 0 8px 0; color: #dc3545; font-size: 20px;">Error</h3>
                    <p style="color: #666; margin: 0 0 20px 0;">${data.message}</p>
                    <button onclick="closeSupervisorModal()" style="padding: 10px 24px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">Close</button>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        modal.style.pointerEvents = 'auto';
        modal.style.opacity = '1';
        modal.innerHTML = `
            <div style="background: white; border-radius: 12px; padding: 40px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                <div style="width: 64px; height: 64px; background: #f8d7da; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                    <span class="material-symbols-outlined" style="font-size: 32px; color: #dc3545;">error</span>
                </div>
                <h3 style="margin: 0 0 8px 0; color: #dc3545; font-size: 20px;">Error</h3>
                <p style="color: #666; margin: 0 0 20px 0;">Failed to assign supervisor</p>
                <button onclick="closeSupervisorModal()" style="padding: 10px 24px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">Close</button>
            </div>
        `;
    });
}

// Caretaker search functionality
function loadCaretakers() {
    const districtFilter = document.getElementById('caretakerDistrictFilter').value;
    const cityFilter = document.getElementById('caretakerCityFilter').value;
    const availability = document.getElementById('caretakerAvailabilityFilter').value;
    
    // Show loading
    document.getElementById('caretakersList').innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 60px;"><div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #a40000; border-radius: 50%; animation: spin 1s linear infinite;"></div><p style="margin-top: 16px; color: #666;">Loading caretakers...</p></div>';
    
    // Make AJAX call to fetch caretakers
    fetch(urlRoot + '/admin/getAvailableCaretakers', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            site_id: siteId,
            district_filter: districtFilter,
            city_filter: cityFilter,
            availability: availability,
            district: siteDistrict,
            city: siteCity
        })
    })
    .then(response => response.json())
    .then(data => {
        displayCaretakers(data.caretakers);
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('caretakersList').innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #dc2626;"><span class="material-symbols-outlined" style="font-size: 64px;">error</span><p style="margin-top: 16px; font-weight: 600;">Error loading caretakers</p></div>';
    });
}

function displayCaretakers(caretakers) {
    const container = document.getElementById('caretakersList');
    
    if (caretakers.length === 0) {
        container.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #666;"><span class="material-symbols-outlined" style="font-size: 64px; color: #ddd;">person_off</span><h3 style="margin: 16px 0 8px; font-size: 18px; font-weight: 600;">No Caretakers Found</h3><p style="color: #999;">No caretakers match your current filter criteria</p></div>';
        return;
    }
    
    let html = '';
    caretakers.forEach(caretaker => {
        const isAssigned = caretaker.current_assignment_count > 0;
        const cardStyle = isAssigned 
            ? 'background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%); border: 1px solid #ffe0b2; box-shadow: 0 4px 12px rgba(255, 152, 0, 0.08);'
            : 'background: linear-gradient(135deg, #ffffff 0%, #f5f9ff 100%); border: 1px solid #e3f2fd; box-shadow: 0 4px 12px rgba(33, 150, 243, 0.08);';
        const avatarBorder = isAssigned ? '#ffe0b2' : '#bbdefb';
        const avatarBg = isAssigned 
            ? 'background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);'
            : 'background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);';
        const avatarColor = isAssigned ? '#ff9800' : '#2196f3';
        
        const profileImg = caretaker.profile_image 
            ? `<img src="${urlRoot}/uploads/applicantPhotos/${caretaker.profile_image}" alt="${caretaker.name}" style="width: 48px; height: 48px; border-radius: 12px; object-fit: cover; border: 2px solid ${avatarBorder};">`
            : `<div style="width: 48px; height: 48px; ${avatarBg} border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 2px solid ${avatarBorder};"><span style="font-weight: 700; font-size: 18px; color: ${avatarColor};">${caretaker.name.charAt(0).toUpperCase()}</span></div>`;
        
        html += `
            <div class="duty-card" style="${cardStyle}">
                <div class="duty-card-header">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        ${profileImg}
                        <div>
                            <h3 class="duty-location">${caretaker.name}</h3>
                            <p style="margin: 5px 0; color: #666; font-size: 14px;">
                                ${caretaker.userID} • ${caretaker.city || 'N/A'}
                            </p>
                        </div>
                    </div>
                    <span class="duty-status" style="background-color: ${isAssigned ? '#ff9800' : '#2196f3'}; color: white; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                        ${isAssigned ? 'Assigned' : 'Caretaker'}
                    </span>
                </div>
                <div style="background: #fafafa; border-radius: 8px; padding: 16px; margin: 12px 0;">
                    <div style="display: grid; grid-template-columns: 1fr; gap: 12px;">
                        <div>
                            <p style="font-size: 12px; color: #999; margin-bottom: 4px;">Email</p>
                            <p style="font-weight: 600; font-size: 14px;">${caretaker.email || 'N/A'}</p>
                        </div>
                    </div>
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e0e0e0;">
                        <p style="font-size: 12px; color: #999; margin-bottom: 4px;">Contact</p>
                        <p style="font-weight: 600; font-size: 14px;">
                            <span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle; color: #4caf50;">phone</span>
                            ${caretaker.contact || 'N/A'}
                        </p>
                    </div>
                    ${isAssigned ? '<div style="margin-top: 12px; padding: 8px 12px; background: #fff3e0; border-radius: 6px;"><p style="margin: 0; font-size: 13px; color: #e65100; font-weight: 600;"><span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">info</span> Currently assigned to another site</p></div>' : ''}
                </div>
                <div class="duty-card-footer">
                    ${isAssigned ? 
                        '<button class="action-btn" disabled style="opacity: 0.5; cursor: not-allowed; background: #e0e0e0; color: #999;"><span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">block</span> Already Assigned</button>' :
                        `<button class="action-btn" onclick="assignCaretaker(${caretaker.id}, '${caretaker.name.replace(/'/g, "\\'")}')"><span class="material-symbols-outlined" style="font-size:16px; vertical-align: middle;">person_add</span> Assign Caretaker</button>`
                    }
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function resetCaretakerFilters() {
    document.getElementById('caretakerDistrictFilter').value = 'same-district';
    document.getElementById('caretakerCityFilter').value = 'same-city';
    document.getElementById('caretakerAvailabilityFilter').value = 'available';
    document.getElementById('caretakersList').innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #666;"><span class="material-symbols-outlined" style="font-size: 64px; color: #ddd;">person_check</span><h3 style="margin: 16px 0 8px; font-size: 18px; font-weight: 600;">Search for Caretakers</h3><p style="color: #999;">Use the filters above to find caretakers available for assignment</p></div>';
}

// Global variable for caretaker assignment
let selectedCaretakerId = null;
let selectedCaretakerName = null;

function assignCaretaker(caretakerId, caretakerName) {
    selectedCaretakerId = caretakerId;
    selectedCaretakerName = caretakerName;
    document.getElementById('caretakerNameDisplay').textContent = caretakerName;
    document.getElementById('caretakerModal').style.display = 'flex';
}

function closeCaretakerModal() {
    selectedCaretakerId = null;
    selectedCaretakerName = null;
    document.getElementById('caretakerModal').style.display = 'none';
}

function confirmCaretakerAssignment() {
    if (!selectedCaretakerId) return;
    
    // Show loading state
    const modal = document.getElementById('caretakerModal');
    modal.style.pointerEvents = 'none';
    modal.style.opacity = '0.7';
    
    fetch(urlRoot + '/admin/assignCaretakerToSite', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            site_id: siteId,
            caretaker_id: selectedCaretakerId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reset opacity and show success message
            modal.style.opacity = '1';
            modal.style.pointerEvents = 'auto';
            modal.innerHTML = `
                <div style="background: white; border-radius: 12px; padding: 50px 40px; max-width: 450px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);">
                        <span class="material-symbols-outlined" style="font-size: 48px; color: #28a745;">check_circle</span>
                    </div>
                    <h3 style="margin: 0 0 12px 0; color: #28a745; font-size: 24px; font-weight: 700;">Assignment Successful!</h3>
                    <p style="color: #666; margin: 0 0 8px 0; font-size: 16px; line-height: 1.5;">
                        <strong style="color: #333;">${selectedCaretakerName}</strong> has been assigned as caretaker
                    </p>
                    <p style="color: #999; margin: 0; font-size: 14px;">
                        Redirecting to updated site view...
                    </p>
                    <div style="margin-top: 20px; width: 100%; height: 4px; background: #e9ecef; border-radius: 2px; overflow: hidden;">
                        <div style="height: 100%; background: linear-gradient(90deg, #28a745, #20c997); border-radius: 2px; animation: progressBar 1.5s ease-out;"></div>
                    </div>
                </div>
            `;
            setTimeout(() => location.reload(), 1500);
        } else {
            // Show error message
            modal.style.pointerEvents = 'auto';
            modal.style.opacity = '1';
            modal.innerHTML = `
                <div style="background: white; border-radius: 12px; padding: 40px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                    <div style="width: 64px; height: 64px; background: #f8d7da; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <span class="material-symbols-outlined" style="font-size: 32px; color: #dc3545;">error</span>
                    </div>
                    <h3 style="margin: 0 0 8px 0; color: #dc3545; font-size: 20px;">Error</h3>
                    <p style="color: #666; margin: 0 0 20px 0;">${data.message}</p>
                    <button onclick="closeCaretakerModal()" style="padding: 10px 24px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">Close</button>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const modal = document.getElementById('caretakerModal');
        modal.style.pointerEvents = 'auto';
        modal.style.opacity = '1';
        modal.innerHTML = `
            <div style="background: white; border-radius: 12px; padding: 40px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center;">
                <div style="width: 64px; height: 64px; background: #f8d7da; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                    <span class="material-symbols-outlined" style="font-size: 32px; color: #dc3545;">error</span>
                </div>
                <h3 style="margin: 0 0 8px 0; color: #dc3545; font-size: 20px;">Error</h3>
                <p style="color: #666; margin: 0 0 20px 0;">Failed to assign caretaker</p>
                <button onclick="closeCaretakerModal()" style="padding: 10px 24px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">Close</button>
            </div>
        `;
    });
}

// Calendar functionality
(function initializeCalendar() {
    const calendarBody = document.getElementById('calendarBody');
    const currentMonthYearSpan = document.getElementById('currentMonthYear');
    const selectedDateSpan = document.getElementById('selectedDate');
    const shiftContent = document.getElementById('shiftContent');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    
    if (!calendarBody || !currentMonthYearSpan || !prevMonthBtn || !nextMonthBtn) {
        return; // Calendar elements not found, skip initialization
    }
    
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    let selectedDateKey = null;
    const requestStart = <?php echo (!empty($data['package_request']) && !empty($data['package_request']->start_date)) ? ('new Date("' . date('Y-m-d', strtotime($data['package_request']->start_date)) . 'T00:00:00")') : 'null'; ?>;
    const requestEnd = <?php echo (!empty($data['package_request']) && !empty($data['package_request']->end_date)) ? ('new Date("' . date('Y-m-d', strtotime($data['package_request']->end_date)) . 'T00:00:00")') : 'null'; ?>;

    function formatDateKey(year, month, day) {
      return `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    }

    function isWithinRequestedPeriod(dateObj) {
      if (!requestStart || !requestEnd) {
        return false;
      }

      const compareDate = new Date(dateObj.getFullYear(), dateObj.getMonth(), dateObj.getDate());
      const start = new Date(requestStart.getFullYear(), requestStart.getMonth(), requestStart.getDate());
      const end = new Date(requestEnd.getFullYear(), requestEnd.getMonth(), requestEnd.getDate());
      return compareDate >= start && compareDate <= end;
    }
    
    // Generate calendar for a specific month and year
    function generateCalendar(month, year) {
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1; // Monday = 0
        
        const today = new Date();
        const isCurrentMonth = today.getFullYear() === year && today.getMonth() === month;
        const todayDate = today.getDate();
        
        calendarBody.innerHTML = '';
        
        // Update month/year display
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        currentMonthYearSpan.textContent = `${monthNames[month]} ${year}`;
        
        // Add previous month's trailing days
        const prevMonth = month === 0 ? 11 : month - 1;
        const prevYear = month === 0 ? year - 1 : year;
        const prevMonthDays = new Date(prevYear, prevMonth + 1, 0).getDate();
        
        for (let i = startingDayOfWeek - 1; i >= 0; i--) {
          const dayValue = prevMonthDays - i;
          const dayElement = createDayElement(dayValue, true, prevMonth, prevYear);
            calendarBody.appendChild(dayElement);
        }
        
        // Add current month's days
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = createDayElement(day, false, month, year);

            if (isCurrentMonth && day === todayDate) {
              dayElement.classList.add('today');
            }

            const dateString = formatDateKey(year, month, day);
            if (dateString === selectedDateKey) {
              dayElement.classList.add('selected');
            }
            
            calendarBody.appendChild(dayElement);
        }
        
        // Add next month's leading days
        const totalCells = calendarBody.children.length;
        const remainingCells = 42 - totalCells; // 6 rows × 7 days = 42 cells
        const nextMonth = month === 11 ? 0 : month + 1;
        const nextYear = month === 11 ? year + 1 : year;
        
        for (let day = 1; day <= remainingCells; day++) {
            const dayElement = createDayElement(day, true, nextMonth, nextYear);
            calendarBody.appendChild(dayElement);
        }
    }
    
    // Create a day element
        function createDayElement(day, isOtherMonth, month, year) {
        const dayElement = document.createElement('div');
          dayElement.className = `calendar-day${isOtherMonth ? ' other-month' : ''}`;
          dayElement.innerHTML = '<span class="day-number">' + String(day) + '</span>';
          dayElement.dataset.day = String(day);
          dayElement.dataset.month = String(month);
          dayElement.dataset.year = String(year);

          const dateObj = new Date(year, month, day);
          const dateString = formatDateKey(year, month, day);

          if (isWithinRequestedPeriod(dateObj)) {
            dayElement.classList.add('service-period');
          }

          if (!isOtherMonth) {
            dayElement.addEventListener('click', () => selectDate(dateString, day, month, year));
          }
        
        return dayElement;
    }
    
    // Select a date and show details
        function selectDate(dateString, day, month, year) {
          selectedDateKey = dateString;
          const allDays = calendarBody.querySelectorAll('.calendar-day');
          allDays.forEach(dayEl => dayEl.classList.remove('selected'));

          const clickedElement = Array.from(allDays).find(dayEl => {
            return !dayEl.classList.contains('other-month') && Number(dayEl.dataset.day) === day;
          });

          if (clickedElement) {
            clickedElement.classList.add('selected');
          }
        
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        selectedDateSpan.textContent = `${monthNames[month]} ${day}, ${year}`;
        
          showDateDetails(dateString, new Date(year, month, day));
    }
    
    // Show details for the selected date
        function showDateDetails(dateString, dateObj) {
          const isRequestedDate = isWithinRequestedPeriod(dateObj);
          const requestRangeText = requestStart && requestEnd
            ? `${requestStart.getFullYear()}-${String(requestStart.getMonth() + 1).padStart(2, '0')}-${String(requestStart.getDate()).padStart(2, '0')} to ${requestEnd.getFullYear()}-${String(requestEnd.getMonth() + 1).padStart(2, '0')}-${String(requestEnd.getDate()).padStart(2, '0')}`
            : 'Not set';

          const statusBadge = isRequestedDate
            ? '<span style="display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;background:#e7f7ef;color:#166534;font-size:12px;font-weight:700;">Within Requested Period</span>'
            : '<span style="display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;background:#f3f4f6;color:#4b5563;font-size:12px;font-weight:700;">Outside Requested Period</span>';

        shiftContent.className = '';
        shiftContent.innerHTML = `
            <div style="padding: 16px;">
              <div style="background: white; border-radius: 8px; padding: 16px; border-left: 4px solid ${isRequestedDate ? '#41a863' : 'var(--accent)'};">
                    <h4 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #333;">Selected Date</h4>
                    <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.6;">
                        <strong>Date:</strong> ${dateString}
                    </p>
                <p style="margin: 8px 0 0 0; font-size: 13px; color: #666; line-height: 1.6;">
                  <strong>Requested Period:</strong> ${requestRangeText}
                </p>
                <div style="margin-top: 10px;">
                  ${statusBadge}
                </div>
                <p style="margin: 10px 0 0 0; font-size: 13px; color: #999;">
                  Officer scheduling can be planned within the highlighted service period.
                    </p>
                </div>
            </div>
        `;
    }
    
    // Navigation: Previous month
    prevMonthBtn.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        generateCalendar(currentMonth, currentYear);
    });
    
    // Navigation: Next month
    nextMonthBtn.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        generateCalendar(currentMonth, currentYear);
    });
    
    // Initialize calendar with current month
    generateCalendar(currentMonth, currentYear);
})();
</script>
<?php require_once APP_ROOT . '/views/components/showNotification.php'; ?>
<script>
// Show flash notifications
<?php 
$siteSuccess = flash('site_success');
$siteError = flash('site_error');
$requestSuccess = flash('request_success');
$requestError = flash('request_error');
?>
<?php if ($siteSuccess): ?>
  showNotification('<?php echo addslashes($siteSuccess); ?>', 'success');
<?php endif; ?>
<?php if ($siteError): ?>
  showNotification('<?php echo addslashes($siteError); ?>', 'error');
<?php endif; ?>
<?php if ($requestSuccess): ?>
  showNotification('<?php echo addslashes($requestSuccess); ?>', 'success');
<?php endif; ?>
<?php if ($requestError): ?>
  showNotification('<?php echo addslashes($requestError); ?>', 'error');
<?php endif; ?>
</script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
```