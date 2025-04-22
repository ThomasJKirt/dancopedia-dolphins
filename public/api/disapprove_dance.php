<?php

require __DIR__ . '/../config/database.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$danceIds = $data['danceIds'] ?? [];

if (!empty($danceIds)) {
    // Convert received IDs to integers and join them by comma.
    $ids = implode(',', array_map('intval', $danceIds));
    $sql = "DELETE FROM dances WHERE dance_id IN ($ids)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Dances deleted successfully."]);
    } else {
        echo json_encode(["error" => "Error deleting records: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "No dance IDs provided."]);
}

$conn->close();

