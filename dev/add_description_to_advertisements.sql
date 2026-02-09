-- Add description column to advertisements table
ALTER TABLE advertisements 
ADD COLUMN description TEXT NULL AFTER title;
