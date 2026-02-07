-- Add client_notes column to equipment_requests table
-- This allows clients to add their own notes when approving/rejecting requests

ALTER TABLE equipment_requests 
ADD COLUMN client_notes TEXT NULL AFTER supervisor_notes;
