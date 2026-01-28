-- Messages Table Installation
-- Run this file to create the messages table for the messaging system
-- This table stores all messages between users in the RedForce system

-- Drop existing table if it exists
DROP TABLE IF EXISTS messages;

-- Create messages table
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL COMMENT 'References Users.id',
    recipient_id INT NOT NULL COMMENT 'References Users.id',
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes for better performance
    INDEX idx_sender (sender_id),
    INDEX idx_recipient (recipient_id),
    INDEX idx_conversation (sender_id, recipient_id),
    INDEX idx_read_status (is_read),
    INDEX idx_created (created_at),
    
    -- Foreign keys to ensure data integrity
    FOREIGN KEY (sender_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES Users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Stores all messages between users in the system';

-- Sample data for testing (optional - remove if not needed)
-- INSERT INTO messages (sender_id, recipient_id, message, is_read, created_at) VALUES
-- (1, 2, 'Test message from user 1 to user 2', FALSE, NOW()),
-- (2, 1, 'Reply from user 2 to user 1', TRUE, NOW());
