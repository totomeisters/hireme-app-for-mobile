<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/jobseekerdetails.php';

class JobSeeker {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getJobSeekerDetailsByUserID($userID) {
        $query = "SELECT * FROM jobseekers WHERE UserID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new JobSeekerDetails(
                $row['JobSeekerID'],
                $row['UserID'],
                $row['FirstName'],
                $row['LastName'],
                $row['BirthDate'],
                $row['Address'],
                $row['ContactNumber']
            );
        } else {
            return null;
        }
    }

    public function getFaveJobsIDByJobseekerID($jobseekerID){
        $stmt = $this->conn->prepare("SELECT * FROM favoritejobs WHERE JobSeekerID = ?");
        $stmt->bind_param("i", $jobseekerID);
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        $jobs = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $jobs[] = $row['JobID'];
            }
            
            $stmt->close();
    
            return $jobs;
        } else {
            return null;
        }
    }
    
}