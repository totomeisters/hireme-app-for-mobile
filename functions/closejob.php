<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../config/config.php';

if (!empty($_POST['jobId'])) {
    $jobID = $_POST['jobId'];

    $sql = "UPDATE `jobs` SET `VerificationStatus` = 'Closed' WHERE `JobID` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $jobID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = array(
            'status' => 'success',
            'message' => 'Job closed successfully',
            'redirect' => './jobs.php'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Error closing job.'
        );
    }
    
$stmt->close();
$conn->close();
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Server error.'
    );
}

header('Content-Type: application/json');
echo json_encode($response);

