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

    public function addJobSeekerDetails($jobSeekerDetails) {
        $userID = $jobSeekerDetails->getUserID();
        $firstName = $jobSeekerDetails->getFirstName();
        $lastName = $jobSeekerDetails->getLastName();
        $birthDate = $jobSeekerDetails->getBirthDate();
        $address = $jobSeekerDetails->getAddress();
        $contactNumber = $jobSeekerDetails->getContactNumber();
    
        try {
          $sql = "INSERT INTO jobseekers (UserID, FirstName, LastName, BirthDate, Address, ContactNumber) VALUES (?, ?, ?, ?, ?, ?)";
          $stmt = $this->conn->prepare($sql);
          $stmt->bind_param("isssss", $userID, $firstName, $lastName, $birthDate, $address, $contactNumber);
          $stmt->execute();
    
          if ($stmt->affected_rows === 1) {
            return true;
          } else {
            return false;
          }
    
          $stmt->close();
        } catch (mysqli_sql_exception $e) {
          return false;
        }
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