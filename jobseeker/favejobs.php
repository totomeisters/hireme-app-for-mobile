<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once '../classes/user.php';
require_once '../classes/jobseeker.php';
require_once '../classes/job.php';

$username = $_SESSION['username'];

$user = new User($conn);
$job = new Job($conn);
$jobseeker = new JobSeeker($conn);

$userID = $user->getUserDetails($username)->getUserID();
$jobseekerID = $jobseeker->getJobSeekerDetailsByUserID($userID);
$favejobs = $jobseeker->getFaveJobsByJobseekerID($jobseekerID);


$pagetitle = "HireMe - Dashboard";
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
            <div class="container mt-5">
        <h1 class="text-center mb-4">Jobs Posted</h1>
        <?php if ($jobs): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th class="d-none d-md-table-cell">Job Type</th>
                        <th class="d-none d-md-table-cell">Location Type</th>
                        <th class="d-none d-md-table-cell">Posting Date</th>
                        <th class="d-none d-md-table-cell">Verification Status</th>
                        <th class="d-none d-md-table-cell">People Interested</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?php echo $job->getJobTitle(); ?></td>
                            <td class="d-none d-md-table-cell"><?php echo $job->getJobType(); ?></td>
                            <td class="d-none d-md-table-cell"><?php echo $job->getJobLocationType(); ?></td>
                            <td class="d-none d-md-table-cell"><?php echo $job->getPostingDate(); ?></td>
                            <td class="d-none d-md-table-cell"><?php echo $job->getVerificationStatus(); ?></td>
                            <td class="d-none d-md-table-cell"><?php echo  ?></td>
                            <td>
                            <form method="post" action="./viewjob.php">
                                <input type="hidden" name="jobID" value="<?php echo $job->getJobID(); ?>">
                                <button type="submit">View</button>
                            </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">No jobs found for this company.</p>
        <?php endif; ?>
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
  </body>
</html>
