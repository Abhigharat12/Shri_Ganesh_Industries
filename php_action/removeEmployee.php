<?php
/**
 * Remove Employee Action
 * Deletes an employee and their associated work logs
 */

require_once 'core.php';

// Initialize response
$valid['success'] = array('success' => false, 'messages' => array());

// Check if employee_id is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $employee_id = intval($_GET['id']);

    // Check if employee exists
    $sql = "SELECT id, name FROM employees WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Employee not found";
        $stmt->close();
        header('location: ../employees.php');
        exit();
    }

    $employee = $result->fetch_assoc();
    $stmt->close();

    // Delete work logs first (due to foreign key)
    $deleteLogsSql = "DELETE FROM work_logs WHERE employee_id = ?";
    $deleteLogsStmt = $connect->prepare($deleteLogsSql);
    $deleteLogsStmt->bind_param("i", $employee_id);
    $deleteLogsStmt->execute();
    $deleteLogsStmt->close();

    // Delete employee
    $deleteSql = "DELETE FROM employees WHERE id = ?";
    $deleteStmt = $connect->prepare($deleteSql);
    $deleteStmt->bind_param("i", $employee_id);

    if ($deleteStmt->execute()) {
        $_SESSION['success'] = "Employee '" . $employee['name'] . "' and all associated work logs have been deleted.";
        header('location: ../employees.php');
        exit();
    } else {
        $_SESSION['error'] = "Error while deleting the employee.";
        header('location: ../employees.php');
        exit();
    }

    $deleteStmt->close();
} else {
    // If accessed directly without id
    header('location: ../employees.php');
    exit();
}

echo json_encode($valid);
