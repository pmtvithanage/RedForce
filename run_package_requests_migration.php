<?php
require_once './app/config/config.php';
require_once './app/libraries/Database.php';

$db = new Database();

try {
    echo "Running migration: update_package_requests_personnel.sql\n";
    
    // Add new personnel columns
    $db->query("ALTER TABLE package_requests 
                ADD COLUMN IF NOT EXISTS number_of_officers INT DEFAULT 0 AFTER number_of_guards");
    $db->execute();
    echo "✓ Added number_of_officers column\n";
    
    $db->query("ALTER TABLE package_requests 
                ADD COLUMN IF NOT EXISTS number_of_supervisors INT DEFAULT 0 AFTER number_of_officers");
    $db->execute();
    echo "✓ Added number_of_supervisors column\n";
    
    $db->query("ALTER TABLE package_requests 
                ADD COLUMN IF NOT EXISTS number_of_caretakers INT DEFAULT 0 AFTER number_of_supervisors");
    $db->execute();
    echo "✓ Added number_of_caretakers column\n";
    
    $db->query("ALTER TABLE package_requests 
                ADD COLUMN IF NOT EXISTS site_id INT DEFAULT NULL AFTER draft_site_id");
    $db->execute();
    echo "✓ Added site_id column\n";
    
    // Add foreign key - check if it exists first
    $db->query("SELECT COUNT(*) as count FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_SCHEMA = :db 
                AND TABLE_NAME = 'package_requests' 
                AND CONSTRAINT_NAME = 'fk_package_request_site'");
    $db->bind(':db', DB_NAME);
    $result = $db->single();
    
    if ($result->count == 0) {
        $db->query("ALTER TABLE package_requests 
                    ADD CONSTRAINT fk_package_request_site 
                    FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE SET NULL");
        $db->execute();
        echo "✓ Added foreign key constraint\n";
    } else {
        echo "✓ Foreign key constraint already exists\n";
    }
    
    // Add index - check if it exists first
    $db->query("SELECT COUNT(*) as count FROM information_schema.STATISTICS 
                WHERE TABLE_SCHEMA = :db 
                AND TABLE_NAME = 'package_requests' 
                AND INDEX_NAME = 'idx_site_id'");
    $db->bind(':db', DB_NAME);
    $result = $db->single();
    
    if ($result->count == 0) {
        $db->query("ALTER TABLE package_requests ADD INDEX idx_site_id (site_id)");
        $db->execute();
        echo "✓ Added index on site_id\n";
    } else {
        echo "✓ Index on site_id already exists\n";
    }
    
    echo "\n✓ Migration completed successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Migration failed: " . $e->getMessage() . "\n";
}
