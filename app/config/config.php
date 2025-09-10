<?php

    // Database configuration and connection logic
    // Database constants
    // These constants should match your database configuration
    // Update these values according to your database setup
    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASSWORD","");
    define("DB_NAME","REDFORCE_db");

    define('APP_ROOT', dirname(__FILE__,2));

    // URL Root
    define('URL_ROOT', 'http://localhost/RedForce');

    // Website Name
    define('SITE_NAME', 'Red Force Security Solutions');

    // Pub Root
    define('PUB_ROOT', dirname(__FILE__,3) . '/public');

    // tail -f /Applications/XAMPP/xamppfiles/logs/php_error_log

?>