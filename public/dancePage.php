<?php
session_start();
$isAdmin = isset($_SESSION["admin_name"]);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dance Page</title>
    <link rel="stylesheet" href="css/Traditional.css" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
</head>
<body>

    <div id="toolbar-container"></div>
    <h1>Dance Page</h1>
    <div id="danceContainer"></div>
    
    

    <div id="chatbox-container"></div>

    <!-- Load Toolbar -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("html/toolbar.php")
                .then(response => response.text())
                .then(data => {
                    document.getElementById("toolbar-container").innerHTML = data;
                })
                .catch(error => console.error("Error loading toolbar:", error));
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


<!-- Load Dance Details according to ID -->
    <script>
        const isAdmin = <?php echo json_encode($isAdmin); ?>;
    </script>

<script src="js/load_dances.js"></script>

<!-- Loads the single dance that is clicked on by user in previous page-->
<!-- Event listener for delete button-->
<!-- Delete dance calls deleteDance script to delete the dance with the id provided,
     deletion results is shown to user either a failed deletion or success and then renavigation to home-->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const danceId = params.get("danceId");
    if (danceId) {
      loadSingleDance(danceId);
    }
  });

  document.addEventListener("click", (e) => {
  if (e.target.matches(".dance-delete-btn")) {
    const danceId = e.target.getAttribute("data-dance-id");
    deleteDance(danceId);
  }
});

  function deleteDance(danceId) {
  if (!confirm("Are you sure you want to delete this dance?")) return;

  fetch("../src/api/deleteDance.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: danceId })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("Dance deleted successfully!");
      
      window.location.href = "index.html"; 
    } else {
      alert(data.error || "Failed to delete dance.");
    }
  })
  .catch(err => {
    console.error(err);
    alert("Error deleting dance.");
  });
}
</script>
</body>
</html>
