<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/company.php';

$company = new Company($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $companyID = $_POST['companyID'];
    $statusErr = 0;
    $companyIDerr = 0;
    $companyName = $company->getCompanyDetailsByCompanyID($companyID)->getCompanyName();

    if(empty($status) || ($status !== "Rejected" && $status !== "Verified")) {
        $statusErr = 1;

        if(empty($status)){
            $response = array('status' => 'error', 'message' => 'Status is missing. Please try again.', 'redirect' => '');
        }
        else{
            $response = array('status' => 'error', 'message' => 'Status is invalid. Please try again.', 'redirect' => '');
        }
    }
    
    if(empty($companyID) || !is_numeric($companyID)) {
        $companyIDerr = 1;
        $response = array('status' => 'error', 'message' => 'Application ID is invalid. Please try again.', 'redirect' => '');
    }

    if($statusErr == 0 && $companyIDerr == 0) {
        if($company->updateCompanyStatus($companyID, $status)) {
            $notifType = 1;
            $notif = $company->addNotif($companyName, $notifType, $status, $companyID);
            if ($notif === true){
                $response = array('status' => 'success', 'message' => 'Status was successfully updated.', 'redirect' => './viewcompanies.php');
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
