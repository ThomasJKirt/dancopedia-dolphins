<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
include '../src/config/mail_config.php';

require "../vendor/autoload.php";

$mail = new PHPMailer(true);

$mail->isSMTP();

$mail->SMTPAuth = true;

//link to smtp server. Do not replace
$mail->Host = 'smtp.gmail.com';
$mail->SMTPSecure = "ssl";
$mail->Port = 465;
$mail->Username = 'brazildances26@gmail.com';
$mail->Password = 'kvua pqvd slcs viqt';

$mail->isHTML(true);

return $mail;