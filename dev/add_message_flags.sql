-- Add is_edited and is_deleted columns to messages table

ALTER TABLE messages 
ADD COLUMN is_edited TINYINT(1) DEFAULT 0 AFTER is_read,
ADD COLUMN is_deleted TINYINT(1) DEFAULT 0 AFTER is_edited;

-- Add index for is_deleted to improve query performance
ALTER TABLE messages
ADD INDEX idx_is_deleted (is_deleted);
