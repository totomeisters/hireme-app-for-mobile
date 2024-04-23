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
  if($userId == null){
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
          if($role == 'Job Seeker'){
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
                <div class="col-lg-12 mb-4 order-0">
                  <div class="card p-2">
                    <form class="row g-3" action="../functions/addjobseekerdetails.php" method="post">
                        <input hidden type="text" class="form-control" id="userID" name="userID" value="<?= $userID; ?>">
                      <div class="col-md-6">
                        <label for="firstName" class="form-label">First Name:</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                      </div>
                      <div class="col-md-6">
                        <label for="lastName" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" required>
                      </div>
                      <div class="col-md-6">
                        <label for="birthDate" class="form-label">Birth Date (YYYY-MM-DD):</label>
                        <input type="date" class="form-control" id="birthDate" name="birthDate" required>
                      </div>
                      <div class="col-md-6">
                        <label for="contactNumber" class="form-label">Contact Number:</label>
                        <input type="text" class="form-control" id="contactNumber" name="contactNumber" required maxlength="11" placeholder="Enter 11-digit mobile number">
                      </div>
                      <div class="col-12">
                        <label for="address" class="form-label">Address:</label>
                        <textarea class="form-control" id="address" name="address" rows="4" required></textarea>
                      </div>
                      <div class="col-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                  </div>
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
    <?php } require_once __DIR__ . "/endscripts.php";?>
  </body>
</html>
