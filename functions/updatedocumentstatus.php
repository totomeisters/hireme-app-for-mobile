<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/companyapplication.php';

$company = new CompanyApplication($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $companyApplicationID = $_POST['companyApplicationID'];
    if($_POST['reason'] == null || empty($_POST['reason'])){
        $reason = 'nullOrEmpty';
    }
    else{
        $reason = $_POST['reason'];
    }

    $statusErr = 0;
    $companyApplicationIDerr = 0;

    if(empty($status) || ($status !== "Rejected" && $status !== "Verified")) {
        $statusErr = 1;

        if(empty($status)){
            $response = array('status' => 'error', 'message' => 'Status is missing. Please try again.', 'redirect' => '');
        }
        else{
            $response = array('status' => 'error', 'message' => 'Status is invalid. Please try again.', 'redirect' => '');
        }
    }
    
    if(empty($companyApplicationID) || !is_numeric($companyApplicationID)) {
        $companyApplicationIDerr = 1;
        $response = array('status' => 'error', 'message' => 'Application ID is invalid. Please try again.', 'redirect' => '');
    }

    if($statusErr == 0 && $companyApplicationIDerr == 0) {
        if($company->updateCompanyApplication($companyApplicationID, $status, $reason)) {
            $response = array('status' => 'success', 'message' => 'Status was successfully updated.', 'redirect' => './viewcompanies.php');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to update status.', 'redirect' => '');
        }
    }
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request method.', 'redirect' => '');
}

echo json_encode($response);
?>
