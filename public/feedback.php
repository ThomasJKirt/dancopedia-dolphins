<?php
@include '../src/config/database.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$success = isset($_GET['success']) && $_GET['success'] == 1;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Feedback</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/Home.css">
    <style>
        input[type=text], select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 6px;
            margin-bottom: 16px;
            resize: vertical;
        }
        input[type=submit] {
            background-color: #04AA6D;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
        .container {
            border-radius: 5px;
            background-color: lightcyan;
            padding: 20px;
            padding-top: 60px;
            padding-bottom: 180px;
            color: #45a049;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            font-size: 20px;
        }
        h1 {
            font-size: 80px;
            font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            color: #45a049;
            padding-bottom: 30px;
        }
        /* Simple alert styling */
        .alert {
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        /* Back Button Styling */
        .back-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        .back-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
<div id="toolbar-container"></div>
<!-- ChatBox -->
<div id="chatbox-container"></div>

<div class="container">
    <h1>Feedback Form</h1>
    <?php if ($success): ?>
        <div class="alert">Feedback submitted successfully!</div>
    <?php endif; ?>
    <form action="../src/api/submit_feedback.php" method="post">
        <label for="fname">First Name</label>
        <input type="text" id="fname" name="fname" placeholder="Your first name..">

        <label for="lname">Last Name</label>
        <input type="text" id="lname" name="lname" placeholder="Your last name..">

        <label for="continent">Continent</label>
        <select id="continent" name="continent">
            <option value="africa">Africa</option>
            <option value="asia">Asia</option>
            <option value="australia">Australia</option>
            <option value="europe">Europe</option>
            <option value="north_america">North America</option>
            <option value="south_america">South America</option>
        </select>

        <label for="feedback">Feedback</label>
        <textarea id="feedback" name="feedback" placeholder="Write feedback.." maxlength="300" style="height:200px"></textarea>

        <input type="submit" value="Submit">
    </form>
    <!-- Back Button -->
    <button type="button" class="back-btn" onclick="window.location.href='index.html'">Back</button>
</div>
</body>
</html>
