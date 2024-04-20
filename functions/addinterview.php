<?php
require_once '../classes/interview.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $interview = new Interview($conn);

    $jobID = $_POST['jobID'];
    $jobSeekerApplicationID = $_POST['jobSeekerApplicationID'];
    $interviewDate = $_POST['interviewDate'];
    $dateMade = date('Y-m-d H:i:s');

    $interviewDetails = new InterviewDetails(null, $jobID, $jobSeekerApplicationID, $interviewDate, $dateMade, null);

    if ($interview->addInterview($interviewDetails)) {
        $response = array('status' => 'success', 'message' => 'Interview set successfully. Connecting to server, please wait.');

    } else {
        $response = array('status' => 'error', 'message' => 'Setting interview failed. Please try again.');
    }

    echo json_encode($response);
    exit();
}
?>