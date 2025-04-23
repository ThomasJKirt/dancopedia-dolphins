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
        /* Map container styles */
        #mapContainer {
            position: relative;
            width: 600px;
            height: 600px;
            border: 1px solid #ccc;
            margin: auto;
            cursor: pointer;
        }
        /* Marker pin styling */
        .pin {
            position: absolute;
            width: 15px;
            height: 15px;
            background-color: red;
            border-radius: 50%;
            border: 2px solid white;
            cursor: pointer;
            transform: translate(-50%, -50%);
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

                <!-- Dance Name -->
                <div class="mb-3">
                    <label for="danceName" class="form-label">Dance Name</label>
                    <input type="text" class="form-control" id="danceName" required>
                </div>

                <!-- Dance Category -->
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

                <!-- Dance Region -->
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

                <!-- Dance Description -->
                <div class="mb-3">
                    <label for="danceDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="danceDescription" rows="3"></textarea>
                </div>

                <!-- Dance Image -->
                <div class="mb-3">
                    <label for="danceImage" class="form-label">Dance Image</label>
                    <input type="file" class="form-control" id="danceImage">
                </div>

                <!-- Map for Pin Placement -->
                <div class="mb-3">
                    <label for="mapContainer" class="form-label">Place the dance pin on the map:</label>
                    <div id="mapContainer">
                        <img src="assets/images/brazil-map.jpg" alt="Brazil Map" width="600" height="600">
                        <!-- The marker will be dynamically added here -->
                    </div>
                    <!-- Hidden fields to store the coordinates -->
                    <input type="hidden" id="pinX" name="pin_x">
                    <input type="hidden" id="pinY" name="pin_y">
                </div>

                <button class="btn btn-primary" type="button" onclick="createDance()">Create Dance</button>

                <div id="feedback" style="margin-top: 1rem; font-weight: bold;"></div>
            </div>
        </div>
    </div>
</section>

<script>
    // Load the toolbar
    document.addEventListener("DOMContentLoaded", function () {
        fetch("html/toolbar.php")
            .then(response => response.text())
            .then(data => document.getElementById("toolbar-container").innerHTML = data);

        // Add event listener for pin placement on the map
        const mapContainer = document.getElementById("mapContainer");
        mapContainer.addEventListener("click", function (event) {
            // Calculate click coordinates relative to the map container
            const rect = mapContainer.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            // Set the hidden input fields with the coordinates (rounded to int)
            document.getElementById("pinX").value = Math.round(x);
            document.getElementById("pinY").value = Math.round(y);

            // Add or update the marker on the map
            let marker = document.getElementById("marker");
            if (!marker) {
                marker = document.createElement("div");
                marker.id = "marker";
                marker.className = "pin";
                mapContainer.appendChild(marker);
            }
            marker.style.left = x + "px";
            marker.style.top = y + "px";
        });
    });

    // Function to submit dance creation data
    function createDance() {
        const feedbackDiv = document.getElementById('feedback');
        const danceName = document.getElementById('danceName').value.trim();
        const categoryId = document.getElementById('danceCategory').value;
        let region = document.getElementById('danceRegion').value;
        if (region === "Rio de Janeiro") {
            region = 1;
        } else if (region === "Northeastern Brazil") {
            region = 2;
        } else if (region === "Pernambuco") {
            region = 3;
        } else if (region === "Bahia") {
            region = 4;
        }
        const description = document.getElementById('danceDescription').value.trim();
        const danceImageFile = document.getElementById('danceImage').files[0];

        // Get the pin coordinates (should be set if user clicked the map)
        const pinX = document.getElementById('pinX').value;
        const pinY = document.getElementById('pinY').value;

        // Validate that a pin has been placed
        if (!pinX || !pinY) {
            feedbackDiv.textContent = 'Please place the pin on the map to set the dance location.';
            return;
        }

        feedbackDiv.textContent = 'Submitting...';

        const formData = new FormData();
        formData.append('dance_name', danceName);
        formData.append('category_id', categoryId);
        formData.append('region', region);
        formData.append('description', description);
        formData.append('pin_x', pinX);
        formData.append('pin_y', pinY);
        if (danceImageFile) {
            formData.append('dance_image', danceImageFile);
        }

        fetch('/api/create_dance.php', {
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
