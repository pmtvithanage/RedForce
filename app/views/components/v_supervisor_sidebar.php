
<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>


<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>/css/components/sidebar_topbar_style.css">
<link rel="stylesheet" href="<?= URL_ROOT ?>/css/style.css">
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
                  <div class="user-name"><?php echo getCurrentUserName() ?? 'User'; ?></div>
                  <div class="user-role"><?php echo getCurrentUserRole() ?? 'Supervisor'; ?></div>
                </div>
            </div>

            <nav class="menu">
            
            <div class="menu-section"></div>
            <a class="menu-item <?php echo ($data['title'] === 'Dashboard') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/supervisor/dashboard"><span class="icon"></span><span class="material-symbols-outlined">dashboard</span><span class="label">Dashboard</span></a>
            <a class="menu-item <?php echo ($data['title'] === 'Attendance') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/supervisor/attendance"><span class="icon"></span><span class="material-symbols-outlined">fact_check</span><span class="label">Attendance</span></a>
            <a class="menu-item <?php echo ($data['title'] === 'Sites') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/supervisor/site_info"><span class="icon"></span><span class="material-symbols-outlined">location_on</span><span class="label">Site Info</span></a>
            <a class="menu-item <?php echo ($data['title'] === 'Messages') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/supervisor/messages"><span class="icon"></span><span class="material-symbols-outlined">mail</span><span class="label">Messages</span></a>
            <a class="menu-item <?php echo ($data['title'] === 'Incidents') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/supervisor/incidents"><span class="icon"></span><span class="material-symbols-outlined">report</span><span class="label">Incidents</span></a>
            <a class="menu-item <?php echo ($data['title'] === 'Leave Requests') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/supervisor/leave_requests"><span class="icon"></span><span class="material-symbols-outlined">summarize</span><span class="label">Request Leaves</span></a>
            <a class="menu-item <?php echo ($data['title'] === 'Profile') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/supervisor/profile"><span class="icon"></span><span class="material-symbols-outlined">person</span><span class="label">Profile</span></a>


        </nav>
      </aside>

      <div class="backdrop" id="backdrop" hidden></div>

      <main class="main">
        <header class="topbar">
          <button id="menuToggle" class="topbar-btn" aria-label="Toggle menu" aria-expanded="false"><span class="material-symbols-outlined">menu</span></button>
          <h1 class="page-title"><?php echo $data['pageTitle'] ?? 'Dashboard'; ?></h1>

          <div class="topbar-right">
  <!-- Profile Toggle -->
  <div class="topbar-user" id="profileToggle">
    <span class="avatar" aria-hidden="true"><span class="material-symbols-outlined">person</span></span>
    <span class="name"><?php echo getCurrentUserName() ?? 'User'; ?></span>
    <span class="caret" aria-hidden="true"><span class="material-symbols-outlined">arrow_drop_down</span></span>
  </div>

  <!-- Dropdown -->
  <div class="profile-dropdown" id="profileDropdown">
    <div class="profile-info">
      <div class="avatar"><span class="material-symbols-outlined">person</span></div>
      <div>
        <h4><?php echo getCurrentUserName() ?? 'User'; ?></h4>
        <p><?php echo getCurrentUserRole() ?? 'Supervisor'; ?></p>
      </div>
    </div>
    <hr>
    <a href="#" class="dropdown-link change-password">
  <span class="material-symbols-outlined">lock</span> Change Password
</a>
    <a href="<?php echo URL_ROOT; ?>/users/logout" class="dropdown-link logout">
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
<?php flash('msg')?>

<script>
  // Wait for DOM to be fully loaded
  document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.getElementById('msg-flash');
    
    if (flashMessage) {
      // Auto-remove after 5 seconds (5000ms)
      setTimeout(function() {
        // Add fade-out animation
        flashMessage.classList.add('fade-out');
        
        // Remove element after animation completes
        setTimeout(function() {
          if (flashMessage.parentNode) {
            flashMessage.parentNode.removeChild(flashMessage);
          }
        }, 300); // Match animation duration (300ms from CSS)
      }, 5000); // Display for 5 seconds
    }
  });
</script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>