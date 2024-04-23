<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['companyID'])){
      $companyID = $_POST['companyID'];
    }
    else{
      $companyID = 0;
    }
}

require_once '../classes/user.php';
require_once '../classes/company.php';
require_once '../classes/job.php';
require_once __DIR__.'/../classes/companyapplication.php';

$user = new User($conn);
$company = new Company($conn);
$job = new Job($conn);
$companyapplication = new CompanyApplication($conn);

$companyapplciationdetails = $companyapplication->getCompanyApplicationDetails($companyID);
$companyDetails = $company->getCompanyDetailsByCompanyID($companyID);
if(!empty($companyDetails)){
    $companyName = $companyDetails->getCompanyName();
    $companyDescription = $companyDetails->getCompanyDescription();
    $companyAddress = $companyDetails->getCompanyAddress();
    $companyStatus = $companyDetails->getVerificationStatus();
}
else{
    echo 'How did you get here?';
}

$pagetitle = "HireMe - View: ".$companyName;
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
              <div class="row mb-4">
              <section id="CompanyDetails"><h2 class="card-title"><strong>Company Details</strong></h2></section>
                <div class="col-md-8">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-2 order-1 align-content-around" style="max-width: 200px; object-fit: scale-down">
                            <img src="../assets/img/heejin.jpg" class="img-fluid rounded mx-auto mb-5" alt="<?= $companyName ?>"> 
                            <!-- will add image upload function for first company application so it can be used here -->
                          </div>
                          <div class="col-md-10 order-0">
                            <h4 class="card-title"><strong><?= ucfirst($companyName); ?></strong></h4>
                            <p class="text-muted"><small><?= ucfirst($companyAddress); ?></small></p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <p><?= ucfirst($companyDescription); ?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="row mb-2">
                    <div class="card">
                        <div class="card-body">
                          <h5 class="card-title">People</h5>
                          <?php 
                            $faveCounts = 0; 
                            $applicantCounts = 0;
                            $jobdetails = $job->getAllJobs($companyID);

                            foreach ($jobdetails as $jobdetail) {
                                $faveCounts += $job->getFaveJobsCountByJobID($jobdetail->getJobID());
                                $applicantCounts += $job->getApplicantsCountByJobID($jobdetail->getJobID());
                            }
                          ?>
                          <p>Interested: <mark><?= $faveCounts; ?></mark></p> <!-- make a function -->
                          <p>Applied: <mark><?= $applicantCounts; ?></mark></p> <!-- make a function -->
                        </div>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title">Verification</h5>

                        <?php
                          if ($companyStatus == 'Pending') {
                        ?>
                          <form id="updateapplication" method="post" action="../functions/updateapplicationstatus.php">
                              <input type="hidden" name="companyID" value="<?= $companyID; ?>">
                              <input type="hidden" name="status" name="applicationID" value="">
                              <button class="btn btn-success" type="submit" name="status" value="Verified">Verify</button>
                              <button class="btn btn-danger" type="submit" name="status" value="Rejected">Reject</button>
                          </form>
                        <?php
                          } else {
                            echo 'This company is already <span class="' . ($companyStatus == 'Verified' ? 'text-success' : 'text-danger') . '">' . strtoupper($companyStatus) . '</span>.';
                          }
                        ?>

                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="card">
                      <a href="#documentSection">
                      <div class="card-body">
                        <div>
                          Click here to go to the Documents Section.
                        </div>
                      </div>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <section id="jobSection">
                <div class="row mb-4">
                <h2 class="card-title"><strong>Jobs Posted</strong></h2>
                  <div class="card">
                        <div class="card-body">
                        <?php if ($jobdetails): ?>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="col-3">Job Title</th>
                        <th class="col-2 d-none d-md-table-cell">Job Type</th>
                        <th class="col-3 d-none d-md-table-cell">Posting Date</th>
                        <th class="col-1 d-none d-md-table-cell">Verification Status</th>
                        <th class="col-1 d-none d-md-table-cell">Interested</th>
                        <th class="col-1 d-none d-md-table-cell">Applicants</th>
                        <th class="col-1">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobdetails as $jobdetail): ?>
                        <tr>
                            <td><?= ucfirst($jobdetail->getJobTitle()); ?></td>
                            <td class="d-none d-md-table-cell"><?= $jobdetail->getJobType(); ?></td>
                            <td class="d-none d-md-table-cell"><?= $jobdetail->getPostingDate(); ?></td>
                            <td class="d-none d-md-table-cell"><?= $jobdetail->getVerificationStatus(); ?></td>
                            <td class="d-none d-md-table-cell"><?= $job->getApplicantsCountByJobID($jobdetail->getJobID()); ?></td>
                            <td class="d-none d-md-table-cell"><?= $job->getFaveJobsCountByJobID($jobdetail->getJobID()); ?></td>
                            <td>
                            <form method="post" action="./viewjob.php">
                                <input type="hidden" name="jobID" value="<?= $jobdetail->getJobID(); ?>">
                                <input type="hidden" name="companyID" value="<?= $companyID; ?>">
                                <button class="btn btn-secondary" type="submit">View</button>
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
                  </div>
                </div>
              </section>
              <section id="documentSection">
                <div class="row mb-2">
                  <div class="col-md-11"><h2 class="card-title"><strong>Documents Submitted</strong></h2></div>
                  <div class="col-md-1">
                    <a href="#CompanyDetails">
                        <button class="btn btn-primary"><i class='bx bx-chevrons-up'></i></button>
                    </a>
                  </div>
                </div>
                  
                <div class="row">
                  <div class="card">
                    <div class="card-body">
                        <?php if ($companyapplciationdetails): ?>
                          <table class="table table-hover table-bordered">
                              <thead>
                                  <tr>
                                      <th class="col-7">Document Name</th>
                                      <th class="col-1 d-none d-md-table-cell">Document Type</th>
                                      <th class="col-3 d-none d-md-table-cell">Posting Date</th>
                                      <th class="col-1 d-none d-md-table-cell">Verification Status</th>
                                      <th class="col-1">Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($companyapplciationdetails as $companyapplciationdetail): ?>
                                      <tr>
                                          <td><?= ucfirst($companyapplciationdetail->getDocumentName()); ?></td>
                                          <td class="d-none d-md-table-cell"><?= strtoupper(pathinfo($companyapplciationdetail->getDocumentFilePath(), PATHINFO_EXTENSION)); ?></td>
                                          <td class="d-none d-md-table-cell"><?= $companyapplciationdetail->getDate(); ?></td>
                                          <td class="d-none d-md-table-cell"><?= $companyapplciationdetail->getVerification(); ?></td>
                                          <td>
                                            <form action="./viewdocument.php" method="post">
                                                <input hidden type="text" name="CompanyApplicationID" value="<?= $companyapplciationdetail->getCompanyApplicationID(); ?>"/>
                                                <input hidden type="text" name="companyID" value="<?= $companyID; ?>"/>
                                                <button class="btn btn-secondary" type="submit">View</button>
                                            </form>
                                          </td>
                                      </tr>
                                  <?php endforeach; ?>
                              </tbody>
                          </table>
                        <?php else: ?>
                            <p class="text-center">Maybe they have yet to submit a document?</p>
                        <?php endif; ?>
                    </div>
                  </div>
                </div>
              </section>

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

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <?php require_once "./endscripts.php";?>

    <script>
      function handleFormSubmission(formData) {
          $.ajax({
              type: 'POST',
              url: '../functions/updatecompanystatus.php',
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
