<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once '../classes/user.php';
require_once '../classes/company.php';

$username = $_SESSION['username'];

$user = new User($conn);
$company = new Company($conn);

$userdetails = $user->getUserDetails($username);
$role = $user->getUserDetails($_SESSION['username'])->getRole();

if(!$userdetails == null){
  $userId = $userdetails->getUserID();
  if(!$userId == null){
    $companydetails = $company->getCompanyDetails($userId);
    if(!$companydetails == null){
      $companyname = $companydetails->getCompanyName();
      $companyID = $companydetails->getCompanyID();
    }
    else{
      echo 'Company Name not found.';
    }
  }
  else{
    echo 'UserID not found.';
  }
}
else{
  echo 'User details not found.';
}

$rolecheck = 0;
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
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Menu -->
        <?php 
          if($role == 'Company'){
            require_once __DIR__ . "/menubar.php";
          }else{
            $rolecheck = 1;
            echo '<img src="../assets/img/error1.gif" alt="Error Image">';
          }
        ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <?php require_once __DIR__ . "/navbar.php";?>
          <!-- / Navbar -->
          <?php 
          if($rolecheck == 1){
            echo '<img src="../assets/img/error1.gif" alt="Error Image">';
          }else{
          
        ?>
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <!-- Card -->
                <div class="col-lg-12 mb-2 order-0">
                  <div class="card p-2">
                  <input type="hidden" id="companyID" value="<?= $companyID; ?>">


                    <?php
                    if (!$companydetails == null) {?>

                        <h5 class="card-title">Hello <?php echo ucfirst($companyname);?>!</h5>
                        <p class="card-text">Please pick an option from the side menu to continue working.</p>

                        <!-- <img
                        src="../assets/img/illustrations/dashboard-company.png"
                        height="100%"
                        alt="Company Dashboard Illustration"
                        /> -->
                        <?php
                    }
                    else{?>

                      <h5 class="card-title">Hello <?php echo ucfirst($username);?>!</h5>
                      <p class="card-text">It looks like you have yet to register your company to us. Click "New Application" on the side bar to get started.</p>
                      <?php
                  }?>

                  </div>
                </div>
                <!-- /Card -->

              </div>
              <div class="row">
                <div class="col-lg-6 mb-2 order-1">
                  <div class="card p-3" id="PieChart">
                    <canvas id="JobListingPieChart"></canvas>
                  </div>
                </div>
                <div class="col-lg-6 mb-2 order-2">
                  <div class="card p-3" id="BarChart">
                    <canvas id="JobListingBarChart"></canvas>
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
    <?php } require_once __DIR__ . "/endscripts.php";?>
    <script src="../assets/js/chart.js"></script>
  </body>
</html>
