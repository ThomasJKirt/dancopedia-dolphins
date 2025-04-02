<?php
// Start the session to check if the user is logged in
session_start();

// Check if the user is logged in and is either an admin or a user
if (!isset($_SESSION['user_name']) && !isset($_SESSION['admin_name'])) {
  header('Location: login.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Welcome to Brazil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="css/Home.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    .background {
      background-color: white;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      text-align: center;
      color: black;
      height: 100vh;
      width: 100vw;
    }
  </style>
</head>

<body>
<div id="toolbar-container"></div>

<section class="one">
  <div class="background">
    <div class="scrollContainer">
      <img src="assets/images/brazil_flag.jpg" alt="Brazil Flag" class="flag">
      <h1>Create A Dance</h1>
      <div class="card-body" style="max-width: 500px; margin: 0 auto; text-align: left;">

        <div class="mb-3">
          <label for="danceName" class="form-label">Dance Name</label>
          <input type="text" class="form-control" id="danceName" required>
        </div>

        <div class="mb-3">
          <label for="danceCategory" class="form-label">Category</label>
          <select class="form-select" id="danceCategory" required>
            <option value="" selected disabled>Choose a category</option>
            <option value="1">Traditional</option>
            <option value="2">Festival</option>
            <option value="3">Partner</option>
            <option value="4">Pop</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="danceRegion" class="form-label">Region</label>
          <select class="form-select" id="danceRegion" required>
            <option value="" selected disabled>Choose a region</option>
            <option value="Rio de Janeiro">Rio de Janeiro</option>
            <option value="Northeastern Brazil">Northeastern Brazil</option>
            <option value="Pernambuco">Pernambuco</option>
            <option value="Bahia">Bahia</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="danceDescription" class="form-label">Description</label>
          <textarea class="form-control" id="danceDescription" rows="3"></textarea>
        </div>

        <div class="mb-3">
          <label for="danceImage" class="form-label">Dance Image</label>
          <input type="file" class="form-control" id="danceImage">
        </div>

        <button class="btn btn-primary" type="button" onclick="createDance()">Create Dance</button>

        <div id="feedback" style="margin-top: 1rem; font-weight: bold;"></div>
      </div>
    </div>
  </div>
</section>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    fetch("html/toolbar.php")
            .then(response => response.text())
            .then(data => document.getElementById("toolbar-container").innerHTML = data);
  });

  function createDance() {
    const feedbackDiv = document.getElementById('feedback');
    const danceName = document.getElementById('danceName').value.trim();
    const categoryId = document.getElementById('danceCategory').value;
    let region = document.getElementById('danceRegion').value;
    if (region == "Rio de Janeiro") {
      region = 1;
    } else if (region == "Northeastern Brazil") {
      region = 2;
    } else if (region == "Pernambuco") {
      region = 3;
    } else if (region == "Bahia") {
      region = 4;
    }
    const description = document.getElementById('danceDescription').value.trim();
    const danceImageFile = document.getElementById('danceImage').files[0];

    feedbackDiv.textContent = 'Submitting...';

    const formData = new FormData();
    formData.append('dance_name', danceName);
    formData.append('category_id', categoryId);
    formData.append('region', region);
    formData.append('description', description);
    if (danceImageFile) {
      formData.append('dance_image', danceImageFile);
    }

    fetch('../src/api/create_dance.php', {
      method: 'POST',
      body: formData
    })
            .then(response => response.text())
            .then(result => {
              feedbackDiv.textContent = result;
            })
            .catch(err => {
              feedbackDiv.textContent = 'Error creating dance: ' + err;
            });
  }
</script>

</body>

</html>