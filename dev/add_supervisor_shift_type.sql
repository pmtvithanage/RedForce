-- Add 'Supervisor' to shift_type ENUM in officer_site_assignments table
ALTER TABLE officer_site_assignments 
MODIFY COLUMN shift_type ENUM('Day', 'Night', 'Full Time', 'Flexible', 'Supervisor') DEFAULT 'Full Time';
