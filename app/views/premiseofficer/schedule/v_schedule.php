<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>
<?php require_once APP_ROOT . '/views/components/v_premiseofficer_sidebar.php'; ?>


<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">


<?php require_once APP_ROOT . '/views/components/calender.php'; ?>
<!-- Pass PHP data to JavaScript -->
<script>
    // Assignment data
    const assignmentData = <?php echo json_encode($data['assignments'] ?? []); ?>;
    
    // Leave dates data
    const leaveDatesData = <?php echo json_encode($data['leaveDates'] ?? []); ?>;
    
    // Base URL for AJAX requests
    const baseURL = '<?php echo URL_ROOT; ?>';
    
    // Debug output
    console.log('Assignment Data:', assignmentData);
    console.log('Leave Dates Data:', leaveDatesData);
    console.log('Base URL:', baseURL);
</script>

<script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/premiseOfficer/schedule.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>