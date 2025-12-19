
USE REDFORCE_db;

-- Add columns for admin approval functionality
ALTER TABLE leave_requests 
ADD COLUMN IF NOT EXISTS admin_response TEXT NULL COMMENT 'Reason for rejection' AFTER status,
ADD COLUMN IF NOT EXISTS reviewed_by INT(11) NULL COMMENT 'Admin user ID who reviewed' AFTER admin_response,
ADD COLUMN IF NOT EXISTS reviewed_at TIMESTAMP NULL COMMENT 'When the request was reviewed' AFTER reviewed_by;

-- Add foreign key constraint for reviewed_by
ALTER TABLE leave_requests
ADD CONSTRAINT fk_reviewed_by 
FOREIGN KEY (reviewed_by) REFERENCES users(id) 
ON DELETE SET NULL;

-- Show the updated table structure
DESCRIBE leave_requests;
