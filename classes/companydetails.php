<?php
if (!isset($_SESSION)) {
    session_start();
}

class CompanyDetails {
    private $companyID;
    private $userID;
    private $companyName;
    private $companyDescription;
    private $companyAddress;
    private $verificationStatus;

    public function __construct($companyID, $userID, $companyName, $companyDescription, $companyAddress, $verificationStatus) {
        $this->companyID = $companyID;
        $this->userID = $userID;
        $this->companyName = $companyName;
        $this->companyDescription = $companyDescription;
        $this->companyAddress = $companyAddress;
        $this->verificationStatus = $verificationStatus;
    }

    // Getters
    public function getCompanyID() {
        return $this->companyID;
    }

    public function getUserID() {
        return $this->userID;
    }

    public function getCompanyName() {
        return $this->companyName;
    }

    public function getCompanyDescription() {
        return $this->companyDescription;
    }

    public function getCompanyAddress() {
        return $this->companyAddress;
    }

    public function getVerificationStatus() {
        return $this->verificationStatus;
    }

    // Setters
    public function setCompanyID($companyID) {
        $this->companyID = $companyID;
    }

    public function setUserID($userID) {
        $this->userID = $userID;
    }

    public function setCompanyName($companyName) {
        $this->companyName = $companyName;
    }

    public function setCompanyDescription($companyDescription) {
        $this->companyDescription = $companyDescription;
    }

    public function setCompanyAddress($companyAddress) {
        $this->companyAddress = $companyAddress;
    }

    public function setVerificationStatus($verificationStatus) {
        $this->verificationStatus = $verificationStatus;
    }
}
