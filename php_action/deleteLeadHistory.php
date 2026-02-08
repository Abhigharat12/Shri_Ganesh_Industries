<?php
require_once 'core.php';

if ($_POST && isset($_POST['history_id'])) {
    $historyId = $_POST['history_id'];
    $leadId = $_POST['lead_id'];

    $stmt = $connect->prepare("DELETE FROM lead_history WHERE id = ?");
    $stmt->bind_param("i", $historyId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'History record deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting history record.']);
    }
}
?>
