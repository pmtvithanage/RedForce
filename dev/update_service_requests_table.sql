-- Update client_requests table to include comprehensive business information
-- Run this script to add new columns for enhanced business registration data

ALTER TABLE `client_requests` 
ADD COLUMN `legal_company_name` VARCHAR(255) NULL AFTER `company_name`,
ADD COLUMN `company_type` VARCHAR(50) NULL AFTER `legal_company_name`,
ADD COLUMN `business_registration_number` VARCHAR(100) NULL AFTER `company_type`,
ADD COLUMN `registered_address` TEXT NULL AFTER `business_registration_number`,
ADD COLUMN `business_document` VARCHAR(255) NULL AFTER `logo_path`;

-- Add comments to explain the columns
ALTER TABLE `client_requests` 
MODIFY COLUMN `legal_company_name` VARCHAR(255) NULL COMMENT 'Legal/Registered company name as on registration documents',
MODIFY COLUMN `company_type` VARCHAR(50) NULL COMMENT 'Company type: LLC, Inc., Partnership, Sole Proprietor, PLC, etc.',
MODIFY COLUMN `business_registration_number` VARCHAR(100) NULL COMMENT 'VAT Number, GSTIN, EIN, Company Number, CIF/NIF, etc.',
MODIFY COLUMN `registered_address` TEXT NULL COMMENT 'Official legal/registered business address',
MODIFY COLUMN `business_document` VARCHAR(255) NULL COMMENT 'Business registration/incorporation certificate filename';

-- If Clients table also needs the same updates, uncomment below:
-- ALTER TABLE `Clients` 
-- ADD COLUMN `legal_company_name` VARCHAR(255) NULL AFTER `company_name`,
-- ADD COLUMN `company_type` VARCHAR(50) NULL AFTER `legal_company_name`,
-- ADD COLUMN `business_registration_number` VARCHAR(100) NULL AFTER `company_type`,
-- ADD COLUMN `registered_address` TEXT NULL AFTER `business_registration_number`,
-- ADD COLUMN `business_document` VARCHAR(255) NULL AFTER `logo_path`;
