<?php
if (!isset($_SESSION)) {
    session_start();
}

if(isset($_GET['token'])){
  if(strlen($_GET['token']) !== 16){

      $error = 'Oops.. your token was invalid.';
  }
  else{
    $token = $_GET['token'];
    $error = 0;
  }
}
else{
  $error = 1;
}

?>

<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="./assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>HireMe - Reset Password</title>

    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="./assets/img/favicon/favicon.ico" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
      <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="./assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="./assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="./assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="./assets/css/demo.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="./assets/vendor/css/pages/page-auth.css" />
    <script src="./assets/vendor/js/helpers.js"></script>
    <script src="./assets/js/config.js"></script>
    <link rel="stylesheet" href="./assets/css/toast.css">
  </head>
  <body>
  <div id="toast-container"></div>
  <div class="overlay"></div>
    <!-- Content -->
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Forgot Password -->
          <?php if($error == 1 || isset($_GET['token'])){ ?>
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                  <img src="./assets/img/favicon/android-chrome-512x512.png" alt="HireMeLogo" style="max-height: 80px;">
              </div>
               <div class="app-brand justify-content-center">
                <span class="logotext text-black">HireMe-App</span>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">Ready to change your password? 🔒</h4>
              <p class="mb-4">Please enter your <?php if($error == 1) {echo 'verification code and ';} ?> new password.</p>
                <form id="formAuthentication" action="./functions/changepassword.php" method="post">

                <?php if(isset($_GET['token'])){ ?>
                    <input type="hidden" name="token" value="<?= $token ?>" class="input-group input-group-merge">
                <?php } elseif($error == 1){ ?>
                  <label for="token" class="form-label">Verification Code</label>
                  <div class="input-group mb-3">                    
                    <input type="text" name="token" value="" placeholder="Enter code here..." class="form-control" required>
                  </div>
                    
                <?php } ?>
                    <div class="mb-3 form-password-toggle">
                        <label for="password">Password:</label>
                        <div class="input-group input-group-merge">
                            <input
                              type="password"
                              id="password"
                              class="form-control"
                              name="password"
                              placeholder="New Password"
                              aria-describedby="password"
                              autofocus
                            />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>
                    <div class="mb-3 form-password-toggle">
                        <label for="confirmPassword">Confirm Password:</label>
                        <div class="input-group input-group-merge">
                            <input
                              type="password"
                              id="confirmPassword"
                              class="form-control"
                              name="confirmPassword"
                              placeholder="Confirm New Password"
                              aria-describedby="confirm password"
                              required
                            />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>
                    <div id="errorDiv" class="text-danger small"></div>
                    <button class="btn btn-primary d-grid w-100 mt-4">Submit</button>
                </form>
              <div class="text-center mt-3">
                <a href="./login.php" class="d-flex align-items-center justify-content-center">
                  <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                  Back to login
                </a>
              </div>
            </div>
          </div>
          <?php } else { ?>
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title">
                                <h3>
                                    <strong>
                                        <?= $error ?>
                                    </strong>
                                </h3>
                            </p>
                        </div>
                    </div>
                    <div class="card card-body text-center mt-3">
                        <a href="./login.php" class="d-flex align-items-center justify-content-center">
                          <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                          Back to login
                        </a>
                    </div>
          <?php } ?>
          <!-- /Forgot Password -->
        </div>
      </div>
    </div>
    <!-- / Content -->

    <script src="./assets/vendor/libs/jquery/jquery.js"></script>
    <script src="./assets/vendor/libs/popper/popper.js"></script>
    <script src="./assets/vendor/js/bootstrap.js"></script>
    <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="./assets/vendor/js/menu.js"></script>
    <script src="./assets/js/main.js"></script>

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
              $('.overlay').hide();
          }, 2000);
      }
      
      $(document).ready(function() {
          $('#formAuthentication').on('submit', function(e) {
              e.preventDefault();
          
              var formData = $(this).serialize();
          
              $.ajax({
                  type: 'POST',
                  url: './functions/changepassword.php',
                  data: formData,
                  dataType: 'json',
                  success: function(response) {
                      if (response.status === 'success') {
                          $('.overlay').show();
                          showToast(response.message, 'success');
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

        function checkPasswords() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;

            if (password !== confirmPassword) {
                displayError("*Passwords do not match.");
                return false;
            }
            return true;
        }

        function displayError(message) {
            var errorDiv = document.getElementById("errorDiv");
            errorDiv.textContent = message;
        }

        document.getElementById("confirmPassword").addEventListener("blur", checkPasswords);

        // Event listener for form submission
        document.querySelector("form").addEventListener("submit", function(event) {
            if (!checkPasswords()) {
                event.preventDefault();
            }
        });
      });
    </script>
  </body>
</html>
