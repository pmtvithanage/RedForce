<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_client_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/client/dashboard_style.css">

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Back Button -->
<div class="main-content">
    <button class="tertiary-btn back-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/client/dashboard'">
        <span class="material-icons">arrow_back</span>
        Back
    </button>

    <!-- Incidents Container -->
    <div class="incidents-container">
        <div class="incidents-header">
            <h2>Incident Reports</h2>
        </div>

        <!-- Content will be added by team member -->
        
    </div>
</div>

<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
