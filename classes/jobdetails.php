<?php

class JobDetails {
    private $jobID;
    private $companyId;
    private $jobTitle;
    private $jobDescription;
    private $jobType;
    private $workType;
    private $salaryMin;
    private $salaryMax;
    private $workHours;
    private $jobLocation;
    private $jobLocationType;
    private $postingDate;
    private $verificationStatus;
    private $jobIndustry;
    private $otherIndustry;
    private $skills;
    private $qualifications;
    private $vacancies;
    private $rejectionReason;

    public function __construct($jobID, $companyId, $jobTitle, $jobDescription, $jobType, $workType, $salaryMin, $salaryMax, $workHours, $jobLocation, $jobLocationType, $postingDate, $verificationStatus, $jobIndustry, $otherIndustry, $skills, $qualifications, $vacancies, $rejectionReason) {
        $this->jobID = $jobID;
        $this->companyId = $companyId;
        $this->jobTitle = $jobTitle;
        $this->jobDescription = $jobDescription;
        $this->jobType = $jobType;
        $this->workType = $workType;
        $this->salaryMin = $salaryMin;
        $this->salaryMax = $salaryMax;
        $this->workHours = $workHours;
        $this->jobLocation = $jobLocation;
        $this->jobLocationType = $jobLocationType;
        $this->postingDate = $postingDate;
        $this->verificationStatus = $verificationStatus;
        $this->jobIndustry = $jobIndustry;
        $this->otherIndustry = $otherIndustry;
        $this->skills = $skills;
        $this->qualifications = $qualifications;
        $this->vacancies = $vacancies;
        $this->rejectionReason = $rejectionReason;
    }

    // Getters
    public function getJobID() {
        return $this->jobID;
    }

    public function getCompanyId() {
        return $this->companyId;
    }

    public function getJobTitle() {
        return $this->jobTitle;
    }

    public function getJobDescription() {
        return $this->jobDescription;
    }

    public function getJobType() {
        return $this->jobType;
    }

    public function getWorkType() {
        return $this->workType;
    }

    public function getSalaryMin() {
        return $this->salaryMin;
    }

    public function getSalaryMax() {
        return $this->salaryMax;
    }

    public function getWorkHours() {
        return $this->workHours;
    }

    public function getJobLocation() {
        return $this->jobLocation;
    }

    public function getJobLocationType() {
        return $this->jobLocationType;
    }

    public function getPostingDate() {
        return $this->postingDate;
    }

    public function getVerificationStatus() {
        return $this->verificationStatus;
    }

    public function getJobIndustry() {
        return $this->jobIndustry;
    }

    public function getOtherIndustry() {
        return $this->otherIndustry;
    }

    public function getSkills() {
        return $this->skills;
    }

    public function getQualifications() {
        return $this->qualifications;
    }

    public function getVacancies() {
        return $this->vacancies;
    }

    public function getRejectionReason() {
        return $this->rejectionReason;
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

    public function setWorkType($workType) {
        $this->workType = $workType;
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

    public function setJobIndustry($jobIndustry) {
        $this->jobIndustry = $jobIndustry;
    }

    public function setOtherIndustry($otherIndustry) {
        $this->otherIndustry = $otherIndustry;
    }

    public function setSkills($skills) {
        $this->skills = $skills;
    }

    public function setQualifications($qualifications) {
        $this->qualifications = $qualifications;
    }

    public function setVacancies($vacancies) {
        $this->vacancies = $vacancies;
    }

    public function setRejectionReason($rejectionReason) {
        $this->rejectionReason = $rejectionReason;
    }
}