<?php
require_once '/auth.php';// include the auth script
requireUser(); // only allow users with 'user' role
?>

<!DOCTYPE html>
<html>
<head>
	<title>User Page</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
  #toolbar-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000; /* ensures it stays above other content */
  }
  #user{
    padding-top: 150px
  }
  </style>
</head>
<body>
<div id="toolbar-container"></div>

<div id="user"> 
  <div class="container text-center mt-4">
      <h1>THIS IS USER HOME PAGE</h1>
      <h3>Welcome, <?php echo $_SESSION["username"]; ?>!</h3>

      <!-- Feedback Button -->
      <div class="mt-3">
          <a href="feedback.php" class="btn btn-success btn-lg">Give Feedback</a>
      </div>

      <div class="mt-4">
          <a href="logout.php" class="btn btn-danger">Logout</a>
      </div>
  </div>
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

