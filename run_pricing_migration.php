<?php
/**
 * Run the custom package pricing migration
 * This adds price_per_officer, price_per_supervisor, and price_per_caretaker columns to packages table
 */

require_once 'app/bootloader.php';

// Create database instance
$db = new Database();

try {
    echo "Running migration: add_custom_package_pricing.sql\n\n";
    
    // Add price_per_officer column
    echo "Adding price_per_officer column...\n";
    $db->query("ALTER TABLE packages ADD COLUMN IF NOT EXISTS price_per_officer DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Price for each officer in custom packages'");
    $db->execute();
    echo "✓ Success\n\n";
    
    // Add price_per_supervisor column
    echo "Adding price_per_supervisor column...\n";
    $db->query("ALTER TABLE packages ADD COLUMN IF NOT EXISTS price_per_supervisor DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Price for each supervisor in custom packages'");
    $db->execute();
    echo "✓ Success\n\n";
    
    // Add price_per_caretaker column
    echo "Adding price_per_caretaker column...\n";
    $db->query("ALTER TABLE packages ADD COLUMN IF NOT EXISTS price_per_caretaker DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Price for each caretaker in custom packages'");
    $db->execute();
    echo "✓ Success\n\n";
    
    // Set default prices for Custom Package
    echo "Setting default prices for Custom Package...\n";
    $db->query("UPDATE packages 
                SET price_per_officer = 15000.00,
                    price_per_supervisor = 20000.00,
                    price_per_caretaker = 12000.00
                WHERE package_name = 'Custom Package'");
    $db->execute();
    echo "✓ Success\n\n";
    
    echo "\nMigration completed successfully!\n";
    echo "The packages table now has:\n";
    echo "- price_per_officer (15,000 LKR for Custom Package)\n";
    echo "- price_per_supervisor (20,000 LKR for Custom Package)\n";
    echo "- price_per_caretaker (12,000 LKR for Custom Package)\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
