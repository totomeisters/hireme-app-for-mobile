<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once '../classes/jobseekerapplication.php';
require_once '../classes/job.php';
require_once '../classes/jobseeker.php';
require_once '../classes/interview.php';

$job = new Job($conn);
$jobseeker = new JobSeeker($conn);
$jobSeekerApplication = new JobSeekerApplication($conn);
$interview = new Interview($conn);

$verifiedApplications = $jobSeekerApplication->getAllVerifiedJobApplicationDetails();

$pagetitle = "HireMe - Candidates";
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
  <?php require_once __DIR__ . "/head.php";?>
  <!-- /Head -->

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Menu -->
          <?php require_once __DIR__ . "/menubar.php";?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <?php require_once __DIR__ . "/navbar.php";?>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <!-- Card -->
                <div class="col-lg-12 mb-4 order-0">
                  <h1 class="text-center mb-4">Verified Applicants</h1>
                    <?php if (!empty($verifiedApplications)): ?>
                        <table id="candidatesTable" class="table table-hover">
                            <tr>
                                <th>Job Title</th>
                                <th>Applicant Name</th>
                                <th class="d-none d-md-table-cell">Application Date</th>
                                <th class="d-none d-md-table-cell">Status</th>
                                <th class="d-none d-md-table-cell">Interview Status</th>
                                <th>Action</th>
                            </tr>
                            <?php foreach ($verifiedApplications as $application): 
                                $jobTitle = $job->getJobDetailsByID($application->getJobID())->getJobTitle();
                                $applicantFName = $jobseeker->getJobSeekerDetailsByUserID($application->getUserID())->getFirstName();
                                $applicantLName = $jobseeker->getJobSeekerDetailsByUserID($application->getUserID())->getLastName();
                                $applicantName = $applicantFName.' '.$applicantLName;

                                if(empty($jobTitle)){
                                    $jobTitle = 'error getting job title';
                                }

                                if(empty($applicantName)){
                                    $applicantName = 'error getting job title';
                                }

                                if(!empty($application->getJobSeekerApplicationID())){
                                  $applicationStatus = $interview->getInterviewByJobSeekerApplicationID($application->getJobSeekerApplicationID())->getStatus();
                                  $status = !empty($applicationStatus) ? $applicationStatus : "Unknown";
                                }
                                
                                
                                ?>
                                <tr>
                                    <td><?= $jobTitle ?></td>
                                    <td><?= $applicantName ?></td>
                                    <td class="d-none d-md-table-cell"><?= date('Y-m-d', strtotime($application->getApplicationDate())) ?></td>
                                    <td class="d-none d-md-table-cell"><?= $application->getStatus() ?></td>
                                    <td class="d-none d-md-table-cell"><?= $status ?></td>
                                    <td>
                                        <form action="./viewapplicant.php" method="post">
                                            <input type="text" value="<?= $application->getUserID() ?>" name="applicantID" hidden>
                                            <input type="text" value="<?= $application->getJobID() ?>" name="jobID" hidden>
                                            <input type="text" value="Candidates" name="referer" hidden>
                                            <button type="submit" class="btn btn-primary">View</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        No verified applicants found.
                    <?php endif; ?>

                </div>
                <!-- /Card -->

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
    <?php require_once __DIR__ . "/endscripts.php";?>
    
    <!-- <script>
        $(document).ready(function() {
            $('#candidatesTable').DataTable();
        });
    </script> -->

  </body>
</html>
