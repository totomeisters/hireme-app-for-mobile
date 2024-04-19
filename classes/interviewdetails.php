<?php

class InterviewDetails {
    private $interviewID;
    private $jobID;
    private $jobSeekerApplicationID;
    private $interviewDate;
    private $dateMade;
    private $status;

    public function __construct($interviewID, $jobID, $jobSeekerApplicationID, $interviewDate, $dateMade, $status) {
        $this->interviewID = $interviewID;
        $this->jobID = $jobID;
        $this->jobSeekerApplicationID = $jobSeekerApplicationID;
        $this->interviewDate = $interviewDate;
        $this->dateMade = $dateMade;
        $this->status = $status;
    }

    // Getters
    public function getInterviewID() {
        return $this->interviewID;
    }

    public function getJobID() {
        return $this->jobID;
    }

    public function getJobSeekerApplicationID() {
        return $this->jobSeekerApplicationID;
    }

    public function getInterviewDate() {
        return $this->interviewDate;
    }

    public function getDateMade() {
        return $this->dateMade;
    }

    public function getStatus() {
        return $this->status;
    }

    // Setters
    public function setInterviewID($interviewID) {
        $this->interviewID = $interviewID;
    }

    public function setJobID($jobID) {
        $this->jobID = $jobID;
    }

    public function setJobSeekerApplicationID($jobSeekerApplicationID) {
        $this->jobSeekerApplicationID = $jobSeekerApplicationID;
    }

    public function setInterviewDate($interviewDate) {
        $this->interviewDate = $interviewDate;
    }

    public function setDateMade($dateMade) {
        $this->dateMade = $dateMade;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}

?>