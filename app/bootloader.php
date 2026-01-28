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
    
    // Update user's last_seen and is_online status if logged in
    if (isset($_SESSION['user_id'])) {
        $db = new Database();
        $db->query("UPDATE Users SET last_seen = NOW(), is_online = 1 WHERE id = :user_id");
        $db->bind(':user_id', $_SESSION['user_id']);
        $db->execute();
    }
?>