<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/jobseekerapplication.php';

$jobapplication = new JobSeekerApplication($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $applicationID = $_POST['applicationID'];
    $statuserr = 0;
    $applicationIDerr = 0;
    $reason = $_POST['reason'] ?? null;

    if(empty($status) || ($status !== "Rejected" && $status !== "Verified")) {
        $statuserr = 1;

        if(empty($status)){
            $response = array('status' => 'error', 'message' => 'Status is missing. Please try again.', 'redirect' => '');
        }
        else{
            $response = array('status' => 'error', 'message' => 'Status is invalid. Please try again.', 'redirect' => '');
        }
    }
    
    if(empty($applicationID) || !is_numeric($applicationID)) {
        $applicationIDerr = 1;
        $response = array('status' => 'error', 'message' => 'Application ID is invalid. Please try again.', 'redirect' => '');
    }

    if($statuserr == 0 && $applicationIDerr == 0) {
        if($jobapplication->changeJobApplicationStatus($status, $applicationID, $reason)) {
            
            if($status=='Verified'){
                $response = array('status' => 'success', 'message' => 'Successfully verified.', 'redirect' => './candidates.php');
            }elseif($status=='Rejected'){
                $response = array('status' => 'success', 'message' => 'Successfully rejected.', 'redirect' => '');
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to update status.', 'redirect' => '');
        }
    }
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request method.', 'redirect' => '');
}

echo json_encode($response);
?>
