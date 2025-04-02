<?php
$host = '127.0.0.1';
$port = 3309;
$username = 'root';
$password = '';
$database = 'brazil_dances';

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
