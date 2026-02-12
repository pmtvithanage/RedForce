-- Update package_requests table to support new personnel types
-- This migration adds columns for officers, supervisors, and caretakers
-- and adds site_id to link existing sites to package requests

-- Add new personnel columns
ALTER TABLE package_requests 
ADD COLUMN IF NOT EXISTS number_of_officers INT DEFAULT 0 AFTER number_of_guards,
ADD COLUMN IF NOT EXISTS number_of_supervisors INT DEFAULT 0 AFTER number_of_officers,
ADD COLUMN IF NOT EXISTS number_of_caretakers INT DEFAULT 0 AFTER number_of_supervisors,
ADD COLUMN IF NOT EXISTS site_id INT DEFAULT NULL AFTER draft_site_id;

-- Add foreign key for site_id
ALTER TABLE package_requests 
ADD CONSTRAINT fk_package_request_site 
FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE SET NULL;

-- Add index for faster queries
ALTER TABLE package_requests 
ADD INDEX idx_site_id (site_id);
