<?php
/**
 * Edit Employee Action
 * Handles updating employee information
 */

require_once 'core.php';

// Initialize response array
$valid['success'] = array('success' => false, 'messages' => array());

// Check if form is submitted
if ($_POST) {
    // Get form data
    $employee_id = intval($_POST['employee_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
    $google_sheet_id = isset($_POST['google_sheet_id']) ? trim($_POST['google_sheet_id']) : '';
    $status = isset($_POST['status']) ? $_POST['status'] : 'active';

    // Validation
    $errors = [];

    if (empty($employee_id)) {
        $errors[] = "Invalid employee ID";
    }

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

    // Check if email already exists for another employee
    $checkSql = "SELECT id FROM employees WHERE email = ? AND id != ?";
    $checkStmt = $connect->prepare($checkSql);
    $checkStmt->bind_param("si", $email, $employee_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $errors[] = "Email already exists for another employee";
    }
    $checkStmt->close();

    if (!empty($contact) && !preg_match('/^[0-9+\-\s]{10,15}$/', $contact)) {
        $errors[] = "Invalid contact number format";
    }

    // If there are validation errors
    if (count($errors) > 0) {
        $_SESSION['error'] = implode("<br>", $errors);
        header('location: ../edit_employee.php?id=' . $employee_id);
        exit();
    }

    // Update employee in database
    $sql = "UPDATE employees SET name = ?, email = ?, contact = ?, google_sheet_id = ?, status = ? WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("sssssi", $name, $email, $contact, $google_sheet_id, $status, $employee_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Employee updated successfully!";
        $stmt->close();
        header('location: ../edit_employee.php?id=' . $employee_id);
        exit();
    } else {
        $_SESSION['error'] = "Error while updating employee: " . $stmt->error;
        $stmt->close();
        header('location: ../edit_employee.php?id=' . $employee_id);
        exit();
    }
} else {
    // If accessed directly without POST
    header('location: ../employees.php');
    exit();
}

echo json_encode($valid);
