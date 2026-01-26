-- Quick Deploy Script for Incident Reports System
-- Run this file to update your database with the new incident_reports schema

USE redforce_db;

-- Add new columns to incident_reports table
ALTER TABLE incident_reports
ADD COLUMN IF NOT EXISTS site_id BIGINT UNSIGNED NULL COMMENT 'Foreign key to sites table' AFTER property_site,
ADD COLUMN IF NOT EXISTS priority ENUM('Low', 'Medium', 'High', 'Critical') DEFAULT 'Medium' COMMENT 'Incident priority level' AFTER severity,
ADD COLUMN IF NOT EXISTS people_involved TEXT NULL COMMENT 'Names of people involved' AFTER additional_details,
ADD COLUMN IF NOT EXISTS latitude DECIMAL(10, 8) NULL COMMENT 'Incident location latitude' AFTER people_involved,
ADD COLUMN IF NOT EXISTS longitude DECIMAL(11, 8) NULL COMMENT 'Incident location longitude' AFTER latitude,
ADD COLUMN IF NOT EXISTS status ENUM('Pending', 'In Progress', 'Resolved', 'Closed') DEFAULT 'Pending' COMMENT 'Incident status' AFTER longitude;

-- Add foreign key constraint
ALTER TABLE incident_reports
ADD CONSTRAINT fk_incident_site 
FOREIGN KEY (site_id) REFERENCES sites(id) 
ON DELETE SET NULL;

-- Add performance indexes
CREATE INDEX idx_site_id ON incident_reports(site_id);
CREATE INDEX idx_priority ON incident_reports(priority);
CREATE INDEX idx_status ON incident_reports(status);
CREATE INDEX idx_incident_date ON incident_reports(incident_date);

-- Make property_site nullable (backward compatibility)
ALTER TABLE incident_reports
MODIFY property_site VARCHAR(50) NULL;

-- Optional: Migrate existing data from property_site to site_id
-- Uncomment the following lines if you want to migrate existing data:
-- UPDATE incident_reports ir
-- SET site_id = (
--     SELECT id FROM sites 
--     WHERE CAST(sites.id AS CHAR) = ir.property_site 
--     OR sites.site_name = ir.property_site
--     LIMIT 1
-- )
-- WHERE ir.property_site IS NOT NULL AND ir.site_id IS NULL;

SELECT 'Incident reports table updated successfully!' as status;
