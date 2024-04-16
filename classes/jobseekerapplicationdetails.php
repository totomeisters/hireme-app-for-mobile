<?php
if (!isset($_SESSION)) {
    session_start();
}

class JobSeekerApplicationDetails {
    private $jobSeekerApplicationID;
    private $jobID;
    private $userID;
    private $resumeFilePath;
    private $applicationDate;
    private $status;

    public function __construct($jobSeekerApplicationID, $jobID, $userID, $resumeFilePath, $applicationDate, $status) {
        $this->jobSeekerApplicationID = $jobSeekerApplicationID;
        $this->jobID = $jobID;
        $this->userID = $userID;
        $this->resumeFilePath = $resumeFilePath;
        $this->applicationDate = $applicationDate;
        $this->status = $status;
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

    public function getApplicationDate() {
        return $this->applicationDate;
    }

    public function getStatus() {
        return $this->status;
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

    public function setApplicationDate($applicationDate) {
        $this->applicationDate = $applicationDate;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}
