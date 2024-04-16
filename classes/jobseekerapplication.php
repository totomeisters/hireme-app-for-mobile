<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/jobseekerapplicationdetails.php';

Class JobSeekerApplication {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addJobApplication($jobID, $userID, $resumeFilePath) {
        $stmt = $this->conn->prepare("INSERT INTO jobseekerapplication (JobID, UserID, ResumeFilePath, ApplicationDate, Status) VALUES (?, ?, ?, NOW(), 'Pending')");

        $stmt->bind_param("sss", $jobID, $userID, $resumeFilePath);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getJobApplicationDetailsByJobID($jobID) {
        $stmt = $this->conn->prepare("SELECT * FROM jobseekerapplication WHERE JobID = ?");
        $stmt->bind_param("s", $jobID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $applications = array();
        while ($row = $result->fetch_assoc()) {
            $application = new JobSeekerApplicationDetails(
                $row['JobSeekerApplicationID'], 
                $row['JobID'], 
                $row['UserID'], 
                $row['ResumeFilePath'], 
                $row['ApplicationDate'], 
                $row['Status']);
            $applications[] = $application;
        }
    
        return $applications;
    }
    
}
?>
