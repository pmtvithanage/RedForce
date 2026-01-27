-- Add district column to sites table
ALTER TABLE sites ADD COLUMN district VARCHAR(100) AFTER address;
