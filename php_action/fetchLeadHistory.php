<?php
require_once 'core.php';

$leadId = $_GET['id'] ?? 0;
if ($leadId > 0) {
    $stmt = $connect->prepare("SELECT id, creation_date, last_interaction, interest_probability, follow_up_date, next_step FROM lead_history WHERE lead_id = ? ORDER BY creation_date DESC");
    $stmt->bind_param("i", $leadId);
    $stmt->execute();
    $result = $stmt->get_result();
    $history = [];
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
    echo json_encode($history);
} else {
    echo json_encode([]);
}
?>
