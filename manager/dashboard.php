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

if (!$userdetails == null) {
  $userId = $userdetails->getUserID();
  if ($userId == null) {
    echo 'UserID not found.';
  }
} else {
  echo 'User details not found.';
}

$rolecheck = 0;
$pagetitle = "HireMe - Dashboard";
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<!-- Head -->
<?php require_once __DIR__ . "/head.php"; ?>
<!-- /Head -->

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

      <!-- Menu -->
      <?php
      if ($role == 'Manager') {
        require_once __DIR__ . "/menubar.php";
      } else {
        $rolecheck = 1;
        echo '<img src="../assets/img/error1.gif" alt="Error Image">';
      }
      ?>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <?php require_once __DIR__ . "/navbar.php"; ?>
        <!-- / Navbar -->
        <?php
        if ($rolecheck == 1) {
          echo '<img src="../assets/img/error1.gif" alt="Error Image">';
        } else {

        ?>
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <!-- Card -->
                <div class="col-lg-12 mb-4 order-0">
                  <div class="card p-2">
                    <p class="m-0">Hello, <span style="font-weight: bold;"><?php echo ucfirst($_SESSION['username']) ?>!</span> 
                      Your streamlined workspace for managing companies and applicants starts here.
                    </p>
                  </div>
                </div>
                <div class="col-lg-12 mb-4 order-0">
                  <div class="row" id="ChartDiv">
                    <div class="col-lg-6 mb-2 order-1">
                      <div class="card p-3">
                        <canvas id="CompanyListingPieChart"></canvas>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-2 order-2">
                      <div class="card p-3">
                        <canvas id="ApplicationsBarChart"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
                
                <style>
                    table, th, td {
                    border:1px solid black;
                    }
                </style>

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

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- / Layout wrapper -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
<?php }
        require_once __DIR__ . "/endscripts.php"; ?>
  <script src="../assets/js/managerchart.js"></script>
</body>

</html>