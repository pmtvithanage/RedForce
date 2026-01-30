-- Create database
CREATE DATABASE IF NOT EXISTS redforce_db;

USE redforce_db;

-- Users table
CREATE TABLE IF NOT EXISTS
    Users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        userID VARCHAR(50) UNIQUE NOT NULL,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM (
            'admin',
            'supervisor',
            'premise officer',
            'mobile rider',
            'client',
            'caretaker'
        ) NOT NULL,
        phone_number VARCHAR(50),
        profile_image VARCHAR(255) DEFAULT NULL,
        status ENUM ('active', 'inactive', 'suspended') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

-- Insert sample users with hashed passwords
-- Password for all users is '1234' (hashed)

INSERT INTO
    Users (userID, name, email, password, role)
VALUES
    (
        'ADMIN001',
        'System Administrator',
        'admin@redforce.com',
        '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm',
        'admin'
    ),
    (
        'SUP001',
        'John Supervisor',
        'supervisor@redforce.com',
        '$2y$12$PLgzkPnfttBkvEgieex87O16vzpxXngoflTqVnTBkVX4PoGDVzB4m',
        'supervisor'
    ),
    (
        'PO001',
        'Nuwan Perera',
        'nuwan.perera@redforce.com',
        '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC',
        'premise officer'
    ),
    (
        'PO002',
        'Kasun Silva',
        'kasun.silva@redforce.com',
        '$2y$12$4r.CSggFlKY4paSWCKyrN.7ROgUSJJddZp7rGlVKrDu7dkcS2xO1W',
        'premise officer'
    ),
    (
        'MR001',
        'Sanjaya Peris',
        'sanjaya.peris@redforce.com',
        '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm',
        'mobile rider'
    ),
    (
        'MR002',
        'Ramesh Nuwan',
        'ramesh.nuwan@redforce.com',
        '$2y$12$PLgzkPnfttBkvEgieex87O16vzpxXngoflTqVnTBkVX4PoGDVzB4m',
        'mobile rider'
    ),
    (
        'CLIENT001',
        'Peoples Bank',
        'contact@peoplesbank.com',
        '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC',
        'client'
    ),
    (
        'CLIENT002',
        'Cargills PLC',
        'security@cargills.com',
        '$2y$12$4r.CSggFlKY4paSWCKyrN.7ROgUSJJddZp7rGlVKrDu7dkcS2xO1W',
        'client'
    ),
    (
        'CARETAKER001',
        'Michael Johnson',
        'johnson.michael@redforce.com',
        '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm',
        'caretaker'
    );
    
CREATE TABLE IF NOT EXISTS recent_activities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  activity_type VARCHAR(500) NOT NULL,
  activity_titel VARCHAR(500),
  activity_details TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
);
-- Advertisements table
CREATE TABLE
    IF NOT EXISTS advertisements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        image_path VARCHAR(500) NOT NULL,
        target_roles TEXT NOT NULL, -- JSON or comma-separated roles
        created_by INT NOT NULL,
        status ENUM ('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES Users (id) ON DELETE CASCADE
    );

-- Leave requests table
CREATE TABLE
    IF NOT EXISTS leave_requests (
        id INT (11) AUTO_INCREMENT PRIMARY KEY,
        caretaker_id INT (11) DEFAULT NULL,
        supervisor_id INT (11) DEFAULT NULL,
        mobilerider_id INT (11) DEFAULT NULL,
        premiseofficer_id INT (11) DEFAULT NULL,
        leave_type VARCHAR(50) NOT NULL,
        reason TEXT NOT NULL,
        start_date DATE NOT NULL,
        end_date DATE NOT NULL,
        proof_file VARCHAR(255) DEFAULT NULL,
        status ENUM ('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (caretaker_id) REFERENCES Users (id) ON DELETE CASCADE,
        FOREIGN KEY (supervisor_id) REFERENCES Users (id) ON DELETE CASCADE,
        FOREIGN KEY (mobilerider_id) REFERENCES Users (id) ON DELETE CASCADE,
        FOREIGN KEY (premiseofficer_id) REFERENCES Users (id) ON DELETE CASCADE
    );

-- Service requests table
CREATE TABLE
    service_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        client_id INT NOT NULL,
        event_name VARCHAR(255) NOT NULL,
        event_description TEXT NOT NULL,
        start_date DATE NOT NULL,
        end_date DATE NOT NULL,
        start_time TIME NOT NULL,
        end_time TIME NOT NULL,
        location VARCHAR(255) NOT NULL,
        guard_type ENUM ('Armed', 'Regular') NOT NULL,
        guard_count INT NOT NULL,
        comments TEXT,
        status ENUM (
            'Pending',
            'Approved',
            'Rejected',
            'In Progress',
            'Completed'
        ) DEFAULT 'Pending',
        submitted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        -- Foreign key to link with users table
        FOREIGN KEY (client_id) REFERENCES Users (id) ON DELETE CASCADE,
        -- Indexes for better performance
        INDEX idx_client_id (client_id),
        INDEX idx_status (status),
        INDEX idx_submitted_date (submitted_date)
    );

CREATE TABLE Notes (
  id INT(11) NOT NULL AUTO_INCREMENT,
  userID VARCHAR(50) NOT NULL,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  is_deleted TINYINT(1) DEFAULT 0,
  PRIMARY KEY (id),
  KEY fk_notes_user_id (userID),
  CONSTRAINT fk_notes_user_id FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE CASCADE ON UPDATE CASCADE
  );
   
-- Table structure for table `incident_reports`
CREATE TABLE IF NOT EXISTS `incident_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) NOT NULL,
  `officer_name` varchar(100) NOT NULL,
  `officer_role` varchar(100) NOT NULL,
  `property_site` varchar(50) DEFAULT NULL COMMENT 'Legacy field - use site_id instead',
  `site_id` BIGINT UNSIGNED NULL COMMENT 'Foreign key to sites table',
  `incident_type` varchar(50) NOT NULL,
  `incident_date` date NOT NULL,
  `incident_time` time NOT NULL,
  `incident_description` text NOT NULL,
  `action_taken` text DEFAULT NULL,
  `severity` varchar(50) DEFAULT NULL COMMENT 'Legacy field - use priority instead',
  `priority` ENUM('Low', 'Medium', 'High', 'Critical') DEFAULT 'Medium' COMMENT 'Incident priority level',
  `people_involved` TEXT NULL COMMENT 'Names of people involved',
  `additional_details` varchar(255) DEFAULT NULL,
  `media_files` text DEFAULT NULL,
  `latitude` DECIMAL(10, 8) NULL COMMENT 'Incident location latitude',
  `longitude` DECIMAL(11, 8) NULL COMMENT 'Incident location longitude',
  `status` ENUM('Pending', 'In Progress', 'Resolved', 'Closed') DEFAULT 'Pending' COMMENT 'Incident status',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  
  -- Foreign Keys
  CONSTRAINT `fk_incident_user` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_incident_site` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  
  -- Indexes
  INDEX `idx_site_id` (`site_id`),
  INDEX `idx_priority` (`priority`),
  INDEX `idx_status` (`status`),
  INDEX `idx_incident_date` (`incident_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================
-- INCIDENT REVIEWS TABLE
-- Stores reviews and comments added by mobile riders to incidents
-- ============================================
CREATE TABLE IF NOT EXISTS `incident_reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `incident_id` INT NOT NULL COMMENT 'Foreign key to incident_reports table',
  `user_id` INT NOT NULL COMMENT 'User who added the review',
  `reviewer_name` VARCHAR(255) NOT NULL COMMENT 'Name of the reviewer',
  `review_type` ENUM('Update', 'Action', 'Comment', 'Follow-up') DEFAULT 'Comment' COMMENT 'Type of review',
  `review_title` VARCHAR(255) NOT NULL COMMENT 'Brief title for the review',
  `review_details` TEXT NOT NULL COMMENT 'Detailed review content',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- Foreign Keys
  CONSTRAINT `fk_review_incident` FOREIGN KEY (`incident_id`) REFERENCES `incident_reports` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE,
  
  -- Indexes
  INDEX `idx_incident_id` (`incident_id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_created_at` (`created_at`),
  INDEX `idx_review_type` (`review_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Incident reviews and updates by mobile riders';


-- Attendance table for QR scanner
CREATE TABLE
    IF NOT EXISTS attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        officer_id VARCHAR(50) NOT NULL,
        supervisor_id INT NOT NULL,
        timestamp DATETIME NOT NULL,
        status ENUM ('present', 'absent') DEFAULT 'present',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (supervisor_id) REFERENCES Users (id) ON DELETE CASCADE,
        INDEX idx_officer_id (officer_id),
        INDEX idx_supervisor_id (supervisor_id),
        INDEX idx_timestamp (timestamp)
    );


-- User details table
CREATE TABLE IF NOT EXISTS user_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nic VARCHAR(20),
    mobile VARCHAR(15),
    address TEXT,
    additional_info JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

-- User permissions table (if needed for future enhancements)
CREATE TABLE IF NOT EXISTS user_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    permission VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);



--================================================2025-12-3 Start(Pasan)========================================


CREATE TABLE
  IF NOT EXISTS client_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone_number VARCHAR(50),
    contact_person_name VARCHAR(255) NOT NULL,
    logo_path VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM ('pending', 'approved', 'rejected') DEFAULT 'pending'
  );

-- Clients table
CREATE TABLE
  IF NOT EXISTS Clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    contact_person_name VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id) ON DELETE CASCADE
  );

-- Add columns to track approval details
ALTER TABLE client_requests
ADD COLUMN approved_by INT NULL AFTER status,
ADD COLUMN approved_at TIMESTAMP NULL,
ADD COLUMN client_id INT NULL,
ADD FOREIGN KEY (approved_by) REFERENCES Users (id);

-- Create sites table with foreign key to clients
CREATE TABLE
  IF NOT EXISTS sites (
    id SERIAL PRIMARY KEY,
    client_id INTEGER NOT NULL REFERENCES Clients (id) ON DELETE CASCADE,
    site_name VARCHAR(255) NOT NULL,
    address VARCHAR(500),
    city VARCHAR(100),
    phone_number VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (client_id, site_name) -- Optional: prevent duplicate site names per client
  );

ALTER TABLE sites
ADD COLUMN IF NOT EXISTS image VARCHAR(255);

ALTER TABLE sites 
ADD COLUMN IF NOT EXISTS latitude DECIMAL(10, 8) NULL AFTER image,
ADD COLUMN IF NOT EXISTS longitude DECIMAL(11, 8) NULL AFTER latitude;


--================================================2025-12-3 End(Pasan)========================================

CREATE TABLE
  IF NOT EXISTS jobApplication (
    id SERIAL PRIMARY KEY,
    role VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    qualifications TEXT NOT NULL,
    due_date DATE NOT NULL,
    status ENUM ('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );
ALTER TABLE jobApplication
ADD COLUMN IF NOT EXISTS completed VARCHAR(255);

ALTER TABLE jobApplication 
ALTER COLUMN status SET DEFAULT 'closed';

ALTER TABLE jobApplication MODIFY due_date DATE NULL;

CREATE TABLE
  IF NOT EXISTS submittedApplications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20),
    date_of_birth DATE,
    NIC INT,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    address TEXT,
    district VARCHAR(50),
    city VARCHAR(50),
    cv VARCHAR(255),
    photo VARCHAR(255),
    role VARCHAR(100),
    status ENUM ('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by INT NULL,
    approved_at TIMESTAMP NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (approved_by) REFERENCES Users(id) ON DELETE SET NULL -- newly added 
  );

-- ========================================
-- LEAVE REQUEST TABLES
-- ========================================

-- Supervisor Leave Requests Table Schema
-- This table stores leave requests submitted by supervisors
CREATE TABLE IF NOT EXISTS supervisor_leave_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supervisor_id INT NOT NULL,
    leave_type VARCHAR(50) NOT NULL,
    reason TEXT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    proof_file VARCHAR(255) DEFAULT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    admin_response TEXT DEFAULT NULL,
    reviewed_by INT DEFAULT NULL,
    reviewed_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (supervisor_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES Users(id) ON DELETE SET NULL
);

-- Add columns for admin approval functionality to leave_requests table
ALTER TABLE leave_requests 
ADD COLUMN IF NOT EXISTS admin_response TEXT NULL COMMENT 'Reason for rejection' AFTER status,
ADD COLUMN IF NOT EXISTS reviewed_by INT(11) NULL COMMENT 'Admin user ID who reviewed' AFTER admin_response,
ADD COLUMN IF NOT EXISTS reviewed_at TIMESTAMP NULL COMMENT 'When the request was reviewed' AFTER reviewed_by;

-- Add foreign key constraint for reviewed_by
ALTER TABLE leave_requests
ADD CONSTRAINT fk_reviewed_by 
FOREIGN KEY (reviewed_by) REFERENCES Users(id) 
ON DELETE SET NULL;

-- Note: If using the existing leave_requests table, make sure it supports multiple user types
-- Alternative: Modify existing leave_requests table to support multiple user types
-- ALTER TABLE leave_requests ADD COLUMN user_type ENUM('caretaker', 'supervisor', 'mobile_rider', 'premise_officer') DEFAULT 'caretaker';
-- ALTER TABLE leave_requests ADD COLUMN user_id INT NOT NULL;

-- ========================================
-- OFFICER ATTENDANCE TABLE
-- ========================================

CREATE TABLE IF NOT EXISTS officer_attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supervisor_id INT NOT NULL COMMENT 'References Users table',
    officer_id VARCHAR(50) NOT NULL COMMENT 'Officer ID from Users table',
    officer_name VARCHAR(255) NOT NULL,
    attendance_date DATE NOT NULL,
    check_in_time TIME DEFAULT NULL,
    check_out_time TIME DEFAULT NULL,
    status ENUM('Present', 'Absent', 'Late', 'Half Day') NOT NULL DEFAULT 'Present',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supervisor_id) REFERENCES Users(id) ON DELETE CASCADE,
    INDEX idx_supervisor_date (supervisor_id, attendance_date),
    INDEX idx_officer_id (officer_id),
    INDEX idx_attendance_date (attendance_date),
    INDEX idx_status (status),
    UNIQUE KEY unique_officer_date (officer_id, attendance_date)
) 
-- ========================================
-- CARETAKER NOTES TABLE
-- Personal notes system for caretakers
-- ========================================

CREATE TABLE IF NOT EXISTS `caretaker_notes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `caretaker_id` INT NOT NULL COMMENT 'Foreign key to Users table',
  `title` VARCHAR(255) NOT NULL,
  `note_content` TEXT NOT NULL,
  `category` ENUM('General', 'Important', 'Reminder', 'Observation') NOT NULL DEFAULT 'General',
  `priority` ENUM('Low', 'Medium', 'High') NOT NULL DEFAULT 'Medium',
  `reminder_date` DATE NULL COMMENT 'Optional reminder date',
  `is_pinned` TINYINT(1) DEFAULT 0 COMMENT '1 = pinned to top',
  `is_completed` TINYINT(1) DEFAULT 0 COMMENT '1 = completed reminder',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- Foreign Key
  CONSTRAINT `fk_notes_caretaker` FOREIGN KEY (`caretaker_id`) REFERENCES `Users`(`id`) ON DELETE CASCADE,
  
  -- Indexes
  INDEX `idx_caretaker` (`caretaker_id`),
  INDEX `idx_category` (`category`),
  INDEX `idx_priority` (`priority`),
  INDEX `idx_reminder_date` (`reminder_date`),
  INDEX `idx_created_at` (`created_at`)
) 
-- Sample data for caretaker_notes (optional)
INSERT INTO `caretaker_notes` (`caretaker_id`, `title`, `note_content`, `category`, `priority`, `reminder_date`) VALUES
(9, 'Check main gate lock', 'Need to inspect the main gate lock mechanism as it was sticking yesterday. May need lubrication or replacement.', 'Observation', 'High', CURDATE()),
(9, 'Submit monthly report', 'Monthly security report due by end of week. Include all incident logs and patrol summaries.', 'Reminder', 'Medium', DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
(9, 'Equipment inventory', 'Flashlight batteries running low. Radio channel 3 has static. Need to request new uniform.', 'General', 'Low', NULL);

-- ========================================
-- EQUIPMENT REQUESTS TABLE
-- System for caretakers to request equipment
-- ========================================

CREATE TABLE IF NOT EXISTS `equipment_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `caretaker_id` INT NOT NULL COMMENT 'Foreign key to Users table',
  `item_name` VARCHAR(255) NOT NULL,
  `quantity` INT NOT NULL,
  `unit_price` DECIMAL(10,2) NOT NULL,
  `total_cost` DECIMAL(10,2) GENERATED ALWAYS AS (`quantity` * `unit_price`) STORED,
  `reason` TEXT NOT NULL,
  `urgency` ENUM('Low', 'Medium', 'High') NOT NULL DEFAULT 'Medium',
  `preferred_supplier` VARCHAR(255) DEFAULT NULL,
  `additional_notes` TEXT DEFAULT NULL,
  `status` ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
  `supervisor_response` TEXT DEFAULT NULL,
  `reviewed_by` INT DEFAULT NULL COMMENT 'Supervisor who reviewed',
  `reviewed_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- Foreign Keys
  CONSTRAINT `fk_equipment_caretaker` FOREIGN KEY (`caretaker_id`) REFERENCES `Users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_equipment_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `Users`(`id`) ON DELETE SET NULL,
  
  -- Indexes
  INDEX `idx_equipment_caretaker` (`caretaker_id`),
  INDEX `idx_equipment_status` (`status`),
  INDEX `idx_equipment_urgency` (`urgency`),
  INDEX `idx_equipment_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS premise_officers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    officerID VARCHAR(50) UNIQUE NOT NULL,
    date_of_birth DATE,
    NIC VARCHAR(20),
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    address TEXT,
    district VARCHAR(50),
    city VARCHAR(50),
    hire_date DATE NOT NULL,
    employment_status ENUM('Active', 'On Leave', 'Terminated', 'Suspended') DEFAULT 'Active',
    rank ENUM('Junior', 'Senior', 'Supervisor') DEFAULT 'Junior',
    rating DECIMAL(3,2) CHECK (rating >= 0 AND rating <= 5),
    shift_pattern VARCHAR(50),
    application_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (userID) REFERENCES Users(id) ON DELETE SET NULL,
    FOREIGN KEY (application_id) REFERENCES submittedApplications(id) ON DELETE SET NULL
);

CREATE OR REPLACE VIEW premise_officers_full_details AS
SELECT 
    po.id AS premise_officer_id,
    po.officerID,
    po.date_of_birth,
    po.NIC,
    po.gender,
    po.address,
    po.district,
    po.city,
    po.hire_date,
    po.employment_status,
    po.rank,
    po.rating,
    po.shift_pattern,
    po.application_id,
    po.created_at AS officer_record_created,
    po.updated_at AS officer_record_updated,
    
    u.id AS user_id,
    u.userID AS user_identifier,
    u.name,
    u.email,
    u.role,
    u.phone_number,
    u.profile_image,
    u.status AS user_status,
    u.created_at AS user_account_created,
    u.updated_at AS user_account_updated
    
FROM premise_officers po
LEFT JOIN Users u ON po.userID = u.id
WHERE u.role = 'premise officer' OR po.userID IS NOT NULL;


CREATE TABLE IF NOT EXISTS routes (
    id VARCHAR(50) PRIMARY KEY,
    route_name VARCHAR(100) NOT NULL,
    description TEXT,
    cities_covered TEXT,
    location VARCHAR(100),
    distance_km DECIMAL(6,2),
    difficulty_level ENUM('Easy', 'Medium', 'Hard'),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_by VARCHAR(50), -- ADDED: Required for FOREIGN KEY
    assigned_rider_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (created_by) REFERENCES Users(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_rider_id) REFERENCES Users(id) ON DELETE SET NULL
);

-- Alter table to remove old columns and add new location column if they exist
ALTER TABLE routes DROP COLUMN IF EXISTS start_city;
ALTER TABLE routes DROP COLUMN IF EXISTS end_city;
ALTER TABLE routes ADD COLUMN IF NOT EXISTS location TEXT;
ALTER TABLE routes ADD COLUMN IF NOT EXISTS assigned_rider_id INT DEFAULT NULL;
ALTER TABLE routes ADD CONSTRAINT fk_routes_assigned_rider FOREIGN KEY (assigned_rider_id) REFERENCES Users(id) ON DELETE SET NULL;

CREATE TABLE IF NOT EXISTS route_sites (
    route_id VARCHAR(50),
    site_id BIGINT UNSIGNED, 
    PRIMARY KEY (route_id, site_id),
    FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE CASCADE,
    FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
);

-- Site visits table to track mobile rider site visits
CREATE TABLE IF NOT EXISTS site_visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_id BIGINT UNSIGNED NOT NULL,
    user_id INT NOT NULL,
    visit_time DATETIME NOT NULL,
    officer_attendance_satisfactory TINYINT(1) DEFAULT 0 COMMENT '1 = satisfactory, 0 = not satisfactory',
    officer_activities TEXT DEFAULT NULL COMMENT 'Description of officer activities observed',
    site_condition ENUM('Excellent', 'Good', 'Fair', 'Poor') DEFAULT 'Good',
    issues_found TEXT DEFAULT NULL COMMENT 'Any issues or concerns identified',
    notes TEXT DEFAULT NULL COMMENT 'Additional notes about the visit',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    INDEX idx_site_user (site_id, user_id),
    INDEX idx_visit_date (visit_time),
    INDEX idx_user_id (user_id)
);

CREATE TABLE IF NOT EXISTS mobile_rider (
    id INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    riderID VARCHAR(50) UNIQUE NOT NULL,
    routeID VARCHAR(50) NOT NULL,
    date_of_birth DATE,
    NIC VARCHAR(20),
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    address TEXT,
    district VARCHAR(50),
    city VARCHAR(50),
    hire_date DATE NOT NULL,
    employment_status ENUM('Active', 'On Leave', 'Terminated', 'Suspended') DEFAULT 'Active',
    rank ENUM('Junior', 'Senior') DEFAULT 'Junior',
    rating DECIMAL(3,2) CHECK (rating >= 0 AND rating <= 5),
    shift_pattern VARCHAR(50),
    application_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (userID) REFERENCES Users(id) ON DELETE SET NULL,
    FOREIGN KEY (routeID) REFERENCES routes(id) ON DELETE CASCADE,  
    FOREIGN KEY (application_id) REFERENCES submittedApplications(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS care_taker (
    id INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    caretakerID VARCHAR(50) UNIQUE NOT NULL,
    date_of_birth DATE,
    NIC VARCHAR(20),
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    address TEXT,
    district VARCHAR(50),
    city VARCHAR(50),
    hire_date DATE NOT NULL,
    employment_status ENUM('Active', 'On Leave', 'Terminated', 'Suspended') DEFAULT 'Active',
    rank ENUM('Junior', 'Senior') DEFAULT 'Junior',
    rating DECIMAL(3,2) CHECK (rating >= 0 AND rating <= 5),
    shift_pattern VARCHAR(50),
    application_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (userID) REFERENCES Users(id) ON DELETE SET NULL, 
    FOREIGN KEY (application_id) REFERENCES submittedApplications(id) ON DELETE SET NULL
);

-- Mobile Rider Full Details View
CREATE OR REPLACE VIEW mobile_rider_full_details AS
SELECT 
    mr.id AS mobile_rider_id,
    mr.riderID,
    mr.date_of_birth,
    mr.NIC,
    mr.gender,
    mr.address,
    mr.district,
    mr.city,
    mr.hire_date,
    mr.employment_status,
    mr.rank,
    mr.rating,
    mr.shift_pattern,
    mr.application_id,
    mr.created_at AS rider_record_created,
    mr.updated_at AS rider_record_updated,
    
    -- Route details
    r.id AS route_id,
    r.route_name,
    r.description AS route_description,
    r.location,
    r.distance_km,
    r.difficulty_level,
    r.status AS route_status,
    
    -- User details
    u.id AS user_id,
    u.userID AS user_identifier,
    u.name,
    u.email,
    u.role,
    u.phone_number,
    u.profile_image,
    u.status AS user_status,
    u.created_at AS user_account_created,
    u.updated_at AS user_account_updated
    
FROM mobile_rider mr
LEFT JOIN Users u ON mr.userID = u.id
LEFT JOIN routes r ON mr.routeID = r.id
WHERE u.role = 'mobile rider' OR mr.userID IS NOT NULL;

-- Care Taker Full Details View
CREATE OR REPLACE VIEW care_taker_full_details AS
SELECT 
    ct.id AS care_taker_id,
    ct.caretakerID,
    ct.date_of_birth,
    ct.NIC,
    ct.gender,
    ct.address,
    ct.district,
    ct.city,
    ct.hire_date,
    ct.employment_status,
    ct.rank,
    ct.rating,
    ct.shift_pattern,
    ct.application_id,
    ct.created_at AS caretaker_record_created,
    ct.updated_at AS caretaker_record_updated,
    
    -- User details
    u.id AS user_id,
    u.userID AS user_identifier,
    u.name,
    u.email,
    u.role,
    u.phone_number,
    u.profile_image,
    u.status AS user_status,
    u.created_at AS user_account_created,
    u.updated_at AS user_account_updated
    
FROM care_taker ct
LEFT JOIN Users u ON ct.userID = u.id
WHERE u.role = 'care taker' OR ct.userID IS NOT NULL;

-- ============================================
-- Package Requests Table (for Client Package System)
-- ============================================
CREATE TABLE IF NOT EXISTS package_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    package_name VARCHAR(100) NOT NULL,
    site_name VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    site_address TEXT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    number_of_guards INT NOT NULL,
    day_guards INT DEFAULT NULL,
    night_guards INT DEFAULT NULL,
    package_price DECIMAL(10,2) NOT NULL,
    comments TEXT DEFAULT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    admin_notes TEXT DEFAULT NULL,
    approved_by INT DEFAULT NULL,
    approved_at DATETIME DEFAULT NULL,
    submitted_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES Users(id) ON DELETE SET NULL,
    INDEX idx_client (client_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create officer_site_assignments table

DROP TABLE IF EXISTS officer_site_assignments;

CREATE TABLE officer_site_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_id BIGINT UNSIGNED NOT NULL,
    officer_id INT NOT NULL,
    shift_type ENUM('Day', 'Night', 'Full Time', 'Flexible') DEFAULT 'Full Time',
    assignment_start DATE NOT NULL,
    assignment_end DATE DEFAULT NULL,
    status ENUM('Active', 'Completed', 'Cancelled') DEFAULT 'Active',
    assigned_by INT DEFAULT NULL,
    assigned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_site (site_id),
    KEY idx_officer (officer_id),
    KEY idx_status (status),
    CONSTRAINT fk_officer_assignment_site FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE,
    CONSTRAINT fk_officer_assignment_officer FOREIGN KEY (officer_id) REFERENCES Users(id) ON DELETE CASCADE,
    CONSTRAINT fk_officer_assignment_assigner FOREIGN KEY (assigned_by) REFERENCES Users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add district, latitude, longitude, phone_number, and image_name to package_requests table
ALTER TABLE package_requests 
ADD COLUMN district VARCHAR(100) DEFAULT NULL AFTER city,
ADD COLUMN latitude DECIMAL(10, 8) DEFAULT NULL AFTER site_address,
ADD COLUMN longitude DECIMAL(11, 8) DEFAULT NULL AFTER latitude,
ADD COLUMN phone_number VARCHAR(15) DEFAULT NULL AFTER longitude,
ADD COLUMN image_name VARCHAR(255) DEFAULT NULL AFTER phone_number;



-- Add columns for draft site workflow


-- Add draft_site_id to package_requests to link to the temporary site
ALTER TABLE package_requests 
ADD COLUMN IF NOT EXISTS draft_site_id INT NULL;

-- Add is_draft flag to sites table to mark temporary sites
ALTER TABLE sites 
ADD COLUMN IF NOT EXISTS is_draft TINYINT(1) NOT NULL DEFAULT 0;

-- Add package_request_id to sites to link back to the request
ALTER TABLE sites 
ADD COLUMN IF NOT EXISTS package_request_id INT NULL;


