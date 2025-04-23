<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/Password.css">
    <style>
        /* Modal Styles */
        .modal {
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            display: none;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
        }
    </style>
</head>
<body>

<div class="form-container">
    <form id="forgotPasswordForm" method="post">
        <h3>Forgot Password</h3>
        <?php
        if (isset($error)) {
            foreach ($error as $error) {
                echo '<span class="error-msg">' . $error . '</span>';
            };
        };
        ?>
        <input type="email" name="email" required placeholder="enter your email">
        <input type="submit" name="submit" value="Reset" class="form-btn">
        <p>don't have an account? <a href="register_form.php">register now</a></p>
    </form>

    <!-- Back Button -->
    <button type="button" onclick="window.location.href='index.html'" class="back-btn">Back</button>
</div>

<!-- Modal Popup for Success Message -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p id="modalContent">Success!</p>
    </div>
</div>

<script>
    // Wait for the DOM to load
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("forgotPasswordForm");
        const modal = document.getElementById("successModal");
        const modalContent = document.getElementById("modalContent");
        const closeModal = document.querySelector(".close");

        form.addEventListener("submit", function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Create a FormData object to send the form data
            const formData = new FormData(form);

            // Use Fetch API to send the POST request
            fetch('send_password.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(result => {
                    // Show the modal and display the result (e.g., success message)
                    modalContent.innerHTML = result;
                    modal.style.display = "block";
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        });

        // When the user clicks the close button, hide the modal
        closeModal.addEventListener("click", function() {
            modal.style.display = "none";
        });

        // Also hide the modal if the user clicks outside the modal content
        window.addEventListener("click", function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    });
</script>

</body>
</html>
