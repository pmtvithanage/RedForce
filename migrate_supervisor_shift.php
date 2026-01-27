<?php
// Run this file once to add 'Supervisor' to shift_type ENUM

require_once 'app/config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "ALTER TABLE officer_site_assignments 
            MODIFY COLUMN shift_type ENUM('Day', 'Night', 'Full Time', 'Flexible', 'Supervisor') DEFAULT 'Full Time'";
    
    $pdo->exec($sql);
    
    echo "✓ Successfully added 'Supervisor' to shift_type ENUM values\n";
    echo "You can now assign supervisors to sites.\n";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
