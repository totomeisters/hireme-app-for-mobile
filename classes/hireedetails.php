<?php

class HireeDetails {
    private $hireeID;
    private $userID;
    private $jobID;
    private $companyID;
    private $fullName;
    private $jobName;
    private $companyName;
    private $applicationID;
    private $dateHired;

    public function __construct($hireeID, $fullName, $jobName, $companyName, $userID, $jobID, $companyID, $applicationID, $dateHired) {
        $this->hireeID = $hireeID;
        $this->userID = $userID;
        $this->jobID = $jobID;
        $this->companyID = $companyID;
        $this->fullName = $fullName;
        $this->jobName = $jobName;
        $this->companyName = $companyName;
        $this->applicationID = $applicationID;
        $this->dateHired = $dateHired;        
    }

    // Getters
    public function getHireeID() {
        return $this->hireeID;
    }

    public function getUserID() {
        return $this->userID;
    }

    public function getJobID() {
        return $this->jobID;
    }

    public function getCompanyID() {
        return $this->companyID;
    }

    public function getFullName() {
        return $this->fullName;
    }
    
    public function getJobName() {
        return $this->jobName;
    }

    public function getCompanyName() {
        return $this->companyName;
    }

    public function getApplicationID() {
        return $this->applicationID;
    }
    
    public function getDateHired() {
        return $this->dateHired;
    }

    // Setters
    public function setUserID($userID) {
        $this->userID = $userID;
    }

    public function setJobID($jobID) {
        $this->jobID = $jobID;
    }

    public function setCompanyID($companyID) {
        $this->companyID = $companyID;
    }

    public function setFullName($fullName) {
        $this->fullName = $fullName;
    }

    public function setJobName($jobName) {
        $this->jobName = $jobName;
    }

    public function setCompanyName($companyName) {
        $this->companyName = $companyName;
    }

    public function setApplicationID($applicationID) {
        $this->applicationID = $applicationID;
    }

    public function setDateHired($dateHired) {
        $this->dateHired = $dateHired;
    }
}
