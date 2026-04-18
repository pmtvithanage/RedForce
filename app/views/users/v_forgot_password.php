<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>/css/users/forgot_password_style.css">

<div class="forgot-wrapper">
    <div class="forgot-card">
        <h1 class="forgot-title">Forgot Password</h1>
        <p class="forgot-text">Enter your User ID. A temporary reset password will be sent to your email and is valid for 1 minute.</p>

        <form class="forgot-form" method="POST" action="<?php echo URL_ROOT; ?>/Users/forgotPassword">
            <label for="userID" class="forgot-label">User ID</label>
            <input
                type="text"
                id="userID"
                name="userID"
                class="forgot-input"
                placeholder="Enter your User ID"
                value="<?php echo $data['userID'] ?? ''; ?>"
                required
            >

            <?php if (!empty($data['userID_err'])): ?>
                <span class="forgot-error"><?php echo $data['userID_err']; ?></span>
            <?php endif; ?>

            <?php if (!empty($data['email_err'])): ?>
                <span class="forgot-error"><?php echo $data['email_err']; ?></span>
            <?php endif; ?>

            <button type="submit" class="forgot-btn">Submit Request</button>
        </form>

        <a href="<?php echo URL_ROOT; ?>/Users/login" class="back-login-link">Back to Login</a>
    </div>
</div>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
