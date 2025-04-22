<?php
require __DIR__ . '/../config/database.php';
session_start();
if (!isset($_SESSION["admin_name"])) {
    echo json_encode(["success" => false, "error" => "Unauthorized."]);
    exit();
}
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$danceId = isset($data['id']) ? (int)$data['id'] : null;

// This part checks if ID is valid
if (!$danceId) {
  echo json_encode(["error" => "No ID provided."]);
  exit;
}

//This is the SQL DB QUERY based on ID
$sql = "DELETE FROM dances WHERE dance_id = {$danceId}";
$result = $conn->query($sql);


//This returns either success or error based on the result of the query
if ($result === TRUE) {

  echo json_encode(["success" => true]);
} else {

  echo json_encode(["error" => $conn->error]);
}

$conn->close();
