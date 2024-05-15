<?php
require_once('../classes/company.php');

$company = new Company($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contactNumber = $_POST['contact_number'];
    $email = $_POST['email'];
    $repPosition = $_POST['rep_position'];
    $repName = $_POST['rep_name'];
    $repNumber = $_POST['rep_number'];
    $companyID = $_POST['companyID'];

    $success = $company->addCompanyProfile($name, $address, $contactNumber, $email, $repPosition, $repName, $repNumber, $companyID);

    if ($success) {
        $response = array('status' => 'success', 'message' => 'Company profile added successfully.', 'redirect' => '../company/dashboard.php');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to add company profile.');
    }
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request method.');
}

echo json_encode($response);
?>
