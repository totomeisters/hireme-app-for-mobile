<?php

class CompanyApplicationDetails {
    private $companyApplicationID;
    private $companyID;
    private $documentName;
    private $documentFilePath;
    private $documentType;
    private $verification;
    private $reasonForRejection;
    private $date;

    public function __construct($companyApplicationID, $companyID, $documentName, $documentFilePath, $documentType, $verification, $reasonForRejection, $date) {
        $this->companyApplicationID = $companyApplicationID;
        $this->companyID = $companyID;
        $this->documentName = $documentName;
        $this->documentFilePath = $documentFilePath;
        $this->documentType = $documentType;
        $this->verification = $verification;
        $this->reasonForRejection = $reasonForRejection;
        $this->date = $date;
    }

    public function getCompanyApplicationID() {
        return $this->companyApplicationID;
    }

    public function setCompanyApplicationID($companyApplicationID) {
        $this->companyApplicationID = $companyApplicationID;
    }

    public function getCompanyID() {
        return $this->companyID;
    }

    public function setCompanyID($companyID) {
        $this->companyID = $companyID;
    }

    public function getDocumentName() {
        return $this->documentName;
    }

    public function setDocumentName($documentName) {
        $this->documentName = $documentName;
    }

    public function getDocumentFilePath() {
        return $this->documentFilePath;
    }

    public function setDocumentFilePath($documentFilePath) {
        $this->documentFilePath = $documentFilePath;
    }

    public function getDocumentType() {
        return $this->documentType;
    }

    public function setDocumentType($documentType) {
        $this->documentType = $documentType;
    }

    public function getVerification() {
        return $this->verification;
    }

    public function setVerification($verification) {
        $this->verification = $verification;
    }

    public function getReasonForRejection() {
        return $this->reasonForRejection;
    }

    public function setReasonForRejection($reasonForRejection) {
        $this->reasonForRejection = $reasonForRejection;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }
}
