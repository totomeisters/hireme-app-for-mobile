<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['jobID']) && isset($_POST['companyID'])){
      $jobID = $_POST['jobID'];
      $companyID = $_POST['companyID'];
    }
    else{
      $jobID = 0;
      $companyID = 0;
    }
}

require_once '../classes/job.php';
require_once '../classes/user.php';
require_once '../classes/jobseeker.php';

$user = new User($conn);
$job = new Job($conn);
$jobseeker = new JobSeeker($conn);
$jobs = $job->getJobDetailsByID($jobID);

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
                <?php if (!empty($jobs)): ?>
                  <div class="card p-2 mb-4">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo ucfirst($jobs->getJobTitle()); ?></h4>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo ucfirst($jobs->getJobLocation()); ?></h6>
                        <p class="card-text"><?php echo ucfirst($jobs->getJobDescription()); ?></p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Job Type: <?php echo $jobs->getJobType(); ?></li>
                            <li class="list-group-item">Job Type: <?php echo $jobs->getJobLocationType(); ?></li>
                            <li class="list-group-item">Salary Range: ₱<?php echo $jobs->getSalaryMin() . ' - ₱' . $jobs->getSalaryMax(); ?></li>
                            <li class="list-group-item">Work Hours: <?php echo $jobs->getWorkHours(); ?></li>
                        </ul>
                        <?php
                            $postingDate = new DateTime($jobs->getPostingDate());
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

                        <p class="card-text"><small class="text-muted">Posted on: <?php echo $jobs->getPostingDate(); ?></small></p>
                        <p class="card-text"><small class="text-muted">Time posted: <?php echo $timePassed; ?></small></p>
                        <p class="card-text"><small class="text-muted">Verification: 
                            <?php 
                                $verification = $jobs->getVerificationStatus();

                                if($verification == 'Pending'){echo '<span class="text-warning">'.$verification.'</span>';}
                                else{echo '<span class="text-success">'.$verification.'</span>';}?></small></p>
                    </div>
                      <form action="./viewcompany.php" method="post">
                        <input type="hidden" name="companyID" value="<?= $companyID; ?>">
                        <button type="submit" class="btn btn-secondary">Go Back</button>
                      </form>
                  </div>
                  <?php else: ?>
                    <div>
                      <p>This place is nonexistent. Pray tell, how didst thou arrive hither? Go back whence you came, human.</p>
                    </div>
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
