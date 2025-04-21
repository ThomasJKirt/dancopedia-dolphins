<?php
require_once 'auth.php';
requireAdmin(); // only allow users with 'admin' role
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- jQuery (needed for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables CSS/JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
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
            <th scope="col">View</th>
        </tr>
        </thead>
        <tbody>
        <!-- Dynamic content loaded via JavaScript -->
        </tbody>
    </table>
    <button id="approve-button" class="btn btn-success">Approve Selected</button>
    <button id="delete-button" class="btn btn-danger">Delete Selected</button>
</div>

<!-- All Dances Section -->
<div class="container mt-4">
    <h2>All Dances</h2>
    <table id="all-dances-table" class="display table table-striped" style="width:100%">
        <thead>
        <tr>
            <th>Dance Name</th>
            <th>Description</th>
            <th>Category</th>
            <th>Region</th>
            <th>View</th>
        </tr>
        </thead>
        <tbody>
        <!-- DataTables will load data here via AJAX -->
        </tbody>
    </table>
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

        // --- Approve/Delete Dances Table Loading ---
        function loadDances() {
            fetch('../src/api/fetch_dances.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({approved: 2})
            })
                .then(response => response.json())
                .then(data => {
                    let tbody = document.querySelector('#dances-table tbody');
                    tbody.innerHTML = ""; // Clear previous content.
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

                        // Link cell.
                        let linkCell = document.createElement('td');
                        let viewLink = document.createElement('a');
                        viewLink.href = 'dancePage.php?danceId=' + dance.dance_id;
                        viewLink.textContent = 'View';
                        linkCell.appendChild(viewLink);
                        row.appendChild(linkCell);

                        tbody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching dances:', error));
        }

        // Initial load of approval table.
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
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({danceIds: selectedIds})
            })
                .then(response => response.json())
                .then(result => {
                    alert(result.message || result.error);
                    loadDances(); // Reload the list after action.
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
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({danceIds: selectedIds})
            })
                .then(response => response.json())
                .then(result => {
                    alert(result.message || result.error);
                    loadDances();
                })
                .catch(error => console.error('Error deleting dances:', error));
        });

        // --- Initialize DataTable for "All Dances" ---
        // The DataTable will use AJAX to fetch all dances data.
        $('#all-dances-table').DataTable({
            "ajax": {
                "url": "../src/api/fetch_dances.php",  // Adjust endpoint if necessary.
                "type": "POST",
                "contentType": "application/json",
                "data": function(d) {
                    return JSON.stringify({});  // Modify data if needed.
                },
                "dataSrc": ""
            },
            "columns": [
                { "data": "dance_name" },
                { "data": "description" },
                { "data": "category" },
                { "data": "region" },
                {
                    "data": "dance_id",
                    "render": function(data, type, row, meta) {
                        return '<a href="dancePage.php?danceId=' + data + '">View</a>';
                    }
                }
            ],
            "pageLength": 10,
            "order": [[0, "asc"]],
            "lengthChange": true,
            "searching": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>

<!-- Bootstrap JS (placed at the end for performance) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
