<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/interviewdetails.php';

class Interview {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addInterview($interviewDetails) {
        
        $jobID = $interviewDetails->getJobID();
        $jobSeekerApplicationID = $interviewDetails->getJobSeekerApplicationID();
        $interviewDate = $interviewDetails->getInterviewDate();
        $dateMade = $interviewDetails->getDateMade();

        $sql = "INSERT INTO Interviews (JobID, JobSeekerApplicationID, InterviewDate, DateMade) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiss", $jobID, $jobSeekerApplicationID, $interviewDate, $dateMade);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllInterviews() {
        $sql = "SELECT InterviewID, JobID, JobSeekerApplicationID, InterviewDate, DateMade, Status FROM Interviews";
        $result = $this->conn->query($sql);
    
        if ($result->num_rows > 0) {
          $interviews = [];
          while ($row = $result->fetch_assoc()) {
            $interview = new InterviewDetails(
              $row["InterviewID"],
              $row["JobID"],
              $row["JobSeekerApplicationID"],
              $row["InterviewDate"],
              $row["DateMade"],
              $row["Status"]
            );
            $interviews[] = $interview;
          }
          return $interviews;
        } else {
          return [];
        }
    }

    public function getAllInterviewsByJobID($jobID) {
      try {
        $stmt = $this->conn->prepare("SELECT * FROM Interviews WHERE JobID=?");
        $stmt->bind_param("i", $jobID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
          $interviews = [];
          while ($row = $result->fetch_assoc()) {
            $interview = new InterviewDetails(
              $row["InterviewID"],
              $row["JobID"],
              $row["JobSeekerApplicationID"],
              $row["InterviewDate"],
              $row["DateMade"],
              $row["Status"]
            );
            $interviews[] = $interview;
          }
          return $interviews;
        } 
        else {
          return [];
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        throw $e;
    }
  }
}
