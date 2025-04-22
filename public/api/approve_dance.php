<?php
require __DIR__ . '/../../src/config/database.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$danceIds = $data['danceIds'] ?? [];

if (!empty($danceIds)) {
    // Build a comma-separated list of integer IDs.
    $ids = implode(',', array_map('intval', $danceIds));
    $sql = "UPDATE dances SET approved = 1 WHERE dance_id IN ($ids)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Dances approved successfully."]);
    } else {
        echo json_encode(["error" => "Error updating records: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "No dance IDs provided."]);
}

$conn->close();
?>
