<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['companyID']) || !empty($_POST['companyID'])) {
        $companyID = $_POST['companyID'];

        require_once '../classes/job.php';

        $job = new Job($conn);
        echo $job->getJobCountByStatus($companyID);
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'No company ID provided'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}
