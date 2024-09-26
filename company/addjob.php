<?php
if (!isset($_SESSION)) {
    session_start();
}

$pagetitle = "HireMe - Post a Job";
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<!-- Head -->
<?php require_once './head.php'; ?>
<!-- /Head -->

<!-- <style>
    .ui-slider {
        background: transparent; /* Set initial background color to transparent */
    }

    .ui-slider .ui-slider-handle {
        background-color: #0d6efd; /* Bootstrap primary blue color */
    }

    .ui-slider-range {
        background-color: #0d6efd; /* Bootstrap primary blue color */
    }
</style> -->

<body>
    <!-- Toast Overlay -->
    <div id="toast-container"></div>
    <div class="overlay"></div>
    <!-- / Toast Overlay   -->

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Menu -->
            <?php require_once "./menubar.php"; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php require_once "./navbar.php"; ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-lg-12 mb-4 order-0">
                                <div class="card p-2">
                                    <form id="formAuthentication" action="../functions/addjob.php" method="post">
                                        <input type="text" id="jobIndustry" name="jobIndustry" value="Hotel and Restaurant Management" hidden>
                                        <input type="number" id="salaryMin" name="salaryMin" value="0" hidden>
                                        <input type="text" name="jobLocationType" value="On Site" hidden>
                                        <div class="form-group mb-2">
                                            <label for="jobTitle">Job Title:</label>
                                            <input type="text" class="form-control" id="jobTitle" name="jobTitle" placeholder="Enter job title" required>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="jobDescription">Job Description:</label>
                                            <textarea id="jobDescription" name="jobDescription" placeholder="Enter Job Description..."></textarea>

                                        </div>
                                        <div class="row">
                                            <div class="form-group mb-2 col-6">
                                                <label for="jobType">Job Type:</label>
                                                <select class="form-control" id="jobType" name="jobType" required>
                                                    <option selected disabled value="">Select job type</option>
                                                    <option value="Full-Time">Full-Time</option>
                                                    <option value="Part-Time">Part-Time</option>
                                                    <option value="Contract">Contract</option>
                                                    <option value="Intern">Intern</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-2 col-6">
                                                <label for="workType">Work Type:</label>
                                                <select class="form-control" id="workType" name="workType" required>
                                                    <option selected disabled value="">Select work type</option>
                                                    <option value="Janitor">Janitor/Cleaner</option>
                                                    <option value="Security Guard">Security Guard</option>
                                                    <option value="Receptionist">Receptionist</option>
                                                    <option value="Mailroom Clerk">Mailroom Clerk</option>
                                                    <option value="Food Service Worker">Food Service Worker</option>
                                                    <option value="Driver">Driver</option>
                                                    <option value="Maintenance Worker">Maintenance Worker</option>
                                                    <option value="others">Others</option>
                                                </select>

                                                <input class="form-control mt-2" type="text" id="otherWorkType" name="otherWorkType" style="display: none;" placeholder="Enter work type...">
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="salaryMin">Minimum Salary:</label>
                                                <input type="number" class="form-control" id="salaryMin" name="salaryMin" placeholder="Enter minimum salary" required>
                                                </div>
                                           
                                            <div class="col-md-6">
                                                <label for="salaryMax">Maximum Salary:</label>
                                                <input type="number" class="form-control" id="salaryMax" name="salaryMax" placeholder="Enter maximum salary" required>
                                         </div>
                                          
                                            <div class="col-md-6">
                                                <label for="workHours">Work Hours:</label>
                                                <input type="text" class="form-control" id="workHours" name="workHours" placeholder="Enter work hours.. example: (8AM-5PM)" required>
                                            </div>
                                        </div>
                                        
                                        <div class="row p-2">
                                            <div class="col-md-6">
                                                <div id="slider-range"></div>
                                            </div>
                                        </div>
                                        <div id="otherIndustryField" class="form-group mb-2" style="display: none;">
                                            <label for="otherIndustry">Other Industry:</label>
                                            <input type="text" class="form-control" id="otherIndustry" name="otherIndustry" placeholder="Enter other industry">
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="jobLocation">Job Location:</label>
                                            <!-- <p class="small"><span class="text-danger">*Required only if choosing "On Site"</span></p> -->
                                            <p class="small"><span class="text-danger">*Antipolo City is automatically added to the address</span></p>
                                            <input type="text" class="form-control" id="jobLocation" name="jobLocation" placeholder="Enter office address">
                                        </div>
                                        <!-- <div class="form-group mb-2">
                                            <label for="jobLocationType">Job Location Type:</label>
                                            <div class="form-check form-check-inline mx-2">
                                                <input class="form-check-input" type="radio" id="wfhRadio" name="jobLocationType" value="WFH" required>
                                                <label class="form-check-label" for="wfhRadio">WFH</label>
                                            </div>
                                            <div class="form-check form-check-inline mx-2">
                                                <input class="form-check-input" type="radio" id="onsiteRadio" name="jobLocationType" value="On Site" required>
                                                <label class="form-check-label" for="onsiteRadio">On Site</label>
                                            </div>
                                        </div> -->
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
    <?php require_once "./endscripts.php"; ?>
    <!-- <script src="../assets/js/quill.js"></script> -->
    <script src="../assets/js/tinymce.js"></script>

    <!-- Salary Slider -->


    <!-- Toast and AJAX -->
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

            const workTypeSelect = document.getElementById('workType');
            const otherWorkTypeInput = document.getElementById('otherWorkType');

            workTypeSelect.addEventListener('change', () => {
                if (workTypeSelect.value === 'others') {
                    otherWorkTypeInput.style.display = 'block';
                    otherWorkTypeInput.required = true;
                } else {
                    otherWorkTypeInput.style.display = 'none';
                    otherWorkTypeInput.required = false;
                }
            });


            $('#formAuthentication').on('submit', function(e) {
                e.preventDefault();

                //   var jobDescription = JSON.stringify(quill.getContents());

                var formData = $(this).serializeArray();

                //   formData.push({ name: 'jobDescription', value: jobDescription });

                $.ajax({
                    type: 'POST',
                    url: '../functions/addjob.php',
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

    <!-- Show text field if industry is not on the list -->
    <script>
        function showTextField() {
            var industrySelect = document.getElementById("jobIndustry");
            var otherIndustryField = document.getElementById("otherIndustryField");

            if (industrySelect.value === "Others") {
                otherIndustryField.style.display = "block";
            } else {
                otherIndustryField.style.display = "none";
            }
        }
    </script>

    <!-- Required job location if "On Site" -->
    <script>
        function requireLocation() {
            var jobLocationType = document.getElementById("onsiteRadio");
            var jobLocation = document.getElementById("jobLocation");

            if (jobLocationType.checked) {
                jobLocation.required = true;
            } else {
                jobLocation.required = false;
            }
        }

        window.onload = function() {
            var onsiteRadio = document.getElementById("onsiteRadio");
            var wfhRadio = document.getElementById("wfhRadio");

            onsiteRadio.addEventListener('change', requireLocation);
            wfhRadio.addEventListener('change', requireLocation);
        }
    </script>

    <!-- Salary Min-Max checker, salarymin cant be greater than salarymax -->
    <script>
        function validateSalaryRange() {
            var minSalary = parseFloat(document.getElementById('salaryMin').value);
            var maxSalary = parseFloat(document.getElementById('salaryMax').value);

            if (minSalary > maxSalary) {
                alert("Minimum salary cannot be greater than maximum salary");
                document.getElementById('salaryMin').value = '';
            }
        }
    </script> 
</body>

</html>