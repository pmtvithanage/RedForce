-- ============================================
-- UPDATE INCIDENT REPORTS TABLE
-- This migration updates the incident_reports table to match the new form structure
-- ============================================

-- First, backup existing data (optional but recommended)
-- CREATE TABLE incident_reports_backup AS SELECT * FROM incident_reports;

-- Add new columns if they don't exist
ALTER TABLE incident_reports
ADD COLUMN IF NOT EXISTS site_id BIGINT UNSIGNED NULL COMMENT 'Foreign key to sites table' AFTER property_site,
ADD COLUMN IF NOT EXISTS priority ENUM('Low', 'Medium', 'High', 'Critical') DEFAULT 'Medium' COMMENT 'Incident priority level' AFTER severity,
ADD COLUMN IF NOT EXISTS people_involved TEXT NULL COMMENT 'Names of people involved' AFTER additional_details,
ADD COLUMN IF NOT EXISTS latitude DECIMAL(10, 8) NULL COMMENT 'Incident location latitude' AFTER people_involved,
ADD COLUMN IF NOT EXISTS longitude DECIMAL(11, 8) NULL COMMENT 'Incident location longitude' AFTER latitude,
ADD COLUMN IF NOT EXISTS status ENUM('Pending', 'In Progress', 'Resolved', 'Closed') DEFAULT 'Pending' COMMENT 'Incident status' AFTER longitude;

-- Add foreign key constraint for site_id
ALTER TABLE incident_reports
ADD CONSTRAINT fk_incident_site 
FOREIGN KEY (site_id) REFERENCES sites(id) 
ON DELETE SET NULL;

-- Add indexes for better performance
ALTER TABLE incident_reports
ADD INDEX IF NOT EXISTS idx_site_id (site_id),
ADD INDEX IF NOT EXISTS idx_priority (priority),
ADD INDEX IF NOT EXISTS idx_status (status),
ADD INDEX IF NOT EXISTS idx_incident_date (incident_date);

-- Update property_site to be nullable since we're using site_id now
ALTER TABLE incident_reports
MODIFY property_site VARCHAR(50) NULL;

-- Note: You can migrate existing data from property_site to site_id if needed:
-- UPDATE incident_reports ir
-- SET site_id = (SELECT id FROM sites WHERE id = ir.property_site OR site_name = ir.property_site)
-- WHERE ir.property_site IS NOT NULL;
