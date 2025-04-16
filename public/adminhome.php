<?php
require_once '/auth.php';// include the auth script
requireAdmin(); // only allow users with 'admin' role
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<!-- Toolbar -->
<div id="toolbar-container"></div>

<h1>THIS IS ADMIN HOME PAGE</h1>
<p><?php echo $_SESSION["admin_name"]; ?></p>
<a href="logout.php">Logout</a>

<!-- Dance Approval Section -->
<div class="container mt-4">
    <h2>Approve or Delete Dances</h2>
    <table class="table table-striped" id="dances-table">
        <thead>
        <tr>
            <th scope="col">Select</th>
            <th scope="col">Dance Name</th>
            <th scope="col">Description</th>
            <th scope="col">Category</th>
            <th scope="col">Region</th>
        </tr>
        </thead>
        <tbody>
        <!-- Dynamic content loaded via JavaScript -->
        </tbody>
    </table>
    <button id="approve-button" class="btn btn-success">Approve Selected</button>
    <button id="delete-button" class="btn btn-danger">Delete Selected</button>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Load toolbar content
        fetch("html/toolbar.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("toolbar-container").innerHTML = data;
                // Reinitialize dropdowns for dynamically added content.
                var dropdownElements = document.querySelectorAll('.dropdown-toggle');
                dropdownElements.forEach(function(dropdownToggleEl) {
                    new bootstrap.Dropdown(dropdownToggleEl);
                });
            });

        // Function to load dances from fetch_dances.php.
        function loadDances() {
            fetch('../src/api/fetch_dances.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({approved: 2})
            })
                .then(response => response.json())
                .then(data => {
                    let tbody = document.querySelector('#dances-table tbody');
                    tbody.innerHTML = ""; // Clear any previous content.
                    data.forEach(dance => {
                        let row = document.createElement('tr');

                        // Checkbox cell.
                        let checkboxCell = document.createElement('td');
                        let checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.classList.add('dance-checkbox');
                        checkbox.value = dance.dance_id;
                        checkboxCell.appendChild(checkbox);
                        row.appendChild(checkboxCell);

                        // Dance Name.
                        let nameCell = document.createElement('td');
                        nameCell.textContent = dance.dance_name;
                        row.appendChild(nameCell);

                        // Description.
                        let descCell = document.createElement('td');
                        descCell.textContent = dance.description;
                        row.appendChild(descCell);

                        // Category.
                        let categoryCell = document.createElement('td');
                        categoryCell.textContent = dance.category;
                        row.appendChild(categoryCell);

                        // Region.
                        let regionCell = document.createElement('td');
                        regionCell.textContent = dance.region;
                        row.appendChild(regionCell);

                        tbody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching dances:', error));
        }

        // Initial load.
        loadDances();

        // Helper to get selected dance IDs.
        function getSelectedDanceIds() {
            const checkboxes = document.querySelectorAll('.dance-checkbox');
            let ids = [];
            checkboxes.forEach(chk => {
                if(chk.checked) {
                    ids.push(chk.value);
                }
            });
            return ids;
        }

        // Approve button event handler.
        document.getElementById('approve-button').addEventListener('click', function() {
            let selectedIds = getSelectedDanceIds();
            if(selectedIds.length === 0) {
                alert('Please select at least one dance to approve.');
                return;
            }
            fetch('../src/api/approve_dance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({danceIds: selectedIds})
            })
                .then(response => response.json())
                .then(result => {
                    alert(result.message || result.error);
                    // Reload the list after action.
                    loadDances();
                })
                .catch(error => console.error('Error approving dances:', error));
        });

        // Delete button event handler.
        document.getElementById('delete-button').addEventListener('click', function() {
            let selectedIds = getSelectedDanceIds();
            if(selectedIds.length === 0) {
                alert('Please select at least one dance to delete.');
                return;
            }
            if(!confirm('Are you sure you want to delete the selected dances?')) {
                return;
            }
            fetch('../src/api/disapprove_dance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({danceIds: selectedIds})
            })
                .then(response => response.json())
                .then(result => {
                    alert(result.message || result.error);
                    // Reload the list after action.
                    loadDances();
                })
                .catch(error => console.error('Error deleting dances:', error));
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
