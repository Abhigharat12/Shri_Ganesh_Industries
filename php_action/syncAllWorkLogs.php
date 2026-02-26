<?php
/**
 * Sync All Work Logs Action
 * Syncs work logs from Google Sheets for all employees
 */

require_once 'core.php';

// Initialize response
$valid['success'] = array('success' => false, 'messages' => array());

// Get all employees with Google Sheet ID
$sql = "SELECT * FROM employees WHERE google_sheet_id IS NOT NULL AND google_sheet_id != ''";
$result = $connect->query($sql);

// Check if query failed - mysqli::query() returns FALSE on failure
if (!$result) {
    $_SESSION['error'] = "Error fetching employees: " . $connect->error;
    header('location: ../employees.php');
    exit();
}

// Check if any employees were found
if ($result->num_rows === 0) {
    $_SESSION['error'] = "No employees with Google Sheet ID found. Please add Google Sheet IDs to employees first.";
    header('location: ../employees.php');
    exit();
}

try {
    // Include Google Sheets Service
    require_once __DIR__ . '/../services/GoogleSheetsService.php';

    // Initialize the service
    $sheetsService = new GoogleSheetsService();

    $totalInserted = 0;
    $totalUpdated = 0;
    $failedEmployees = [];

    while ($employee = $result->fetch_assoc()) {
        try {
            // Validate spreadsheet access
            if (!$sheetsService->validateSpreadsheet($employee['google_sheet_id'])) {
                $failedEmployees[] = $employee['name'] . " (Cannot access sheet)";
                continue;
            }

            // Fetch work logs from Google Sheet
            $workLogs = $sheetsService->fetchWorkLogs($employee['google_sheet_id']);

            if (empty($workLogs)) {
                continue;
            }

            // Insert or update work logs
            foreach ($workLogs as $workLog) {
                // Check if this record already exists
                $checkSql = "SELECT id FROM work_logs WHERE employee_id = ? AND source_row_identifier = ?";
                $checkStmt = $connect->prepare($checkSql);
                $checkStmt->bind_param("is", $employee['id'], $workLog['source_row_identifier']);
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
                        $totalUpdated++;
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
                    $employee['id'],
                    $workLog['editable_date'],
                    $workLog['system_record_date'],
                    $workLog['description'],
                    $workLog['hours'],
                    $workLog['overtime'],
                    $workLog['remarks'],
                    $workLog['source_row_identifier']
                );

                if ($insertStmt->execute()) {
                    $totalInserted++;
                }
                $insertStmt->close();
            }
        } catch (Exception $e) {
            $failedEmployees[] = $employee['name'] . " (" . $e->getMessage() . ")";
        }
    }

    // Build success message
    $message = "Global sync completed: " . $totalInserted . " new records inserted, " . $totalUpdated . " records updated.";
    
    if (count($failedEmployees) > 0) {
        $message .= " Failed: " . implode(", ", $failedEmployees);
        $_SESSION['error'] = $message;
    } else {
        $_SESSION['success'] = $message;
    }

    header('location: ../employees.php');
    exit();

} catch (Exception $e) {
    $_SESSION['error'] = "Global Sync Error: " . $e->getMessage();
    header('location: ../employees.php');
    exit();
}

echo json_encode($valid);
