<?php
require __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

// Check DB connection
if ($conn->connect_error) {
  echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
  exit;
}


//SQL query for user feedback
$sql = "
    SELECT 
    feedback.id,
    feedback.username,
    feedback.fname,
    feedback.lname,
    feedback.continent,
    feedback.feedback_text,
    feedback.created_at
  FROM feedback
  ORDER BY feedback.created_at DESC
";


$result = $conn->query($sql);
$feedbacks = [];

//If feedbacks are found, loop through them and add them to the feedbacks array to be returned as JSON
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $feedbacks[] = [
      "id" => $row['id'],
      "username" => $row['username'],
      "fname" => $row['fname'],
      "lname" => $row['lname'],
      "continent" => $row['continent'],
      "feedback_text" => $row['feedback_text'],
      "created_at" => $row['created_at']
    ];
  }
} else {
  error_log("No feedback found.");
}

echo json_encode($feedbacks);
$conn->close();
exit;
