-- Add default packages if they don't exist
-- These packages are system defaults and cannot be deleted

-- First, add is_default column if it doesn't exist
ALTER TABLE packages ADD COLUMN IF NOT EXISTS is_default TINYINT(1) DEFAULT 0 COMMENT 'System default packages cannot be deleted';

-- Custom Package (for clients who want to customize)
INSERT INTO packages (package_name, description, number_of_officers, number_of_supervisors, number_of_caretakers, package_price, background_image, status, is_default, created_at)
SELECT 'Custom Package', 'Create a customized security package tailored to your specific requirements and budget.', 0, 0, 0, 0, NULL, 'Active', 1, NOW()
WHERE NOT EXISTS (SELECT 1 FROM packages WHERE package_name = 'Custom Package');

-- Extra Security Officer Package
INSERT INTO packages (package_name, description, number_of_officers, number_of_supervisors, number_of_caretakers, package_price, background_image, status, is_default, created_at)
SELECT 'Extra Security Officer', 'Add one additional security officer to your existing package for enhanced coverage.', 1, 0, 0, 15000, NULL, 'Active', 1, NOW()
WHERE NOT EXISTS (SELECT 1 FROM packages WHERE package_name = 'Extra Security Officer');

-- Extra Supervisor Package
INSERT INTO packages (package_name, description, number_of_supervisors, number_of_officers, number_of_caretakers, package_price, background_image, status, is_default, created_at)
SELECT 'Extra Supervisor', 'Add one additional supervisor to your existing package for better team management.', 1, 0, 0, 20000, NULL, 'Active', 1, NOW()
WHERE NOT EXISTS (SELECT 1 FROM packages WHERE package_name = 'Extra Supervisor');

-- Extra Caretaker Package
INSERT INTO packages (package_name, description, number_of_caretakers, number_of_officers, number_of_supervisors, package_price, background_image, status, is_default, created_at)
SELECT 'Extra Caretaker', 'Add one additional caretaker to your existing package for comprehensive site maintenance.', 1, 0, 0, 12000, NULL, 'Active', 1, NOW()
WHERE NOT EXISTS (SELECT 1 FROM packages WHERE package_name = 'Extra Caretaker');

-- Update existing default packages (in case they were created manually)
UPDATE packages SET is_default = 1 WHERE package_name IN ('Custom Package', 'Extra Security Officer', 'Extra Supervisor', 'Extra Caretaker');
