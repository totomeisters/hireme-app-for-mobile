<?php

require_once('../classes/jobseekerapplication.php');
require_once('../classes/jobseeker.php');
require_once('../classes/job.php');
require_once('../classes/interview.php');

$jobseekerapplication = new JobSeekerApplication($conn);
$jobseeker = new JobSeeker($conn);
$job = new Job($conn);
$application = new Interview($conn);

$interviews = $application->getAllInterviews();

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

echo json_encode($events);

?>
