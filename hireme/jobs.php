<?php
class JobService
{
    function list_jobs(
        $jobDesc,
        $salMin,
        $salMax,
        $jobLoc
    ) {
        $conn = new ConnectDb();
        if ($conn->get_db()) {

            $filter_array = array();
            $filterString = '';

            if ($jobDesc != '') {
                $data = "a.JobDescription = '$jobDesc'";
                array_push($filter_array, $data);
            }
            if ($salMin != '') {
                $data = "a.SalaryMin = '$salMin'";
                array_push($filter_array, $data);
            }
            if ($salMax != '') {
                $data = "a.SalaryMax = '$salMax'";
                array_push($filter_array, $data);
            }
            if ($jobLoc != '') {
                $data = "a.JobLocation = '$jobLoc'";
                array_push($filter_array, $data);
            }

            if ($jobDesc != '' || $salMin != '' || $salMax != '' || $jobLoc != '') {
                for ($i = 0; $i < count($filter_array); $i++) {
                    $filterString = $filterString . " && ";
                    $filterString = $filterString . $filter_array[$i];
                }
            }

            $sql = "SELECT b.CompanyName, a.*  FROM `jobs` a, `companies` b WHERE  a.CompanyID = b.CompanyID AND a.VerificationStatus = 'Verified'" . $filterString;
            $result = mysqli_query($conn->get_db(), $sql);
            if (mysqli_num_rows($result) >= 1) {

                header("Content-Type: JSON");
                $response['verdict'] = true;
                $response['rows'] = mysqli_num_rows($result);
                $data_array = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    array_push($data_array, $row);
                }
                $response['job_list'] = $data_array;
                return json_encode($response, JSON_PRETTY_PRINT);

            } else {
                header("Content-Type: JSON");
                $response['verdict'] = true;
                $response['rows'] = 0;
                $response['message'] = "No listings available!";
                return json_encode($response, JSON_PRETTY_PRINT);
            }

        } else {
            header("Content-Type: JSON");
            $response['verdict'] = false;
            $response['message'] = "No DB Connection!";
            return json_encode($response, JSON_PRETTY_PRINT);
        }
    }

    function list_job_rec(
        $user_id
    ) {
        if ($user_id == "") {
            header("Content-Type: JSON");
            $response['verdict'] = false;
            $response['message'] = "No user specified!";
            return json_encode($response, JSON_PRETTY_PRINT);
        } else {

            $conn = new ConnectDb();
            if ($conn->get_db()) {

                $filter_array = array();
                $filterString = '';

                $sql = "SELECT a.WorkType FROM jobs a, jobseekerapplication b WHERE b.UserID = '$user_id' AND a.JobID = b.JobID";

                $result = mysqli_query($conn->get_db(), $sql);
                if (mysqli_num_rows($result) >= 1) {

                    while ($row = mysqli_fetch_assoc($result)) {
                        $row_worktype = $row['WorkType'];
                        $rec_sql = "SELECT b.CompanyName, a.*  FROM `jobs` a, `companies` b WHERE  a.CompanyID = b.CompanyID AND WorkType = '$row_worktype'";

                        $rec_result = mysqli_query($conn->get_db(), $rec_sql);
                        if (mysqli_num_rows($rec_result) >= 1) {
                            header("Content-Type: JSON");
                            $response['verdict'] = true;
                            $response['rows'] = mysqli_num_rows($rec_result);
                            $data_array = array();
                            while ($row = mysqli_fetch_assoc($rec_result)) {
                                array_push($data_array, $row);
                            }
                            $response['job_list'] = $data_array;
                            return json_encode($response, JSON_PRETTY_PRINT);
                        }
                    }

                } else {
                    header("Content-Type: JSON");
                    $response['verdict'] = true;
                    $response['rows'] = 0;
                    $response['message'] = "No recommendations available!";
                    return json_encode($response, JSON_PRETTY_PRINT);
                }

            } else {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "No DB Connection!";
                return json_encode($response, JSON_PRETTY_PRINT);
            }
        }
    }



    function job_application_list(
        $user_id
    ) {
        $conn = new ConnectDb();
        if ($conn->get_db()) {
            if ($user_id == "") {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "User ID empty!";
                return json_encode($response, JSON_PRETTY_PRINT);
            } else {
                $sql = "SELECT b.JobDescription, c.CompanyName, a.* FROM `jobseekerapplication` a, `jobs` b, `companies` c WHERE a.UserID = '$user_id' && a.JobID = b.JobID && b.CompanyID = c.CompanyID;";
                $result = mysqli_query($conn->get_db(), $sql);
                if (mysqli_num_rows($result) >= 1) {

                    header("Content-Type: JSON");
                    $response['verdict'] = true;
                    $response['rows'] = mysqli_num_rows($result);
                    $data_array = array();
                    while ($row = mysqli_fetch_assoc($result)) {
                        array_push($data_array, $row);
                    }
                    $response['application_list'] = $data_array;
                    return json_encode($response, JSON_PRETTY_PRINT);

                } else {
                    header("Content-Type: JSON");
                    $response['verdict'] = true;
                    $response['rows'] = 0;
                    $response['message'] = "No job application yet!";
                    return json_encode($response, JSON_PRETTY_PRINT);

                }
            }
        } else {
            header("Content-Type: JSON");
            $response['verdict'] = false;
            $response['message'] = "No DB Connection!";
            return json_encode($response, JSON_PRETTY_PRINT);
        }
    }

    function interview_list(
        $user_id
    ) {
        $conn = new ConnectDb();
        if ($conn->get_db()) {
            if ($user_id == "") {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "User ID empty!";
                return json_encode($response, JSON_PRETTY_PRINT);
            } else {
                $sql = "SELECT d.Status AS 'JobAppStat', b.JobTitle, c.CompanyName, a.* FROM `interviews` a, `jobs` b, `companies` c, `jobseekerapplication` d WHERE a.JobSeekerApplicationID = d.JobSeekerApplicationID && a.JobID = b.JobID && d.UserID = '$user_id' && b.CompanyID = c.CompanyID;";
                $result = mysqli_query($conn->get_db(), $sql);
                if (mysqli_num_rows($result) >= 1) {

                    header("Content-Type: JSON");
                    $response['verdict'] = true;
                    $response['rows'] = mysqli_num_rows($result);
                    $data_array = array();
                    while ($row = mysqli_fetch_assoc($result)) {
                        array_push($data_array, $row);
                    }
                    $response['application_list'] = $data_array;
                    return json_encode($response, JSON_PRETTY_PRINT);

                } else {
                    header("Content-Type: JSON");
                    $response['verdict'] = true;
                    $response['rows'] = 0;
                    $response['message'] = "No job interviews yet!";
                    return json_encode($response, JSON_PRETTY_PRINT);

                }
            }
        } else {
            header("Content-Type: JSON");
            $response['verdict'] = false;
            $response['message'] = "No DB Connection!";
            return json_encode($response, JSON_PRETTY_PRINT);
        }
    }

    function apply_job_with_url($user_id, $resume_file, $job_id, $url) {
        $conn = new ConnectDb();
        $db = $conn->get_db();

        if (!$db) {
            return json_encode(['verdict' => false, 'message' => 'No DB Connection!'], JSON_PRETTY_PRINT);
        }

        if (empty($user_id) || empty($job_id) || empty($url)) {
            return json_encode(['verdict' => false, 'message' => 'Missing required fields!'], JSON_PRETTY_PRINT);
        }

        // Handle file upload
        $resume_data = null;
        if (isset($resume_file) && $resume_file['error'] === UPLOAD_ERR_OK) {
            // Read file content as blob
            $resume_data = file_get_contents($resume_file['tmp_name']);
        }

        // Insert application with URL in ResumeFilePath
        $insert = "INSERT INTO jobseekerapplication (JobID, UserID, ResumeFilePath, resumefile, ApplicationDate, Status)
                   VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, 'Pending')";
        $stmt = $db->prepare($insert);
        $stmt->bind_param("iiss", $job_id, $user_id, $url, $resume_data);

        if ($stmt->execute()) {
            $app_id = $stmt->insert_id;
            return json_encode([
                'verdict' => true,
                'application_id' => $app_id,
                'message' => 'Job application created with URL and file'
            ], JSON_PRETTY_PRINT);
        } else {
            return json_encode(['verdict' => false, 'message' => 'Application failed!'], JSON_PRETTY_PRINT);
        }
    }

    function apply_job_with_file($user_id, $resume_file, $job_id) {
        $conn = new ConnectDb();
        $db = $conn->get_db();

        if (!$db) {
            return json_encode(['verdict' => false, 'message' => 'No DB Connection!'], JSON_PRETTY_PRINT);
        }

        if (empty($user_id) || empty($job_id)) {
            return json_encode(['verdict' => false, 'message' => 'Missing required fields!'], JSON_PRETTY_PRINT);
        }

        // Handle file upload
        $resume_data = null;
        if (isset($resume_file) && $resume_file['error'] === UPLOAD_ERR_OK) {
            // Read file content as blob
            $resume_data = file_get_contents($resume_file['tmp_name']);
        }

        // Insert application with the file and null URL (store file data in ResumeFilePath)
        $insert = "INSERT INTO jobseekerapplication (JobID, UserID, ResumeFilePath, resumefile, ApplicationDate, Status)
                   VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, 'Pending')";
        $stmt = $db->prepare($insert);
        $stmt->bind_param("iiss", $job_id, $user_id, $resume_data, $resume_data);

        if ($stmt->execute()) {
            $app_id = $stmt->insert_id;
            return json_encode([
                'verdict' => true,
                'application_id' => $app_id,
                'message' => 'Job application created with resume file'
            ], JSON_PRETTY_PRINT);
        } else {
            return json_encode(['verdict' => false, 'message' => 'Application failed!'], JSON_PRETTY_PRINT);
        }
    }

    function apply_job_with_url_only($user_id, $job_id, $url) {
        $conn = new ConnectDb();
        $db = $conn->get_db();

        if (!$db) {
            return json_encode(['verdict' => false, 'message' => 'No DB Connection!'], JSON_PRETTY_PRINT);
        }

        if (empty($user_id) || empty($job_id) || empty($url)) {
            return json_encode(['verdict' => false, 'message' => 'Missing required fields!'], JSON_PRETTY_PRINT);
        }

        // Insert application with URL only (store URL in ResumeFilePath)
        $insert = "INSERT INTO jobseekerapplication (JobID, UserID, ResumeFilePath, resumefile, ApplicationDate, Status)
                   VALUES (?, ?, ?, NULL, CURRENT_TIMESTAMP, 'Pending')";
        $stmt = $db->prepare($insert);
        $stmt->bind_param("iss", $job_id, $user_id, $url);

        if ($stmt->execute()) {
            $app_id = $stmt->insert_id;
            return json_encode([
                'verdict' => true,
                'application_id' => $app_id,
                'message' => 'Job application created with URL only'
            ], JSON_PRETTY_PRINT);
        } else {
            return json_encode(['verdict' => false, 'message' => 'Application failed!'], JSON_PRETTY_PRINT);
        }
    }

    function apply_job_without_file_or_url($user_id, $job_id) {
        $conn = new ConnectDb();
        $db = $conn->get_db();

        if (!$db) {
            return json_encode(['verdict' => false, 'message' => 'No DB Connection!'], JSON_PRETTY_PRINT);
        }

        if (empty($user_id) || empty($job_id)) {
            return json_encode(['verdict' => false, 'message' => 'Missing required fields!'], JSON_PRETTY_PRINT);
        }

        // Insert application without file or URL (store both as null)
        $insert = "INSERT INTO jobseekerapplication (JobID, UserID, ResumeFilePath, resumefile, ApplicationDate, Status)
                   VALUES (?, ?, NULL, NULL, CURRENT_TIMESTAMP, 'Pending')";
        $stmt = $db->prepare($insert);
        $stmt->bind_param("ii", $job_id, $user_id);

        if ($stmt->execute()) {
            $app_id = $stmt->insert_id;
            return json_encode([
                'verdict' => true,
                'application_id' => $app_id,
                'message' => 'Job application created without file or URL'
            ], JSON_PRETTY_PRINT);
        } else {
            return json_encode(['verdict' => false, 'message' => 'Application failed!'], JSON_PRETTY_PRINT);
        }
    }
}
       