-- Create database
CREATE DATABASE IF NOT EXISTS REDFORCE_db;
USE REDFORCE_db;

-- Users table
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userID VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'supervisor', 'premise officer', 'mobile rider', 'client', 'caretaker') NOT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample users with hashed passwords
-- Password for all users is '1234' (hashed)
INSERT INTO Users (userID, name, email, password, role) VALUES
('ADMIN001', 'System Administrator', 'admin@redforce.com', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 'admin'),
('SUP001', 'John Supervisor', 'supervisor@redforce.com', '$2y$12$PLgzkPnfttBkvEgieex87O16vzpxXngoflTqVnTBkVX4PoGDVzB4m', 'supervisor'),
('PO001', 'Nuwan Perera', 'nuwan.perera@redforce.com', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 'premise officer'),
('PO002', 'Kasun Silva', 'kasun.silva@redforce.com', '$2y$12$4r.CSggFlKY4paSWCKyrN.7ROgUSJJddZp7rGlVKrDu7dkcS2xO1W', 'premise officer'),
('MR001', 'Sanjaya Peris', 'sanjaya.peris@redforce.com', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 'mobile rider'),
('MR002', 'Ramesh Nuwan', 'ramesh.nuwan@redforce.com', '$2y$12$PLgzkPnfttBkvEgieex87O16vzpxXngoflTqVnTBkVX4PoGDVzB4m', 'mobile rider'),
('CLIENT001', 'People\'s Bank', 'contact@peoplesbank.com', '$2y$12$HHNqTdJVndZwH74yXKDPsOTQISNg5RyAVe1Il80CQdmP.TBqxknKC', 'client'),
('CLIENT002', 'Cargills PLC', 'security@cargills.com', '$2y$12$4r.CSggFlKY4paSWCKyrN.7ROgUSJJddZp7rGlVKrDu7dkcS2xO1W', 'client'),
('CARETAKER001', 'Michael Johnson', 'johnson.michael@redforce.com', '$2y$12$EH037Jls82SuFzQGiggeu.PKPhpNOjdpxI9JNXKzoK5gZJ2XbBiNm', 'caretaker');



NOTES TABLE

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