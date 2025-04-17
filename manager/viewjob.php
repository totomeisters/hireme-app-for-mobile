<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['jobID']) && isset($_POST['companyID']) && isset($_POST['userID']) && isset($_POST['applicationID']) && isset($_POST['hireeID'])) {
      $jobID = $_POST['jobID'];
      $companyID = $_POST['companyID'];
      $applicantID = $_POST['userID'];
      $applicationID = $_POST['applicationID'];
      $referer = $_POST['referer'] ?? null;
      $hireeID = $_POST['hireeID'];
    }
    elseif(isset($_POST['jobID']) && isset($_POST['companyID'])){
      $jobID = $_POST['jobID'];
      $companyID = $_POST['companyID'];
    }
    else{
      $jobID = 0;
      $companyID = 0;
    }
}
elseif (isset($_SESSION['viewjobID']) && isset($_SESSION['viewcompanyID'])){
    $jobID = $_SESSION['viewjobID'];
    $companyID = $_SESSION['viewcompanyID'];
    $_SESSION['viewjobID'] = null;
    $_SESSION['viewcompanyID'] = null;
}
else{
  $jobID = 0;
  $companyID = 0;
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
    <!-- Toast Overlay -->
    <div id="toast-container"></div>
    <div class="overlay"></div>
    <!-- / Toast Overlay   -->
    
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
                  <div class="row">
                  <div class="col-md-9">
                    <div class="card p-2 mb-4">
                      <div class="card-body">
                        <h4 class="card-title"><strong><?= ucfirst($jobs->getJobTitle()); ?></strong></h4>
                        <h6 class="card-subtitle mb-2"><?= ucfirst($jobs->getJobLocation()); ?></h6>
                        <p class="card-subtitle mb-2"><strong>Job Type: </strong><?= $jobs->getJobType(); ?></p>
                        <p class="card-subtitle mb-2"><strong>Location Type: </strong><?= $jobs->getJobLocationType(); ?></p>
                        <p class="card-subtitle mb-2"><strong>Salary Range: </strong>₱<?= $jobs->getSalaryMin() . ' - ₱' . $jobs->getSalaryMax(); ?></p>
                        <p class="card-subtitle mb-4"><strong>Work Hours: </strong><?= $jobs->getWorkHours(); ?></p>
                        <p class="card-subtitle mb-4"><strong>Skills: </strong><?= $jobs->getSkills(); ?></p>
                        <p class="card-subtitle mb-4"><strong>Qualifications: </strong><?= $jobs->getQualifications(); ?></p>
                        <p class="card-subtitle mb-4"><strong>Vacancies: </strong><?= $vacancies = $jobs->getVacancies()==0 ? 'Indefinite Hiring' : $jobs->getVacancies() ?></p>
                        <p class="card-text"><?= ucfirst($jobs->getJobDescription()); ?></p>
                      </div>
                    </div>
                    <?php if(!empty($referer) && $referer === 'viewhiree'){?>
                      <form action="./viewhiree.php" method="post">
                          <input type="text" value="<?= $applicantID ?>" name="userID" hidden>
                          <input type="text" value="<?= $jobID ?>" name="jobID" hidden>
                          <input type="text" value="<?= $applicationID ?>" name="applicationID" hidden>
                          <input type="text" value="<?= $hireeID ?>" name="hireeID" hidden>
                          <button type="submit" class="btn btn-primary">Go Back</button>
                      </form>
                    <?php } else{?>
                      <form action="./viewcompany.php" method="post">
                        <input type="hidden" name="companyID" value="<?= $companyID; ?>">
                        <button type="submit" class="btn btn-secondary">Go Back</button>
                      </form>
                    <?php } ?>  
                  </div>
                  <div class="col-md-3">
                    <div class="row">
                      <div class="card p-2 mb-4">
                        <div class="card-body">
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
                            <p class="card-text"><small class="text-muted">Posted on: <?= $jobs->getPostingDate(); ?></small></p>
                            <p class="card-text"><small class="text-muted">Time posted: <?= $timePassed; ?></small></p>
                            <p class="card-text"><small class="text-muted">Verification: 
                                <?php 
                                    $verification = $jobs->getVerificationStatus();

                                    if($verification == 'Pending'){echo '<span class="text-warning">'.$verification.'</span>';}
                                    elseif($verification == 'Verified'){echo '<span class="text-success">'.$verification.'</span>';}
                                    else{echo '<span class="text-danger">'.$verification.'</span>';}?></small></p>
                            <?php if($jobs->getRejectionReason() != null){?>
                            <p class="card-text"><small class="text-muted">Remarks: <?= $jobs->getRejectionReason(); ?></small></p>
                            <?php } ?>
                        </div>
                      </div>
                    </div>

                    <?php
                          if ($verification == 'Pending') {
                        ?>
                    <div class="row">
                      <div class="card p-2 mb-2">
                        <div class="card-body">
                          <h6>Verification</h6>
                        
                          <form id="updateapplication" method="post" action="../functions/updatejobstatus.php">
                              <input type="hidden" name="jobID" value="<?= $jobID; ?>">
                              <input type="hidden" name="companyID" value="<?= $companyID; ?>">
                              <button class="btn btn-success m-1" type="submit" name="status" value="Verified">Accept Job Vacancy</button>
                              <button class="btn btn-danger m-1" type="submit" name="status" value="Rejected">Reject Job Vacancy</button>
                          </form>
                        </div>
                      </div>
                    </div>
                    <?php } ?>

                  </div>
                  </div>
                  <?php else: ?>
                    <div>
                      <p>This place is nonexistent. Pray tell, how didst thou arrive hither?</p>
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

  <!-- Confirmation Modal -->
  <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                  <!-- <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button> -->
              </div>
              <p class="mx-3"><small class="text-muted">To dismiss or close this message, please click anywhere outside the box.</small></p>
              <div class="modal-body">
                  <span id="modalMessage"></span>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" id="modalconfirm" class="btn btn-primary">Confirm</button>
              </div>
          </div>
      </div>
  </div>
  <!-- / Confirmation Modal -->

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <?php require_once "./endscripts.php";?>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      function handleFormSubmission(formData) {
          $.ajax({
              type: 'POST',
              url: '../functions/updatejobstatus.php',
              data: formData,
              dataType: 'json',
              success: function(response) {
                  if (response.status === 'success') {
                      $('.overlay').show();
                      showToast(response.message, 'success');
                      setTimeout(function() {
                          window.location.href = response.redirect;
                      }, 1400);
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
      }

      function showToast(message, type) {
          var toast = $('<div>', {
              class: 'toast ' + type,
              text: message
          });
          $('#toast-container').append(toast);
          toast.addClass('show');
          setTimeout(function() {
              toast.remove();
              $('.overlay').hide();
          }, 2000);
      }
      
          var confirmationModal = document.getElementById("confirmationModal");
          var verifyButton = document.querySelector("#updateapplication button[value='Verified']");
          var rejectButton = document.querySelector("#updateapplication button[value='Rejected']");
          var statusValue = "";

          verifyButton.addEventListener("click", function(event) {
              event.preventDefault();
              statusValue = "Verified";
              let reason = "";
              var modalMessage = confirmationModal.querySelector(".modal-body");
              modalMessage.innerHTML = "You clicked <strong class='text-success'>VERIFY</strong>. Are you sure you want to continue? <strong>This action cannot be undone.</strong>";
              var modal = new bootstrap.Modal(confirmationModal);
              modal.show();
          });
        
          rejectButton.addEventListener("click", function(event) {
            event.preventDefault();
            statusValue = "Rejected";
            var modalMessage = confirmationModal.querySelector(".modal-body");
            modalMessage.innerHTML = "You clicked <strong class='text-danger'>REJECT</strong>. Are you sure you want to continue? <strong>This action cannot be undone.</strong>";
            modalMessage.innerHTML += '<textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Reason for Rejection"></textarea>';
            var modal = new bootstrap.Modal(confirmationModal);
            modal.show();
          });
              
          document.getElementById("confirmationModal").querySelector(".btn-primary").addEventListener("click", function () {
              var reason = "";
              if(statusValue === "Rejected"){
                let reasonField = document.getElementById('reason');
                let reason = reasonField.value.trim();
                
                if (reason === "" && statusValue === "Rejected") {
                  alert("Please provide a reason for rejection.");
                  reasonField.focus();
                  return;
                }
              }
            
              var formData = $('#updateapplication').serialize();
              formData += "&status=" + statusValue;
              formData += "&reason=" + reason;
            
              handleFormSubmission(formData);
          });

      });
  </script>

  </body>
</html>
