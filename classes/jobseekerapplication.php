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

        $stmt->bind_param("iis", $jobID, $userID, $resumeFilePath);

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
                $row['resumefile'], 
                $row['ApplicationDate'], 
                $row['Status'],
                $row['RejectionReason']
            );
            $applications[] = $application;
        }
    
        return $applications;
    }

    public function getJobApplicationDetailsByUserID($userID, $jobID) {
        $stmt = $this->conn->prepare("SELECT * FROM jobseekerapplication WHERE UserID = ? AND JobID = ?");
        $stmt->bind_param("ii", $userID, $jobID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $applications = array();
        while ($row = $result->fetch_assoc()) {
            $application = new JobSeekerApplicationDetails(
                $row['JobSeekerApplicationID'], 
                $row['JobID'], 
                $row['UserID'], 
                $row['ResumeFilePath'], 
                $row['resumefile'], 
                $row['ApplicationDate'], 
                $row['Status'],
                $row['RejectionReason']
            );
            $applications[] = $application;
        }
    
        return $applications;
    }

    public function getJobApplicationDetailsByID($applicationID) {
        $stmt = $this->conn->prepare("SELECT * FROM jobseekerapplication WHERE JobSeekerApplicationID = ?");
        $stmt->bind_param("i", $applicationID);
        $stmt->execute();
        $result = $stmt->get_result();
      
        $application = $result->fetch_assoc();
      
        if ($application) {
          return new JobSeekerApplicationDetails(
            $application['JobSeekerApplicationID'],
            $application['JobID'],
            $application['UserID'],
            $application['ResumeFilePath'],
            $application['resumefile'], 
            $application['ApplicationDate'],
            $application['Status'],
            $application['RejectionReason']
          );
        } else {
          return null;
        }
    }  

    public function getAllVerifiedJobApplicationDetails() {
        $stmt = $this->conn->prepare("SELECT * FROM jobseekerapplication WHERE Status = 'Verified'");
        $stmt->execute();
        $result = $stmt->get_result();
    
        $applications = array();
        while ($row = $result->fetch_assoc()) {
            $application = new JobSeekerApplicationDetails(
                $row['JobSeekerApplicationID'], 
                $row['JobID'], 
                $row['UserID'], 
                $row['ResumeFilePath'], 
                $row['resumefile'], 
                $row['ApplicationDate'], 
                $row['Status'],
                $row['RejectionReason']
            );
            $applications[] = $application;
        }
    
        return $applications;
    }

    public function getAllJobApplications() {
        $sql = "SELECT
                    MONTH(ApplicationDate) AS month,
                    Status,
                    COUNT(*) AS count
                FROM
                    jobseekerapplication
                GROUP BY
                    MONTH(ApplicationDate), Status
                ORDER BY
                    month, Status;";
        $result = $this->conn->query($sql);

        if($result){
        $response = [
            'verified' => array_fill(0, 12, 0),
            'pending'  => array_fill(0, 12, 0),
            'rejected' => array_fill(0, 12, 0),
            'hired' => array_fill(0, 12, 0),
        ];
        
        foreach ($result as $row) {
            $month = $row['month'] - 1;
            $status = strtolower($row['Status']);
            $response[$status][$month] = (int)$row['count'];
        }

        return json_encode($response);
        } else {
            return json_encode(array('error' => 'Failed to fetch data from the database'));
        }
    }

    public function changeJobApplicationStatus($status, $applicationID, $reason) {
        $stmt = $this->conn->prepare("UPDATE jobseekerapplication SET Status = ?, RejectionReason = ? WHERE JobSeekerApplicationID = ?;");

        $stmt->bind_param("ssi", $status, $reason, $applicationID);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
}
?>
