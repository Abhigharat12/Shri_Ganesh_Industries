<?php
require_once 'core.php';

if ($_POST && isset($_POST['lead_id'])) {
    $leadId = $_POST['lead_id'];

    $userId = $_SESSION['userId'];
    $stmt = $connect->prepare("INSERT INTO lead_history (lead_id, creation_date, last_interaction, interaction_type, interaction_notes, interest_probability, follow_up_date, follow_up_status, next_step, updated_by) VALUES (?, NOW(), NOW(), 'Call', '', '25%', NULL, 'Pending', '', ?)");
    $stmt->bind_param("ii", $leadId, $userId);

    if ($stmt->execute()) {
        // Update lead status to 'Working' (2)
        $updateStmt = $connect->prepare("UPDATE lead SET status = 2 WHERE id = ?");
        $updateStmt->bind_param("i", $leadId);
        $updateStmt->execute();

        echo json_encode(['success' => true, 'message' => 'New history record added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding history record.']);
    }
}
?>
