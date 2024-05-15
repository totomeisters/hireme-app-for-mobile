<?php
class CompanyProfile {
    private $profileId;
    private $name;
    private $address;
    private $contactNumber;
    private $email;
    private $repPosition;
    private $repName;
    private $repNumber;
    private $companyId;

    public function __construct($profileId, $name, $address, $contactNumber, $email, $repPosition, $repName, $repNumber, $companyId) {
        $this->profileId = $profileId;
        $this->name = $name;
        $this->address = $address;
        $this->contactNumber = $contactNumber;
        $this->email = $email;
        $this->repPosition = $repPosition;
        $this->repName = $repName;
        $this->repNumber = $repNumber;
        $this->companyId = $companyId;
    }

    // Getters
    public function getProfileId() {
        return $this->profileId;
    }

    public function getName() {
        return $this->name;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getContactNumber() {
        return $this->contactNumber;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRepPosition() {
        return $this->repPosition;
    }

    public function getRepName() {
        return $this->repName;
    }

    public function getRepNumber() {
        return $this->repNumber;
    }

    public function getCompanyId() {
        return $this->companyId;
    }

    // Setters
    public function setProfileId($profileId) {
        $this->profileId = $profileId;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setContactNumber($contactNumber) {
        $this->contactNumber = $contactNumber;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setRepPosition($repPosition) {
        $this->repPosition = $repPosition;
    }

    public function setRepName($repName) {
        $this->repName = $repName;
    }

    public function setRepNumber($repNumber) {
        $this->repNumber = $repNumber;
    }

    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
    }
}
