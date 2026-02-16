<?php
/**
 * Install payment_pending table
 * Run this file once to create the payment_pending table
 */

// Load the bootstrap file
require_once '../app/bootloader.php';

// Create database connection
$db = new Database();

echo "Installing payment_pending table...\n";

try {
    // Read SQL file
    $sql = file_get_contents(__DIR__ . '/dev/create_payment_pending_table.sql');
    
    // Split by semicolons and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        $db->query($statement);
        $db->execute();
    }
    
    echo "✓ payment_pending table created successfully!\n";
    echo "The payment system is now ready to process payments.\n";
    
} catch (Exception $e) {
    echo "✗ Error creating table: " . $e->getMessage() . "\n";
    exit(1);
}
