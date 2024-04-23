<?php
if (!isset($_SESSION)) {
  session_start();
}

require_once('../classes/jobseeker.php');
require_once('../classes/jobseekerdetails.php');

$userID = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_NUMBER_INT);
$firstName = filter_input(INPUT_POST, 'firstName');
$lastName = filter_input(INPUT_POST, 'lastName');
$birthDate = filter_input(INPUT_POST, 'birthDate');
$address = filter_input(INPUT_POST, 'address');
$contactNumber = filter_input(INPUT_POST, 'contactNumber');

$jobSeekerDetails = new JobSeekerDetails(null, $userID, $firstName, $lastName, $birthDate, $address, $contactNumber);

$jobSeeker = new JobSeeker($conn);

$result = $jobSeeker->addJobSeekerDetails($jobSeekerDetails);

if ($result) {
  header('Location: ./dashboard.php');
} else {
  echo "Error adding job seeker details.";
}
