<?php
if (!isset($_SESSION)) {
    session_start();
}

$pagetitle = "HireMe - Post a Job";
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
  <?php require_once './head.php';?>
  <!-- /Head -->

  <body>
    <!-- Toast Overlay -->
    <div id="toast-container"></div>
    <div class="overlay"></div>
    <!-- / Toast Overlay   -->

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Menu -->
        <?php require_once "./menubar.php";?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
            <?php require_once "./navbar.php";?>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="col-lg-12 mb-4 order-0">
                        <div class="card p-2">
                        <form id="formAuthentication" action="../functions/addjob.php" method="post">
                            <div class="form-group mb-2">
                                <label for="jobTitle">Job Title:</label>
                                <input type="text" class="form-control" id="jobTitle" name="jobTitle" placeholder="Enter job title" required>
                            </div>
                            <div class="form-group mb-2">
                              <label for="jobDescription">Job Description:</label>
                              <textarea class="form-control" id="jobDescription" name="jobDescription" rows="4" placeholder="Enter job description" required></textarea>
                                                  </div>
                                                  <div class="form-group mb-2">
                              <label for="jobType">Job Type:</label>
                              <select class="form-control" id="jobType" name="jobType" required>
                                  <option value="">Select job type</option>
                                  <option value="Full-Time">Full-Time</option>
                                  <option value="Part-Time">Part-Time</option>
                                  <option value="Contract">Contract</option>
                                  <option value="Intern">Intern</option>
                              </select>
                                                  </div>
                                                  <div class="row">
                              <div class="form-group mb-2 col-md-6">
                                  <label for="salaryMin">Minimum Salary:</label>
                                  <input type="number" class="form-control" id="salaryMin" name="salaryMin" placeholder="Enter minimum salary" required>
                              </div>
                              <div class="form-group mb-2 col-md-6">
                                  <label for="salaryMax">Maximum Salary:</label>
                                  <input type="number" class="form-control" id="salaryMax" name="salaryMax" placeholder="Enter maximum salary" required>
                              </div>
                                                  </div>
                                                  <div class="row p-4">
                              <div class="col-md-12">
                                  <div id="slider-range"></div>
                              </div>
                                                  </div>
                                                  <div class="form-group mb-2">
                              <label for="workHours">Work Hours:</label>
                              <input type="text" class="form-control" id="workHours" name="workHours" placeholder="Enter work hours (8AM-5PM)" required>
                                                  </div>
                                                  <div class="form-group mb-2">
                              <label for="jobLocation">Job Location:</label>
                              <input type="text" class="form-control" id="jobLocation" name="jobLocation" placeholder="Enter office address">
                                                  </div>
                                                  <div class="form-group mb-2">
                              <label for="jobLocationType">Job Location Type:</label>
                              <div class="form-check form-check-inline mx-2">
                                  <input class="form-check-input" type="radio" id="wfhRadio" name="jobLocationType" value="WFH" required>
                                  <label class="form-check-label" for="wfhRadio">WFH</label>
                              </div>
                              <div class="form-check form-check-inline mx-2">
                                  <input class="form-check-input" type="radio" id="onsiteRadio" name="jobLocationType" value="On Site" required>
                                  <label class="form-check-label" for="onsiteRadio">On Site</label>
                              </div>
                            </div>
                                    
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
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
    <?php require_once "./endscripts.php";?>

    <script>
      $(function() {
          $("#slider-range").slider({
              range: true,
              min: 0,
              max: 100000, // Maximum of 6 digits
              step: 1000, // Increment by 1000
              values: [0, 100000], // Default values
              slide: function(event, ui) {
                  $("#salaryMin").val(ui.values[0]);
                  $("#salaryMax").val(ui.values[1]);
              }
          });
          $("#salaryMin").val($("#slider-range").slider("values", 0));
          $("#salaryMax").val($("#slider-range").slider("values", 1));
      });
    </script>

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
              // Hide the overlay after the toast has faded out
              $('.overlay').hide();
          }, 2000); // 3000 milliseconds = 3 seconds
      }
      
      $(document).ready(function() {
          $('#formAuthentication').on('submit', function(e) {
              e.preventDefault();
          
              var formData = $(this).serialize();
          
              $.ajax({
                  type: 'POST',
                  url: '../functions/addjob.php', // replace with your actual login script URL
                  data: formData,
                  dataType: 'json',
                  success: function(response) {
                      if (response.status === 'success') {
                          // Show the overlay
                          $('.overlay').show();
                          showToast(response.message, 'success');
                          // Wait for the toast to disappear before redirecting
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
