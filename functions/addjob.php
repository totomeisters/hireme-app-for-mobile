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
                 
    $jobTitle = htmlspecialchars($_POST['jobTitle']);
    $jobDescription = htmlspecialchars($_POST['jobDescription']);
    $jobType = htmlspecialchars($_POST['jobType']);
    $salaryMin = htmlspecialchars($_POST['salaryMin']);
    $salaryMax = htmlspecialchars($_POST['salaryMax']);
    $workHours = htmlspecialchars($_POST['workHours']);
    $jobLocation = htmlspecialchars($_POST['jobLocation']);
    $jobLocationType = htmlspecialchars($_POST['jobLocationType']);

    $job = new Job($conn);
    if($job->addJob($companyID, $jobTitle, $jobDescription, $jobType, $salaryMin, $salaryMax, $workHours, $jobLocation, $jobLocationType)){
        $response = array('status' => 'success', 'message' => 'Successfully added job. Connecting to server, please wait.', 'redirect' => './jobs.php');
    }
    else {
        $response = array('status' => 'error', 'message' => 'Adding job failed. Please try again.', 'redirect' => './addjob.php');
    }

    echo json_encode($response);
    exit();
}
?>