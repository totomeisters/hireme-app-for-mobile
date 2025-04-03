
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 // OLD CODE BOSSING IN CASE NA MAGKA ERROR ////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/user.php';
require_once '../classes/company.php';
require_once '../classes/job.php';
require_once '../classes/jobseekerapplication.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $jobapplication = new JobSeekerApplication($conn);

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

    $workType = $_POST['workType'];

    if ($workType === 'others') {
        if (!isset($_POST['otherWorkType'])) {
            $response = array('status' => 'error', 'message' => 'Work type is missing.', 'redirect' => './addjob.php');
        } else {
            $otherWorkType = htmlspecialchars($_POST['otherWorkType']);
            $workType = ucfirst($otherWorkType);
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
    if ($job->addJob($companyID, $jobTitle, $jobDescription, $jobType, $salaryMin, $salaryMax, $workHours, $jobLocation, $jobLocationType, $jobIndustry, $otherIndustry, $workType)) {
        if ($job->addNotif($companyName, $jobTitle, 0, 0)) {
            $jobDetails = $job->getJobsByWorkType($workType);
            if ($jobDetails) {
                foreach ($jobDetails as $jobDetail) {
                    $jobapplicationDetails = $jobapplication->getJobApplicationDetailsByJobID($jobDetail['JobID']);
                    if ($jobapplicationDetails) {
                        // foreach ($jobapplicationDetails as $jobapplicationDetail) {
                        //     $applicantUserID = $jobapplicationDetail->getUserID();
                        //     $userEmail = $user->getUserDetailsByUserID($applicantUserID)->getEmail();
                        //     if ($job->newJobNotif($userEmail, $jobTitle, $jobDescription) !== true) {
                        //         $response = array('status' => 'error', 'message' => 'Notifying applicants failed. Please try again.', 'redirect' => './addjob.php');
                        //     } 
                        //     else {
                        //         $response = array('status' => 'success', 'message' => 'Successfully notified possible applicants. Connecting to server, please wait.', 'redirect' => './jobs.php');
                        //     }
                        // }
                    }
                }
            } else {
            $response = array('status' => 'error', 'message' => 'Error adding job. Please try again.', 'redirect' => './addjob.php');
        }

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
  // OLD CODE BOSSING IN CASE MAG KA ERROR ////////////////////////////////////////////////////////////////////////////////////////////////////////// LAGYAN LANG NG COMMENT /* */
?>

