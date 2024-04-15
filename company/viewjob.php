<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $jobID = $_POST['jobID'];
}

require_once '../classes/job.php';

$job = new Job($conn);
$companyId = 1;
$jobs = $job->getJobDetails($jobID);
$pagetitle = "HireMe - View Job # ".$jobID;
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
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <!-- Card -->
                <div class="col-lg-12 mb-4 order-0">
                <?php if ($jobs): ?>
                <?php foreach ($jobs as $job): ?>
                  <div class="card p-2">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $job->getJobTitle(); ?></h4>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $job->getJobLocation(); ?></h6>
                        <p class="card-text"><?php echo $job->getJobDescription(); ?></p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Job Type: <?php echo $job->getJobType(); ?></li>
                            <li class="list-group-item">Job Type: <?php echo $job->getJobLocationType(); ?></li>
                            <li class="list-group-item">Salary Range: ₱<?php echo $job->getSalaryMin() . ' - ₱' . $job->getSalaryMax(); ?></li>
                            <li class="list-group-item">Work Hours: <?php echo $job->getWorkHours(); ?></li>
                        </ul>
                        <?php
                            $postingDate = new DateTime($job->getPostingDate());
                            $currentDate = new DateTime();
                            $interval = $currentDate->diff($postingDate);

                            $timePassed = '';
                            if ($interval->y) {
                                $timePassed = $interval->format('%y year/s ago');
                            } elseif ($interval->m) {
                                $timePassed = $interval->format('%m month/s ago');
                            } elseif ($interval->d) {
                                $timePassed = $interval->format('%d day/s ago');
                            } elseif ($interval->h) {
                                $timePassed = $interval->format('%h hour/s ago');
                            } elseif ($interval->i) {
                                $timePassed = $interval->format('%i minute/s ago');
                            } else {
                                $timePassed = $interval->format('%s seconds ago');
                            }
                            ?>

                        <p class="card-text"><small class="text-muted">Posted on: <?php echo $job->getPostingDate(); ?></small></p>
                        <p class="card-text"><small class="text-muted">Time posted: <?php echo $timePassed; ?></small></p>
                        <p class="card-text"><small class="text-muted">Verification: 
                            <?php 
                                $verification = $job->getVerificationStatus();

                                if($verification == 'Pending'){echo '<span class="text-warning">'.$verification.'</span>';}
                                else{echo '<span class="text-success">'.$verification.'</span>';}?></small></p>
                    </div>
                    <a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : './jobs.php'; ?>" class="btn btn-secondary">Back</a>
                  </div>
                <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No job details. Don't play with the URL kid. Gonna send you to God, bye.</p>
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
    <?php require_once "./endscripts.php";?>
  </body>
</html>
