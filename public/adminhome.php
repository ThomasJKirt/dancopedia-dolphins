<?php
session_start();


if(!isset($_SESSION["admin_name"]))
{
	header("location:login.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin Page</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/Home.css">
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

    .toolBar {
      position: sticky;
      top: 0;
      width: 100%;
      text-align: center;
      padding: 10px;
      padding-bottom: 10px;
      background-color: lightblue;
      color: darkgreen;
    }
    #admin{
    padding-top: 150px
  }
  </style>
</head>
<body>
<div id="toolbar-container"></div>

<div id="admin"> 
<h1>THIS IS ADMIN HOME PAGE</h1><?php echo $_SESSION["admin_name"] ?>
<a href="logout.php">Logout</a>
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