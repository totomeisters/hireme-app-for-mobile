<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once '../classes/user.php';
require_once '../classes/company.php';

$username = $_SESSION['username'];

$user = new User($conn);
$company = new Company($conn);

$userId = $user->getUserDetails($username)->getUserID();
$companyID = $company->getCompanyDetails($userId)->getCompanyID();

$pagetitle = "HireMe - Interviews";
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
  <input type="hidden" id="companyID" value="<?= $companyID; ?>">
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
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <!-- Card -->
                <div class="col-md-8 col-sm-8 mb-4 order-0">
                    <div id='calendar' class="card p-4"></div>
                </div>
                <div class="col-md-4 col-sm-4 mb-4 order-1">
                    <h4>Event Details</h4>
                    <div id='card'>Select an event on the calendar to view the details.</div>
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

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <?php require_once __DIR__ . "/endscripts.php";?>
    <script src="../assets/js/calendar.js"></script>
  </body>
</html>
