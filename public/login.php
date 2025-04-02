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
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../public/css/Login.css">
</head>

<body>

    <div class="form-container">

        <form action="send_password.php" method="post">
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

</body>

</html>