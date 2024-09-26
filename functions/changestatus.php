<?php
require_once '../classes/hiree.php';
require_once '../classes/hireedetails.php';
require_once '../classes/jobseekerapplication.php';

$hiree = new Hiree($conn);
$application = new JobSeekerApplication($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST["status"];
    $applicationID = filter_var($_POST['applicationID'], FILTER_SANITIZE_NUMBER_INT);

    if ($status !== 'Hired' && $status !== 'Rejected') {
        $response = array('status' => 'error', 'message' => 'Invalid status. Must be "Hired" or "Rejected".');
        echo json_encode($response);
        exit();
    }

    if (!is_numeric($applicationID)) {
        $response = array('status' => 'error', 'message' => 'Invalid application ID. Must be an integer.');
        echo json_encode($response);
        exit();
    }

    if($status === 'Hired' ){

        $jobID = $_POST['jobID'];
        $jobName = $_POST['jobName'];
        $userID = $_POST['userID'];
        $fullName = $_POST['fullName'];
        $companyID = $_POST['companyID'];
        $companyName = $_POST['companyName'];
        $dateHired = $_POST['dateHired'];
    
        $hireeDetails = new HireeDetails(null, $fullName, $jobName, $companyName, $userID, $jobID, $companyID, $applicationID, $dateHired);

        if ($application->changeJobApplicationStatus('Hired', $applicationID)) {
            if ($hiree->addHiree($hireeDetails)) {
                $response = array('status' => 'success', 'message' => 'Hiree recorded successfully. Connecting to server, please wait.');
        
            } else {
                $response = array('status' => 'error', 'message' => 'Adding hiree to records failed. Please try again.');
            }    
        } else {
            $response = array('status' => 'error', 'message' => 'Error adding hiree to records. Please try again.');
        }

    } 
    else{
        if ($application->changeJobApplicationStatus('Rejected', $applicationID)) {
            $response = array('status' => 'success', 'message' => 'Successfully rejected application. Connecting to server, please wait.');
    
        } else {
            $response = array('status' => 'error', 'message' => 'Rejecting application failed. Please try again.');
        }
    }
    

    echo json_encode($response);
    exit();
}
?>