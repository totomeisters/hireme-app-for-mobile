<?php
$servername = "localhost";
$serverusername = "root";
$serverpassword = "";
$key = "hireme";
$dbname = "u201145375_hiremedb";

$conn = new mysqli($servername, $serverusername, $serverpassword,$dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
