<?php
include '../classes/interview.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $interview = new Interview($conn);

    $jobID = $_POST['job_id'];
    $jobSeekerApplicationID = $_POST['job_seeker_application_id'];
    $interviewDate = $_POST['interview_date'];
    $dateMade = date('Y-m-d H:i:s');

    $interviewDetails = new InterviewDetails(null, $jobID, $jobSeekerApplicationID, $interviewDate, $dateMade);

    if ($interview->addInterview($interviewDetails)) {
        echo "Interview added successfully.";
    } else {
        echo "Error adding interview.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST Add Interview</title>
</head>
<body>
    <h2>TEST Add Interview</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="job_id">Job ID:</label>
        <input type="text" id="job_id" name="job_id" required><br><br>
        
        <label for="job_seeker_application_id">Job Seeker Application ID:</label>
        <input type="text" id="job_seeker_application_id" name="job_seeker_application_id" required><br><br>
        
        <label for="interview_date">Interview Date:</label>
        <input type="datetime-local" id="interview_date" name="interview_date" required><br><br>
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>