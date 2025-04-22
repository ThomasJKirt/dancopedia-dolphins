<?php

require __DIR__ . '/../../src/config/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

// Ensure connection is successful
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$sql = "
    SELECT 
        dances.dance_id,
        dances.dance_name, 
        dances.description, 
        dances.x, 
        dances.y,
        region.region_name, 
        media.media_url, 
        media.alttext, 
        dance_categories.category_name
    FROM dances
    LEFT JOIN media ON dances.media_id = media.media_id
    LEFT JOIN dance_categories ON dances.category_id = dance_categories.category_id
    LEFT JOIN region ON dances.region = region.region_key
    WHERE dances.approved = 1
";

$result = $conn->query($sql);

$dances = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dances[] = [
            "dance_id" => $row['dance_id'] ?? '',
            "dance_name" => $row['dance_name'] ?? 'Unknown',
            "description" => $row['description'] ?? 'No description available',
            "x" => (int)($row['x'] ?? 0),
            "y" => (int)($row['y'] ?? 0),
            "region" => $row['region_name'] ?? 'Unknown',
            "media_url" => $row['media_url'] ?? '',
            "alttext" => $row['alttext'] ?? 'Dance image',
            "category" => $row['category_name'] ?? 'Uncategorized'
        ];
    }
} else {
    error_log("No approved dances found for the query.");
}

echo json_encode($dances);
$conn->close();

