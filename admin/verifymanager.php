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
$pagetitle = "HireMe - Verify Managers";
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
          if($role == 'Admin'){
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
                  <?php

                    $userDetailsArray = $user->getAllFutureManager();

                    if ($userDetailsArray) {
                        echo "<table class='table table-bordered table-hover'>";
                        echo "<tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Action</th>
                              </tr>";

                        foreach ($userDetailsArray as $userDetails) {
                            $number =+ 1; 
                            echo "<tr>";
                            echo "<td>" . $number . "</td>";
                            echo "<td>" . $userDetails->getUsername() . "</td>";
                            echo "<td>" . $userDetails->getEmail() . "</td>";
                            echo "<td>
                                    <form id='verifyManager".$userDetails->getUserID()."' class='verify-form' action='../functions/verifymanager.php' method='post'>
                                        <input type='text' name='userID' value=".$userDetails->getUserID()." hidden/>
                                        <button type='button' class='btn btn-success verify-btn'>Verify</button>
                                    </form>
                                 </td>";
                            echo "</tr>";
                        }
                    
                        echo "</table>";
                    } else {
                        echo "No users found.";
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
    <?php } require_once __DIR__ . "/endscripts.php";?>

    <script>
        $(document).ready(function() {
        $('.verify-btn').click(function() {
            var form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to verify this manager. This action cannot be undone.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, verify',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: form.attr('action'),
                        data: form.serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                $('.overlay').show();
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 1600,
                                    timerProgressBar: true,
                                }).then((result) => {
                                    if (result.dismiss === Swal.DismissReason.timer || result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else if (response.status === 'error') {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'warning'
                                });
                            } else {
                                console.error('Unknown response status:', response.status);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred. Please try again.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
        });
    </script>

  </body>
</html>
