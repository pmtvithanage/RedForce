<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

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
        background-color: #f0f0f0;
        /* Fallback color if image fails to load */
        border: 4px solid #ffffff;
        /* Optional: white border */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        /* Optional: subtle shadow */
    }

    .profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Ensures the image covers the circle without distortion */
        object-position: center;
        /* Centers the image focus */
    }

    /* Optional: Hover effect */
    .avatar-circle-large:hover .profile-image {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }

    .edit-avatar-btn {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s;
    }

    .edit-avatar-btn:hover {
        transform: scale(1.1);
    }

    .profile-avatar-large {
        position: relative;
        width: fit-content;
        margin: 0 auto;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header h3 {
        flex: 1;
        margin: 0;
    }

    .card-header button {
        background: none;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
    }

    .card-header button:hover {
        transform: scale(1.1);
        transition: transform 0.2s;
    }

    .main-content {
        width: 90%;
        margin: 24px auto;
        padding: 0;
        min-height: auto;
    }

    .profile-container {
        max-width: none;
    }

    .profile-grid {
        margin-top: 0 !important;
        gap: 20px;
    }

    .profile-card,
    .info-card,
    .security-card {
        border-radius: 8px;
        border: 1px solid #ececec;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        padding: 16px 20px;
    }

    .card-body {
        padding: 20px;
    }

    @media (max-width: 768px) {
        .main-content {
            width: 95%;
            margin: 16px auto;
        }
    }
</style>

<div class="main-content">
    <div class="profile-container">



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
                            <img class="profile-image" src="<?php echo URL_ROOT; ?>/uploads/clientLogos/<?php echo $data['client']->profile_image; ?>" alt="Profile Image">
                        </div>

                    </div>
                    <div class="profile-name">
                        <?php echo $data['client']->name; ?>
                    </div>
                    <div class="profile-role">Client</div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="info-card">
                <div class="card-header">
                    <span class="material-icons">contact_phone</span>
                    <h3>Contact Information</h3>
                    <button class="action-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/editProfile'">
                        <span class="material-icons">edit</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-icon">
                            <span class="material-icons">phone</span>
                        </div>
                        <div class="info-details">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value"><?php echo $data['client']->phone_number; ?></div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-icon">
                            <span class="material-icons">email</span>
                        </div>
                        <div class="info-details">
                            <div class="info-label">Email Address</div>
                            <div class="info-value"><?php echo $data['client']->email; ?></div>
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
                        <button class="btn-primary" style="border: none; cursor: pointer;" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/editProfile?show=password'">
                            Change Password
                        </button>
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