<?php
@include '../config/database.php';
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $continent = trim($_POST['continent']);
    $feedback = trim($_POST['feedback']);

    if (strlen($feedback) > 300) {
        die('Feedback must be 300 characters or less.');
    }

    // Escape feedback text to prevent SQL injection
    $feedback = mysqli_real_escape_string($conn, $feedback);

    $insert_query = "INSERT INTO feedback (username, fname, lname, continent, feedback_text, created_at) 
                     VALUES ('$username', '$fname', '$lname', '$continent', '$feedback', NOW())";

    if (mysqli_query($conn, $insert_query)) {
        header('Location: ../../public/feedback.php?success=1');
        exit();
    } else {
        die('Error submitting feedback: ' . mysqli_error($conn));
    }
}
?>
