-- Add 'Supervisor Approved' status to equipment_requests table
-- This allows tracking when supervisor approves before client final approval

ALTER TABLE equipment_requests 
MODIFY COLUMN status ENUM('Pending', 'Supervisor Approved', 'Approved', 'Rejected') 
DEFAULT 'Pending';
