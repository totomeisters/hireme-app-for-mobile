<?php                  /*                                                                                 //OLD CODE
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
*/
?>

<?php
// Ensure session is started
if (!isset($_SESSION)) {
    session_start();
}

// Include the BinaryPDF class
require_once '../config/config.php'; // Ensure the database connection is configured
require_once '../classes/pdf.php'; // Include the BinaryPDF class

// Establish database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Create an instance of BinaryPDF
$pdfGenerator = new BinaryPDF($conn);

// Check if userId and jobId are provided in the request
if (!isset($_GET['userId']) || !isset($_GET['jobId'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing userId or jobId']);
    exit;
}

$userId = $_GET['userId'];
$jobId = $_GET['jobId'];

// Fetch the binary data or file path
$binaryData = $pdfGenerator->getApplicantResume($userId, $jobId);

if ($binaryData === "No data found") {
    // NEW CODE: Handle case where no resume is found
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'Resume not found for Applicant #' . $userId . ' and Job #' . $jobId]);
    exit;
} elseif (is_string($binaryData)) {
    // NEW CODE: Check if the binary data is a file path
    if (file_exists($binaryData)) {
        // Serve the file directly if it exists on the server
        header('Content-Type: application/pdf');
        readfile($binaryData); // Read and output the file contents
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'File not found on server for Applicant #' . $userId . ' and Job #' . $jobId]);
        exit;
    }
} else {
    // Serve binary data (BLOB)
    header('Content-Type: application/pdf'); // Set the content type to PDF
    echo $binaryData; // Output the binary data
}

// Close the database connection
$conn->close();
?>