<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/job.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['notificationId'])) {

    $notificationId = (int) $_POST['notificationId'];
    $job = new Job($conn);

    if (isset($_POST['notificationId'])) {
        $readnotif = $job->readNotification($notificationId);
        if ($readnotif == true) {
            $status = 'success';
        } else {
            $status = 'error';
            $message = $readnotif;
        }
    }

    $response = array('status' => $status, 'message' => $message);

    echo json_encode($response);
    exit();
}
