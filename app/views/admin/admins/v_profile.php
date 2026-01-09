<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
  <?php require_once APP_ROOT . '/views/components/v_adminsidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Profile CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/components/profile_style.css">

<style>
    .avatar-circle-large {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f0f0f0; /* Fallback color if image fails to load */
    border: 4px solid #ffffff; /* Optional: white border */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Optional: subtle shadow */
}

.profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures the image covers the circle without distortion */
    object-position: center; /* Centers the image focus */
}

/* Optional: Hover effect */
.avatar-circle-large:hover .profile-image {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

.btn-secondary{
    margin-left:auto; 
}
</style>

<div class="main-content">
    <div class="profile-container">
        


        <div class="profile-grid" style="margin-top:80px;">
            <!-- Profile Card -->
            <div class="profile-card">
                <div class="card-header">
                    <span class="material-icons">account_circle</span>
                    <h3>Profile Information</h3>
                </div>
                <div class="card-body">
                    <div class="profile-avatar-large">
                        <div class="avatar-circle-large">
                            <img class="profile-image" src="<?php echo URL_ROOT; ?>/uploads/image/<?php echo $data['admin']->profile_image; ?>" alt="Profile Image"> 
                        </div>
                    </div>
                    <div class="profile-name">
                        <?php echo $data['admin']->name; ?>
                    </div>
                    <div class="profile-role">Admin</div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="info-card">
                <div class="card-header">
                    <span class="material-icons">contact_phone</span>
                    <h3>Contact Information</h3>

                    <button class="btn btn-secondary" onclick="window.location.href='<?php echo URL_ROOT; ?>/admin/edit_profile/<?php echo $data['admin']->userID; ?>'">
                        <span class="material-icons">edit</span> Edit Profile
                    </button>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-icon">
                            <span class="material-icons">phone</span>
                        </div>
                        <div class="info-details">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value"><?php echo $data['admin']->phone_number; ?></div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-icon">
                            <span class="material-icons">email</span>
                        </div>
                        <div class="info-details">
                            <div class="info-label">Email Address</div>
                            <div class="info-value"><?php echo $data['admin']->email; ?></div>
                        </div>
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