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
$pagetitle = "HireMe - Registration";
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<!-- Head -->
<?php require_once __DIR__ . "/head.php"; ?>
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
                                <div class="col-lg-12 mb-4 order-0">
                                    <div class="card p-2">
                                        <form id="formAuthentication">
                                            <input type="text" name="companyID" id="companyID" value="<?= $companydetails->getCompanyID(); ?>" hidden />
                                            <input type="text" name="type" id="type" value="profile" hidden />

                                            <div class="col-md-6">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter company email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
                                                <button type="button" class="btn btn-primary mt-2" id="checkEmailBtn" disabled>Verify Email</button>
                                            </div>
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
<?php }
                require_once __DIR__ . "/endscripts.php"; ?>
<script src="../assets/js/chart.js"></script>
<script>
    getotp.init();

document.getElementById("email").addEventListener("input", function () {
    const emailValue = this.value.trim();
    const verifyBtn = document.getElementById("checkEmailBtn");
    verifyBtn.disabled = emailValue === "";
});

document.getElementById('checkEmailBtn').addEventListener('click', function (e) {
    e.preventDefault();
    const email = document.getElementById('email').value;

    fetch('../functions/otp.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `email=${encodeURIComponent(email)}`
      })
      .then(res => res.json())
      .then(response => {
        if (response.otp_id) {
            const otpId = response.otp_id;
        }
        if (response.link) {
          window.open(response.link, '_self');
        } else {
          alert('OTP request failed.');
        }
      })
      .catch(err => alert('Error: ' + err));
  });

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
</script>
</body>

</html>