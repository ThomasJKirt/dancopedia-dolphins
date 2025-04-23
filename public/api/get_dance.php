<?php
// get_dance_by_name.php

require __DIR__ . '/../../src/config/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

// Ensure connection is successful
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Retrieve POST data
$filters = json_decode(file_get_contents("php://input"), true);
$dance_id = isset($filters['danceId']) ? $conn->real_escape_string($filters['danceId']) : null;

if (empty($dance_id)) {
    echo json_encode(["error" => "Dance name not provided."]);
    exit;
}

// Build the SQL query to match the dance by its name
$sql = "
    SELECT 
        dances.dance_id,
        dances.dance_name, 
        dances.description, 
        region.region_name, 
        media.media_url, 
        media.alttext, 
        dance_categories.category_name
    FROM dances
    LEFT JOIN media ON dances.media_id = media.media_id
    LEFT JOIN dance_categories ON dances.category_id = dance_categories.category_id
    LEFT JOIN region ON dances.region = region.region_key
    WHERE dances.dance_id = '$dance_id'
";

// Execute the query
$result = $conn->query($sql);
$dance_info = [];

if ($result && $result->num_rows > 0) {
    // Return the first matching record
    $dance_info = $result->fetch_assoc();
} else {
    $dance_info = ["error" => "No dance found with the provided name."];
}

echo json_encode($dance_info);
$conn->close();
?>
