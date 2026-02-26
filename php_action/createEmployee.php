<?php
/**
 * Create Employee Action
 * Handles creating new employees in the system
 */

require_once 'core.php';

// Initialize response array
$valid['success'] = array('success' => false, 'messages' => array());

// Check if form is submitted
if ($_POST) {
    // Validate required fields
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
    $google_sheet_id = isset($_POST['google_sheet_id']) ? trim($_POST['google_sheet_id']) : '';
    $status = isset($_POST['status']) ? $_POST['status'] : 'active';

    // Validation
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
        $errors[] = "Name should contain only letters and spaces";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Check if email already exists
    $checkSql = "SELECT id FROM employees WHERE email = ?";
    $checkStmt = $connect->prepare($checkSql);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $errors[] = "Email already exists in the system";
    }
    $checkStmt->close();

    if (!empty($contact) && !preg_match('/^[0-9+\-\s]{10,15}$/', $contact)) {
        $errors[] = "Invalid contact number format";
    }

    // If there are validation errors
    if (count($errors) > 0) {
        $_SESSION['error'] = implode("<br>", $errors);
        header('location: ../add_employee.php');
        exit();
    }

    // Insert employee into database
    $sql = "INSERT INTO employees (name, email, contact, google_sheet_id, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $contact, $google_sheet_id, $status);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Employee added successfully!";
        $stmt->close();
        header('location: ../add_employee.php');
        exit();
    } else {
        $_SESSION['error'] = "Error while adding employee: " . $stmt->error;
        $stmt->close();
        header('location: ../add_employee.php');
        exit();
    }
} else {
    // If accessed directly without POST
    header('location: ../employees.php');
    exit();
}

echo json_encode($valid);
