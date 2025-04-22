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
    crossorigin="anonymous" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body>

  <div id="toolbar-container"></div>
  <h1>Dance Page</h1>
  <div id="danceContainer"></div>



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

      fetch("api/deleteDance.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            id: danceId
          })
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

  <!-- This script manages user updates for dances-->
  <script>
    document.addEventListener("click", function(e) {
      if (!e.target.matches(".dance-update-btn")) return;

      const button = e.target;
      const dancePage = button.closest(".dance-page");

      //Selects current fields in the dance page for later updating
      const danceNameEl = dancePage.querySelector("h2");
      const regionEl = dancePage.querySelector(".dance-details p:nth-of-type(1)");
      const categoryEl = dancePage.querySelector(".dance-details p:nth-of-type(2)");
      const descEl = dancePage.querySelector(".danceDescription");

      // If button is still on update it will then allow for editing, otherwise it will process the update
      if (button.textContent === "Update") {
        // This gets current values to pre-select in dropdowns
        const currentRegion = regionEl.textContent.replace('Region:', '').trim();
        const currentCategory = categoryEl.textContent.replace('Category:', '').trim();

        // This replaces existing text with inputs fpr updating
        danceNameEl.innerHTML = `<input type="text" value="${danceNameEl.textContent.trim()}" id="updateDanceName" class="form-control">`;

        regionEl.innerHTML = `
      <strong>Region:</strong> 
      <select id="updateDanceRegion" class="form-select">
        <option value="1" ${currentRegion === "Rio de Janeiro" ? "selected" : ""}>Rio de Janeiro</option>
        <option value="2" ${currentRegion === "Northeastern Brazil" ? "selected" : ""}>Northeastern Brazil</option>
        <option value="3" ${currentRegion === "Pernambuco" ? "selected" : ""}>Pernambuco</option>
        <option value="4" ${currentRegion === "Bahia" ? "selected" : ""}>Bahia</option>
      </select>`;

        categoryEl.innerHTML = `
      <strong>Category:</strong>
      <select id="updateDanceCategory" class="form-select">
        <option value="1" ${currentCategory === "Traditional" ? "selected" : ""}>Traditional</option>
        <option value="2" ${currentCategory === "Festival" ? "selected" : ""}>Festival</option>
        <option value="3" ${currentCategory === "Partner" ? "selected" : ""}>Partner</option>
        <option value="4" ${currentCategory === "Pop" ? "selected" : ""}>Pop</option>
      </select>`;

        descEl.innerHTML = `<textarea id="updateDanceDescription" class="form-control">${descEl.textContent.trim()}</textarea>`;

        button.textContent = "Submit";
      } else {


        //Gathering updated values to update in DB
        const updatedName = document.getElementById("updateDanceName").value;
        const updatedRegionSelect = document.getElementById("updateDanceRegion");
        const updatedCategorySelect = document.getElementById("updateDanceCategory");
        const updatedDesc = document.getElementById("updateDanceDescription").value;
        const danceId = button.getAttribute("data-dance-id");
        const updatedRegionValue = updatedRegionSelect.value;
        const updatedRegionText = updatedRegionSelect.options[updatedRegionSelect.selectedIndex].text;
        const updatedCategoryValue = updatedCategorySelect.value;
        const updatedCategoryText = updatedCategorySelect.options[updatedCategorySelect.selectedIndex].text;


        fetch("../api/updateDance.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify({
              dance_id: danceId,
              dance_name: updatedName,
              region: updatedRegionValue,
              category: updatedCategoryValue,
              description: updatedDesc
            })
          })
          .then(res => {

            if (!res.ok) {
              return res.text().then(text => {
                console.error("Server response:", text);
                throw new Error("Server returned error status: " + res.status);
              });
            }
            return res.json();
          })
          .then(data => {
            if (data.success) {
              alert("Dance updated successfully!");
            } else {
              alert("Update failed: " + (data.error || "Unknown error"));
              console.error("Update error details:", data);
            }
          })
          .catch(err => {
            alert("Error updating dance: " + err.message);
            console.error("Fetch error:", err);
          });

        // Update the page with updated text
        danceNameEl.textContent = updatedName;
        regionEl.innerHTML = `<strong>Region:</strong> ${updatedRegionText}`;
        categoryEl.innerHTML = `<strong>Category:</strong> ${updatedCategoryText}`;
        descEl.textContent = updatedDesc;

        button.textContent = "Update";
      }
    });
  </script>
</body>

</html>