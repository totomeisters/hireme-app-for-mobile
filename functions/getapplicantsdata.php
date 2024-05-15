<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['companyID']) && !empty($_POST['companyID'])) {
        $companyID = $_POST['companyID'];
        
        require_once '../classes/job.php';
        
        $job = new Job($conn);
        $jobdetails = $job->getAllJobs($companyID); // Assuming getAllJobs() retrieves all job details for a given company
        $applicantData = array();

        // Initialize array to store total applicants for each month
        $totalApplicantsByMonth = array_fill(0, 12, 0);

        foreach ($jobdetails as $jobdetail){
            $jobID = $jobdetail->getJobID();
            $jsonData = json_decode($job->getApplicantCountByMonth($jobID), true);

            foreach ($jsonData as $data) {
                $month = intval($data['month']) - 1; // Adjust month index to match array index
                $applicants = intval($data['applicants']);
                $totalApplicantsByMonth[$month] += $applicants;
            }
        }

        // Construct data array for chart
        $dataValues = array_values($totalApplicantsByMonth);

        echo json_encode($dataValues);
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing or empty CompanyID'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}
