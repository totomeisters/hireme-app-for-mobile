<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/hireedetails.php';

class Hiree
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllHirees() {
        $stmt = $this->conn->prepare("SELECT * FROM hirees");
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        $hireeDetails = [];
        while ($row = $result->fetch_assoc()) {
            $hireeDetails[] = new HireeDetails(
                $row['HireeID'],
                $row['FullName'],
                $row['JobName'],
                $row['CompanyName'],
                $row['UserID'],
                $row['JobID'],
                $row['CompanyID'],
                $row['ApplicationID'],
                $row['DateHired']
            );
        }
    
        $stmt->close();
    
        return $hireeDetails;
    }

    public function getHireeDetailsByID($hireeID) {
        $stmt = $this->conn->prepare("SELECT * FROM hirees WHERE HireeID = ?");
        $stmt->bind_param("i", $hireeID);
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return new HireeDetails(
                $row['HireeID'],
                $row['FullName'],
                $row['JobName'],
                $row['CompanyName'],
                $row['UserID'],
                $row['JobID'],
                $row['CompanyID'],
                $row['ApplicationID'],
                $row['DateHired']
            );
        } else {
            $stmt->close();
            return null;
        }
    }

    public function addHiree($hireeDetails)
    {
        $fullName = $hireeDetails->getFullName();
        $jobName = $hireeDetails->getJobName();
        $companyName = $hireeDetails->getCompanyName();
        $userID = $hireeDetails->getUserID();
        $jobID = $hireeDetails->getJobID();
        $companyID = $hireeDetails->getCompanyID();
        $applicationID = $hireeDetails->getApplicationID();
        $dateHired = $hireeDetails->getDateHired();

        try {
            $stmt = $this->conn->prepare("INSERT INTO hirees (FullName, JobName, CompanyName, UserID, JobID, CompanyID, ApplicationID, DateHired) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiiiis", $fullName, $jobName, $companyName, $userID, $jobID, $companyID, $applicationID, $dateHired);
            $result = $stmt->execute();
            $stmt->close();
            if (!$result) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
