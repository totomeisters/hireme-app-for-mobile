<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once '../classes/user.php';
require_once '../classes/jobseeker.php';
require_once '../classes/job.php';

$username = $_SESSION['username'];

$job = new Job($conn);
$user = new User($conn);
$jobseeker = new JobSeeker($conn);

$userID = $user->getUserDetails($username)->getUserID();
$jobseekerID = $jobseeker->getJobSeekerDetailsByUserID($userID)->getJobSeekerID();
$favejobsID = $jobseeker->getFaveJobsIDByJobseekerID($jobseekerID); //returns fave job id, use that as parameter to get all job details within a foreach

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
    <!-- Toast Overlay -->
    <div id="toast-container"></div>
    <div class="overlay"></div>
    <!-- / Toast Overlay -->

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
        <h1 class="text-center mb-4">Favorite Jobs</h1>
        <?php if ($favejobsID): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Job Title</th>
                        <th class="d-none d-md-table-cell">Job Type</th>
                        <th class="d-none d-md-table-cell">Location Type</th>
                        <th class="d-none d-md-table-cell">Posting Date</th>
                        <th class="d-none d-md-table-cell">People Interested</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($favejobsID as $favejobID):
                        $jobdetails = $job->getJobDetailsByID($favejobID);
                        if ($jobdetails): 
                          $jobID = $jobdetails->getJobID();
                          $favorite = $jobdetails->getJobID();?>
                            <tr>
                                <td>
                                  <form id="favejobform_<?php echo $jobID; ?>" method="post" action="../functions/favejob.php">
                                    <input type="hidden" name="jobID" value="<?php echo $jobID; ?>">
                                    <input type="hidden" name="jobSeekerID" value="<?php echo $jobseekerID; ?>">
                                    <input type="hidden" name="favoriteAction" value="<?php echo ($jobID == $favorite) ? 'unfavorite' : 'favorite'; ?>">
                                    <input type="hidden" name="referer" value="./favejobs.php">
                                    <?php
                                      if ($jobID == $favorite){
                                        echo '<button type="submit" class="btn btn-circle"><i class="bx bxs-heart"></i></button>';
                                      }
                                      else{
                                        echo '<button type="submit" class="btn btn-circle"><i class="bx bx-heart"></i></button>';
                                      }
                                    ?>
                                  </form>
                                </td>
                                <td><?php echo $jobdetails->getJobTitle(); ?></td>
                                <td class="d-none d-md-table-cell"><?php echo $jobdetails->getJobType(); ?></td>
                                <td class="d-none d-md-table-cell"><?php echo $jobdetails->getJobLocationType(); ?></td>
                                <td class="d-none d-md-table-cell"><?php echo $jobdetails->getPostingDate(); ?></td>
                                <td class="d-none d-md-table-cell"><?php echo $job->getFaveJobsCountByJobID($jobdetails->getJobID());?></td>
                                <td>
                                    <form method="post" action="./viewjob.php">
                                        <input type="hidden" name="jobID" value="<?php echo $jobID; ?>">
                                        <button class="btn btn-primary" type="submit">View</button>
                                    </form>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">There was a problem getting job details.</td>
                            </tr>
                        <?php endif;
                    endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">You have yet to add a job to your "favorite" list. Pick one now at the Jobs tab on the side bar.</p>
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
          $('[id^=favejobform]').on('submit', function(e) {
              e.preventDefault();
          
              var formData = $(this).serialize();
          
              $.ajax({
                  type: 'POST',
                  url: '../functions/favejob.php',
                  data: formData,
                  dataType: 'json',
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
