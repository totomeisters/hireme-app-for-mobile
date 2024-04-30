<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__.'/../classes/companyapplication.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['CompanyApplicationID']) && isset($_POST['companyID'])){
        $companyapplicationID = $_POST['CompanyApplicationID'];
        $companyID = $_POST['companyID'];
      }
      else{
        $companyapplicationID = 0;
        $companyID = 0;
      }

}
elseif (isset($_SESSION['viewcompanyApplicationID']) && isset($_SESSION['viewcompanyID'])){
    $companyapplicationID = $_SESSION['viewcompanyApplicationID'];
    $companyID = $_SESSION['viewcompanyID'];
    $_SESSION['viewcompanyApplicationID'] = null;
    $_SESSION['viewcompanyID'] = null;
}
else{
  $companyapplicationID = 0;
  $companyID = 0;
}

$companyapplication = new CompanyApplication($conn);
$companyapplicationdetails = $companyapplication->getCompanyApplicationDetailsByID($companyapplicationID);

$pagetitle = "HireMe - Company Documents";
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
                    <div class="col-lg-8 order-0 my-1">
                        <div class="card p-2 my-1 col">
                        <?php
                            if ($companyapplicationdetails){
                                foreach ($companyapplicationdetails as $companyapplicationdetail){
                                    // Assuming you have a file path in a variable
                                    $filePath = $companyapplicationdetail->getDocumentFilePath(); // Replace with your actual file path
                                    $fileName = $companyapplicationdetail->getDocumentName();

                                    // Get the file extension
                                    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                                    // Check the file extension and generate the HTML accordingly
                                    switch ($fileExtension) {
                                        case 'pdf':
                                            // Display PDF file using an object tag
                                            echo '<object data="' . $filePath . '" type="application/pdf" width="100%" height="600px">';
                                            echo '<p>It appears you don\'t have a PDF plugin for this browser.</p>';
                                            echo 'Click here to download the PDF file: <a href="' . $filePath . '">'.$fileName.'</a>';
                                            echo '</object>';

                                            break;
                                        case 'jpg':
                                        case 'jpeg':
                                        case 'png':
                                            // Display image file using an img tag
                                            echo '<img src="' . $filePath . '" alt="'.$fileName.'" style="height: 80vh; object-fit: scale-down; 
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
                        <div class="row card mb-2 py-3 px-2">
                        <?php
                            if ($companyapplicationdetails){
                                foreach ($companyapplicationdetails as $companyapplicationdetail){
                                    $companyApplicationID = $companyapplicationdetail->getCompanyApplicationID();
                                    $documentname = $companyapplicationdetail->getDocumentName();
                                    $documenttype = $companyapplicationdetail->getDocumentFilePath();
                                        $fileextension = pathinfo($documenttype, PATHINFO_EXTENSION);
                                    $verificationstatus = $companyapplicationdetail->getVerification();
                                    $rejectionreason = $companyapplicationdetail->getReasonForRejection();
                                    $dateposted = $companyapplicationdetail->getDate();

                                    echo "<strong><p>Name: </strong>". $documentname.'</p>';
                                    echo "<strong><p>Date Posted: </strong>". $dateposted.'</p>';
                                    echo "<strong><p>File Type: </strong>". strtoupper($fileextension).'</p>';
                                    echo "<strong><p>Verification Status: </strong>". $verificationstatus.'</p>';
                                    if(isset($rejectionreason) && $verificationstatus == 'Rejected'){
                                        echo "<strong><p>Reason for Rejection: </strong>". $rejectionreason.'</p>';
                                    }
                                }
                            }
                            else{
                                echo "Error getting document details.";
                            }
                        ?>
                        </div>
                        <div class="row card mb-2 py-3 px-2">
                              <h5 class="card-title">Verification</h5>
                              
                        <?php
                          if ($verificationstatus == 'Pending') {
                        ?>
                          <form id="updateapplication" method="post" action="../functions/updatedocumentstatus.php">
                              <input type="hidden" name="companyApplicationID" value="<?= $companyApplicationID; ?>">
                              <input type="hidden" name="companyID" value="<?= $companyID; ?>">
                              <button class="btn btn-success" type="submit" name="status" value="Verified">Verify</button>
                              <button class="btn btn-danger" type="submit" name="status" value="Rejected">Reject</button>
                          </form>
                        <?php
                          } else {
                            echo '<p>This document is already <span class="' . ($verificationstatus == 'Verified' ? 'text-success' : 'text-danger') . '">' . strtoupper($verificationstatus) . '</span>.</p>';
                          }
                        ?>

                        </div>
                        <div class="row card py-3">
                          <form action="./viewcompany.php" method="post">
                            <input type="hidden" name="companyID" value="<?= $companyID; ?>">
                            <button type="submit" class="btn btn-secondary">Go Back</button>
                          </form>
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
                  <button type="button" class="btn btn-primary">Confirm</button>
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
              url: '../functions/updatedocumentstatus.php',
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
        console.log('Message: ' + message, 'Type: ' + type);
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
              var modalMessage = confirmationModal.querySelector(".modal-body");
              modalMessage.innerHTML = "You clicked <strong class='text-success'>VERIFY</strong>. Are you sure you want to continue? <strong>This action cannot be undone.</strong>";
              var modal = new bootstrap.Modal(confirmationModal);
              modal.show();
          });
        
          rejectButton.addEventListener("click", function(event) {
            event.preventDefault();
            statusValue = "Rejected";
            var modalMessage = confirmationModal.querySelector(".modal-body");
            modalMessage.innerHTML = "You clicked <strong class='text-danger'>REJECT</strong>. Are you sure you want to continue? <strong>This action cannot be undone.</strong><br><div class='my-4'><label for='reasonInput'><span class='text-danger'>*</span>Reason for Rejection:</label><textarea id='reasonInput' class='form-control mt-3' name='reason' placeholder='THIS IS REQUIRED'></textarea></div>";
            var reasonInput = document.getElementById("reasonInput");
            var modal = new bootstrap.Modal(confirmationModal);
            modal.show();
          });
        
          document.getElementById("confirmationModal").querySelector(".btn-primary").addEventListener("click", function() {
              var formData = $('#updateapplication').serialize();
              formData += "&status=" + statusValue;
              formData += "&reason=";

              if (statusValue === "Rejected") {
                  var reasonValue = document.getElementById("reasonInput").value.trim();

                  if (reasonValue === "") { //prevent form submission if reason for rejection is blank
                      showToast("Please provide a reason for rejection.", "warning");
                      return;
                  } else{
                    formData += "&reason=" + reasonValue;
                  }
              }
            
              handleFormSubmission(formData);
          });
      });
  </script>
    
  </body>
</html>
