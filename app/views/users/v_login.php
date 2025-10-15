<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>/css/users/login_style.css">

<div class="login-container">
    <!-- Left Section - Red Gradient with Emblem -->
    <div class="login-left">
        <div class="back-arrow">
            <a href="<?php echo URL_ROOT; ?>" class="back-btn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5M12 19L5 12L12 5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
        
        <div class="emblem-container">
            <div class="emblem-lion">
                <img src="<?php echo URL_ROOT; ?>/public/img/logo.png" alt="RED FORCE Emblem">
            </div>
        </div>
    </div>

    <!-- Right Section - Dark Background with Login Form -->
    <div class="login-right" style="background-image: url('<?php echo URL_ROOT; ?>/img/login-back.png');">
        <div class="security-bg"></div>
        <div class="login-form-container">
            <h1 class="login-title">login</h1>
            
            <?php if (isset($data['error'])): ?>
                <div class="error-message"><?php echo $data['error']; ?></div>
            <?php endif; ?>
            
            <?php flash('login_error'); ?>
            <?php flash('access_error'); ?>
            
            <form class="login-form" method="POST" action="<?php echo URL_ROOT; ?>/Users/login">
                <div class="form-group">
                    <input type="text" id="userID" name="userID" class="form-input" placeholder="User ID" value="<?php echo $data['userID'] ?? ''; ?>" required>
                    <div class="input-underline"></div>
                    <?php if (!empty($data['userID_err'])): ?>
                        <span class="error-text"><?php echo $data['userID_err']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <input type="password" id="password" name="password" class="form-input" placeholder="Password" required>
                    <div class="input-underline"></div>
                    <?php if (!empty($data['password_err'])): ?>
                        <span class="error-text"><?php echo $data['password_err']; ?></span>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="login-button">login</button>
            </form>
            
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>