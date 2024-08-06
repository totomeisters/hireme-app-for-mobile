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

    $events = [];
    foreach ($jobdetails as $jobdetail) {
        $interviews = $application->getAllInterviewsByJobID($jobdetail->getJobID());

        foreach ($interviews as $interview) {
            $jobTitle = $job->getJobDetailsByID($interview->getJobID())->getJobTitle();
            $jobseekerDetails = $jobseeker->getJobSeekerDetailsByUserID(
                $jobseekerapplication->getJobApplicationDetailsByID($interview->getJobSeekerApplicationID())->getUserID()
            );

            $event = array(
                'id' => $interview->getInterviewID(),
                'title' => 'Interview',
                'start' => $interview->getInterviewDate(),
                'interviewdate' => DateTime::createFromFormat('Y-m-d H:i:s', $interview->getInterviewDate())->format('F d, Y (h:i A)'),
                'name' => $jobseekerDetails->getFirstName() . ' ' . $jobseekerDetails->getLastName(),
                'job' => ucfirst($jobTitle),
                'applicantID' => $jobseekerDetails->getUserID(),
                'jobID' => $interview->getJobID(),
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
