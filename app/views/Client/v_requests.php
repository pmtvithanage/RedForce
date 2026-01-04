<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/components/flash_msg.css">

<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Link to Requests CSS -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/requests_style.css">

<div class="main-content">
    <?php flash('package_success'); ?>
    <?php flash('package_error'); ?>

    <div class="page-header">
        <a href="<?php echo URL_ROOT; ?>/client/packageHistory" class="secondary-btn">
            <span class="material-icons">history</span>
            View History
        </a>
    </div>

    <!-- Package Cards Grid -->
    <div class="packages-grid">
        <!-- Basic Package -->
        <a href="<?php echo URL_ROOT; ?>/client/basicPackage" class="package-card">
            <div class="package-header basic-header">
                <h3>Basic Package :</h3>
                <p class="officer-count">2 officers</p>
            </div>
            <div class="package-image">
                <img src="<?php echo URL_ROOT; ?>/img/SecurityOfficer.png" alt="Security Officers">
            </div>
            <div class="package-price">
                <span class="price">LKR 30,000/=</span>
                <span class="period">/monthly</span>
                <p class="shift-info">(Night shift included)</p>
            </div>
        </a>

        <!-- Budget Package -->
        <a href="<?php echo URL_ROOT; ?>/client/budgetPackage" class="package-card">
            <div class="package-header budget-header">
                <h3>Budget Package :</h3>
                <p class="officer-count">4 officers</p>
            </div>
            <div class="package-image">
                <img src="<?php echo URL_ROOT; ?>/img/SecurityOfficer.png" alt="Security Officers">
            </div>
            <div class="package-price">
                <span class="price">LKR 56,000/=</span>
                <span class="period">/monthly</span>
                <p class="shift-info">(Night shift included)</p>
            </div>
        </a>

        <!-- Vigilant Package -->
        <a href="<?php echo URL_ROOT; ?>/client/vigilantPackage" class="package-card">
            <div class="package-header vigilant-header">
                <h3>Vigilant Package :</h3>
                <p class="officer-count">8 officers</p>
            </div>
            <div class="package-image">
                <img src="<?php echo URL_ROOT; ?>/img/SecurityOfficer.png" alt="Security Officers">
            </div>
            <div class="package-price">
                <span class="price">LKR 110,000/=</span>
                <span class="period">/monthly</span>
                <p class="shift-info">(Night shift included)</p>
            </div>
        </a>

        <!-- Pro Package -->
        <a href="<?php echo URL_ROOT; ?>/client/proPackage" class="package-card">
            <div class="package-header pro-header">
                <h3>Pro Package :</h3>
                <p class="officer-count">10 officers</p>
            </div>
            <div class="package-image">
                <img src="<?php echo URL_ROOT; ?>/img/SecurityOfficer.png" alt="Security Officers">
            </div>
            <div class="package-price">
                <span class="price">LKR 138,000/=</span>
                <span class="period">/monthly</span>
                <p class="shift-info">(Night shift included)</p>
            </div>
        </a>

        <!-- Ultra Package -->
        <a href="<?php echo URL_ROOT; ?>/client/ultraPackage" class="package-card">
            <div class="package-header ultra-header">
                <h3>Ultra Package :</h3>
                <p class="officer-count">12 officers</p>
            </div>
            <div class="package-image">
                <img src="<?php echo URL_ROOT; ?>/img/SecurityOfficer.png" alt="Security Officers">
            </div>
            <div class="package-price">
                <span class="price">LKR 167,000/=</span>
                <span class="period">/monthly</span>
                <p class="shift-info">(Night shift included)</p>
            </div>
        </a>

        <!-- Custom Package -->
        <a href="<?php echo URL_ROOT; ?>/client/customPackage" class="package-card">
            <div class="package-header custom-header">
                <h3>Customize your</h3>
                <p class="officer-count">package</p>
            </div>
            <div class="package-image">
                <img src="<?php echo URL_ROOT; ?>/img/SecurityOfficer.png" alt="Security Officers">
            </div>
            <div class="package-price custom-price">
                <span class="material-icons">settings</span>
                <p class="custom-text">Build Your Own</p>
            </div>
        </a>
    </div>
</div>

</main>
</div>

<div class="backdrop" id="backdrop" hidden></div>
<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>