<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/job.php';
require_once '../classes/company.php';

$job = new Job($conn);
$company = new Company($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $jobID = $_POST['jobID'];
    $companyID = $_POST['companyID'];
    $statusErr = 0;
    $jobIDerr = 0;
    $jobTitle = $job->getJobDetailsByID($jobID)->getJobTitle();
    $companyId = $job->getJobDetailsByID($jobID)->getCompanyId();
    $companyName = $company->getCompanyDetailsByCompanyID($companyId)->getCompanyName();
    $reason = $_POST['reason'] ?? "No reason provided.";

    if (empty($status) || ($status !== "Rejected" && $status !== "Verified")) {
        $statusErr = 1;

        if (empty($status)) {
            $response = array('status' => 'error', 'message' => 'Status is missing. Please try again.', 'redirect' => '');
        } else {
            $response = array('status' => 'error', 'message' => 'Status is invalid. Please try again.', 'redirect' => '');
        }
    }

    if (empty($jobID) || !is_numeric($jobID)) {
        $jobIDerr = 1;
        $response = array('status' => 'error', 'message' => 'Application ID is invalid. Please try again.', 'redirect' => '');
    }

    if ($statusErr == 0 && $jobIDerr == 0) {
        if ($job->updateJobStatus($status, $jobID, $reason)) {
            $job->addNotif(null, $jobTitle, 1, $companyID);
            if (isset($companyId) && isset($companyName)){
                $content = ucfirst($jobTitle)." at ".ucfirst($companyName);
                if($job->addUserNotif($content) != true) {
                    $response = array('status' => 'error', 'message' => 'Failed to add user notification.', 'redirect' => '');
                }
            }
            $_SESSION['viewjobID'] = $jobID;
            $_SESSION['viewcompanyID'] = $companyID;
            $response = array('status' => 'success', 'message' => 'Status was successfully updated.', 'redirect' => './viewjob.php');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to update status.', 'redirect' => '');
        }
    }
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request method.', 'redirect' => '');
}

echo json_encode($response);
