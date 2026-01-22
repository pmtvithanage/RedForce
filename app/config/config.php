<?php

    // Database configuration and connection logic
    // Database constants
    // These constants should match your database configuration
    // Update these values according to your database setup
    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASSWORD","");
    define("DB_NAME","redforce_db");

    define('APP_ROOT', dirname(__FILE__,2));

    // URL Root
    define('URL_ROOT', 'http://localhost/RedForce');

    // Website Name
    define('SITE_NAME', 'Red Force Security Solutions');

    // Pub Root
    define('PUB_ROOT', dirname(__FILE__,3) . '/public');

    // SMTP settings for PHPMailer
    define('SMTP_HOST', 'smtp.gmail.com');
    define('SMTP_PORT', 587);
    define('SMTP_USER', 'pmihirangaa321@gmail.com');
    define('SMTP_PASS', 'dlov khkx gdno lumy');
    define('SMTP_SECURE', 'tls'); // 'tls' or 'ssl'
    define('SMTP_FROM', 'no-reply@redforce.com');
    define('SMTP_FROM_NAME', 'Red Force');

    // tail -f /Applications/XAMPP/xamppfiles/logs/php_error_log

?>