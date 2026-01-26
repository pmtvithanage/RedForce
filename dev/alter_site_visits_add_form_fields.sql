-- Migration: Add form fields to site_visits table
-- Date: 2026-01-26
-- Description: Adds fields for officer attendance, activities, site condition, issues, and notes

USE redforce_db;

-- Add new columns to site_visits table
ALTER TABLE site_visits
ADD COLUMN IF NOT EXISTS officer_attendance_satisfactory TINYINT(1) DEFAULT 0 COMMENT '1 = satisfactory, 0 = not satisfactory' AFTER visit_time,
ADD COLUMN IF NOT EXISTS officer_activities TEXT DEFAULT NULL COMMENT 'Description of officer activities observed' AFTER officer_attendance_satisfactory,
ADD COLUMN IF NOT EXISTS site_condition ENUM('Excellent', 'Good', 'Fair', 'Poor') DEFAULT 'Good' AFTER officer_activities,
ADD COLUMN IF NOT EXISTS issues_found TEXT DEFAULT NULL COMMENT 'Any issues or concerns identified' AFTER site_condition,
ADD COLUMN IF NOT EXISTS notes TEXT DEFAULT NULL COMMENT 'Additional notes about the visit' AFTER issues_found;

-- Verify the changes
DESCRIBE site_visits;
