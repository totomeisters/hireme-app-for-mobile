<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once '../classes/job.php';
require_once '../classes/user.php';
require_once '../classes/company.php';

$username = $_SESSION['username'];

$job = new Job($conn);
$user = new User($conn);
$company = new Company($conn);

$userId = $user->getUserDetails($username)->getUserID();
$companyId = $company->getCompanyDetails($userId)->getCompanyID();
$jobdetails = $job->getAllJobs($companyId);
$pagetitle = "HireMe - Jobs";
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
            <div class="container mt-5">
        <h1 class="text-center mb-4">Jobs You Posted</h1>
        <?php if ($jobdetails): ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th class="d-none d-md-table-cell">Job Type</th>
                        <th class="d-none d-md-table-cell">Posting Date</th>
                        <th class="d-none d-md-table-cell">Verification Status</th>
                        <th class="d-none d-md-table-cell">Interested</th>
                        <th class="d-none d-md-table-cell">Applicants</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobdetails as $jobdetail): ?>
                        <tr>
                            <td><?= $jobdetail->getJobTitle(); ?></td>
                            <td class="d-none d-md-table-cell"><?= $jobdetail->getJobType(); ?></td>
                            <td class="d-none d-md-table-cell"><?= $jobdetail->getPostingDate(); ?></td>
                            <td class="d-none d-md-table-cell"><?= $jobdetail->getVerificationStatus(); ?></td>
                            <td class="d-none d-md-table-cell"><?= $job->getApplicantsCountByJobID($jobdetail->getJobID()); ?></td>
                            <td class="d-none d-md-table-cell"><?= $job->getFaveJobsCountByJobID($jobdetail->getJobID()); ?></td>
                            <td>
                            <form method="post" action="./viewjob.php">
                                <input type="hidden" name="jobID" value="<?= $jobdetail->getJobID(); ?>">
                                <button class="btn btn-primary" type="submit">View</button>
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
    <?php require_once "./endscripts.php";?>
  </body>
</html>
