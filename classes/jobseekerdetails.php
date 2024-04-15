<?php
if (!isset($_SESSION)) {
    session_start();
}

class JobSeekerDetails {
    private $jobSeekerID;
    private $userID;
    private $firstName;
    private $lastName;
    private $birthDate;
    private $address;
    private $contactNumber;

    public function __construct($jobSeekerID, $userID, $firstName, $lastName, $birthDate, $address, $contactNumber) {
        $this->jobSeekerID = $jobSeekerID;
        $this->userID = $userID;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthDate = $birthDate;
        $this->address = $address;
        $this->contactNumber = $contactNumber;
    }

    // Getters
    public function getJobSeekerID() {
        return $this->jobSeekerID;
    }

    public function setJobSeekerID($jobSeekerID) {
        $this->jobSeekerID = $jobSeekerID;
    }

    public function getUserID() {
        return $this->userID;
    }

    public function setUserID($userID) {
        $this->userID = $userID;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    // Setters

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getBirthDate() {
        return $this->birthDate;
    }

    public function setBirthDate($birthDate) {
        $this->birthDate = $birthDate;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getContactNumber() {
        return $this->contactNumber;
    }

    public function setContactNumber($contactNumber) {
        $this->contactNumber = $contactNumber;
    }
}
?>