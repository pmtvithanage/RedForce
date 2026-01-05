<?php

    // Load configuration
    require_once 'config/config.php';
    
    // Load helpers
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once 'helpers/auth_helper.php';
    require_once 'helpers/image_upload_helper.php';
    require_once 'helpers/Time_Convert_Helper.php';
    require_once 'helpers/email_helper.php';
    //require_once 'helpers/session_helper.php';
    
    // Load libraries and configurations
    require_once 'libraries/Core.php';
    require_once 'libraries/Controller.php';
    require_once 'libraries/Database.php';
?>