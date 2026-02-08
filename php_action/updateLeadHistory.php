<?php
require_once 'core.php';

if ($_POST && isset($_POST['history_id'])) {
    $historyId = $_POST['history_id'];
    $interestProbability = $_POST['interest_probability'];
    $followUpDate = $_POST['follow_up_date'];
    $nextStep = $_POST['next_step'];
    $interactionType = $_POST['interaction_type'];
    $interactionNotes = $_POST['interaction_notes'];
    $followUpStatus = $_POST['follow_up_status'];
    $userId = $_SESSION['userId'];

    $stmt = $connect->prepare("UPDATE lead_history SET interest_probability = ?, follow_up_date = ?, next_step = ?, interaction_type = ?, interaction_notes = ?, follow_up_status = ?, last_interaction = NOW(), updated_by = ? WHERE id = ?");
    $stmt->bind_param("ssssssii", $interestProbability, $followUpDate, $nextStep, $interactionType, $interactionNotes, $followUpStatus, $userId, $historyId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'History updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating history.']);
    }
}
?>
