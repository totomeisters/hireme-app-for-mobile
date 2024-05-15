<?php
class CompanyDocumentsCheck
{
    private $listID;
    private $companyID;
    private $sec;
    private $businessPermit;
    private $bir;
    private $mayorPermit;
    private $certificate;

    // Constructor
    public function __construct($listID, $companyID, $sec, $businessPermit, $bir, $mayorPermit, $certificate)
    {
        $this->listID = $listID;
        $this->companyID = $companyID;
        $this->sec = $sec;
        $this->businessPermit = $businessPermit;
        $this->bir = $bir;
        $this->mayorPermit = $mayorPermit;
        $this->certificate = $certificate;
    }

    // Getter and Setter for listID
    public function getListID()
    {
        return $this->listID;
    }

    public function setListID($listID)
    {
        $this->listID = $listID;
    }

    // Getter and Setter for companyID
    public function getCompanyID()
    {
        return $this->companyID;
    }

    public function setCompanyID($companyID)
    {
        $this->companyID = $companyID;
    }

    // Getter and Setter for sec
    public function getSec()
    {
        return $this->sec;
    }

    public function setSec($sec)
    {
        $this->sec = $sec;
    }

    // Getter and Setter for businessPermit
    public function getBusinessPermit()
    {
        return $this->businessPermit;
    }

    public function setBusinessPermit($businessPermit)
    {
        $this->businessPermit = $businessPermit;
    }

    // Getter and Setter for bir
    public function getBir()
    {
        return $this->bir;
    }

    public function setBir($bir)
    {
        $this->bir = $bir;
    }

    // Getter and Setter for mayorPermit
    public function getMayorPermit()
    {
        return $this->mayorPermit;
    }

    public function setMayorPermit($mayorPermit)
    {
        $this->mayorPermit = $mayorPermit;
    }

    // Getter and Setter for certificate
    public function getCertificate()
    {
        return $this->certificate;
    }

    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;
    }
}
