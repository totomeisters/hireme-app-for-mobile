<?php
$servername = "localhost";
$serverusername = "u201145375_root";
$serverpassword = "Hireme@pp722";
$key = "hireme";
$dbname = "u201145375_hiremedb";

$conn = new mysqli($servername, $serverusername, $serverpassword,$dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
