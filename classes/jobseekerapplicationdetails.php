<?php

class JobSeekerApplicationDetails {
    private $jobSeekerApplicationID;
    private $jobID;
    private $userID;
    private $resumeFilePath;
    private $resumeFile;
    private $applicationDate;
    private $status;
    private $rejectionReason;

    public function __construct($jobSeekerApplicationID, $jobID, $userID, $resumeFilePath, $resumeFile, $applicationDate, $status, $rejectionReason) {
        $this->jobSeekerApplicationID = $jobSeekerApplicationID;
        $this->jobID = $jobID;
        $this->userID = $userID;
        $this->resumeFilePath = $resumeFilePath;
        $this->resumeFile = $resumeFile;
        $this->applicationDate = $applicationDate;
        $this->status = $status;    
        $this->rejectionReason = $rejectionReason;
    }

    // Getters
    public function getJobSeekerApplicationID() {
        return $this->jobSeekerApplicationID;
    }

    public function getJobID() {
        return $this->jobID;
    }

    public function getUserID() {
        return $this->userID;
    }

    public function getResumeFilePath() {
        return $this->resumeFilePath;
    }

    public function getResumeFile() {
        return $this->resumeFile;
    }

    public function getApplicationDate() {
        return $this->applicationDate;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getRejectionReason() {
        return $this->rejectionReason;
    }

    // Setters
    public function setJobSeekerApplicationID($jobSeekerApplicationID) {
        $this->jobSeekerApplicationID = $jobSeekerApplicationID;
    }

    public function setJobID($jobID) {
        $this->jobID = $jobID;
    }

    public function setUserID($userID) {
        $this->userID = $userID;
    }

    public function setResumeFilePath($resumeFilePath) {
        $this->resumeFilePath = $resumeFilePath;
    }

    public function setResumeFile($resumeFile) {
        $this->resumeFile = $resumeFile;
    }

    public function setApplicationDate($applicationDate) {
        $this->applicationDate = $applicationDate;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setRejectionReason($rejectionReason) {
        $this->rejectionReason = $rejectionReason;
    }
}
