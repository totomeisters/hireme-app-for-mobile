<?php
if (!isset($_SESSION)) {
    session_start();
}

if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}
    
require_once __DIR__.'/../classes/user.php';
require_once __DIR__.'/../classes/company.php';
require_once __DIR__.'/../classes/companyapplication.php';

$user = new User($conn);
$company = new Company($conn);
$companyapplication = new CompanyApplication($conn);

$userID = $user->getUserDetails($username)->getUserID();
$companyID = $company->getCompanyDetails($userID)->getCompanyID();
$companyapplciationdetails = $companyapplication->getCompanyApplicationDetails($companyID);

$pagetitle = "HireMe - Requirement Verification";
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
                        <div class="card p-2 my-1">

                        <form id="formAuthentication" action="../functions/uploadcompanydocument.php" method="post" enctype="multipart/form-data">
                          <div class="form-group my-2">
                            <label for="DocumentName">Document Name</label>
                            <input type="text" class="form-control" id="DocumentName" name="DocumentName" required>
                          </div>
                          <div class="form-group my-4">
                            <label for="fileUpload">Upload Document</label><br>
                            <input type="file" class="form-control-file" id="fileUpload" name="fileUpload">
                          </div>
                          <button type="submit" class="btn btn-primary my-2">Submit</button>
                        </form>

                        </div>
                    </div>
                    <div class="col-lg-4 order-1 my-1">
                      <h4>Documents Sent</h4>
                      <p class="card-text"><small class="text-muted">Click to view details.</small></p>

                        
                        <?php if ($companyapplciationdetails): ?>
                            <?php foreach ($companyapplciationdetails as $companyapplciationdetail): ?>
                            <?php $companyapplciationID = $companyapplciationdetail->getCompanyApplicationID();?>

                            <form class="viewdocumentform" action="./viewdocument.php" method="post">
                              <div class="viewdocumentbutton cursor-pointer card p-2 my-3">
                                <input hidden type="text" name="CompanyApplicationID" value="<?php echo $companyapplciationID;?>"/>

                                <p class="card-text my-1"><?php echo $companyapplciationdetail->getDocumentName(); ?></p>
                                <p class="card-text mb-1">
                                        <small class="text-muted">Verification: 
                                            <?php 
                                                $verification = $companyapplciationdetail->getVerification();
                                        
                                                switch ($verification) {
                                                    case 'Pending':
                                                        echo '<span class="text-warning">'.$verification.'</span>';
                                                        break;
                                                    case 'Verified':
                                                        echo '<span class="text-success">'.$verification.'</span>';
                                                        break;
                                                    case 'Rejected':
                                                        echo '<span class="text-danger">'.$verification.'</span>';
                                                        break;
                                                    default:
                                                        echo '<span>'.$verification.'</span>';
                                                        break;
                                                }
                                            ?>
                                        </small>
                                    </p>
                                    <?php 
                                      $reason = $companyapplciationdetail->getReasonForRejection();
                                      if(isset($reason)){
                                        echo '<p class="card-text mb-1"><small class="text-muted">Reason for Rejection: <span class="text-warning">'.$reason.'</span></small></p>';
                                      }
                                    ?>      
                              </div>
                            </form>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center">Maybe you haven't sent a document?<br>Complete the form on this page to send one.</p>
                        <?php endif; ?>
                        
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
      document.addEventListener("DOMContentLoaded", function() {
          // Get all the forms
          var forms = document.querySelectorAll(".viewdocumentform");
      
          // Loop through each form
          forms.forEach(function(myForm) {
              // Get the submit button within this form
              var submitButton = myForm.querySelector(".viewdocumentbutton");
          
              // Add the event listener to the submit button
              submitButton.addEventListener("click", function(event) {
                  // Prevent the default form submission
                  event.preventDefault();
              
                  // Submit the form
                  myForm.submit();
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
          $('#formAuthentication').on('submit', function(e) {
              e.preventDefault();
          
              var formData = new FormData(this);
          
              $.ajax({
                  type: 'POST',
                  url: '../functions/uploadcompanydocument.php', // replace with your actual login script URL
                  data: formData,
                  dataType: 'json',
                  contentType: false,
                  cache: false,
                  processData:false,
                  success: function(response) {
                      if (response.status === 'success') {
                          // Show the overlay
                          $('.overlay').show();
                          showToast(response.message, 'success');
                          // Wait for the toast to disappear before redirecting
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
