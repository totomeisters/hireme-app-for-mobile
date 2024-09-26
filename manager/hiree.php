<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once '../classes/user.php';
require_once '../classes/company.php';
require_once '../classes/hiree.php';

$username = $_SESSION['username'];

$user = new User($conn);
$company = new Company($conn);
$hiree = new Hiree($conn);

$userdetails = $user->getUserDetails($username);

if (!$userdetails == null) {
    $userId = $userdetails->getUserID();
    if (!$userId == null) {
    } else {
        echo 'UserID not found.';
    }
} else {
    echo 'User details not found.';
}

$pagetitle = "HireMe - Hired Applicants";
?>

<!DOCTYPE html>
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free">
<!-- Head -->
<?php require_once __DIR__ . "/head.php"; ?>
<!-- /Head -->

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Menu -->
            <?php require_once __DIR__ . "/menubar.php"; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php require_once __DIR__ . "/navbar.php"; ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <!-- Card -->
                            <div class="col-lg-12 mb-4 order-0">
                                <h3>Hired Applicants</h3>
                                <table id="hireeTable" class="table table-hover" style="width:100%">
                                    <?php if ($hireeDetails = $hiree->getAllHirees()) { ?>
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Job Title</th>
                                                <th>Company Name</th>
                                                <th>Date Hired</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($hireeDetails as $hireeDetail) {?>
                                                <tr>
                                                    <td><?= $hireeDetail->getFullName(); ?></td>
                                                    <td><?= $hireeDetail->getJobName(); ?></td>
                                                    <td><?= $hireeDetail->getCompanyName(); ?></td>
                                                    <td><?= $hireeDetail->getDateHired(); ?></td>
                                                    <td>
                                                        <form action="./viewhiree.php" method="post">
                                                            <input type="text" value="<?= $hireeDetail->getUserID() ?>" name="userID" hidden>
                                                            <input type="text" value="<?= $hireeDetail->getJobID() ?>" name="jobID" hidden>
                                                            <input type="text" value="<?= $hireeDetail->getApplicationID() ?>" name="applicationID" hidden>
                                                            <input type="text" value="<?= $hireeDetail->getHireeID() ?>" name="hireeID" hidden>
                                                            <button type="submit" class="btn btn-primary">View</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    <?php } else {
                                        echo "No records found.";
                                    } ?>
                                </table>
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
    <?php require_once __DIR__ . "/endscripts.php"; ?>
    <script>
        $(document).ready(function() {
            $('#hireeTable').DataTable({
                order: [
                    [0, 'asc']
                ]
            });
        });
    </script>
</body>

</html>