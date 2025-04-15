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

<body>
    <!-- Toast Overlay -->
    <div id="toast-container"></div>
    <div class="overlay"></div>
    <!-- / Toast Overlay -->

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
                                        <input type="text" name="jobLocationType" value="On Site" hidden>
                                        
                                        <!-- Job Title -->
                                        <div class="form-group mb-2">
                                            <label for="jobTitle">Job Title:</label>
                                            <input type="text" class="form-control" id="jobTitle" name="jobTitle" placeholder="Enter job title" required>
                                        </div>
                                        
                                        <!-- Job Description -->
                                        <div class="form-group mb-2">
                                            <label for="jobDescription">Job Description:</label>
                                            <textarea id="jobDescription" name="jobDescription" placeholder="Enter Job Description..." class="form-control" ></textarea>
                                        </div>
                                        
                                        <!-- Job Type and Work Type -->
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
                                                    <option value="Chef/Cook">Chef/Cook</option>
                                                    <option value="Housekeeping">Housekeeping</option>
                                                    <option value="Food Service Worker">Food Service Worker</option>
                                                    <option value="Driver">Driver</option>
                                                    <option value="Maintenance Worker">Maintenance Worker</option>
                                                    <option value="others">Others</option>
                                                </select>
                                                <input class="form-control mt-2" type="text" id="otherWorkType" name="otherWorkType" style="display: none;" placeholder="Enter work type..." >
                                            </div>
                                        </div>

                                        <!-- Salary and Work Hours -->
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
                                            
                                            <div class="col-md-6">
                                                <div data-bs-toggle="tooltip" data-bs-placement="top" title="Pick zero for indefinite hiring.">
                                                  <label for="slots">Vacancies:</label>
                                                  <input type="number" class="form-control" id="slots" name="slots" required min="0" step="1">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Skills -->
                                        <div class="row">
                                        <div class="form-group mb-2 col-6">
                                            <label for="skills">Skills:</label>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownSkills" data-bs-toggle="dropdown" aria-expanded="false" >
                                                    Select Skills
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownSkills">
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="skill1" name="skills[]" value="Attention to detail">
                                                            <label class="form-check-label" for="skill1">Attention to detail</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="skill2" name="skills[]" value="Leadership">
                                                            <label class="form-check-label" for="skill2">Leadership</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="skill3" name="skills[]" value="Problem solving">
                                                            <label class="form-check-label" for="skill3">Problem solving</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="skill4" name="skills[]" value="Adaptability">
                                                            <label class="form-check-label" for="skill4">Adaptability</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="skill5" name="skills[]" value="Conflict resolution">
                                                            <label class="form-check-label" for="skill5">Conflict resolution</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="skill6" name="skills[]" value="Customer service">
                                                            <label class="form-check-label" for="skill6">Customer service</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="skill7" name="skills[]" value="Multitasking">
                                                            <label class="form-check-label" for="skill7">Multitasking</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="skill8" name="skills[]" value="Teamwork">
                                                            <label class="form-check-label" for="skill8">Teamwork</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="skill9" name="skills[]" value="Cultural awareness">
                                                            <label class="form-check-label" for="skill9">Cultural awareness</label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Qualifications -->
                                        <div class="form-group mb-2 col-6">
                                            <label for="qualifications">Qualifications:</label>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownQualifications" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Select Qualifications
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownQualifications">
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="qualification1" name="qualifications[]" value="College Graduate">
                                                            <label class="form-check-label" for="qualification1">College Graduate</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="qualification2" name="qualifications[]" value="High School Graduate">
                                                            <label class="form-check-label" for="qualification2">High School Graduate</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="qualification3" name="qualifications[]" value="5 Months Work Experience">
                                                            <label class="form-check-label" for="qualification3">5 Months Work Experience</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="qualification4" name="qualifications[]" value="1 Year Work Experience">
                                                            <label class="form-check-label" for="qualification4">1 Year Work Experience</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="qualification5" name="qualifications[]" value="2 Years Work Experience">
                                                            <label class="form-check-label" for="qualification5">2 Years Work Experience</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="qualification6" name="qualifications[]" value="3 Years Work Experience">
                                                            <label class="form-check-label" for="qualification6">3 Years Work Experience</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="qualification7" name="qualifications[]" value="Certifications">
                                                            <label class="form-check-label" for="qualification7">Certifications</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="qualification8" name="qualifications[]" value="Specialized Training">
                                                            <label class="form-check-label" for="qualification8">Specialized Training</label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        </div>
                                        <!-- Job Location -->
                                        <div class="form-group mb-2">
                                            <label for="jobLocation">Job Location:</label>
                                            <p class="small"><span class="text-danger">*Antipolo City is automatically added to the address</span></p>
                                            <input type="text" class="form-control" id="jobLocation" name="jobLocation" placeholder="Enter office address" required>
                                        </div>
                                        
                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

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
    <?php require_once "./endscripts.php"; ?>
    <script src="../assets/js/tinymce.js"></script>

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
            }, 2000); // 2000 milliseconds = 2 seconds
        }

        $(document).ready(function() {
            // Handle work type selection
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

            // Single form submission handler with validation
            $('#formAuthentication').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                
                // Check if both salary fields have values
                var minSalary = parseFloat($('#salaryMin').val());
                var maxSalary = parseFloat($('#salaryMax').val());

                // Perform salary range validation
                if (!isNaN(minSalary) && !isNaN(maxSalary)) {
                    if (minSalary > maxSalary) {
                        alert("Minimum salary cannot be greater than maximum salary");
                        $('#salaryMin').val('');  // Reset the min salary field
                        return false;  // Stop processing and prevent AJAX submission
                    }
                }

                // If validation passes, proceed with AJAX form submission
                var formData = $(this).serializeArray();

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
                        console.log("Error response:", xhr.responseText);
                        showToast('An error occurred. Please try again.', 'error');
                    }
                });
            });
        });
    </script>
</body>
</html>