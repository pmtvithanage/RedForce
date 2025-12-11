<?php
    // Show all errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../app/bootloader.php';

    $core = new Core();
    
?>