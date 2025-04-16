<?php
@include '../src/config/database.php';

require_once '/auth.php';// include the auth script
requireLogin(); // require to login

$success = isset($_GET['success']) && $_GET['success'] == 1;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/Feedback.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
<div id="toolbar-container"></div>
<!-- ChatBox -->
<div id="chatbox-container"></div>
<div class="feedback-container">
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
<!-- Load Bootstrap bundle first -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script>
    /* global bootstrap */
    document.addEventListener("DOMContentLoaded", function() {
        fetch("html/toolbar.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("toolbar-container").innerHTML = data;
                // Reinitialize dropdowns for dynamically added content
                var dropdownElements = document.querySelectorAll('.dropdown-toggle');
                dropdownElements.forEach(function(dropdownToggleEl) {
                    new bootstrap.Dropdown(dropdownToggleEl);
                });
            });
    });
</script>
</body>
</html>