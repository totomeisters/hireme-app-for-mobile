<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/user.php';
require_once '../classes/company.php';
require_once '../classes/job.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    echo $_POST['jobID'];
}
?>