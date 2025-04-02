<?php

@include '../src/config/database.php';

if (isset($_POST['submit'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = md5($_POST['password']);
    $cpass = md5($_POST['cpassword']);
    $user_type = $_POST['user_type'];
    $email_hash = hash('sha256', $email);

    $select = "SELECT * FROM users_form WHERE username = '$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'User already exists!';
    } else {
        if ($pass != $cpass) {
            $error[] = 'Passwords do not match!';
        } else {
            $insert = "INSERT INTO users_form(username, password, user_type, email_hash, created_at) 
                       VALUES('$email', '$pass', '$user_type', '$email_hash', NOW())";
            mysqli_query($conn, $insert);
            header('location:login.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="../public/css/Register.css">
</head>
<body>

<div class="form-container">
    <form action="" method="post">
        <h3>Sign Up</h3>
        <?php
        if (isset($error)) {
            foreach ($error as $error) {
                echo '<span class="error-msg">' . $error . '</span>';
            }
        }
        ?>
        <input type="email" name="email" required placeholder="Enter your email" class="large-input">
        <input type="password" name="password" required placeholder="Enter your password">
        <input type="password" name="cpassword" required placeholder="Confirm your password">
        <select name="user_type">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <input type="submit" name="submit" value="Sign Up" class="form-btn">
        <p>Already have an account? <a href="login.php">Login now</a></p>
    </form>
    <button type="button" onclick="window.location.href='index.html'" class="back-btn">Back</button>
</div>

<style>
    .large-input {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        font-size: 16px;
    }
</style>

</body>
</html>
