<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>


    <!-- Content will be loaded here -->

   
  <style>
    :root{
      --bg: #f0f2f5;
      --card: #fff;
      --muted: #606770;
      --accent: #1877f2;
      --accent-plain: #e7f0ff;
      --container-padding: 18px;
      --shadow: 0 6px 18px rgba(20,20,40,0.06);
      --glass: rgba(255,255,255,0.8);
    }


    /* COVER + PROFILE */
    .cover {
      width:100%-12px;
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

    /* profile card overlapping */
    

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
      width: 128px;
      height: 128px;
      border-radius: 50%;
      object-fit:cover;
      border:6px solid var(--card);
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
      background: transparent;
      color: #111827;
      border:1px solid #e6e6e6;
      box-shadow:none;
      font-weight:600;
    }
  </style>

  <div class="shell" role="main">

    <!-- COVER -->
    <div class="cover" aria-hidden="true">
      <!-- If you want to place something on the cover (edit button), place absolute elements here -->
      <div style="position:absolute;right:12px;bottom:12px">
        <button class="btn secondary" style="padding:8px 10px;">Change Cover</button>
      </div>
    </div>

    <!-- MAIN GRID -->
    <div class="profile-main" style="margin-top:18px;">
      <!-- LEFT: Profile summary -->
      <div class="left-column">
        <div class="profile-card" aria-label="Profile summary">
          <div class="avatar-wrap">
            <img class="avatar-image" src="" alt="Profile photo">
          </div>

          <div style="height:86px"></div> <!-- spacer to accommodate absolute avatar -->

          <div class="mini-about"><strong>About</strong><div class="spacer"></div>
            Loves coding, weightlifting, and open-source. Lives in Colombo. Speaks Sinhala & English.
          </div>

          <div style="height:12px"></div>

          <div>
            <div class="card-title">Details</div>
            <div class="small muted">
              <div>Joined: 2020</div>
              <div>Works at: Example Co.</div>
            </div>
          </div>

        </div>
      </div>

      
  </div>


    
    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>