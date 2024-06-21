<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/user.php';
require_once '../classes/company.php';
require_once '../classes/job.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $username = $_SESSION['username'];
    $user = new User($conn);
    $userID = $user->getUserDetails($username)->getUserID();

    $company = new Company($conn);
    $companydetails = $company->getCompanyDetails($userID);

    if (isset($companydetails)) {
        $companyID = $companydetails->getCompanyID();
    } else {
        echo "Company details are not available.";
    }

    if (htmlspecialchars($_POST['salaryMin']) < htmlspecialchars($_POST['salaryMax'])) {
        $salaryMin = htmlspecialchars($_POST['salaryMin']);
        $salaryMax = htmlspecialchars($_POST['salaryMax']);
    } else {
        $salaryMax = htmlspecialchars($_POST['salaryMin']);
        $salaryMin = htmlspecialchars($_POST['salaryMax']);
    }

    if (isset($_POST['jobLocation'])) {
        $jobLocation = $_POST['jobLocation'];
        if (strpos($jobLocation, " Antipolo City") !== false) {
            $jobLocation = str_replace(" Antipolo City", "", $jobLocation);
            $jobLocation = trim($jobLocation);
        }
    }

    $workHours = htmlspecialchars($_POST['workHours']);
    $jobLocation = $jobLocation . ' (Antipolo City)';
    $jobLocationType = htmlspecialchars($_POST['jobLocationType']);
    $jobIndustry = htmlspecialchars($_POST['jobIndustry']);
    $otherIndustry = htmlspecialchars($_POST['otherIndustry']);
    $jobTitle = htmlspecialchars($_POST['jobTitle']);
    $jobDescription = $_POST['jobDescription'];
    $jobType = htmlspecialchars($_POST['jobType']);
    $companyName = $companydetails->getCompanyName();

    if ($jobLocationType === "On Site" && ($jobLocation == null || empty($jobLocation))) {
        $response = array('status' => 'error', 'message' => 'Job location is required if the job is "On Site". Please try again.', 'redirect' => './addjob.php');
        echo json_encode($response);
        exit();
    }

    $job = new Job($conn);
    if ($job->addJob($companyID, $jobTitle, $jobDescription, $jobType, $salaryMin, $salaryMax, $workHours, $jobLocation, $jobLocationType, $jobIndustry, $otherIndustry)) {
        if ($job->addNotif($companyName, $jobTitle, 0, 0)) {
            $response = array('status' => 'success', 'message' => 'Successfully added job. Connecting to server, please wait.', 'redirect' => './jobs.php');
        } else {
            $response = array('status' => 'error', 'message' => 'Notifiying the managers failed. Please try again.', 'redirect' => './addjob.php');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Adding job failed. Please try again.', 'redirect' => './addjob.php');
    }

    echo json_encode($response);
    exit();
}
