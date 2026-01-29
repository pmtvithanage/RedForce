-- Add district, latitude, longitude, phone_number, and image_name to package_requests table
ALTER TABLE package_requests 
ADD COLUMN district VARCHAR(100) DEFAULT NULL AFTER city,
ADD COLUMN latitude DECIMAL(10, 8) DEFAULT NULL AFTER site_address,
ADD COLUMN longitude DECIMAL(11, 8) DEFAULT NULL AFTER latitude,
ADD COLUMN phone_number VARCHAR(15) DEFAULT NULL AFTER longitude,
ADD COLUMN image_name VARCHAR(255) DEFAULT NULL AFTER phone_number;
