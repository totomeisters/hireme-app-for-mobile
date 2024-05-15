<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/companyapplicationdetails.php';

class CompanyApplication
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function addCompanyApplication($companyID, $documentname, $documentfilepath, $documenttype, $document)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO companyapplication (CompanyID, DocumentName, DocumentFilePath, DocumentType, ReasonForRejection, VerificationStatus, Date) VALUES (?, ?, ?, ?, NULL, 'Pending', NOW());");
            $stmt->bind_param("isss", $companyID, $documentname, $documentfilepath, $documenttype);
            $result = $stmt->execute();
            $stmt->close();

            $stmt2 = $this->conn->prepare("UPDATE companydocuments SET $document = 2 WHERE CompanyID = ?");
            $stmt2->bind_param("i", $companyID);
            $result2 = $stmt2->execute();
            $stmt2->close();

            if (!$result || !$result2) {
                return "One of the update queries failed.";
            }
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getCompanyApplicationDetails($companyID)
    {
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
                    $row['DocumentType'],
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

    public function getCompanyApplicationDetailsByID($companyapplciationID)
    {
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
                    $row['DocumentType'],
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

    public function updateCompanyApplication($companyApplicationID, $status, $reason, $document, $docstatus, $companyID)
    {
        try {
            // Update companyapplication table
            $stmt1 = $this->conn->prepare("UPDATE companyapplication SET VerificationStatus = ?, ReasonForRejection = ? WHERE CompanyApplicationID = ?");
            $stmt1->bind_param("ssi", $status, $reason, $companyApplicationID);
            $result1 = $stmt1->execute();
            $stmt1->close();

            // Update companydocuments table
            $stmt2 = $this->conn->prepare("UPDATE companydocuments SET $document = ? WHERE CompanyID = ?");
            $stmt2->bind_param("ii", $docstatus, $companyID);
            $result2 = $stmt2->execute();
            $stmt2->close();

            if (!$result1 || !$result2) {
                return "One of the update queries failed.";
            }

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
