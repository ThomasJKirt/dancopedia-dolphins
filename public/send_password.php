<?php

require_once __DIR__ . '/../src/config/database.php';

require "../vendor/autoload.php";

//to get the local repository name for url
$currentDir = getcwd();

$parentDir = dirname($currentDir);

$dirNames = explode(DIRECTORY_SEPARATOR, $parentDir);


if (isset($_POST["email"])) {
    $email = $_POST["email"];

    $token = bin2hex(random_bytes(16));

    $token_hash = hash("sha256", $token);

    $expire = date("Y-m-d H:i:s", time() + 60 * 15);

    $sql = "UPDATE users_form 
            SET reset_token = ?, 
                reset_token_expires = ? 
            WHERE email = ?";


    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param("sss", $token_hash, $expire, $email);

        if ($stmt->execute()) {

            echo "Reset token successfully saved for email: " . htmlspecialchars($email);
        } else {

            echo "Failed to update reset token for email: " . htmlspecialchars($email);
        }

        $stmt->close();
    } else {
        echo "Failed to prepare SQL statement.";
    }
} else {
    echo "Email is required.";
}

if ($conn->affected_rows) {
    $mail = require "api/mail_password_reset.php";
    $mail->setFrom("brazildances26@gmail.com");
    $mail->addAddress($email);
    $mail->Subject = "Reset Password";
    $mail->Body = "<h3>Click </h3><a href= 'http://localhost/dancopedia-dolphins/public/reset_password.php?token=$token'>this link</a><h3> to reset your password.</h3>";
    try {
        $mail->send();
    } catch (Exception $e) {
        echo "Message wasn't sent. Mailer error: {$mail->ErrorInfo}";
    }
}

echo " Message Sent!";