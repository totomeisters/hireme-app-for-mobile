<?php
$servername = "localhost";
$serverusername = "root";
$serverpassword = "";
$dbname = "hireme";

$conn = new mysqli($servername, $serverusername, $serverpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
