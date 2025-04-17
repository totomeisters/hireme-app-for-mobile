

   <?php  /*                                                                                          // OLD CODE
if (!isset($_SESSION)) { 
    session_start();
}
require_once __DIR__ . '/../config/config.php';

class BinaryPDF
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getApplicantResume($userId, $jobId) {
        $sql = "SELECT * FROM resumedata WHERE userId = ? AND jobId = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $jobId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $binaryData = $row["resumePath"];
            return $binaryData;
        } else {
            $message = "No data found";
            return $message;
        }
    }
} */
?>



<?php
// Ensure session is started
if (!isset($_SESSION)) {
    session_start();
}

// Include required configuration
require_once __DIR__ . '/../config/config.php';

class BinaryPDF
{
    private $conn;

    // Constructor to initialize database connection
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // UPDATED CODE: Fetch applicant's resume for a specific userId and jobId
    public function getApplicantResume($userId, $jobId) {
        // Ensure we fetch the latest or most relevant resume
        $sql = "SELECT resumePath FROM resumedata WHERE userId = ? AND jobId = ? ORDER BY uploaded_at DESC LIMIT 1"; // Added ORDER BY to fetch the latest entry
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $jobId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Return the resume path if found, otherwise return an error message
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["resumePath"]; // Return the resumePath (file path or BLOB)
        } else {
            return "No data found"; // Return a string indicating no data
        }
    }
}
?>