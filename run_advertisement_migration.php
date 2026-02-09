<?php
/**
 * Migration Script: Add description column to advertisements table
 * Run this file once to update the database schema
 * Access via: http://localhost/RedForce/run_advertisement_migration.php
 */

require_once 'app/config/config.php';
require_once 'app/libraries/Database.php';

try {
    $db = new Database();
    
    // Check if column already exists
    $db->query("SHOW COLUMNS FROM advertisements LIKE 'description'");
    $result = $db->single();
    
    if ($result) {
        echo '<h2>Migration Status: Already Applied</h2>';
        echo '<p>The description column already exists in the advertisements table.</p>';
    } else {
        // Add the description column
        $db->query("ALTER TABLE advertisements ADD COLUMN description TEXT NULL AFTER title");
        $db->execute();
        
        echo '<h2>Migration Status: Success!</h2>';
        echo '<p>Successfully added description column to advertisements table.</p>';
        echo '<p style="color: green; font-weight: bold;">✓ Database schema updated successfully!</p>';
    }
    
    echo '<hr>';
    echo '<p><strong>Note:</strong> You can now delete this file for security.</p>';
    echo '<p><a href="' . URL_ROOT . '/admin/advertisements">Go to Advertisements</a></p>';
    
} catch (PDOException $e) {
    echo '<h2>Migration Status: Failed</h2>';
    echo '<p style="color: red;">Error: ' . $e->getMessage() . '</p>';
    echo '<p>Please run the SQL manually:</p>';
    echo '<pre>ALTER TABLE advertisements ADD COLUMN description TEXT NULL AFTER title;</pre>';
}
?>
