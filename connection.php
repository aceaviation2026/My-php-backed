<?php

$host = "127.0.0.1";
$username = "root";
$password = "";
$database = "aviation_db";

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>