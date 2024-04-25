<?php
$servername = "localhost";
$serverusername = "root";
$serverpassword = "";
$dbname = "hireme1";

$conn = new mysqli($servername, $serverusername, $serverpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
