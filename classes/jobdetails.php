<?php
if (!isset($_SESSION)) {
    session_start();
}
class JobDetails {
    private $jobID;
    private $companyId;
    private $jobTitle;
    private $jobDescription;
    private $jobType;
    private $salaryMin;
    private $salaryMax;
    private $workHours;
    private $jobLocation;
    private $jobLocationType;
    private $postingDate;
    private $verificationStatus;

    public function __construct($jobID, $companyId, $jobTitle, $jobDescription, $jobType, $salaryMin, $salaryMax, $workHours, $jobLocation, $jobLocationType, $postingDate, $verificationStatus) {
        $this->jobID = $jobID;
        $this->companyId = $companyId;
        $this->jobTitle = $jobTitle;
        $this->jobDescription = $jobDescription;
        $this->jobType = $jobType;
        $this->salaryMin = $salaryMin;
        $this->salaryMax = $salaryMax;
        $this->workHours = $workHours;
        $this->jobLocation = $jobLocation;
        $this->jobLocationType = $jobLocationType;
        $this->postingDate = $postingDate;
        $this->verificationStatus = $verificationStatus;
    }

    // Getters
    public function getJobID(){
        return $this->jobID;
    }

    public function getCompanyId(){
        return $this->companyId;
    }

    public function getJobTitle(){
        return $this->jobTitle;
    }

    public function getJobDescription(){
        return $this->jobDescription;
    }

    public function getJobType(){
        return $this->jobType;
    }

    public function getSalaryMin(){
        return $this->salaryMin;
    }

    public function getSalaryMax(){
        return $this->salaryMax;
    }

    public function getWorkHours(){
        return $this->workHours;
    }

    public function getJobLocation(){
        return $this->jobLocation;
    }

    public function getJobLocationType(){
        return $this->jobLocationType;
    }

    public function getPostingDate(){
        return $this->postingDate;
    }

    public function getVerificationStatus(){
        return $this->verificationStatus;
    }

    // Setters
    public function setJobID($jobID) {
        $this->jobID = $jobID;
    }

    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
    }

    public function setJobTitle($jobTitle) {
        $this->jobTitle = $jobTitle;
    }

    public function setJobDescription($jobDescription) {
        $this->jobDescription = $jobDescription;
    }

    public function setJobType($jobType) {
        $this->jobType = $jobType;
    }

    public function setSalaryMin($salaryMin) {
        $this->salaryMin = $salaryMin;
    }

    public function setSalaryMax($salaryMax) {
        $this->salaryMax = $salaryMax;
    }

    public function setWorkHours($workHours) {
        $this->workHours = $workHours;
    }

    public function setJobLocation($jobLocation) {
        $this->jobLocation = $jobLocation;
    }

    public function setJobLocationType($jobLocationType) {
        $this->jobLocationType = $jobLocationType;
    }

    public function setPostingDate($postingDate) {
        $this->postingDate = $postingDate;
    }

    public function setVerificationStatus($verificationStatus) {
        $this->verificationStatus = $verificationStatus;
    }
}