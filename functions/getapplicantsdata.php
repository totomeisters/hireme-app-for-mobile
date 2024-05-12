<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['jobID']) || !empty($_POST['jobID'])) {
        $jobID = $_POST['jobID'];

        require_once '../classes/job.php';

        $job = new Job($conn);
        echo $job->getApplicantCountByMonth($jobID);
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing or empty JobID'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}
