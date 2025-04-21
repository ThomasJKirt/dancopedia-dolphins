<?php

@include '../src/config/database.php';

session_start();

if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = md5($_POST['password']);
    $user_type = $_POST['user_type'] ?? 'user';

    $select = " SELECT * FROM users_form WHERE username = '$name' && password = '$pass' ";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $_SESSION['user_type'] = $row['user_type'];
        $_SESSION['username'] = $row['username'];
        if ($row['user_type'] == 'admin') {
            $_SESSION['admin_name'] = $row['username'];
            header('location:adminhome.php');
        }
        if ($row['user_type'] == 'user') {
            $_SESSION['user_name'] = $row['username'];
            header('location:userhome.php');
        }
    } else {
        $error[] = 'incorrect username or password!';
    }
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login form</title>
    <link rel="stylesheet" href="css/Login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div id="toolbar-container"></div>
<div class="form-container">

    <form action="" method="post">
        <h3>login now</h3>
        <?php
        if (isset($error)) {
            foreach ($error as $error) {
                echo '<span class="error-msg">' . $error . '</span>';
            };
        };
        ?>
        <input type="text" name="username" required placeholder="enter your username">
        <input type="password" name="password" required placeholder="enter your password">
        <input type="submit" name="submit" value="login now" class="form-btn">
        <p>don't have an account? <a href="register_form.php">register now</a></p>
        <p>forgot password? <a href="forgot_password.php">reset here</a></p>
    </form>

    <!-- Back Button -->
    <button type="button" onclick="window.location.href='index.html'" class="back-btn">Back</button>

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
