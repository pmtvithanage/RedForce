-- Add last_seen and is_online to Users table for online status tracking
ALTER TABLE Users 
ADD COLUMN is_online TINYINT(1) DEFAULT 0 AFTER password,
ADD COLUMN last_seen DATETIME NULL AFTER is_online;

-- Add index for online status queries
ALTER TABLE Users
ADD INDEX idx_is_online (is_online),
ADD INDEX idx_last_seen (last_seen);

-- Add read_at timestamp to messages table to track when message was seen
ALTER TABLE messages 
ADD COLUMN read_at DATETIME NULL AFTER is_read;

-- Add index for read_at
ALTER TABLE messages
ADD INDEX idx_read_at (read_at);
