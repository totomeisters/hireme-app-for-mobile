<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
        echo json_encode(['status' => 'error', 'message' => 'Company details are not available.']);
        exit();
    }

    // Validate and handle salary values
    if (htmlspecialchars($_POST['salaryMin']) < htmlspecialchars($_POST['salaryMax'])) {
        $salaryMin = htmlspecialchars($_POST['salaryMin']);
        $salaryMax = htmlspecialchars($_POST['salaryMax']);
    } else {
        $salaryMax = htmlspecialchars($_POST['salaryMin']);
        $salaryMin = htmlspecialchars($_POST['salaryMax']);
    }

    // Validate and process job location
    if (isset($_POST['jobLocation'])) {
        $jobLocation = $_POST['jobLocation'];
        if (strpos($jobLocation, " Antipolo City") !== false) {
            $jobLocation = str_replace(" Antipolo City", "", $jobLocation);
            $jobLocation = trim($jobLocation);
        }
        $jobLocation = $jobLocation . ' (Antipolo City)';
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Job location is required.', 'redirect' => './addjob.php']);
        exit();
    }

    // Validate and process Work Type
    $workType = $_POST['workType'];
    if ($workType === 'others') {
        if (!isset($_POST['otherWorkType']) || empty($_POST['otherWorkType'])) {
            echo json_encode(['status' => 'error', 'message' => 'Work type is missing.', 'redirect' => './addjob.php']);
            exit();
        } else {
            $workType = ucfirst(htmlspecialchars($_POST['otherWorkType']));
        }
    }

    // Handle Skills and Qualifications
    $skills = implode(", ", $_POST['skills'] ?? []);
    $qualifications = implode(", ", $_POST['qualifications'] ?? []);

    // Handle undefined 'otherIndustry'
    $otherIndustry = isset($_POST['otherIndustry']) ? htmlspecialchars($_POST['otherIndustry']) : '';

    // Other job parameters
    $workHours = htmlspecialchars($_POST['workHours']);
    $jobLocationType = htmlspecialchars($_POST['jobLocationType']);
    $jobIndustry = htmlspecialchars($_POST['jobIndustry']);
    $jobTitle = htmlspecialchars($_POST['jobTitle']);
    $jobDescription = $_POST['jobDescription'];
    $jobType = htmlspecialchars($_POST['jobType']);
    $vacancies = is_numeric($_POST['vacancies']);
    $companyName = $companydetails->getCompanyName();

    // Validate job location type for "On Site"
    if ($jobLocationType === "On Site" && empty($jobLocation)) {
        echo json_encode(['status' => 'error', 'message' => 'Job location is required if the job is "On Site".', 'redirect' => './addjob.php']);
        exit();
    }

    // Add job to the database
    $job = new Job($conn);
    if ($job->addJob($companyID, $jobTitle, $jobDescription, $jobType, $salaryMin, $salaryMax, $workHours, $jobLocation, $jobLocationType, $jobIndustry, $otherIndustry, $workType, $skills, $qualifications, $vacancies)) {
        if ($job->addNotif($companyName, $jobTitle, 0, 0)) {
            // Notify applicants based on work type
            $jobDetails = $job->getJobsByWorkType($workType);
            if ($jobDetails) {
                foreach ($jobDetails as $jobDetail) {
                    $jobapplicationDetails = $jobapplication->getJobApplicationDetailsByJobID($jobDetail['JobID']);
                    if ($jobapplicationDetails) {
                        // Uncomment the following block if applicant notifications are required
                        // foreach ($jobapplicationDetails as $jobapplicationDetail) {
                        //     $applicantUserID = $jobapplicationDetail->getUserID();
                        //     $userEmail = $user->getUserDetailsByUserID($applicantUserID)->getEmail();
                        //     if ($job->newJobNotif($userEmail, $jobTitle, $jobDescription) !== true) {
                        //         echo json_encode(['status' => 'error', 'message' => 'Notifying applicants failed.']);
                        //         exit();
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
?>