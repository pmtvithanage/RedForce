
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
            <div class="user-name"><?php echo getCurrentUserName() ?? 'Admin User'; ?></div>
            <div class="user-role"><?php echo getCurrentUserRole() ?? 'Super Admin'; ?></div>
          </div>
        </div>

        <nav class="menu">
          <div class="menu-section">MAIN</div>
          <a class="menu-item <?php echo ($data['title'] === 'Dashboard') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/admin/dashboard"><span class="icon"></span><span class="material-symbols-outlined">dashboard</span><span class="label">Dashboard</span></a>

          <div class="menu-section">MANAGEMENT</div>
          <a class="menu-item <?php echo ($data['title'] === 'Officers') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/admin/officers"><span class="icon"></span><span class="material-symbols-outlined">group</span><span class="label">Officers</span></a>
          <a class="menu-item <?php echo ($data['title'] === 'Clients') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/admin/clients"><span class="icon"></span><span class="material-symbols-outlined">badge</span><span class="label">Clients</span></a>
          <a class="menu-item <?php echo ($data['title'] === 'Routes') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/admin/routes"><span class="icon"></span><span class="material-symbols-outlined">route</span><span class="label">Routes</span></a>
          <a class="menu-item <?php echo ($data['title'] === 'Salary') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/admin/clients_payments"><span class="icon"></span><span class="material-symbols-outlined">payments</span><span class="label">Client Payments</span></a>
          <a class="menu-item <?php echo ($data['title'] === 'Advertisements') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/admin/advertisements"><span class="icon"></span><span class="material-symbols-outlined">campaign</span><span class="label">Advertisements</span></a>
          <a class="menu-item <?php echo ($data['title'] === 'Incidents') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/admin/incidents"><span class="icon"></span><span class="material-symbols-outlined">report</span><span class="label">Incidents</span></a>
          <a class="menu-item <?php echo ($data['title'] === 'Reports') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/admin/reports"><span class="icon"></span><span class="material-symbols-outlined">summarize</span><span class="label">Reports</span></a>

          <div class="menu-section">SYSTEM</div>
        <?php if(isset($_SESSION['user_userID']) && $_SESSION['user_userID'] == 'ADMIN001'): ?>
          <a class="menu-item <?php echo ($data['title'] === 'Admins') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/admin/admins"><span class="icon"></span><span class="material-symbols-outlined">settings</span><span class="label">Admins</span></a>
        <?php else: ?>
            <a class="menu-item <?php echo ($data['title'] === 'Profile') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/admin/Profile"><span class="icon"></span><span class="material-symbols-outlined">settings</span><span class="label">Profile</span></a>
        <?php endif; ?>
        
          </nav>
      </aside>

      <div class="backdrop" id="backdrop" hidden></div>

      <main class="main">
        <header class="topbar">
          <button id="menuToggle" class="topbar-btn" aria-label="Toggle menu" aria-expanded="false"><span class="material-symbols-outlined">menu</span></button>
          <h1 class="page-title"><?php echo $data['pageTitle']; ?></h1>

          <div class="topbar-right">
  <!-- Profile Toggle -->
  <div class="topbar-user" id="profileToggle">
    <span class="avatar" aria-hidden="true"><span class="material-symbols-outlined">person</span></span>
    <span class="name"><?php echo getCurrentUserName() ?? 'Admin'; ?></span>
    <span class="caret" aria-hidden="true"><span class="material-symbols-outlined">arrow_drop_down</span></span>
  </div>

  <!-- Dropdown -->
  <div class="profile-dropdown" id="profileDropdown">
    <div class="profile-info">
      <div class="avatar"><span class="material-symbols-outlined">person</span></div>
      <div>
        <h4><?php echo getCurrentUserName() ?? 'Admin User'; ?></h4>
        <p><?php echo getCurrentUserRole() ?? 'Super Admin'; ?></p>
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

  //------------Flash Message ----------------//
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










//------------------Loading Button Script------------------//
  // Show loading state on anchor buttons until navigation and globally block other actions
(function() {
  // Create or return the global overlay that blocks clicks
  function getGlobalOverlay() {
    var id = 'global-loading-overlay';
    var overlay = document.getElementById(id);
    if (!overlay) {
      overlay = document.createElement('div');
      overlay.id = id;
      overlay.style.position = 'fixed';
      overlay.style.top = 0;
      overlay.style.left = 0;
      overlay.style.width = '100%';
      overlay.style.height = '100%';
      overlay.style.zIndex = 9999;
      overlay.style.background = 'rgba(255,255,255,0.0001)';
      overlay.style.cursor = 'wait';
      overlay.style.pointerEvents = 'auto';
      overlay.setAttribute('aria-hidden', 'true');
      document.body.appendChild(overlay);
    }
    return overlay;
  }

  // keep a global counter to support nested loading calls
  function incGlobalLoading() {
    window.__globalLoadingCounter = (window.__globalLoadingCounter || 0) + 1;
    if (window.__globalLoadingCounter === 1) {
      var ov = getGlobalOverlay();
      ov.style.display = 'block';
    }
  }
  function decGlobalLoading() {
    window.__globalLoadingCounter = Math.max((window.__globalLoadingCounter || 1) - 1, 0);
    if (window.__globalLoadingCounter === 0) {
      var ov = document.getElementById('global-loading-overlay');
      if (ov) ov.style.display = 'none';
    }
  }

  function makeLoading(el) {
    if (el.dataset.loading === '1') return;
    el.dataset.loading = '1';

    // store original html
    if (!el.dataset.origHtml) el.dataset.origHtml = el.innerHTML;
    el.classList.add('btn-loading');
    el.setAttribute('aria-busy', 'true');

    // disable interaction on the element itself
    try {
      if (el.tagName.toLowerCase() === 'button' || el.tagName.toLowerCase() === 'input') {
        el.disabled = true;
      } else {
        el.style.pointerEvents = 'none';
        el.setAttribute('aria-disabled', 'true');
        if (el.hasAttribute('tabindex')) {
          el.dataset.origTabindex = el.getAttribute('tabindex');
        }
        el.setAttribute('tabindex', '-1');
      }
    } catch (e) { /* ignore */ }

    var label ='Loading...';
    el.innerHTML = '<span class="spinner" aria-hidden="true"></span>' + label;

    // block all other actions globally
    incGlobalLoading();
  }

  function restoreLoading(el) {
    if (!el || el.dataset.loading !== '1') return;
    delete el.dataset.loading;
    el.classList.remove('btn-loading');
    el.removeAttribute('aria-busy');

    try {
      if (el.tagName.toLowerCase() === 'button' || el.tagName.toLowerCase() === 'input') {
        el.disabled = false;
      } else {
        el.style.pointerEvents = '';
        el.removeAttribute('aria-disabled');
        if (el.dataset.origTabindex !== undefined) {
          el.setAttribute('tabindex', el.dataset.origTabindex);
          delete el.dataset.origTabindex;
        } else {
          el.removeAttribute('tabindex');
        }
      }
    } catch (e) { /* ignore */ }

    if (el.dataset.origHtml) {
      el.innerHTML = el.dataset.origHtml;
      // keep origHtml for potential future restores
    }

    // un-block global actions
    decGlobalLoading();
  }

  function handleClick(e) {
    var el = e.currentTarget;
    var href = el.getAttribute('href');
    if (!href || href === '#') return; // nothing to do

    if (el.dataset.loading === '1') {
      e.preventDefault();
      return; // already loading
    }

    e.preventDefault();
    makeLoading(el);

    // small delay to show spinner before navigating
    setTimeout(function(){
      window.location.href = href;
    }, 80);
  }

  document.addEventListener('DOMContentLoaded', function(){
    // target elements with class 'loading'
    var selectors = '.loading';
    var els = document.querySelectorAll(selectors);
    els.forEach(function(a){
      // anchors -> intercept navigation
      if (a.tagName.toLowerCase() === 'a') {
        a.addEventListener('click', handleClick);
      }

      // buttons -> show loading on click (may submit forms)
      if (a.tagName.toLowerCase() === 'button') {
        a.addEventListener('click', function(ev){
          if (a.dataset.loading === '1') { ev.preventDefault(); return; }
          makeLoading(a);
        });
      }
    });

  });
})();
</script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>