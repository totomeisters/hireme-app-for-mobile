<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['applicantID'])){
      $applicantID = $_POST['applicantID'];
    }
    else{
      $applicantID = 0;
    }
}

require_once '../classes/jobseeker.php';
require_once '../classes/jobseekerapplication.php';

$jobseeker = new JobSeeker($conn);
$jobseekerapplication = new JobSeekerApplication($conn);

$applicationdetails = $jobseekerapplication->getJobApplicationDetailsByUserID($applicantID);
$applicantdetails = $jobseeker->getJobSeekerDetailsByUserID($applicantID);

$pagetitle = "HireMe - View Applicant # ".$applicantID;
?>

<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <!-- Head -->
    <?php require_once './head.php';?>
  <!-- /Head -->

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Menu -->
          <?php require_once "./menubar.php";?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
            <?php require_once "./navbar.php";?>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <!-- <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                Applicant ID:
              </div>
            </div> -->
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="col-lg-8 order-0 my-1">
                        <div class="card p-2 my-1 col">
                        <?php
                            if ($applicationdetails){
                                foreach ($applicationdetails as $applicationdetail){
                                    // Assuming you have a file path in a variable
                                    $filePath = $applicationdetail->getResumeFilePath();

                                    // Get the file extension
                                    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                                    // Check the file extension and generate the HTML accordingly
                                    switch ($fileExtension) {
                                        case 'pdf':
                                            // Display PDF file using an object tag
                                            echo '<object data="' . $filePath . '" type="application/pdf" width="100%" height="600px">';
                                            echo '<p>It appears you don\'t have a PDF plugin for this browser.</p>';
                                            echo 'Click here to download the PDF file: <a href="' . $filePath . '">Resume PDF</a>';
                                            echo '</object>';

                                            break;
                                        case 'jpg':
                                        case 'jpeg':
                                        case 'png':
                                            // Display image file using an img tag
                                            echo '<img src="' . $filePath . '" alt="Resume" style="height: 80vh; object-fit: scale-down;" 
                                                    class="img-fluid bg-secondary rounded border border-dark">';
                                            break;
                                        default:
                                            // Handle other file types or unknown extensions
                                            echo "Unsupported file type.";
                                            break;
                                    }
                                }
                            }
                            else{
                                echo "Error getting document details.";
                            }
                        ?>
                        </div>
                    </div>
                    <div class="col-lg-4 order-1 my-1">
                        <div class="row">
                            <div class="card p-2 my-1">
                                <h5>Applicant Details</h5>
                                <?php
                                    if ($applicantdetails){
                                            $name = $applicantdetails->getFirstName() .' '. $applicantdetails->getLastName();
                                            $address = $applicantdetails->getAddress();
                                            $contactnumber = $applicantdetails->getContactNumber();
                                            $birthdate = $applicantdetails->getBirthDate();
                                            $today = new DateTime();
                                            $birthday = new DateTime($birthdate);
                                            $age = $today->diff($birthday)->y;
                                        
                                            echo "<strong><p>Name: </strong>". $name .'</p>';
                                            echo "<strong><p>Birth Date: </strong>". $birthdate .'</p>';
                                            echo "<strong><p>Birth Date: </strong>". $age .' years old</p>';
                                            echo "<strong><p>Address: </strong>". $address .'</p>';
                                            echo "<strong><p>Contact Number: </strong>". $contactnumber .'</p>';
                                    }
                                    else{
                                        echo "Error getting applicant details.";
                                    }
                                ?>
                            </div>

                            <div class="card p-2 my-1">
                                <h5>Application Details</h5>
                                <?php
                                    if ($applicationdetails){
                                        foreach ($applicationdetails as $applicationdetail){

                                            $filePath = $applicationdetail->getResumeFilePath();
                                            $fileExtension = strtoupper(pathinfo($filePath, PATHINFO_EXTENSION));
                                            $applicationdate = $applicationdetail->getApplicationDate();
                                            $postingDate = new DateTime($applicationdate);
                                            $currentDate = new DateTime();
                                            $interval = $currentDate->diff($postingDate);

                                            $timePassed = '';
                                            if ($interval->y) {
                                                $timePassed = $interval->format('%y year(s) ago');
                                            } elseif ($interval->m) {
                                                $timePassed = $interval->format('%m month(s) ago');
                                            } elseif ($interval->d) {
                                                $timePassed = $interval->format('%d day(s) ago');
                                            } elseif ($interval->h) {
                                                $timePassed = $interval->format('%h hour(s) ago');
                                            } elseif ($interval->i) {
                                                $timePassed = $interval->format('%i minute(s) ago');
                                            } else {
                                                $timePassed = $interval->format('%s seconds ago');
                                            }
                                        
                                            echo "<strong><p>File Type: </strong>". $fileExtension .'</p>';
                                            echo "<strong><p>Application Date: </strong>". $applicationdate .'</p>';
                                            echo "<strong><p>Sent: </strong>". $timePassed .'</p>';
                                        }
                                    }
                                    else{
                                        echo "Error getting application details.";
                                    }
                                ?>
                            </div>
                            <div class="card p-2 my-1">
                                <h5>Verification</h5>
                                <?php
                                    if ($applicationdetails){
                                        foreach ($applicationdetails as $applicationdetail){
                                            echo 'nice';
                                        }
                                    }
                                    else{
                                        echo "Error getting application details.";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer>
              <div class="container">
                <div class="mb-2 mb-md-1">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  <span class="footer-link fw-bolder"> HireMe</span>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <?php require_once "./endscripts.php";?>
  </body>
</html>
