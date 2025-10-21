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
        // Handle password change
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        // Validate and update password here
        $message = 'Password updated successfully!';
    }
    
    if (isset($_POST['change_contact'])) {
        // Handle contact change
        $newContact = $_POST['new_contact'];
        // Update contact in database
        $clientData['contact'] = $newContact;
        $message = 'Contact number updated successfully!';
    }
    
    if (isset($_POST['change_email'])) {
        // Handle email change
        $newEmail = $_POST['new_email'];
        // Update email in database
        $clientData['email'] = $newEmail;
        $message = 'Email updated successfully!';
    }
}

// Check for modal triggers
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
            <h3>Change Settings</h3>
            <a href="?" class="close-btn">&times;</a>
        </div>
        <div class="modal-content">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Enter your current password:</label>
                    <input type="password" name="current_password" required>
                </div>
                
                <div class="form-group">
                    <label>Enter new password:</label>
                    <input type="password" name="new_password" required>
                </div>
                
                <div class="form-group">
                    <label>Confirm Password:</label>
                    <input type="password" name="confirm_password" required>
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
                    <label>Enter new contact number:</label>
                    <input type="tel" name="new_contact" value="<?php echo htmlspecialchars($clientData['contact']); ?>" required>
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
            <h3>Change Email</h3>
            <a href="?" class="close-btn">&times;</a>
        </div>
        <div class="modal-content">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Enter new email:</label>
                    <input type="email" name="new_email" value="<?php echo htmlspecialchars($clientData['email']); ?>" required>
                </div>
                
                <button type="submit" name="change_email" class="save-btn">Save Changes</button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="main-content">
    <div class="settings-container">
        <div class="settings-header">
            <h2 class="page-title">Settings</h2>
        </div>
        
        <?php if ($message): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>
        
        <div class="profile-section">
            <div class="profile-avatar">
                <div class="avatar-circle">
                    <span class="material-icons">person</span>
                </div>
            </div>
            
            <div class="profile-info">
                <div class="info-group">
                    <div class="info-item">
                        <div class="info-label">Client Name -</div>
                        <div class="info-value"><?php echo htmlspecialchars($clientData['name']); ?></div>
                    </div>
                </div>
                
                <div class="info-group password-row">
                    <a href="?change_password=1" class="change-btn">Change Password</a>
                </div>
                
                <div class="info-group">
                    <div class="info-item">
                        <div class="info-label">Contact Number -</div>
                        <div class="info-value"><?php echo htmlspecialchars($clientData['contact']); ?></div>
                    </div>
                    <a href="?change_contact=1" class="change-btn">Change Contact No</a>
                </div>
                
                <div class="info-group">
                    <div class="info-item">
                        <div class="info-label">Email -</div>
                        <div class="info-value"><?php echo htmlspecialchars($clientData['email']); ?></div>
                    </div>
                    <a href="?change_email=1" class="change-btn">Change Email</a>
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