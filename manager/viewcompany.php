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

$user = new User($conn);
$company = new Company($conn);

$companyDetails = $company->getCompanyDetailsByCompanyID($companyID);
if(!empty($companyDetails)){
    $companyName = $companyDetails->getCompanyName();
    $companyDescription = $companyDetails->getCompanyDescription();
    $companyAddress = $companyDetails->getCompanyAddress();
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
              <h2 class="card-title"><strong>Company Details</strong></h2>
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
                          <p>Interested: <mark>9384</mark></p> <!-- make a function -->
                          <p>Applied: <mark>2243</mark></p> <!-- make a function -->
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="card">
                        <div class="card-body mb-2">
                          <h5 class="card-title">Verification</h5>
                          <button class="btn btn-success">Verify</button> <!-- make a function -->
                          <button class="btn btn-danger">Reject</button> <!-- make a function -->
                        </div>
                        <div>
                          <a href="#documentSection">Click here to go to the Documents Section.</a>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              <section id="jobSection">
                <div class="row mb-4">
                <h2 class="card-title"><strong>Jobs Posted</strong></h2>
                  <div class="card">
                        <div class="card-body">
                          jobs here
                        </div>
                  </div>
                </div>
              </section>
              <section id="documentSection">
                <div class="row mb-4">
                <h2 class="card-title"><strong>Documents Submitted</strong></h2>
                  <div class="card">
                        <div class="card-body">
                          documents here
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

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <?php require_once "./endscripts.php";?>
  </body>
</html>
