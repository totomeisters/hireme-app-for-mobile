<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once '../classes/jobseekerapplication.php';

    $seeker = new JobSeekerApplication($conn);
    echo $seeker->getAllJobApplications();
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}