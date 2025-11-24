-- Create database
CREATE DATABASE IF NOT EXISTS REDFORCE_db;

USE REDFORCE_db;

-- Users table
CREATE TABLE
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
        status ENUM ('active', 'inactive', 'suspended') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

-- Insert sample users with hashed passwords
-- Password for all users is '1234' (hashed)
NOTES TABLE
CREATE TABLE
    Notes (
        id INT (11) NOT NULL AUTO_INCREMENT,
        userID VARCHAR(50) NOT NULL,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        is_deleted TINYINT (1) DEFAULT 0,
        PRIMARY KEY (id),
        KEY fk_notes_user_id (userID),
        CONSTRAINT fk_notes_user_id FOREIGN KEY (userID) REFERENCES Users (userID) ON DELETE CASCADE ON UPDATE CASCADE
    );

ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

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

-- Advertisements table
CREATE TABLE
    Advertisements (
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
        FOREIGN KEY (caretaker_id) REFERENCES users (id) ON DELETE CASCADE,
        FOREIGN KEY (supervisor_id) REFERENCES users (id) ON DELETE CASCADE,
        FOREIGN KEY (mobilerider_id) REFERENCES users (id) ON DELETE CASCADE,
        FOREIGN KEY (premiseofficer_id) REFERENCES users (id) ON DELETE CASCADE
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
        FOREIGN KEY (client_id) REFERENCES users (id) ON DELETE CASCADE,
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
   
  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


  Table structure for table `incident_reports`
--

CREATE TABLE `incident_reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `officer_name` varchar(100) NOT NULL,
  `officer_role` varchar(100) NOT NULL,
  `property_site` varchar(50) DEFAULT NULL,
  `incident_type` varchar(50) NOT NULL,
  `incident_date` date NOT NULL,
  `incident_time` time NOT NULL,
  `incident_description` text NOT NULL,
  `action_taken` text DEFAULT NULL,
  `severity` varchar(50) DEFAULT NULL,
  `additional_details` varchar(255) DEFAULT NULL,
  `media_files` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




 Indexes for table `incident_reports`

ALTER TABLE `incident_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_incident_user` (`user_id`);


 AUTO_INCREMENT for table `incident_reports`

ALTER TABLE `incident_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;


 Constraints for table `incident_reports`

ALTER TABLE `incident_reports`
  ADD CONSTRAINT `fk_incident_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;


-- Attendance table for QR scanner
CREATE TABLE
    IF NOT EXISTS attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        officer_id VARCHAR(50) NOT NULL,
        supervisor_id INT NOT NULL,
        timestamp DATETIME NOT NULL,
        status ENUM ('present', 'absent') DEFAULT 'present',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (supervisor_id) REFERENCES users (id) ON DELETE CASCADE,
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
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User permissions table (if needed for future enhancements)
CREATE TABLE IF NOT EXISTS user_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    permission VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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
FOREIGN KEY (reviewed_by) REFERENCES users(id) 
ON DELETE SET NULL;

-- Note: If using the existing leave_requests table, make sure it supports multiple user types
-- Alternative: Modify existing leave_requests table to support multiple user types
-- ALTER TABLE leave_requests ADD COLUMN user_type ENUM('caretaker', 'supervisor', 'mobile_rider', 'premise_officer') DEFAULT 'caretaker';
-- ALTER TABLE leave_requests ADD COLUMN user_id INT NOT NULL;
