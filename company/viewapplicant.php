<?php
if (!isset($_SESSION)) {
  session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['applicantID']) && isset($_POST['jobID']) && isset($_POST['referer'])) {
    $applicantID = $_POST['applicantID'];
    $jobID = $_POST['jobID'];
    $referer = $_POST['referer'];
  } else {
    $applicantID = 0;
    $jobID = 0;
    $referer = 0;
  }
}

require_once '../classes/jobseeker.php';
require_once '../classes/job.php';
require_once '../classes/jobseekerapplication.php';
require_once '../classes/interview.php';
require_once '../classes/pdf.php';

$pdfGenerator = new BinaryPDF($conn);
$interview = new Interview($conn);
$jobseeker = new JobSeeker($conn);
$jobseekerapplication = new JobSeekerApplication($conn);
$job = new Job($conn);

$jobName = $job->getJobDetailsByID($jobID)->getJobTitle();
$applicationdetails = $jobseekerapplication->getJobApplicationDetailsByUserID($applicantID, $jobID);
$applicantdetails = $jobseeker->getJobSeekerDetailsByUserID($applicantID);

$pagetitle = "HireMe - View Applicant # " . $applicantID;
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
                Applicant ID:
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
                    <h5><strong>Applicant Details</strong></h5>
                    <?php
                    if ($applicantdetails) {
                      $name = ucfirst($applicantdetails->getFirstName()) . ' ' . ucfirst($applicantdetails->getLastName());
                      $address = $applicantdetails->getAddress();
                      $contactnumber = $applicantdetails->getContactNumber();
                      $birthdate = $applicantdetails->getBirthDate();
                      $today = new DateTime();
                      $birthday = new DateTime($birthdate);
                      $age = $today->diff($birthday)->y;

                      echo "<strong><p class='mb-0'>Name: </strong>" . $name . '</p>';
                      echo "<strong><p class='mb-0'>Birth Date: </strong>" . $birthdate . '</p>';
                      echo "<strong><p class='mb-0'>Age: </strong>" . $age . ' years old</p>';
                      echo "<strong><p class='mb-0'>Address: </strong>" . $address . '</p>';
                      echo "<strong><p class='mb-0'>Contact Number: </strong>" . $contactnumber . '</p>';
                    } else {
                      echo "Error getting applicant details.";
                    }
                    ?>
                  </div>

                  <div class="card p-2 my-1">
                    <h5><strong>Application Details</strong></h5>
                    <?php
                    if ($applicationdetails) {
                      // $resumeId = $applicationdetail->getResumeFilePath();
                      // $fileExtension = strtoupper(pathinfo($resumeId, PATHINFO_EXTENSION));
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

                      // echo "<strong><p>File Type: </strong>". $fileExtension .'</p>';
                      echo "<strong><p class='mb-0'>Application Date: </strong>" . $applicationdate . '</p>';
                      echo "<strong><p class='mb-0'>Sent: </strong>" . $timePassed . '</p>';
                      echo "<strong><p class='mb-0'>Application ID: </strong>" . $applicationID . '</p>';
                    } else {
                      echo "Error getting application details.";
                    }
                    ?>
                  </div>
                  <div class="card p-2 my-1">
                    <h5><strong>Application Status</strong></h5>
                    <?php
                    if ($applicationdetails) {
                      $applicationID = $applicationdetail->getJobSeekerApplicationID();
                      $status = $applicationdetail->getStatus();
                      $interviewcheck = $interview->getInterviewByJobSeekerApplicationID($applicationID);

                      if ($status === "Hired") {
                        echo '<p>This application is already marked as <strong><span class="text-success">HIRED</span></strong>.</p>';
                      } elseif ($status === "Rejected") {
                        echo '<p>This application is already marked as <strong><span class="text-danger">REJECTED</span></strong>.</p>';
                      } elseif ($status === "Verified" && (empty($interviewcheck) || $interviewcheck == null)) {
                        echo '<button id="setInterviewButton" type="button" class="btn btn-primary" data-toggle="modal" data-target="#setInterviewModal">Set Interview</button>';
                      } elseif ($status === "Verified" && (!empty($interviewcheck) || $interviewcheck !== null)) {
                        echo '<p>This application is set for an interview. To check the interview details, <a href="./interviews.php" target="_blank">Click Here</a> or visit the Interviews page from the side menubar.</p>';
                        echo '<button id="changeStatusButton" type="button" class="btn btn-primary" data-toggle="modal" data-target="#changeStatusModal">Change Status</button>';
                      } elseif ($status === "Pending") {
                        echo '<form id="updateapplication" method="post" action="../functions/updateapplicationstatus.php">
                                                <input type="hidden" name="applicationID" value="' . $applicationID . '">
                                                <button class="btn btn-success" type="submit" name="status" value="Verified">Verify</button>
                                                <button class="btn btn-danger" type="submit" name="status" value="Rejected">Reject</button>
                                              </form>';
                      } else {
                        echo 'Application status is unknown.';
                      }
                    } else {
                      echo "There was a problem fetching application details from the database.";
                    }
                    ?>
                  </div>
                  <?php
                  if ($referer == "ViewJob") { ?>
                    <form class="p-2 mt-4" method="post" action="./viewjob.php">
                      <input type="hidden" name="jobID" value="<?= $jobID; ?>">
                      <div class="card">
                        <button class="btn btn-secondary" type="submit">Back</button>
                      </div>
                    </form>
                  <?php } elseif ($referer == "Candidates") { ?>
                    <form class="p-2 mt-4" method="post" action="./candidates.php">
                      <div class="card">
                        <button class="btn btn-secondary" type="submit">Back</button>
                      </div>
                    </form>
                  <?php } elseif ($referer == "Interviews") { ?>
                    <form class="p-2 mt-4" method="post" action="./interviews.php">
                      <div class="card">
                        <button class="btn btn-secondary" type="submit">Back</button>
                      </div>
                    </form>
                  <?php } else {
                    echo 'You are not supposed to be here.';
                  } ?>
                </div>
              </div>
            </div>
          </div>
          <!-- / Content -->

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
                  <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> -->
                  <button type="button" class="btn btn-primary">Continue</button>
                </div>
              </div>
            </div>
          </div>
          <!-- / Confirmation Modal -->

          <!-- Set Interview Modal -->
          <div class="modal fade" id="setInterviewModal" tabindex="-2" role="dialog" aria-labelledby="setInterviewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="setInterviewModalLabel">Set Interview</h5>
                  <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form id="setInterview" method="post" action="../functions/addinterview.php">
                    <input type="text" id="jobID" name="jobID" value="<?= $jobID; ?>" hidden required>
                    <input type="text" id="jobSeekerApplicationID" name="jobSeekerApplicationID" value="<?= $applicationID ?>" hidden required>

                    <div class="form-group mb-5">
                      <label for="interview_date">Interview Date:</label>
                      <input type="datetime-local" class="form-control" id="interviewDate" name="interviewDate" required min="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>

                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- / Set Interview Modal -->

          <!-- Change Application Status Modal -->
          <div class="modal fade" id="changeStatusModal" tabindex="-2" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="changeStatusModalLabel">Change Application Status</h5>
                  <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form id="changeapplicationstatus" method="post" action="../functions/changeapplicationstatus.php">
                    <input type="text" id="jobID" name="jobID" value="<?= $jobID; ?>" hidden>
                    <input type="text" id="jobName" name="jobName" value="<?= $jobName; ?>" hidden>
                    <input type="text" id="userID" name="userID" value="<?= $applicantID; ?>" hidden>
                    <input type="text" id="fullName" name="fullName" value="<?= $name ?>" hidden>
                    <input type="text" id="companyID" name="companyID" value="<?= $companyID ?>" hidden>
                    <input type="text" id="companyName" name="companyName" value="<?= $companyName ?>" hidden>
                    <input type="text" id="applicationID" name="applicationID" value="<?= $applicationID ?>" hidden>

                    <div class="form-group mb-5">
                      <label for="dateHired">Date Hired:</label>
                      <p><span class="text-danger">*</span><small class="text-muted">Not required if choosing "Rejected".</small></p>
                      <input type="datetime-local" class="form-control" id="dateHired" name="dateHired" required max="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>

                    <div class="form-group mb-5">
                      <label for="status">Status:</label>
                      <select class="form-control" id="status" name="status" required>
                        <option selected disabled>-- Select an option --</option>
                        <option value="Hired">Hired</option>
                        <option value="Rejected">Rejected</option>
                      </select>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- / Change Application Status Modal -->

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

  <script>
    $(document).ready(function() {
      var setInterviewButton = document.getElementById("setInterviewButton");

      setInterviewButton.addEventListener("click", function(event) {
        var modal = new bootstrap.Modal(document.getElementById("setInterviewModal"));
        modal.show();
      })

      $('#setInterview').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
          type: 'POST',
          url: '../functions/addinterview.php',
          data: formData,
          dataType: 'json',
          success: function(response) {
            if (response.status === 'success') {
              $('.overlay').show();
              showToast(response.message, 'success');
              setTimeout(function() {
                location.reload();
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
      });
    });
  </script>

  <script>
    $(document).ready(function() {
      var changeStatusButton = document.getElementById("changeStatusButton");

      changeStatusButton.addEventListener("click", function(event) {
        var modal = new bootstrap.Modal(document.getElementById("changeStatusModal"));
        modal.show();
      })

      $('#status').change(function() {
        if ($(this).val() === 'Rejected') {
          $('#dateHired').prop('required', false);
        } else {
          $('#dateHired').prop('required', true);
        }
      });

      $('#changeapplicationstatus').submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
          type: 'POST',
          url: '../functions/changestatus.php',
          data: formData,
          dataType: 'json',
          success: function(response) {
            if (response.status === 'success') {
              $('.overlay').show();
              showToast(response.message, 'success');
              setTimeout(function() {
                location.reload();
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
      });
    });
  </script>

  <script>
    function handleFormSubmission(formData) {
      $.ajax({
        type: 'POST',
        url: '../functions/updateapplicationstatus.php',
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

    function getPDF(userId, jobId) {
      fetch(`../functions/getpdf.php?userId=${userId}&jobId=${jobId}`)
        .then(response => response.blob())
        .then(blob => {
          const url = URL.createObjectURL(blob);
          const pdfViewer = document.getElementById('pdfViewer');
          pdfViewer.src = url;
        })
        .catch(error => {
          console.error('Error fetching PDF:', error);
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
      const userId = <?= $applicantID ?>;
      const jobId = <?= $jobID ?>;
      getPDF(userId, jobId); // display pdf if dom is loaded

      var confirmationModal = document.getElementById("confirmationModal");
      var verifyButton = document.querySelector("#updateapplication button[value='Verified']");
      var rejectButton = document.querySelector("#updateapplication button[value='Rejected']");
      var statusValue = "";

      verifyButton.addEventListener("click", function(event) {
        event.preventDefault();
        statusValue = "Verified";
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
        var modal = new bootstrap.Modal(confirmationModal);
        modal.show();
      });

      document.getElementById("confirmationModal").querySelector(".btn-primary").addEventListener("click", function() {
        var formData = $('#updateapplication').serialize();
        formData += "&status=" + statusValue;
        handleFormSubmission(formData);
      });
    });
  </script>
</body>

</html>