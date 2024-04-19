<?php
require_once '../classes/interview.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $interview = new Interview($conn);

    $jobID = $_POST['job_id'];
    $jobSeekerApplicationID = $_POST['job_seeker_application_id'];
    $interviewDate = $_POST['interview_date'];
    $dateMade = date('Y-m-d H:i:s');

    $interviewDetails = new InterviewDetails(null, $jobID, $jobSeekerApplicationID, $interviewDate, $dateMade, null);

    if ($interview->addInterview($interviewDetails)) {
        echo "Interview added successfully.";
    } else {
        echo "Error adding interview.";
    }

    $conn->close();
}
?>