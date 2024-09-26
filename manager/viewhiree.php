<?php
if (!isset($_SESSION)) {
  session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['applicationID']) && isset($_POST['jobID']) && isset($_POST['userID']) && isset($_POST['hireeID'])) {
    $applicationID = $_POST['applicationID'];
    $jobID = $_POST['jobID'];
    $applicantID = $_POST['userID'];
    $hireeID = $_POST['hireeID'];
  } else {
    $applicationID = 0;
    $jobID = 0;
    $applicantID = 0;
    $hireeID = 0;
  }
}

require_once '../classes/jobseeker.php';
require_once '../classes/job.php';
require_once '../classes/jobseekerapplication.php';
require_once '../classes/hiree.php';

$jobseeker = new JobSeeker($conn);
$jobseekerapplication = new JobSeekerApplication($conn);
$job = new Job($conn);
$hiree = new Hiree($conn);

$hireedetails = $hiree->getHireeDetailsByID($hireeID);
$jobName = $job->getJobDetailsByID($jobID)->getJobTitle();
$applicationdetails = $jobseekerapplication->getJobApplicationDetailsByUserID($applicantID, $jobID);
$applicantdetails = $jobseeker->getJobSeekerDetailsByUserID($applicantID);

$pagetitle = "HireMe - View Hiree # " . $applicantID;
?>

<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
<!-- Head -->
<?php require_once './head.php'; ?>
<!-- /Head -->

<body>
  <!-- Toast Overlay -->
  <div id="toast-container"></div>
  <div class="overlay"></div>
  <!-- / Toast Overlay -->

  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

      <!-- Menu -->
      <?php require_once "./menubar.php"; ?>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <?php require_once "./navbar.php"; ?>
        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <!-- <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                Hiree ID:
              </div>
            </div> -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
              <div class="col-lg-8 order-0 my-1">
                <div class="card p-2 my-1 col">
                  <?php

                  function modify_google_drive_link($link)
                  {
                    $view_pos = strpos($link, '/view');
                    if ($view_pos !== false) {
                      return substr($link, 0, $view_pos) . '/preview';
                    } else {
                      return $link;
                    }
                  }

                  function is_google_drive_link($link)
                  {
                    $pattern = '/^https:\/\/drive\.google\.com\/file\/d\/[a-zA-Z0-9\-_]+\/(.+)/';
                    return preg_match($pattern, $link) === 1;
                  }

                  if ($applicationdetails) {
                    foreach ($applicationdetails as $applicationdetail) {
                      $resumeId = $applicationdetail->getResumeFilePath();
                      $link = modify_google_drive_link($resumeId);

                      if (is_google_drive_link($resumeId)) {
                        echo '<iframe src="' . $link . '" width="100%" height="600px"></iframe>';
                      } else {
                        echo "This is not a Google Drive link.";
                      }
                    }
                  } else {
                    echo "Error getting document details.";
                  }
                  ?>
                </div>
              </div>
              <div class="col-lg-4 order-1 my-1">
                <div class="row">
                  <div class="card p-2 my-1">
                    <h5><strong>Hiree Details</strong></h5>
                    <?php
                    if ($applicantdetails) {
                      $name = ucfirst($applicantdetails->getFirstName()) . ' ' . ucfirst($applicantdetails->getLastName());
                      $address = $applicantdetails->getAddress();
                      $contactnumber = $applicantdetails->getContactNumber();
                      $birthdate = $applicantdetails->getBirthDate();
                      $today = new DateTime();
                      $birthday = new DateTime($birthdate);
                      $age = $today->diff($birthday)->y;

                      echo "<strong><p class='mb-1'>Name: </strong>" . $name . '</p>';
                      echo "<strong><p class='mb-1'>Birth Date: </strong>" . $birthdate . '</p>';
                      echo "<strong><p class='mb-1'>Age: </strong>" . $age . ' years old</p>';
                      echo "<strong><p class='mb-1'>Address: </strong>" . $address . '</p>';
                      echo "<strong><p class='mb-1'>Contact Number: </strong>" . $contactnumber . '</p>';
                    } else {
                      echo "Error getting applicant details.";
                    }
                    ?>
                  </div>

                  <div class="card p-2 my-1">
                    <h5><strong>Application Details</strong></h5>
                    <?php
                    if ($applicationdetails) {
                      $applicationdate = $applicationdetail->getApplicationDate();
                      $applicationID = $applicationdetail->getJobSeekerApplicationID();
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

                      echo "<strong><p class='mb-1'>Application Date: </strong>" . $applicationdate . '</p>';
                      echo "<strong><p class='mb-1'>Sent: </strong>" . $timePassed . '</p>';
                      echo "<strong><p class='mb-1'>Application ID: </strong>" . $applicationID . '</p>';
                    } else {
                      echo "Error getting application details.";
                    }
                    ?>
                  </div>
                  <div class="card p-2 my-1">
                    <h5><strong>Job Details</strong></h5>
                    <?php
                    if ($hireedetails) {
                        echo "<strong><p class='mb-1'>Company Name: </strong>" . $hireedetails->getCompanyName() . '</p>';
                        echo "<strong><p class='mb-1'>Job Title: </strong>" . $hireedetails->getJobName() . '</p>';
                        echo "<strong><p class='mb-1'>Date Hired: </strong>" . $hireedetails->getDateHired() . '</p>';
                        echo '<form method="post" action="./viewjob.php">
                                <input type="text" name="jobID" value="'.$hireedetails->getJobID().'" hidden>
                                <input type="text" name="companyID" value="'.$hireedetails->getCompanyID().'" hidden>
                                <input type="text" name="referer" value="viewhiree" hidden>
                                <input type="text" value="'.$hireedetails->getUserID().'" name="userID" hidden>
                                <input type="text" value="'.$hireedetails->getJobID().'" name="jobID" hidden>
                                <input type="text" value="'.$hireedetails->getApplicationID().'" name="applicationID" hidden>
                                <input type="text" value="'.$hireedetails->getHireeID().'" name="hireeID" hidden>
                                <button class="btn btn-primary" type="submit">View Job</button>
                            </form>';
                    } else {
                      echo "There was a problem fetching application details from the database.";
                    }
                    ?>
                  </div>
                    <button class="btn btn-secondary mt-5" type="submit"><a href="./hiree.php" class="link-light">Back</a></button>
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
  <?php require_once "./endscripts.php"; ?>

</body>

</html>