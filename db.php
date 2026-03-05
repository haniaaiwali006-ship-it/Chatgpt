<?php
$servername = "localhost";
$username = "rsoa_rsoa278_5";
$password = "654321#";
$dbname = "rsoa_rsoa278_5";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
