<?php
if(!session_start()){
    session_start();
}
if(isset($_GET['companyID'])) {
    $companyId = $_GET['companyID'];

require_once('../classes/jobseekerapplication.php');
require_once('../classes/jobseeker.php');
require_once('../classes/job.php');
require_once('../classes/interview.php');

$jobseekerapplication = new JobSeekerApplication($conn);
$jobseeker = new JobSeeker($conn);
$job = new Job($conn);
$application = new Interview($conn);

$jobdetails = $job->getAllJobs($companyId);

foreach ($jobdetails as $jobdetail) {
    // get all interviews for all jobs for the current company
    $interviews = $application->getAllInterviewsByJobID($jobdetail->getJobID());

$events = [];
foreach ($interviews as $interview) {
    $jobID = $interview->getJobID();
    $jobTitle = $job->getJobDetailsByID($jobID)->getJobTitle();

    $jobseekerapplicationID = $interview->getJobSeekerApplicationID();
    $userID = $jobseekerapplication->getJobApplicationDetailsByID($jobseekerapplicationID)->getUserID();
    $userFName = $jobseeker->getJobSeekerDetailsByUserID($userID)->getFirstName();
    $userLName = $jobseeker->getJobSeekerDetailsByUserID($userID)->getLastName();
    $userFullName = $userFName.' '.$userLName;

    $date_string = $interview->getInterviewDate();
    $date_object = DateTime::createFromFormat('Y-m-d H:i:s', $date_string);
    $date = $date_object->format('F d, Y (h:i A)');

    $event = array(
        'id' => $interview->getInterviewID(),
        'title' => 'Interview',
        'start' => $interview->getInterviewDate(),
        'interviewdate' => $date,
        'name' => $userFullName,
        'job' => ucfirst($jobTitle),
    );
    $events[] = $event;
}
}

echo json_encode($events);

} else {
    http_response_code(400);
    echo json_encode(array("error" => "CompanyID is not provided"));
    exit;
}
?>
