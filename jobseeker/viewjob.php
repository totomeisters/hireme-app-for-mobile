<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $jobID = $_POST['jobID'];
}

require_once '../classes/job.php';
require_once '../classes/jobseekerapplication.php';

$job = new Job($conn);
$jobseekerapplication = new JobSeekerApplication($conn);
$jobseekerapplicationdetails = $jobseekerapplication->getJobApplicationDetailsByJobID($jobID);
$jobdetails = $job->getJobDetailsByID($jobID);
$pagetitle = "HireMe - View Job #".$jobID;
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
    <!-- Toast Overlay -->
    <div id="toast-container"></div>
    <div class="overlay"></div>
    <!-- / Toast Overlay -->

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
                <?php if (!empty($jobdetails)): ?>
                  <div class="card p-2">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-10"></div>
                        <div class="col-2">
                          <a href="#applysection">Apply Here!</a>
                        </div>
                      </div>
                        <h4 class="card-title"><?php echo $jobdetails->getJobTitle(); ?></h4>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $jobdetails->getJobLocation(); ?></h6>
                        <p class="card-text"><?php echo $jobdetails->getJobDescription(); ?></p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Job Type: <?php echo $jobdetails->getJobType(); ?></li>
                            <li class="list-group-item">Job Type: <?php echo $jobdetails->getJobLocationType(); ?></li>
                            <li class="list-group-item">Salary Range: ₱<?php echo $jobdetails->getSalaryMin() . ' - ₱' . $jobdetails->getSalaryMax(); ?></li>
                            <li class="list-group-item">Work Hours: <?php echo $jobdetails->getWorkHours(); ?></li>
                        </ul>
                        <?php
                            $postingDate = new DateTime($jobdetails->getPostingDate());
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

                        <p class="card-text"><small class="text-muted">Posted on: <?php echo $jobdetails->getPostingDate(); ?></small></p>
                        <p class="card-text"><small class="text-muted">Time posted: <?php echo $timePassed; ?></small></p>
                    </div>
                    <a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : './jobs.php'; ?>" class="btn btn-secondary">Back</a>
                  </div>
                    <?php else: ?>
                        <p class="text-center">No job details. Don't play with the URL kid. Gonna send you to God, bye.</p>
                    <?php endif; ?>
                </div>

                <!-- /Card -->

              </div>
              <div class="row">
              <a id="applysection"></a>
              <?php if(empty($jobseekerapplicationdetails)) : ?>
                <div class="col-6 order-1">
                  <div class="card p-2">
                    <div class="card-body">
                      <form id="jobForm" method="post" action="../functions/jobapplication.php" enctype="multipart/form-data">
                          <input type="hidden" name="jobID" value="<?php echo $jobID;?>">
                          <input type="hidden" name="userID" value="<?php echo $userID;?>">

                          <div class="row">
                              <label for="resumeFilePath" class="my-1">Upload your résumé here:</label>
                              <input type="file" class="form-control-file my-1" id="resumeFilePath" name="resumeFilePath">
                          </div>
                          <button type="submit" class="btn btn-primary my-1">Submit</button>
                      </form>
                    </div>
                  </div>
                </div>
                <?php else: ?>
                    <div class="col-6 order-1">
                        <div class="card p-2">
                            <div class="card-body">
                                <?php foreach ($jobseekerapplicationdetails as $application):
                                  $postingDate = new DateTime($application->getApplicationDate());
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
                                ?>
                                    <p>Résumé sent: <strong><?php echo $timePassed;?></strong></p>
                                    <p>Date sent: <strong><?php echo $application->getApplicationDate();?></strong></p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-6 order-1">
                  <div class="card p-2">
                    <div class="card-body">
                      something something here idk
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

    <script>
      function showToast(message, type) {
          var toast = $('<div>', {
              class: 'toast ' + type,
              text: message
          });
          $('#toast-container').append(toast);
          toast.addClass('show');
          setTimeout(function() {
              toast.remove();
              // Hide the overlay after the toast has faded out
              $('.overlay').hide();
          }, 2000); // 3000 milliseconds = 3 seconds
      }
      
      $(document).ready(function() {
          $('#jobForm').on('submit', function(e) {
              e.preventDefault();
          
              var formData = new FormData(this);
          
              $.ajax({
                  type: 'POST',
                  url: '../functions/jobapplication.php',
                  data: formData,
                  dataType: 'json',
                  contentType: false,
                  cache: false,
                  processData:false,
                  success: function(response) {
                      if (response.status === 'success') {
                          $('.overlay').show();
                          showToast(response.message, 'success');
                          setTimeout(function() {
                              window.location.href = response.redirect;
                          }, 1900);
                      } else if (response.status === 'error') {
                          showToast(response.message, 'warning');
                      } else {
                          console.error('Unknown response status:', response.status);
                      }
                  },
                  error: function(xhr, status, error) {
                      console.error('AJAX Error:', error);
                      showToast('An error occurred. Please try again.', 'error');
                  }
              });
          });
      });
    </script>
  </body>
</html>
