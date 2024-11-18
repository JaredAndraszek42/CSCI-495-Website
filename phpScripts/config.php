<?php
$dbservername = "localhost";
$dbusername = "BlueTeam";
$dbpassword = "blue2024";
$dbname = "backend_db";

// Create connection
$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
$GLOBALS['conn'] = $conn;

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
