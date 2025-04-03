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

if(!$userdetails == null){
  $userId = $userdetails->getUserID();
  if(!$userId == null){
  }
  else{
    echo 'UserID not found.';
  }
}
else{
  echo 'User details not found.';
}

$pagetitle = "HireMe - Unverified Companies";
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
                <div class="col-lg-12 mb-4 order-0">
                    <h3>Unverified Companies</h3>
                    <div class="card p-2">
                <?php
                    $unVerifiedCompanies = $company->getAllUnverifiedCompanies();
                    if (!empty($unVerifiedCompanies)) {
                ?>
                        <table id="unverified" class="table table-hover table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="col-1" scope="col">#</th>
                                    <th class="col-2" scope="col">Company Name</th>
                                    <th class="col-5" scope="col">Address</th>
                                    <th class="col-2" scope="col">Verification Status</th>
                                    <th class="col-2" scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; 
                                    foreach ($unVerifiedCompanies as $unVerifiedCompany) { ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo $unVerifiedCompany->getCompanyName(); ?></td>
                                        <td><?php echo $unVerifiedCompany->getCompanyAddress(); ?></td>
                                        <td><?php echo $unVerifiedCompany->getVerificationStatus(); ?></td>
                                        <td>
                                            <form action="./viewcompany.php" method="post">
                                              <input type="hidden" name="companyID" value="<?= $unVerifiedCompany->getCompanyID(); ?>">
                                              <button type="submit" class="btn btn-warning">View Details</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                <?php
                    } else {
                        echo '<p class="p-3 pb-1">No Unverified Companies.</p>';
                    }
                ?>              
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
    <?php require_once __DIR__ . "/endscripts.php";?>
    <script>
      new DataTable('#unverified');
    </script>
  </body>
</html>
