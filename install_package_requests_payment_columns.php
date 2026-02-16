<?php
/**
 * Installation script for adding payment tracking columns to package_requests table
 * Run this file once to add payment_status and payment_id columns
 */

require_once 'app/config/config.php';

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASSWORD;
    private $dbname = DB_NAME;
    
    private $dbh;
    private $stmt;
    private $error;
    
    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo "Connection Error: " . $this->error;
            die();
        }
    }
    
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }
    
    public function execute() {
        return $this->stmt->execute();
    }
}

echo "=== Package Requests Payment Columns Migration ===\n\n";

try {
    $db = new Database();
    
    echo "Step 1: Checking if columns already exist...\n";
    $db->query("SHOW COLUMNS FROM package_requests LIKE 'payment_status'");
    $db->execute();
    
    echo "Step 2: Adding payment_status column...\n";
    $db->query("ALTER TABLE package_requests 
                ADD COLUMN payment_status ENUM('unpaid', 'paid') DEFAULT 'unpaid' AFTER status");
    
    if ($db->execute()) {
        echo "✓ payment_status column added successfully\n";
    }
    
    echo "Step 3: Adding payment_id column...\n";
    $db->query("ALTER TABLE package_requests 
                ADD COLUMN payment_id VARCHAR(100) DEFAULT NULL AFTER payment_status");
    
    if ($db->execute()) {
        echo "✓ payment_id column added successfully\n";
    }
    
    echo "Step 4: Adding index for payment_status...\n";
    $db->query("ALTER TABLE package_requests 
                ADD INDEX idx_payment_status (payment_status)");
    
    if ($db->execute()) {
        echo "✓ Index added successfully\n";
    }
    
    echo "Step 5: Updating existing records...\n";
    $db->query("UPDATE package_requests SET payment_status = 'unpaid' WHERE payment_status IS NULL");
    
    if ($db->execute()) {
        echo "✓ Existing records updated\n";
    }
    
    echo "\n=== Migration completed successfully! ===\n";
    echo "\nChanges made:\n";
    echo "- Added payment_status column (ENUM: 'unpaid', 'paid')\n";
    echo "- Added payment_id column (VARCHAR(100))\n";
    echo "- Added index on payment_status for better query performance\n";
    echo "- Updated existing records to have 'unpaid' status\n";
    
} catch(PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "\n⚠ Columns already exist. Migration may have been run previously.\n";
    } else {
        echo "\n✗ Error: " . $e->getMessage() . "\n";
        echo "\nPlease check your database connection and try again.\n";
    }
}

echo "\nYou can now process payments and the package_requests table will be updated automatically.\n";
?>
