<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<?php
// Handle form submissions
$showPasswordModal = false;
$showContactModal = false;
$showEmailModal = false;
$message = '';

// Mock client data - replace with actual database query
$clientData = [
    'name' => "People's Bank PLC",
    'contact' => '0112 112 112',
    'email' => 'peoplesbank@hotmail.com'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        $message = 'Password updated successfully!';
    }
    
    if (isset($_POST['change_contact'])) {
        $newContact = $_POST['new_contact'];
        $clientData['contact'] = $newContact;
        $message = 'Contact number updated successfully!';
    }
    
    if (isset($_POST['change_email'])) {
        $newEmail = $_POST['new_email'];
        $clientData['email'] = $newEmail;
        $message = 'Email updated successfully!';
    }
}

if (isset($_GET['change_password'])) {
    $showPasswordModal = true;
}
if (isset($_GET['change_contact'])) {
    $showContactModal = true;
}
if (isset($_GET['change_email'])) {
    $showEmailModal = true;
}
?>

<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Profile CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/profile_style.css">

<!-- Password Change Modal -->
<?php if ($showPasswordModal): ?>
<div class="modal-overlay">
    <div class="change-modal">
        <div class="modal-header">
            <h3>Change Password</h3>
            <a href="?" class="close-btn">&times;</a>
        </div>
        <div class="modal-content">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required placeholder="Enter current password">
                </div>
                
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required placeholder="Enter new password">
                </div>
                
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required placeholder="Confirm new password">
                </div>
                
                <button type="submit" name="change_password" class="save-btn">Save Changes</button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Contact Change Modal -->
<?php if ($showContactModal): ?>
<div class="modal-overlay">
    <div class="change-modal">
        <div class="modal-header">
            <h3>Change Contact Number</h3>
            <a href="?" class="close-btn">&times;</a>
        </div>
        <div class="modal-content">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="tel" name="new_contact" value="<?php echo htmlspecialchars($clientData['contact']); ?>" required placeholder="Enter new contact number">
                </div>
                
                <button type="submit" name="change_contact" class="save-btn">Save Changes</button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Email Change Modal -->
<?php if ($showEmailModal): ?>
<div class="modal-overlay">
    <div class="change-modal">
        <div class="modal-header">
            <h3>Change Email Address</h3>
            <a href="?" class="close-btn">&times;</a>
        </div>
        <div class="modal-content">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="new_email" value="<?php echo htmlspecialchars($clientData['email']); ?>" required placeholder="Enter new email">
                </div>
                
                <button type="submit" name="change_email" class="save-btn">Save Changes</button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="main-content">
    <div class="profile-container">
        <div class="profile-header">
            <h2>My Profile</h2>
            <p>Manage your account information and settings</p>
        </div>

        <?php if ($message): ?>
        <div class="success-message">
            <span class="material-icons">check_circle</span>
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <div class="profile-grid">
            <!-- Profile Card -->
            <div class="profile-card">
                <div class="card-header">
                    <span class="material-icons">account_circle</span>
                    <h3>Profile Information</h3>
                </div>
                <div class="card-body">
                    <div class="profile-avatar-large">
                        <div class="avatar-circle-large">
                            <span class="material-icons">person</span>
                        </div>
                    </div>
                    <div class="profile-name">
                        <?php echo htmlspecialchars($clientData['name']); ?>
                    </div>
                    <div class="profile-role">Client Account</div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="info-card">
                <div class="card-header">
                    <span class="material-icons">contact_phone</span>
                    <h3>Contact Information</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-icon">
                            <span class="material-icons">phone</span>
                        </div>
                        <div class="info-details">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value"><?php echo htmlspecialchars($clientData['contact']); ?></div>
                        </div>
                        <a href="?change_contact=1" class="action-btn">
                            <span class="material-icons">edit</span>
                        </a>
                    </div>

                    <div class="info-row">
                        <div class="info-icon">
                            <span class="material-icons">email</span>
                        </div>
                        <div class="info-details">
                            <div class="info-label">Email Address</div>
                            <div class="info-value"><?php echo htmlspecialchars($clientData['email']); ?></div>
                        </div>
                        <a href="?change_email=1" class="action-btn">
                            <span class="material-icons">edit</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Security Card -->
            <div class="security-card">
                <div class="card-header">
                    <span class="material-icons">security</span>
                    <h3>Security Settings</h3>
                </div>
                <div class="card-body">
                    <div class="security-item">
                        <div class="security-icon">
                            <span class="material-icons">lock</span>
                        </div>
                        <div class="security-details">
                            <div class="security-title">Password</div>
                            <div class="security-desc">Change your account password</div>
                        </div>
                        <a href="?change_password=1" class="btn-primary">
                            Change Password
                        </a>
                    </div>
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