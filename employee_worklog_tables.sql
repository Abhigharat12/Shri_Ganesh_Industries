-- Employee Work Log Management Module - Database Tables
-- Run this SQL to create the necessary tables

-- Create employees table
CREATE TABLE IF NOT EXISTS `employees` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `contact` VARCHAR(50) DEFAULT NULL,
  `google_sheet_id` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create work_logs table
CREATE TABLE IF NOT EXISTS `work_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `employee_id` INT(11) NOT NULL,
  `editable_date` DATE DEFAULT NULL,
  `system_record_date` DATE DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `hours` DECIMAL(5,2) DEFAULT 0.00,
  `overtime` DECIMAL(5,2) DEFAULT 0.00,
  `remarks` TEXT DEFAULT NULL,
  `source_row_identifier` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `source_row_identifier` (`source_row_identifier`),
  KEY `system_record_date` (`system_record_date`),
  CONSTRAINT `work_logs_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
