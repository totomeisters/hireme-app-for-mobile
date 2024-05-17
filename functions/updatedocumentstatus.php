<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/companyapplication.php';

$company = new CompanyApplication($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reason = null;
    if(isset($_POST['reason']) && !empty($_POST['reason'])){
        $reason = $_POST['reason'];
    }
    $status = $_POST['status'];
    $companyApplicationID = $_POST['companyApplicationID'];
    $companyID = $_POST['companyID'];
    $docstatus = ($status == "Verified") ? 1 : 0;
    if ($_POST['DocumentType']) {
        switch ($_POST['DocumentType']) {
            case 'BIR Registration':
                $document = 'bir';
                break;
            case 'SEC Registration':
                $document = 'sec';
                break;
            case 'Business Permit':
                $document = 'businesspermit';
                break;
            case 'Mayor\'s Permit':
                $document = 'mayorpermit';
                break;
            case 'Certificate':
                $document = 'certificate';
                break;
        }
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
        $_SESSION['viewcompanyApplicationID'] = $companyApplicationID;
        $_SESSION['viewcompanyID'] = $companyID;
        $update = $company->updateCompanyApplication($companyApplicationID, $status, $reason, $document, $docstatus, $companyID);
        if($update === true) {
            $response = array('status' => 'success', 'message' => 'Status was successfully updated.', 'redirect' => './viewdocument.php');
        } else {
            $response = array('status' => 'error', 'message' => $update, 'redirect' => '');
        }
    }
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request method.', 'redirect' => '');
}

echo json_encode($response);
?>
