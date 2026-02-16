-- Create payment_pending table to temporarily store payment data before PayHere processing
CREATE TABLE IF NOT EXISTS payment_pending (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    site_data TEXT COMMENT 'JSON array of site payment data',
    request_data TEXT COMMENT 'JSON array of pending request payment data',
    client_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_order_id (order_id),
    INDEX idx_client_id (client_id),
    FOREIGN KEY (client_id) REFERENCES Users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Temporary storage for payment data before PayHere processing';

-- Add index for faster lookups
ALTER TABLE payment_pending ADD INDEX idx_created_at (created_at);

-- Clean up old pending payments (older than 24 hours) - optional maintenance
-- This can be run periodically or as a cron job
-- DELETE FROM payment_pending WHERE created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR);
