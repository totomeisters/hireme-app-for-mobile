<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once '../classes/user.php';
require_once '../classes/company.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract form data
    $companyname = $_POST['companyName'];
    $companydesc = $_POST['companyDescription'];
    $companyaddress = $_POST['companyAddress'];
    $username = $_SESSION['username'];
    $user = new User($conn);
    $userID = $user->getUserDetails($username)->getUserID();

    if(!$userID == null) {
        $company = new Company($conn);
        if($company->addCompany($companyname, $companydesc, $companyaddress, $userID)){
            $response = array('status' => 'success', 'message' => 'First step completed. You will be redirected shortly.', 'redirect' => '../company/email-verification.php');
        }
        else {
            $response = array('status' => 'error', 'message' => 'Company registration failed.');
        }
    } 
    else {
        $response = array('status' => 'error', 'message' => 'Error. User ID not found.');
    }

    echo json_encode($response);
    exit();
}

if ($_POST["type"] == "profile") {
    // Extract form data
    $company_name = $_POST['name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $rep_position = $_POST['rep_position'];
    $rep_name = $_POST['rep_name'];
    $rep_number = $_POST['rep_number'];
    $companyID = $_POST['companyID'];

    if(!$userID == null) {
        $company = new Company($conn);
        if($company->addCompanyDetails($companyID, $companyName, $address, $contactNumber, $email, $repPosition, $repName, $repNumber)){
            $response = array('status' => 'success', 'message' => 'Company registered. You will be redirected shortly.', 'redirect' => '../company/dashboard.php');
        }
        else {
            $response = array('status' => 'error', 'message' => 'Company registration failed.');
        }
    } 
    else {
        $response = array('status' => 'error', 'message' => 'Error. User ID not found.');
    }

    echo json_encode($response);
    exit();
}
?>
