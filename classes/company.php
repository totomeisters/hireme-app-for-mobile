<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/companydetails.php';

class Company {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addCompany($companyname, $companydesc, $companyaddress, $userID) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO Companies (UserID, CompanyName, CompanyDescription, CompanyAddress) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $userID, $companyname, $companydesc, $companyaddress);
            $result = $stmt->execute();
            $stmt->close();
            if (!$result) {
                // handle error
                return false;
            }
            return true;
        } catch (Exception $e) {
            // handle exception
            return false;
        }
    }

    public function getCompanyDetails($userID) {
        $stmt = $this->conn->prepare("SELECT * FROM companies WHERE UserID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $companyDetails = new CompanyDetails(
                $row['CompanyID'],
                $row['UserID'],
                $row['CompanyName'],
                $row['CompanyDescription'],
                $row['CompanyAddress'],
                $row['VerificationStatus']
            );

            $stmt->close();

            return $companyDetails;
        } else {
            return null;
        }
    }
    
}