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

    <!-- DUTY POINTS SECTION -->
    <div class="duty-points-section">
      <div class="section-header">
        <h2 class="section-title" >Duty Points</h2>
        <button class="secondary-btn add-duty-btn" onClick="window.location.href='<?php echo URL_ROOT; ?>/admin/adddutypoint'">
          <span class="material-symbols-outlined" style="font-size:18px;" >add</span>
          Add Duty Point
        </button>
      </div>

      <div class="duty-cards-container">
        <!-- Duty Card 1 -->
        <div class="duty-card">
          <div class="duty-card-header">
            <h3 class="duty-location">Main Entrance</h3>
            <span class="duty-status status-active">Active</span>
          </div>
          
          <div class="duty-shifts">
            <div class="shift">
              <div class="shift-header">
                <span class="material-symbols-outlined shift-icon" style="color: #f59e0b;">wb_sunny</span>
                <span class="shift-title">Day Shift (08:00 - 20:00)</span>
              </div>
              
              <!-- Day Shift Supervisor -->
              <div class="supervisor-info">
                <img src="<?php echo URL_ROOT; ?>/img/avatar1.jpg" alt="Day Shift Supervisor" class="supervisor-avatar">
                <div class="supervisor-details">
                  <div class="supervisor-name">John Smith</div>
                  <div class="supervisor-role">Day Shift Supervisor</div>
                </div>
              </div>
              
              <div class="officer-list">
                <div class="officer-item">
                  <img src="<?php echo URL_ROOT; ?>/img/avatar2.jpg" alt="Officer" class="officer-avatar">
                  <span class="officer-name">Michael Brown</span>
                </div>
                <div class="officer-item">
                  <img src="<?php echo URL_ROOT; ?>/img/avatar3.jpg" alt="Officer" class="officer-avatar">
                  <span class="officer-name">Sarah Johnson</span>
                </div>
                <div class="officer-item">
                  <img src="<?php echo URL_ROOT; ?>/img/avatar4.jpg" alt="Officer" class="officer-avatar">
                  <span class="officer-name">Robert Davis</span>
                </div>
              </div>
            </div>
            
            <div class="shift">
              <div class="shift-header">
                <span class="material-symbols-outlined shift-icon" style="color: #1e40af;">dark_mode</span>
                <span class="shift-title">Night Shift (20:00 - 08:00)</span>
              </div>
              
              <!-- Night Shift Supervisor -->
              <div class="supervisor-info">
                <img src="<?php echo URL_ROOT; ?>/img/avatar5.jpg" alt="Night Shift Supervisor" class="supervisor-avatar">
                <div class="supervisor-details">
                  <div class="supervisor-name">Jennifer Wilson</div>
                  <div class="supervisor-role">Night Shift Supervisor</div>
                </div>
              </div>
              
              <div class="officer-list">
                <div class="officer-item">
                  <img src="<?php echo URL_ROOT; ?>/img/avatar6.jpg" alt="Officer" class="officer-avatar">
                  <span class="officer-name">David Miller</span>
                </div>
                <div class="officer-item">
                  <img src="<?php echo URL_ROOT; ?>/img/avatar7.jpg" alt="Officer" class="officer-avatar">
                  <span class="officer-name">Emma Thompson</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="duty-card-footer">
            <button class="action-btn" onClick="window.location.href='<?php echo URL_ROOT; ?>/admin/editassignment'">Edit Assignment</button>
          </div>
        </div>
        
        <!-- Duty Card 2 -->
        <div class="duty-card">
          <div class="duty-card-header">
            <h3 class="duty-location">Parking Area</h3>
            <span class="duty-status status-active">Active</span>
          </div>
          
          <div class="duty-shifts">
            <div class="shift">
              <div class="shift-header">
                <span class="material-symbols-outlined shift-icon" style="color: #f59e0b;">wb_sunny</span>
                <span class="shift-title">Day Shift (08:00 - 20:00)</span>
              </div>
              
              <!-- Day Shift Supervisor -->
              <div class="supervisor-info">
                <img src="<?php echo URL_ROOT; ?>/img/avatar8.jpg" alt="Day Shift Supervisor" class="supervisor-avatar">
                <div class="supervisor-details">
                  <div class="supervisor-name">James Anderson</div>
                  <div class="supervisor-role">Day Shift Supervisor</div>
                </div>
              </div>
              
              <div class="officer-list">
                <div class="officer-item">
                  <img src="<?php echo URL_ROOT; ?>/img/avatar9.jpg" alt="Officer" class="officer-avatar">
                  <span class="officer-name">Lisa Garcia</span>
                </div>
                <div class="officer-item">
                  <img src="<?php echo URL_ROOT; ?>/img/avatar10.jpg" alt="Officer" class="officer-avatar">
                  <span class="officer-name">Thomas White</span>
                </div>
              </div>
            </div>
            
            <div class="shift">
              <div class="shift-header">
                <span class="material-symbols-outlined shift-icon" style="color: #1e40af;">dark_mode</span>
                <span class="shift-title">Night Shift (20:00 - 08:00)</span>
              </div>
              
              <!-- Night Shift Supervisor -->
              <div class="supervisor-info">
                <img src="<?php echo URL_ROOT; ?>/img/avatar11.jpg" alt="Night Shift Supervisor" class="supervisor-avatar">
                <div class="supervisor-details">
                  <div class="supervisor-name">Olivia Martinez</div>
                  <div class="supervisor-role">Night Shift Supervisor</div>
                </div>
              </div>
              
              <div class="officer-list">
                <div class="officer-item">
                  <img src="<?php echo URL_ROOT; ?>/img/avatar12.jpg" alt="Officer" class="officer-avatar">
                  <span class="officer-name">Daniel Clark</span>
                </div>
                <div class="officer-item">
                  <img src="<?php echo URL_ROOT; ?>/img/avatar13.jpg" alt="Officer" class="officer-avatar">
                  <span class="officer-name">Sophia Rodriguez</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="duty-card-footer">
            <button class="action-btn">Edit Assignment</button>
          </div>
        </div>
        
        <!-- Duty Card 3 -->
        <div class="duty-card">
          <div class="duty-card-header">
            <h3 class="duty-location">West Wing</h3>
            <span class="duty-status status-inactive">Inactive</span>
          </div>
          
          <div class="duty-shifts">
            <div class="shift">
              <div class="shift-header">
                <span class="material-symbols-outlined shift-icon" style="color: #f59e0b;">wb_sunny</span>
                <span class="shift-title">Day Shift (08:00 - 20:00)</span>
              </div>
              
              <!-- Day Shift Supervisor -->
              <div class="supervisor-info">
                <img src="<?php echo URL_ROOT; ?>/img/avatar14.jpg" alt="Day Shift Supervisor" class="supervisor-avatar">
                <div class="supervisor-details">
                  <div class="supervisor-name">William Lee</div>
                  <div class="supervisor-role">Day Shift Supervisor</div>
                </div>
              </div>
              
              <div class="officer-list">
                <div class="officer-item">
                  <img src="<?php echo URL_ROOT; ?>/img/avatar15.jpg" alt="Officer" class="officer-avatar">
                  <span class="officer-name">Emily Taylor</span>
                </div>
                <div class="officer-item">
                  <img src="<?php echo URL_ROOT; ?>/img/avatar16.jpg" alt="Officer" class="officer-avatar">
                  <span class="officer-name">Christopher Harris</span>
                </div>
              </div>
            </div>
            
            <div class="shift">
              <div class="shift-header">
                <span class="material-symbols-outlined shift-icon" style="color: #1e40af;">dark_mode</span>
                <span class="shift-title">Night Shift (20:00 - 08:00)</span>
              </div>
              
              <!-- Night Shift Supervisor -->
              <div class="supervisor-info">
                <img src="<?php echo URL_ROOT; ?>/img/avatar17.jpg" alt="Night Shift Supervisor" class="supervisor-avatar">
                <div class="supervisor-details">
                  <div class="supervisor-name">Matthew Walker</div>
                  <div class="supervisor-role">Night Shift Supervisor</div>
                </div>
              </div>
              
              <div class="officer-list">
                <div class="officer-item">
                  <img src="<?php echo URL_ROOT; ?>/img/avatar18.jpg" alt="Officer" class="officer-avatar">
                  <span class="officer-name">Amanda King</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="duty-card-footer">
            <button class="action-btn">Edit Assignment</button>
          </div>
        </div>
      </div>
    </div>
    
</div>

<div class="backdrop" id="backdrop" hidden></div>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>