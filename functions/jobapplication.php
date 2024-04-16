<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/jobseekerapplication.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jobID = $_POST['jobID'];
    $userID = $_POST['userID'];
    $resumeFilePath = $_FILES['resumeFilePath'];

    if (empty($jobID) || empty($userID)) {
        $response = array('status' => 'error', 'message' => 'Invalid job ID or user ID.', 'redirect' => './jobs.php');
        echo json_encode($response);
        exit();
    }

    if(empty($resumeFilePath)){
        $response = array('status' => 'error', 'message' => 'No file uploaded.', 'redirect' => './jobs.php');
        echo json_encode($response);
        exit();
    }
    

    $documentName = 'resume_' . $userID .'_'. microtime(true) . '_' . rand(1000, 9999);

    if (!isset($_FILES['resumeFilePath'])) {
        $response = array('status' => 'error', 'message' => 'Please add a file and try again.', 'redirect' => './jobs.php');
        echo json_encode($response);
        exit();
    }

    $fileName = basename($_FILES['resumeFilePath']['name']);
    $fileName = filter_var($fileName, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    $maxFileSize = 10 * 1024 * 1024; // 10MB

    if (!in_array($_FILES['resumeFilePath']['type'], $allowedTypes)) {
        $response = array('status' => 'error', 'message' => 'Invalid file type. Please try again.', 'redirect' => './jobs.php');
        echo json_encode($response);
        exit();
    }

    if ($_FILES['resumeFilePath']['size'] > $maxFileSize) {
        $response = array('status' => 'error', 'message' => 'File size exceeds the limit. Please try again.', 'redirect' => './jobs.php');
        echo json_encode($response);
        exit();
    }

    $finalFileName = $documentName . '.' . $fileExtension;

    $documentfilepath = '../jobseeker/documents/' . $finalFileName;

    if (!move_uploaded_file($_FILES['resumeFilePath']['tmp_name'], $documentfilepath)) {
        $response = array('status' => 'error', 'message' => 'Error uploading document. Please try again.', 'redirect' => './jobs.php');
    } else {
        $jobApplication = new JobSeekerApplication($conn);
        $result = $jobApplication->addJobApplication($jobID, $userID, $documentfilepath);

        if ($result) {
            $response = array(
                'status' => 'success',
                'message' => 'Job application submitted successfully.',
                'redirect' => './jobs.php'
            );
            echo json_encode($response);
            exit;
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Error submitting job application. Please try again.'
            );
            echo json_encode($response);
            exit;
        }
    }

    echo json_encode($response);
    exit;
}
?>
