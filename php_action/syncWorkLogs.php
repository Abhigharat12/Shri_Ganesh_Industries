<?php
/**
 * Sync Work Logs Action
 * Syncs work logs from Google Sheet for a specific employee
 */

require_once 'core.php';

// Initialize response
$valid['success'] = array('success' => false, 'messages' => array());

// Check if employee_id is provided
if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
    $employee_id = intval($_GET['employee_id']);

    // Get employee data
    $sql = "SELECT * FROM employees WHERE id = ?";
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

    // Check if Google Sheet ID is provided
    if (empty($employee['google_sheet_id'])) {
        $_SESSION['error'] = "Google Sheet ID is not set for employee: " . $employee['name'];
        header('location: ../view_work_logs.php?employee_id=' . $employee_id);
        exit();
    }

    try {
        // Include Google Sheets Service
        require_once __DIR__ . '/../services/GoogleSheetsService.php';

        // Initialize the service
        $sheetsService = new GoogleSheetsService();

        // Validate spreadsheet access
        if (!$sheetsService->validateSpreadsheet($employee['google_sheet_id'])) {
            throw new Exception("Cannot access Google Sheet. Please check the Sheet ID and ensure it's shared with the Service Account.");
        }

        // Fetch work logs from Google Sheet
        $workLogs = $sheetsService->fetchWorkLogs($employee['google_sheet_id']);

        if (empty($workLogs)) {
            $_SESSION['success'] = "No work logs found in Google Sheet for: " . $employee['name'];
            header('location: ../view_work_logs.php?employee_id=' . $employee_id);
            exit();
        }

        // Insert or update work logs
        $insertedCount = 0;
        $updatedCount = 0;

        foreach ($workLogs as $workLog) {
            // Check if this record already exists
            $checkSql = "SELECT id FROM work_logs WHERE employee_id = ? AND source_row_identifier = ?";
            $checkStmt = $connect->prepare($checkSql);
            $checkStmt->bind_param("is", $employee_id, $workLog['source_row_identifier']);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                // Record already exists, update it
                $existingData = $checkResult->fetch_assoc();
                $existingId = $existingData['id'];
                $checkStmt->close();

                $updateSql = "UPDATE work_logs SET 
                    editable_date = ?, 
                    system_record_date = ?, 
                    description = ?, 
                    hours = ?, 
                    overtime = ?, 
                    remarks = ?
                    WHERE id = ?";
                
                $updateStmt = $connect->prepare($updateSql);
                $updateStmt->bind_param(
                    "sssdidi",
                    $workLog['editable_date'],
                    $workLog['system_record_date'],
                    $workLog['description'],
                    $workLog['hours'],
                    $workLog['overtime'],
                    $workLog['remarks'],
                    $existingId
                );

                if ($updateStmt->execute()) {
                    $updatedCount++;
                }
                $updateStmt->close();
                continue;
            }
            $checkStmt->close();

            // Insert new record
            $insertSql = "INSERT INTO work_logs (
                employee_id, 
                editable_date, 
                system_record_date, 
                description, 
                hours, 
                overtime, 
                remarks,
                source_row_identifier
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $insertStmt = $connect->prepare($insertSql);
            $insertStmt->bind_param(
                "isssddss",
                $employee_id,
                $workLog['editable_date'],
                $workLog['system_record_date'],
                $workLog['description'],
                $workLog['hours'],
                $workLog['overtime'],
                $workLog['remarks'],
                $workLog['source_row_identifier']
            );

            if ($insertStmt->execute()) {
                $insertedCount++;
            }
            $insertStmt->close();
        }

        $_SESSION['success'] = "Sync completed for " . $employee['name'] . ": " . $insertedCount . " new records inserted, " . $updatedCount . " records updated.";
        header('location: ../view_work_logs.php?employee_id=' . $employee_id);
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = "Sync Error: " . $e->getMessage();
        header('location: ../view_work_logs.php?employee_id=' . $employee_id);
        exit();
    }
} else {
    // If accessed directly without employee_id
    header('location: ../employees.php');
    exit();
}

echo json_encode($valid);
