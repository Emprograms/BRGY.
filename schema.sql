CREATE DATABASE IF NOT EXISTS barangay_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE barangay_db;

-- Users table (Admin + Resident accounts)
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role ENUM('admin','resident') NOT NULL DEFAULT 'resident',
  full_name VARCHAR(150) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Resident registry table (RBI/Inhabitants)
CREATE TABLE IF NOT EXISTS residents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_by_user_id INT NULL,
  last_name VARCHAR(80) NOT NULL,
  first_name VARCHAR(80) NOT NULL,
  middle_name VARCHAR(80) NULL,
  sex ENUM('Male','Female') NOT NULL,
  birthdate DATE NOT NULL,
  civil_status ENUM('Single','Married','Widowed','Separated') NOT NULL,
  household_id VARCHAR(50) NOT NULL,
  is_pwd TINYINT(1) NOT NULL DEFAULT 0,
  is_solo_parent TINYINT(1) NOT NULL DEFAULT 0,
  is_osy TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_res_created_by FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE INDEX idx_res_name ON residents(last_name, first_name);
CREATE INDEX idx_res_household ON residents(household_id);