<?php
/**
 * Payments Table Installation Script
 * Run this file once to create the payments table in the database
 */

require_once 'app/config/config.php';
require_once 'app/libraries/Database.php';

$db = new Database();

echo "<h2>Installing Payments Table...</h2>";

try {
    // Read SQL file
    $sql = file_get_contents('dev/create_payments_table.sql');
    
    if ($sql === false) {
        throw new Exception("Could not read SQL file");
    }

    // Split SQL into individual queries
    $queries = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($queries as $query) {
        if (!empty($query)) {
            $db->query($query);
            $db->execute();
            echo "<p style='color: green;'>✓ Query executed successfully</p>";
        }
    }

    echo "<h3 style='color: green;'>✓ Payments table installed successfully!</h3>";
    echo "<p>You can now access the payments page at: <a href='" . URL_ROOT . "/client/payments'>" . URL_ROOT . "/client/payments</a></p>";

} catch (Exception $e) {
    echo "<h3 style='color: red;'>✗ Installation failed!</h3>";
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
