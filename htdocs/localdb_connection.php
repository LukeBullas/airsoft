<?php
$servername = "localhost:3306";
$username = "luke";
$password = "admin";
$db = "airsoft";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
?>