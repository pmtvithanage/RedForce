<?php
/**
 * Migration Script: Add payment tracking columns to package_requests table
 * Run this file once to add payment_status and payment_id columns
 */

require_once './app/config/config.php';
require_once './app/libraries/Database.php';

$db = new Database();

try {
    echo "Starting migration: Add payment columns to package_requests table\n";
    echo "========================================\n\n";

    // Check if columns already exist
    $db->query("SHOW COLUMNS FROM package_requests LIKE 'payment_status'");
    $paymentStatusExists = $db->single();

    $db->query("SHOW COLUMNS FROM package_requests LIKE 'payment_id'");
    $paymentIdExists = $db->single();

    if ($paymentStatusExists && $paymentIdExists) {
        echo "✓ Columns already exist. No migration needed.\n";
        exit(0);
    }

    // Add payment_status column
    if (!$paymentStatusExists) {
        echo "Adding payment_status column...\n";
        $db->query("ALTER TABLE package_requests 
                    ADD COLUMN payment_status ENUM('unpaid', 'paid') DEFAULT 'unpaid' AFTER status");
        $db->execute();
        echo "✓ Added payment_status column\n";
    } else {
        echo "✓ payment_status column already exists\n";
    }

    // Add payment_id column
    if (!$paymentIdExists) {
        echo "Adding payment_id column...\n";
        $db->query("ALTER TABLE package_requests 
                    ADD COLUMN payment_id VARCHAR(100) DEFAULT NULL AFTER payment_status");
        $db->execute();
        echo "✓ Added payment_id column\n";
    } else {
        echo "✓ payment_id column already exists\n";
    }

    // Add index on payment_status
    echo "Adding index on payment_status...\n";
    try {
        $db->query("ALTER TABLE package_requests ADD INDEX idx_payment_status (payment_status)");
        $db->execute();
        echo "✓ Added index on payment_status\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "✓ Index already exists\n";
        } else {
            throw $e;
        }
    }

    // Update existing records to have unpaid status
    echo "Updating existing records...\n";
    $db->query("UPDATE package_requests SET payment_status = 'unpaid' WHERE payment_status IS NULL");
    $db->execute();
    $rowsUpdated = $db->rowCount();
    echo "✓ Updated {$rowsUpdated} records to 'unpaid' status\n";

    echo "\n========================================\n";
    echo "Migration completed successfully!\n";
    echo "========================================\n";

    // Show table structure
    echo "\nCurrent table structure:\n";
    $db->query("DESCRIBE package_requests");
    $columns = $db->resultSet();
    
    echo "\nColumns related to payment:\n";
    foreach ($columns as $column) {
        if (strpos($column->Field, 'payment') !== false || strpos($column->Field, 'status') !== false) {
            echo "  - {$column->Field}: {$column->Type} (Default: {$column->Default})\n";
        }
    }

} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}
?>
