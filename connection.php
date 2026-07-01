// <?php

// $host = "127.0.0.1";
// $username = "root";
// $password = "";
// $database = "aviation_db";

// $conn = new mysqli(
//     $host,
//     $username,
//     $password,
//     $database
// );

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// ?>

<?php

$host = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_NAME');

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
