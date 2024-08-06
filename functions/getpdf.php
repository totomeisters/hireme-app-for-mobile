<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/pdf.php';

$pdfGenerator = new BinaryPDF($conn);

if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];
    $jobId = $_GET['jobId'];
    $binaryData = $pdfGenerator->getApplicantResume($userId, $jobId);

    if (is_string($binaryData) && $binaryData !== 'No data found') {
        header('Content-Type: application/pdf');
        echo $binaryData;
    } else {
        http_response_code(404);
        echo 'PDF not found for Applicant #'.$userId.' and Job #'.$jobId;
    }
}

?>
