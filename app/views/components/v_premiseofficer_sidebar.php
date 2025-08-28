
<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>


<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>/css/components/sidebar_topbar_style.css">

<div class="app">
    <aside class="sidebar" aria-label="Primary">
        <div class="brand">
            <div class="brand-mark" aria-hidden="true">
                <img src="<?php echo URL_ROOT; ?>/public/img/logo.png" alt="RED FORCE" class="logo-img">
            </div>
            <div class="brand-text">
                <span class="brand-title">RED FORCE</span>
            </div>
        </div>

        <div class="user-card">
            <div class="avatar" aria-hidden="true"><span class="material-symbols-outlined">person</span></div>
                <div class="user-meta">
                    <div class="user-name">User</div>
                    <div class="user-role">Grade</div>
                </div>
            </div>

            <nav class="menu">
            
            <div class="menu-section"></div>
            <a class="menu-item <?php echo ($data['title'] === 'Dashboard') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/premiseofficer/dashboard"><span class="icon"></span><span class="material-symbols-outlined">dashboard</span><span class="label">Dashboard</span></a>
            <a class="menu-item <?php echo ($data['title'] === 'Schedule') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/premiseofficer/schedule"><span class="icon"></span><span class="material-symbols-outlined">schedule</span><span class="label">Schedule</span></a>
            <a class="menu-item <?php echo ($data['title'] === 'Requests') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/premiseofficer/requests"><span class="icon"></span><span class="material-symbols-outlined">summarize</span><span class="label">Request Leaves</span></a>


        </nav>
      </aside>

      <main class="main">
        <header class="topbar">
          <button id="menuToggle" class="topbar-btn" aria-label="Toggle menu" aria-expanded="false"><span class="material-symbols-outlined">menu</span></button>
          <h1 class="page-title"><?php echo $data['title']; ?></h1>

          <div class="topbar-right">
  <!-- Profile Toggle -->
  <div class="topbar-user" id="profileToggle">
    <span class="avatar" aria-hidden="true"><span class="material-symbols-outlined">person</span></span>
    <span class="name">User</span>
    <span class="caret" aria-hidden="true"><span class="material-symbols-outlined">arrow_drop_down</span></span>
  </div>

  <!-- Dropdown -->
  <div class="profile-dropdown" id="profileDropdown">
    <div class="profile-info">
      <div class="avatar"><span class="material-symbols-outlined">person</span></div>
      <div>
        <h4>User</h4>
        <p>grade</p>
      </div>
    </div>
    <hr>
    <a href="#" class="dropdown-link change-password">
  <span class="material-symbols-outlined">lock</span> Change Password
</a>
    <a href="<?php echo URL_ROOT; ?>/home/index" class="dropdown-link logout">
      <span class="material-symbols-outlined">logout</span> Logout
    </a>
  </div>
</div>

        </header>

      <!-- Change Password Modal -->
<div class="modal-overlay" id="passwordModal" hidden>
  <div class="modal">
    <h2>Change Password</h2>
    <form id="changePasswordForm">
      <label for="currentPassword">Current Password</label>
      <input type="password" id="currentPassword" name="currentPassword" required>

      <label for="newPassword">New Password</label>
      <input type="password" id="newPassword" name="newPassword" required>

      <label for="confirmPassword">Confirm New Password</label>
      <input type="password" id="confirmPassword" name="confirmPassword" required>

      <div class="modal-actions">
        <button type="button" id="cancelPasswordBtn" class="btn-cancel">Cancel</button>
        <button type="submit" class="btn-save">Save</button>
      </div>
    </form>
  </div>
</div>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>