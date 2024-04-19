<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['applicantID']) && isset($_POST['jobID'])){
      $applicantID = $_POST['applicantID'];
      $jobID = $_POST['jobID'];
    }
    else{
      $applicantID = 0;
      $jobID = 0;
    }
}

require_once '../classes/jobseeker.php';
require_once '../classes/jobseekerapplication.php';

$jobseeker = new JobSeeker($conn);
$jobseekerapplication = new JobSeekerApplication($conn);

$applicationdetails = $jobseekerapplication->getJobApplicationDetailsByUserID($applicantID);
$applicantdetails = $jobseeker->getJobSeekerDetailsByUserID($applicantID);

$pagetitle = "HireMe - View Applicant # ".$applicantID;
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
                            if ($applicationdetails){
                                foreach ($applicationdetails as $applicationdetail){
                                    // Assuming you have a file path in a variable
                                    $filePath = $applicationdetail->getResumeFilePath();

                                    // Get the file extension
                                    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                                    // Check the file extension and generate the HTML accordingly
                                    switch ($fileExtension) {
                                        case 'pdf':
                                            // Display PDF file using an object tag
                                            echo '<object data="' . $filePath . '" type="application/pdf" width="100%" height="600px">';
                                            echo '<p>It appears you don\'t have a PDF plugin for this browser.</p>';
                                            echo 'Click here to download the PDF file: <a href="' . $filePath . '">Resume PDF</a>';
                                            echo '</object>';

                                            break;
                                        case 'jpg':
                                        case 'jpeg':
                                        case 'png':
                                            // Display image file using an img tag
                                            echo '<img src="' . $filePath . '" alt="Resume" style="height: 80vh; object-fit: scale-down;" 
                                                    class="img-fluid bg-secondary rounded border border-dark">';
                                            break;
                                        default:
                                            // Handle other file types or unknown extensions
                                            echo "Unsupported file type.";
                                            break;
                                    }
                                }
                            }
                            else{
                                echo "Error getting document details.";
                            }
                        ?>
                        </div>
                    </div>
                    <div class="col-lg-4 order-1 my-1">
                        <div class="row">
                            <div class="card p-2 my-1">
                                <h5>Applicant Details</h5>
                                <?php
                                    if ($applicantdetails){
                                            $name = $applicantdetails->getFirstName() .' '. $applicantdetails->getLastName();
                                            $address = $applicantdetails->getAddress();
                                            $contactnumber = $applicantdetails->getContactNumber();
                                            $birthdate = $applicantdetails->getBirthDate();
                                            $today = new DateTime();
                                            $birthday = new DateTime($birthdate);
                                            $age = $today->diff($birthday)->y;
                                        
                                            echo "<strong><p>Name: </strong>". $name .'</p>';
                                            echo "<strong><p>Birth Date: </strong>". $birthdate .'</p>';
                                            echo "<strong><p>Birth Date: </strong>". $age .' years old</p>';
                                            echo "<strong><p>Address: </strong>". $address .'</p>';
                                            echo "<strong><p>Contact Number: </strong>". $contactnumber .'</p>';
                                    }
                                    else{
                                        echo "Error getting applicant details.";
                                    }
                                ?>
                            </div>

                            <div class="card p-2 my-1">
                                <h5>Application Details</h5>
                                <?php
                                    if ($applicationdetails){
                                        foreach ($applicationdetails as $applicationdetail){

                                            $filePath = $applicationdetail->getResumeFilePath();
                                            $fileExtension = strtoupper(pathinfo($filePath, PATHINFO_EXTENSION));
                                            $applicationdate = $applicationdetail->getApplicationDate();
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
                                        
                                            echo "<strong><p>File Type: </strong>". $fileExtension .'</p>';
                                            echo "<strong><p>Application Date: </strong>". $applicationdate .'</p>';
                                            echo "<strong><p>Sent: </strong>". $timePassed .'</p>';
                                        }
                                    }
                                    else{
                                        echo "Error getting application details.";
                                    }
                                ?>
                            </div>
                            <div class="card p-2 my-1">
                                <h5>Verification</h5>
                                <?php
                                  if ($applicationdetails) {
                                      $applicationID = $applicationdetail->getJobSeekerApplicationID();
                                      $status = $applicationdetail->getStatus();
                                  
                                      if ($status === "Pending") {
                                  ?>
                                          <form id="updateapplication" method="post" action="../functions/updateapplicationstatus.php">
                                              <input type="hidden" name="applicationID" value="<?= $applicationID; ?>">
                                              <input type="hidden" name="status" name="applicationID" value="">
                                              <button class="btn btn-success" type="submit" name="status" value="Verified">Verify</button>
                                              <button class="btn btn-danger" type="submit" name="status" value="Rejected">Reject</button>
                                          </form>
                                  <?php
                                      }
                                      else{
                                        $statuscolor = ($status === "Rejected") ? 'text-danger' : 'text-success';
                                        echo 'This application is already marked as: <strong class="'.$statuscolor.'">'. strtoupper($status) .'</strong>.<br>';
                                        echo 'This applicant can now be seen on the candidates page.';
                                      }
                                  } else {
                                      echo "Error getting application details.";
                                  }
                                ?>
                            </div>
                            <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmationModal"> -->
                              <!-- Test Modal -->
                            <!-- </button> -->
                            <form class="p-2 mt-4" method="post" action="./viewjob.php">
                              <input type="hidden" name="jobID" value="<?= $jobID; ?>">
                              <div class="card">
                                <button class="btn btn-primary" type="submit">Back</button>
                              </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- / Content -->

            <!-- Modal -->
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
            <!-- / Modal -->

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

    <!-- <script>
      // Wait for the DOM to be fully loaded
      document.addEventListener("DOMContentLoaded", function() {
          // Get the modal and buttons
          var confirmationModal = document.getElementById("confirmationModal");
          var verifyButton = document.querySelector("#updateapplication button[value='Verified']");
          var rejectButton = document.querySelector("#updateapplication button[value='Rejected']");
      
          // Function to handle form submission after confirmation
          function handleFormSubmission(status) {
              // Set the value of the hidden input field
              document.querySelector("#updateapplication input[name='status']").value = status;
              // Submit the form
              document.getElementById("updateapplication").submit();
          }
        
          // Event listener for the Verify button
          verifyButton.addEventListener("click", function(event) {
              // Prevent the default form submission behavior
              event.preventDefault();
              // Display the modal
              var modal = new bootstrap.Modal(confirmationModal);
              modal.show();
              // Event listener for the Continue button in the modal
              document.getElementById("confirmationModal").querySelector(".btn-primary").addEventListener("click", function() {
                  // Handle form submission with "Verified" status
                  handleFormSubmission("Verified");
              });
          });
        
          // Event listener for the Reject button
          rejectButton.addEventListener("click", function(event) {
              // Prevent the default form submission behavior
              event.preventDefault();
              // Display the modal
              var modal = new bootstrap.Modal(confirmationModal);
              modal.show();
              // Event listener for the Continue button in the modal
              document.getElementById("confirmationModal").querySelector(".btn-primary").addEventListener("click", function() {
                  // Handle form submission with "Rejected" status
                  handleFormSubmission("Rejected");
              });
          });
      });
    </script>

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
          $('#updateapplication').on('submit', function(e) {
              e.preventDefault();
          
              var formData = $(this).serialize();
              console.log(formData);
          
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
          });

          $('#updateapplication button[type="submit"]').click(function(e) {
              e.preventDefault();

              var status = $(this).val();
              $('#updateapplication input[name="status"]').val(status);

              $('#updateapplication').submit();
          });
      });
    </script> -->

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

      document.addEventListener("DOMContentLoaded", function() {
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
