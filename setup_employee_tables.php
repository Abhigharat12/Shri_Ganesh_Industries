<?php
/**
 * Script to create Employee Work Log Management tables
 * Run this once to set up the database tables
 * Access this file in browser: http://localhost/penglead/setup_employee_tables.php
 */

// Database credentials
$localhost = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "jaiganesh_industries";
$port = 3306;
// $port = 3307;

echo "<h2>Setting up Employee Work Log Management Tables</h2>";

try {
    // Connect to database
    $connect = new mysqli($localhost, $username, $password, $dbname, $port);
    
    if ($connect->connect_error) {
        die("Connection Failed: " . $connect->connect_error);
    }
    
    echo "<p>Connected to database successfully!</p>";
    
    // Create employees table
    $employees_sql = "CREATE TABLE IF NOT EXISTS `employees` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `name` VARCHAR(255) NOT NULL,
      `email` VARCHAR(255) NOT NULL,
      `contact` VARCHAR(50) DEFAULT NULL,
      `google_sheet_id` VARCHAR(255) DEFAULT NULL,
      `status` ENUM('active', 'inactive') DEFAULT 'active',
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    if ($connect->query($employees_sql) === TRUE) {
        echo "<p style='color: green;'>✓ Employees table created successfully!</p>";
    } else {
        echo "<p style='color: red;'>✗ Error creating employees table: " . $connect->error . "</p>";
    }
    
    // Create work_logs table
    $work_logs_sql = "CREATE TABLE IF NOT EXISTS `work_logs` (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    if ($connect->query($work_logs_sql) === TRUE) {
        echo "<p style='color: green;'>✓ Work logs table created successfully!</p>";
    } else {
        echo "<p style='color: red;'>✗ Error creating work_logs table: " . $connect->error . "</p>";
    }
    
    // Verify tables exist
    $result = $connect->query("SHOW TABLES LIKE 'employees'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Employees table exists!</p>";
    }
    
    $result = $connect->query("SHOW TABLES LIKE 'work_logs'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Work logs table exists!</p>";
    }
    
    echo "<h3 style='color: green;'>Setup completed successfully!</h3>";
    echo "<p>You can now delete this file for security reasons.</p>";
    
    $connect->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
