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
  if (!$userId == null) {
    $companydetails = $company->getCompanyDetails($userId);
    if (!$companydetails == null) {
      $companyname = $companydetails->getCompanyName();
      $companyID = $companydetails->getCompanyID();
      $companyProfile = $company->getCompanyProfile($companyID);
    } else {
      echo 'Company Name not found.';
    }
  } else {
    echo 'UserID not found.';
  }
} else {
  echo 'User details not found.';
}

$rolecheck = 0;
$pagetitle = "HireMe - Profile";
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
      if ($role == 'Company') {
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
                <div class="col-lg-12 mb-2 order-0">
                  <?php
                  if ($companyProfile !== null) {
                    echo '<div class="card">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title"><span class="fw-bold">' . $companyProfile->getName() . '</span></h5>';
                    echo '<p class="card-text"><span class="fw-bold">Company Address: </span>' . $companyProfile->getAddress() . '</p>';
                    echo '<p class="card-text"><span class="fw-bold">Company Contact Number: </span>' . $companyProfile->getContactNumber() . '</p>';
                    echo '<p class="card-text"><span class="fw-bold">Company Email: </span>' . $companyProfile->getEmail() . '</p>';
                    echo '<p class="card-text"><span class="fw-bold">Representative\'s Name: </span>' . $companyProfile->getRepName() . '</p>';
                    echo '<p class="card-text"><span class="fw-bold">Representative\'s Position: </span>' . $companyProfile->getRepPosition() . '</p>';
                    echo '<p class="card-text"><span class="fw-bold">Representative\'s Number: </span>' . $companyProfile->getRepNumber() . '</p>';
                    echo '</div>';
                    echo '</div>';
                  } else {
                    echo '<p>No company profile found.</p>';
                  }
                  ?>
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
<?php }
        require_once __DIR__ . "/endscripts.php"; ?>
<script src="../assets/js/chart.js"></script>
</body>

</html>