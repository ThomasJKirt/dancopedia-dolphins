<?php
session_start();
if(!isset($_SESSION["admin_name"])) {
    header("Location: index.html");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Feedback</title>
  <link rel="stylesheet" href="css/Traditional.css" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body>
  <div id="toolbar-container"></div>
  <h1>User Feedback</h1>
  <div class="container mt-4">
    <div id="feedback-table-container"></div>
  </div>
  <div id="chatbox-container"></div>

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

  <!-- Load Chatbox -->
  <script>
    fetch('html/chatbox.html')
      .then(response => response.text())
      .then(html => {
        document.getElementById('chatbox-container').innerHTML = html;
        const script = document.createElement('script');
        script.src = "js/chatbox.js";
        script.defer = true;
        document.body.appendChild(script);
      })
      .catch(error => console.error('Error loading chatbox:', error));
  </script>

  <script>
    const isAdmin = <?php echo json_encode($isAdmin); ?>;
  </script>

  <!-- Load Feedback Table -->
  <script>
    fetch("api/fetch_feedback.php")
      .then(res => res.json())
      .then(data => {
        if (!data.length) {
          document.getElementById("feedback-table-container").innerHTML = "<p>No feedback found.</p>";
          return;
        }

        // Create table header and body
        let table = `
        <table class="table table-striped table-bordered">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Continent</th>
              <th>Comment</th>
              <th>Date</th>
             </tr>
          </thead>
        <tbody>
      `;

        // Loop through feedback data and create table rows for display
        data.forEach(fb => {
          table += `
          <tr>
            <td>${fb.id}</td>
            <td>${fb.username}</td>
            <td>${fb.fname}</td>
            <td>${fb.lname}</td>
            <td>${fb.continent}</td>
            <td>${fb.feedback_text}</td>
            <td>${new Date(fb.created_at).toLocaleString()}</td>
          </tr>
        `;
        });

        table += "</tbody></table>";
        document.getElementById("feedback-table-container").innerHTML = table;
      }) //Catches load errors and displays an error message
      .catch(err => {
        console.error("Failed to load feedback:", err);
        document.getElementById("feedback-table-container").innerHTML = "<p>Error loading feedback.</p>";
      });
  </script>
</body>

</html>