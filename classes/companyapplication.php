<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/companyapplicationdetails.php';

class CompanyApplication {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addCompanyApplication($companyID, $documentname, $documentfilepath) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO companyapplication (CompanyID, DocumentName, DocumentFilePath, ReasonForRejection, VerificationStatus, Date) VALUES (?, ?, ?, NULL, 'Pending', NOW());");
            $stmt->bind_param("iss", $companyID, $documentname, $documentfilepath);
            $result = $stmt->execute();
            $stmt->close();
            if (!$result) {
                $errormsg = "SQL Query failed.";
                return $errormsg;
            }
            return true;
        } catch (Exception $e) {
            $errormsg = "Try failed, catch activated.";
            return $errormsg;
        }
    }    

    public function getCompanyApplicationDetails($companyID) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM CompanyApplication WHERE CompanyID=?");
            $stmt->bind_param("i", $companyID);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
    
            $companies = array();
    
            while ($row = $result->fetch_assoc()) {
                $companyApplication = new CompanyApplicationDetails(
                    $row['CompanyApplicationID'],
                    $row['CompanyID'],
                    $row['DocumentName'],
                    $row['DocumentFilePath'],
                    $row['VerificationStatus'],
                    $row['ReasonForRejection'],
                    $row['Date']
                );
    
                $companies[] = $companyApplication;
            }
    
            if (empty($companies)) {
                return false;
            }
    
            return $companies;
    
        } catch (Exception $e) {
            return false;
        }
    }

    public function getCompanyApplicationDetailsByID($companyapplciationID) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM CompanyApplication WHERE CompanyApplicationID=?");
            $stmt->bind_param("i", $companyapplciationID);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
    
            $companies = array();
    
            while ($row = $result->fetch_assoc()) {
                $companyApplication = new CompanyApplicationDetails(
                    $row['CompanyApplicationID'],
                    $row['CompanyID'],
                    $row['DocumentName'],
                    $row['DocumentFilePath'],
                    $row['VerificationStatus'],
                    $row['ReasonForRejection'],
                    $row['Date']
                );
    
                $companies[] = $companyApplication;
            }
    
            if (empty($companies)) {
                return false;
            }
    
            return $companies;
    
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateCompanyApplication($companyApplicationID, $status, $reason) {
        try {
            $stmt = $this->conn->prepare("UPDATE companyapplication SET VerificationStatus = ?, ReasonForRejection = ? WHERE CompanyApplicationID = ?");
            $stmt->bind_param("ssi", $status, $reason, $companyApplicationID);
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