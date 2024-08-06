<?php
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
}
