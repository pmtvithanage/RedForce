
<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php
// Fetch notifications and user profile for current user
$notificationModel = null;
$notifications = [];
$unreadCount = 0;
$userProfileImage = 'default.png';

if (isset($_SESSION['user_userID'])) {
    require_once APP_ROOT . '/models/M_notifications.php';
    require_once APP_ROOT . '/models/M_client.php';
    $notificationModel = new M_notifications();
    $clientModel = new M_client();
    $notifications = $notificationModel->getNotifications($_SESSION['user_id']);
    
    // Fetch current user's profile image
    $currentUser = $clientModel->getclientById($_SESSION['user_userID']);
    if ($currentUser && isset($currentUser->profile_image)) {
        $userProfileImage = $currentUser->profile_image;
    }
    
    // Count unread notifications
    $unreadCount = 0;
    foreach ($notifications as $notif) {
        if (!$notif->is_read) {
            $unreadCount++;
        }
    }
    // Limit to first 5 for dropdown
    $dropdownNotifications = array_slice($notifications, 0, 5);
}
?>


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
            <img class="avatar" src="<?php echo URL_ROOT; ?>/uploads/clientLogos/<?php echo isset($userProfileImage) ? $userProfileImage : 'default.png'; ?>" alt="Profile" onerror="this.src='<?php echo URL_ROOT; ?>/uploads/applicantPhotos/default.png'" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
          <div class="user-meta">
            <div class="user-name"><?php echo getCurrentUserName() ?? 'User'; ?></div>
            <div class="user-role"><?php echo getCurrentUserRole() ?? 'Client'; ?></div>
          </div>
        </div>

        <nav class="menu">
          <a class="menu-item <?php echo ($data['title'] === 'Dashboard') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/client/dashboard"><span class="icon"></span><span class="material-symbols-outlined">dashboard</span><span class="label">Dashboard</span></a>
          <a class="menu-item <?php echo ($data['title'] === 'Sites') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/client/sites"><span class="icon"></span><span class="material-symbols-outlined">location_city</span><span class="label">Sites</span></a>
          <a class="menu-item <?php echo ($data['title'] === 'Requests') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/client/requests"><span class="icon"></span><span class="material-symbols-outlined">badge</span><span class="label">Requests</span></a>
          <a class="menu-item <?php echo ($data['title'] === 'Messages') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/client/messages"><span class="icon"></span><span class="material-symbols-outlined">mail</span><span class="label">Messages</span></a>
          <a class="menu-item <?php echo ($data['title'] === 'Equipment Requests') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/client/equipmentRequests"><span class="icon"></span><span class="material-symbols-outlined">inventory</span><span class="label">Equipment Requests</span></a>
          <a class="menu-item <?php echo ($data['title'] === 'Payments') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/client/payments"><span class="icon"></span><span class="material-symbols-outlined">payment</span><span class="label">Payments</span></a>
          <a class="menu-item <?php echo ($data['title'] === 'Profile') ? 'is-active' : ''; ?>" href="<?php echo URL_ROOT; ?>/client/profile"><span class="icon"></span><span class="material-symbols-outlined">person</span><span class="label">Profile</span></a>
          
        </nav>
      </aside>

      <div class="backdrop" id="backdrop" hidden></div>

      <main class="main">
        <header class="topbar">
          <button id="menuToggle" class="topbar-btn" aria-label="Toggle menu" aria-expanded="false"><span class="material-symbols-outlined">menu</span></button>
          <h1 class="page-title"><?php echo isset($data['pageTitle']) ? $data['pageTitle'] : (isset($data['title']) ? $data['title'] : 'Dashboard'); ?></h1>

          <div class="topbar-right">
  <!-- Notification Toggle -->
  <div class="topbar-notification" id="notificationToggle">
    <button class="notification-btn" aria-label="Notifications">
      <span class="material-symbols-outlined">notifications</span>
      <?php if ($unreadCount > 0): ?>
      <span class="notification-badge"><?php echo $unreadCount; ?></span>
      <?php endif; ?>
    </button>
  </div>

  <!-- Notification Dropdown -->
  <div class="notification-dropdown" id="notificationDropdown" hidden>
    <div class="notification-header">
      <h4>Notifications</h4>
    </div>
    <div class="notification-list">
      <?php if (!empty($dropdownNotifications)): ?>
        <?php foreach ($dropdownNotifications as $notification): ?>
          <div class="notification-item <?php echo !$notification->is_read ? 'unread' : ''; ?>">
            <span class="notification-icon">
              <span class="material-symbols-outlined"><?php echo htmlspecialchars($notification->icon ?? 'notifications'); ?></span>
            </span>
            <div class="notification-content">
              <div class="notification-title"><?php echo htmlspecialchars($notification->title); ?></div>
              <div class="notification-time"><?php echo time_elapsed_string($notification->created_at); ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="notification-item">
          <div class="notification-content">
            <div class="notification-title">No notifications</div>
          </div>
        </div>
      <?php endif; ?>
    </div>
    <div class="notification-footer">
      <a href="<?php echo URL_ROOT; ?>/client/notifications" class="btn-view-all">View All Notifications</a>
    </div>
  </div>

  <!-- Profile Toggle -->
  <div class="topbar-user" id="profileToggle">
    <img class="avatar" src="<?php echo URL_ROOT; ?>/uploads/clientLogos/<?php echo isset($userProfileImage) ? $userProfileImage : 'default.png'; ?>" alt="Profile" onerror="this.src='<?php echo URL_ROOT; ?>/uploads/clientLogos/default.png'" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
    <span class="name"><?php echo getCurrentUserName() ?? 'Client'; ?></span>
    <span class="caret"><span class="material-symbols-outlined">arrow_drop_down</span></span>
  </div>

  <!-- Dropdown -->
  <div class="profile-dropdown" id="profileDropdown">
    <div class="profile-info">
      <img class="avatar" src="<?php echo URL_ROOT; ?>/uploads/clientLogos/<?php echo isset($userProfileImage) ? $userProfileImage : 'default.png'; ?>" alt="Profile" onerror="this.src='<?php echo URL_ROOT; ?>/uploads/clientLogos/default.png'" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
      <div>
        <h4><?php echo getCurrentUserName() ?? 'User'; ?></h4>
        <p><?php echo getCurrentUserRole() ?? 'Client'; ?></p>
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
  // Notification and Profile Dropdown Toggle
  document.addEventListener('DOMContentLoaded', function() {
    const notificationToggle = document.getElementById('notificationToggle');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = document.getElementById('profileDropdown');

    // Toggle notification dropdown
    if (notificationToggle && notificationDropdown) {
      notificationToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationDropdown.toggleAttribute('hidden');
        if (profileDropdown && !profileDropdown.hasAttribute('hidden')) {
          profileDropdown.setAttribute('hidden', '');
        }
      });
    }

    // Toggle profile dropdown
    if (profileToggle && profileDropdown) {
      profileToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        profileDropdown.toggleAttribute('hidden');
        if (notificationDropdown && !notificationDropdown.hasAttribute('hidden')) {
          notificationDropdown.setAttribute('hidden', '');
        }
      });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
      if (notificationDropdown && !notificationDropdown.hasAttribute('hidden') && 
          !notificationToggle.contains(e.target) && !notificationDropdown.contains(e.target)) {
        notificationDropdown.setAttribute('hidden', '');
      }
      if (profileDropdown && !profileDropdown.hasAttribute('hidden') && 
          !profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
        profileDropdown.setAttribute('hidden', '');
      }
    });
  });

  // Flash message handler
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