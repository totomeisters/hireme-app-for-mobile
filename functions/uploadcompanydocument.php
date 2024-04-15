<?php
if(!session_start()){
    session_start();
}

if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}
    
require_once __DIR__.'/../classes/user.php';
require_once __DIR__.'/../classes/company.php';
require_once __DIR__.'/../classes/companyapplication.php';

$user = new User($conn);
$company = new Company($conn);
$companyapplication = new CompanyApplication($conn);

$userID = $user->getUserDetails($username)->getUserID();
$companyID = $company->getCompanyDetails($userID)->getCompanyID();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $docname = $_POST['DocumentName'];
    $documentName = 'file_' . microtime(true) . '_' . rand(1000, 9999);

    if (!isset($_FILES['fileUpload'])) {
        $response = array('status' => 'error', 'message' => 'Please add a file and try again.', 'redirect' => './verification.php');
        echo json_encode($response);
        exit();
    }

    $fileName = basename($_FILES['fileUpload']['name']);
    $fileName = filter_var($fileName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    $maxFileSize = 10 * 1024 * 1024; // 10MB

    if (!in_array($_FILES['fileUpload']['type'], $allowedTypes)) {
        $response = array('status' => 'error', 'message' => 'Invalid file type. Please try again.', 'redirect' => './verification.php');
        echo json_encode($response);
        exit();
    }

    if ($_FILES['fileUpload']['size'] > $maxFileSize) {
        $response = array('status' => 'error', 'message' => 'File size exceeds the limit. Please try again.', 'redirect' => './verification.php');
        echo json_encode($response);
        exit();
    }

    $finalFileName = $documentName . '.' . $fileExtension;

    $documentfilepath = '../company/documents/' . $finalFileName;

    // upload doc muna bago mag sql query, "move_uploaded_file($_FILES['fileUpload']['tmp_name'], $documentfilepath)" muna
    // tas saka lang mag send $documentName saka $documentfilepath

        if (!move_uploaded_file($_FILES['fileUpload']['tmp_name'], $documentfilepath)) {
            $response = array('status' => 'error', 'message' => 'Error uploading document. Please try again.', 'redirect' => './verification.php');
        } 
        else {
            $addapplication = $companyapplication->addCompanyApplication($companyID, $docname, $documentfilepath);

            if(!$addapplication == true){
                $response = array('status' => 'error', 'message' => 'Error connecting to server. Please try again.', 'redirect' => './verification.php');
            }

            $response = array('status' => 'success', 'message' => 'Successfully added document. Page will reload please wait.', 'redirect' => './verification.php');
        }
        
    echo json_encode($response);
    exit();
}
?>


