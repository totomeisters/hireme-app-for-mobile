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

    <title>HireMe - Register</title>

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
          <!-- Register Card -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                  <img src="hireme_logo1.png" alt="HireMeLogo" style="max-height: 150px;">
              </div>
               <div class="app-brand justify-content-center">
                <span class="logotext text-black">HireMe-App</span>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">Welcome to HireMe-App! 🚀</h4>
              <p class="mb-4">Job hunting has never been this easy!</p>

              <form id="formAuthentication" class="mb-3" action="./functions/register.php" method="POST">
                <div class="mb-3">
                <div id="managerRoleMessage" class="small mb-2"><span class="text-danger">*Manager accounts are not granted access immediately. Approval from system admins are needed.</span></div>
                <label for="role" class="form-label">What is your role?</label>
                  <select class="form-control" id="role" name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="Company">Company</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input
                    type="text"
                    class="form-control"
                    id="username"
                    name="username"
                    placeholder="Enter your username"
                    autofocus
                    required
                  />
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" required/>
                </div>
                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="password">Password</label>
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

<!-- Popup HTML -->
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="hidePopup()">&times;</span>
        <h2>Privacy Policy</h2>
        <p><strong>Effective Date:</strong> September 1, 2024</p>
        <p><strong>1. Overview</strong><br>HireMe-App values your privacy. This policy details how we collect, use, and protect your information.</p>
        <p><strong>2. Information We Collect</strong><br>- Personal Info: Email, username, password, phone number.<br>- Job & Company Info: Details related to job openings and companies.</p>
        <p><strong>3. How We Use Your Data</strong><br>- To provide and improve our services.<br>- To communicate with you about updates and job-related matters.</p>
        <p><strong>4. Data Sharing</strong><br>We don’t sell your info. We may share it with service providers or as required by law.</p>
        <p><strong>5. Security</strong><br>We use reasonable measures to protect your data but cannot guarantee complete security.</p>
        <p><strong>6. Your Rights</strong><br>You can access, correct, or request deletion of your personal info by contacting us.</p>
        <p><strong>7. Changes</strong><br>We may update this policy and will post changes on our website.</p>
        <p><strong>8. Contact</strong><br>For questions, email us at hiremeapp722@gmail.com.</p>
        
        <h2>Terms of Service</h2>
        <p><strong>Effective Date:</strong> September 1, 2024</p>
        <p><strong>1. Agreement</strong><br>By using HireMe-App, you agree to these terms. If you disagree, do not use our services.</p>
        <p><strong>2. User Responsibilities</strong><br>- Keep your account details secure.<br>- Don’t use the site for illegal activities.</p>
        <p><strong>3. Intellectual Property</strong><br>All content on our site is owned by us or our partners.</p>
        <p><strong>4. Liability</strong><br>We aren’t liable for any damages arising from your use of our site.</p>
        <p><strong>5. Termination</strong><br>We can suspend or terminate your account if you breach these terms.</p>
        <p><strong>6. Changes</strong><br>We may update these terms, and your continued use means you accept the changes.</p>
        <p><strong>7. Contact</strong><br>For any questions, email us at hiremeapp722@gmail.com.</p>
    </div>
</div>
<style>
    .popup {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }
    .popup-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 80%;
        max-width: 600px;
        max-height: 80vh; /* Limit the height of the popup content */
        overflow-y: auto; /* Enable vertical scrolling */
    }
    .popup-content h2 {
        margin-top: 0;
    }
    .close {
        display: block;
        text-align: right;
        cursor: pointer;
        color: #888;
    }
    .close:hover {
        color: #000;
    }
</style>

<script>
    function showPopup() {
        document.getElementById('popup').style.display = 'flex';
    }

    function hidePopup() {
        document.getElementById('popup').style.display = 'none';
    }

    document.querySelector('a[href="javascript:void(0);"]').addEventListener('click', showPopup);
</script>


<div class="mb-3">
  <div class="form-check">
    <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" required/>
    <label class="form-check-label" for="terms-conditions">
      I agree to
<a href="javascript:void(0);" onclick="showPopup()">Privacy Policy & Terms</a>
    </label>
  </div>
</div>

                <button class="btn btn-warning d-grid w-100">Sign up</button>
              </form>

              <p class="text-center">
                <span>Already have an account?</span>
                <a href="./login.php">
                  <span>Sign in instead</span>
                </a>
              </p>
            </div>
          </div>
          <!-- Register Card -->
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
                  url: './functions/register.php',
                  data: formData,
                  dataType: 'json',
                  success: function(response) {
                      if (response.status === 'success') {
                          $('.overlay').show();
                          showToast(response.message, 'success');// wait for the toast to disappear before redirecting
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

          var roleSelect = document.getElementById("role");
          var managerRoleMessage = document.getElementById("managerRoleMessage");

          managerRoleMessage.style.display = "none";

          roleSelect.addEventListener("change", function() {
              if (roleSelect.value === "Manager") {
                  managerRoleMessage.style.display = "block";
              } else {
                  managerRoleMessage.style.display = "none";
              }
          }); 
      });
    </script>
  </body>
</html>
