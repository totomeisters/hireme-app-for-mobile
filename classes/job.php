<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/jobdetails.php';

class Job {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addJob($companyId, $jobTitle, $jobDescription, $jobType, $salaryMin, $salaryMax, $workHours, $jobLocation, $jobLocationType) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO Jobs (CompanyID, 
                                                            JobTitle, 
                                                            JobDescription, 
                                                            JobType, 
                                                            SalaryMin, 
                                                            SalaryMax, 
                                                            WorkHours, 
                                                            JobLocation, 
                                                            JobLocationType, 
                                                            PostingDate, 
                                                            VerificationStatus)
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Pending')");
            // Assuming $this->conn is your database connection object
            
            // Bind parameters
            $stmt->bind_param("isssddsss", $companyId, $jobTitle, $jobDescription, $jobType, $salaryMin, $salaryMax, $workHours, $jobLocation, $jobLocationType);
            
            // Execute the statement
            $result = $stmt->execute();
            
            // Close the statement
            $stmt->close();
            
            if (!$result) {
                // handle error
                return false;
            }
            
            return true;
        } catch (Exception $e) {
            // handle exception
            return false;
        }
    }

    public function getAllJobs($companyId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM Jobs WHERE CompanyID=?");
            $stmt->bind_param("i", $companyId);
            $stmt->execute();
            $result = $stmt->get_result();
            $jobs = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close(); // Close the statement after fetching the results
    
            if (empty($jobs)) {
                return []; // Return an empty array instead of false
            }
    
            $jobObjects = [];
            foreach ($jobs as $job) {
                // Ensure all keys exist before accessing them
                // Use uppercase keys if the column names in the database are uppercase
                if (isset(
                    $job['JobID'], $job['CompanyID'], $job['JobTitle'], $job['JobDescription'],
                    $job['JobType'], $job['SalaryMin'], $job['SalaryMax'], $job['WorkHours'],
                    $job['JobLocation'], $job['JobLocationType'], $job['PostingDate'], $job['VerificationStatus']
                )) {
                    $jobObjects[] = new JobDetails(
                        $job['JobID'],
                        $job['CompanyID'],
                        $job['JobTitle'],
                        $job['JobDescription'],
                        $job['JobType'],
                        $job['SalaryMin'],
                        $job['SalaryMax'],
                        $job['WorkHours'],
                        $job['JobLocation'],
                        $job['JobLocationType'],
                        $job['PostingDate'],
                        $job['VerificationStatus']
                    );
                } else {
                    // Handle the case where not all keys exist
                    // This could be logging an error, skipping the job, or throwing an exception
                    error_log("Incomplete job data for JobID: " . ($job['JobID'] ?? 'Unknown JobID'));
                }
            }
    
            return $jobObjects;
        } catch (Exception $e) {
            // Log the error or handle it in a way that allows for recovery
            error_log($e->getMessage());
            throw $e; // Rethrow the exception to allow for higher-level error handling
        }
    }
    
    public function getJobDetails($jobID) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM Jobs WHERE JobID=?");
            $stmt->bind_param("i", $jobID);
            $stmt->execute();
            $result = $stmt->get_result();
            $jobs = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close(); // Close the statement after fetching the results
    
            if (empty($jobs)) {
                return []; // Return an empty array instead of false
            }
    
            $jobObjects = [];
            foreach ($jobs as $job) {
                // Ensure all keys exist before accessing them
                // Use uppercase keys if the column names in the database are uppercase
                if (isset(
                    $job['JobID'], $job['CompanyID'], $job['JobTitle'], $job['JobDescription'],
                    $job['JobType'], $job['SalaryMin'], $job['SalaryMax'], $job['WorkHours'],
                    $job['JobLocation'], $job['JobLocationType'], $job['PostingDate'], $job['VerificationStatus']
                )) {
                    $jobObjects[] = new JobDetails(
                        $job['JobID'],
                        $job['CompanyID'],
                        $job['JobTitle'],
                        $job['JobDescription'],
                        $job['JobType'],
                        $job['SalaryMin'],
                        $job['SalaryMax'],
                        $job['WorkHours'],
                        $job['JobLocation'],
                        $job['JobLocationType'],
                        $job['PostingDate'],
                        $job['VerificationStatus']
                    );
                } else {
                    // Handle the case where not all keys exist
                    // This could be logging an error, skipping the job, or throwing an exception
                    error_log("Incomplete job data for JobID: " . ($job['JobID'] ?? 'Unknown JobID'));
                }
            }
    
            return $jobObjects;
        } catch (Exception $e) {
            // Log the error or handle it in a way that allows for recovery
            error_log($e->getMessage());
            throw $e; // Rethrow the exception to allow for higher-level error handling
        }
    }
}