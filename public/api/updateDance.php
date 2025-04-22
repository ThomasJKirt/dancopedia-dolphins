<?php
require __DIR__ . '/../../src/config/database.php';
session_start();

// This ensures user is logged in as admin before perfoming the update
if (!isset($_SESSION["admin_name"])) {
  echo json_encode(["success" => false, "error" => "Unauthorized."]);
  exit();
}


$data = json_decode(file_get_contents("php://input"), true);

// Checks if data is valid JSON
if ($data === null) {
  echo json_encode(["success" => false, "error" => "Invalid JSON data: " . json_last_error_msg()]);
  exit;
}

$danceId = isset($data['dance_id']) ? (int) $data['dance_id'] : null;
$danceName = isset($data['dance_name']) ? $data['dance_name'] : null;
$region = isset($data['region']) ? (int) $data['region'] : null; // Cast to integer
$category = isset($data['category']) ? (int) $data['category'] : null; // Cast to integer
$description = isset($data['description']) ? $data['description'] : null;

if (!$danceId) {
  echo json_encode(["success" => false, "error" => "No dance ID provided."]);
  exit;
}

if (!$danceName || !$region || !$category) {
  echo json_encode(["success" => false, "error" => "Required fields missing."]);
  exit;
}


$sql = "UPDATE dances 
            SET dance_name = '$danceName',
                region = $region,
                category_id = $category,
                description = '$description'
            WHERE dance_id = $danceId";

$result = $conn->query($sql);


if ($result === TRUE) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false, "error" => "Database error: " . $conn->error]);
}

$conn->close();
