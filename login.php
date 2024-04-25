<?php
if (!isset($_SESSION)) {
    session_start();
}
else {
  session_destroy();
  session_start();
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

    <title>HireMe - Login</title>

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
          <!-- Register -->
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
              <h4 class="mb-2">Welcome to back! 👋</h4>
              <p class="mb-4">Please sign-in to your account and continue the adventure!</p>

              <form id="formAuthentication" class="mb-3" action="./functions/login.php" method="POST">
                <div class="mb-3">
                  <label for="email" class="form-label">Email or Username</label>
                  <input
                    type="text"
                    class="form-control"
                    id="email"
                    name="email-username"
                    placeholder="Enter your email or username"
                    autofocus
                  />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                    <a href="./forgot-password.php">
                      <small>Forgot Password?</small>
                    </a>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                      required
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                </div>
                <div class="mb-3">
                  <button class="btn btn-success d-grid w-100" type="submit">Sign in</button>
                </div>
              </form>

              <p class="text-center">
                <span>New on our platform?</span>
                <a href="./register.php">
                  <span>Create an account</span>
                </a>
              </p>
            </div>
          </div>
          <!-- /Register -->
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
                  url: './functions/login.php',
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
      });
    </script>
  </body>
</html>
