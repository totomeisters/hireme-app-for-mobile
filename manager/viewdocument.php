<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__.'/../classes/companyapplication.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $companyapplicationID = $_POST['CompanyApplicationID'];
    $companyID = $_POST['companyID'];

}
else{
    echo "How'd you get in here?";
    exit();
    header("Location: ./dashboard.php");
}

$companyapplication = new CompanyApplication($conn);
$companyapplicationdetails = $companyapplication->getCompanyApplicationDetailsByID($companyapplicationID);

$pagetitle = "HireMe - Company Documents";
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
                    <div class="col-lg-8 order-0 my-1">
                        <div class="card p-2 my-1 col">
                        <?php
                            if ($companyapplicationdetails){
                                foreach ($companyapplicationdetails as $companyapplicationdetail){
                                    // Assuming you have a file path in a variable
                                    $filePath = $companyapplicationdetail->getDocumentFilePath(); // Replace with your actual file path
                                    $fileName = $companyapplicationdetail->getDocumentName();

                                    // Get the file extension
                                    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                                    // Check the file extension and generate the HTML accordingly
                                    switch ($fileExtension) {
                                        case 'pdf':
                                            // Display PDF file using an object tag
                                            echo '<object data="' . $filePath . '" type="application/pdf" width="100%" height="600px">';
                                            echo '<p>It appears you don\'t have a PDF plugin for this browser.</p>';
                                            echo 'Click here to download the PDF file: <a href="' . $filePath . '">'.$fileName.'</a>';
                                            echo '</object>';

                                            break;
                                        case 'jpg':
                                        case 'jpeg':
                                        case 'png':
                                            // Display image file using an img tag
                                            echo '<img src="' . $filePath . '" alt="'.$fileName.'" style="height: 80vh; object-fit: scale-down; 
                                                    class="img-fluid bg-secondary rounded border border-dark">';
                                            break;
                                        default:
                                            // Handle other file types or unknown extensions
                                            echo "Unsupported file type.";
                                            break;
                                    }
                                }
                            }
                            else{
                                echo "Error getting document details.";
                            }
                        ?>
                        </div>
                    </div>
                    <div class="col-lg-4 order-1 my-1">
                        <div class="row card mb-2 py-3 px-2">
                        <?php
                            if ($companyapplicationdetails){
                                foreach ($companyapplicationdetails as $companyapplicationdetail){
                                    $documentname = $companyapplicationdetail->getDocumentName();
                                    $documenttype = $companyapplicationdetail->getDocumentFilePath();
                                        $fileextension = pathinfo($documenttype, PATHINFO_EXTENSION);
                                    $verificationstatus = $companyapplicationdetail->getVerification();
                                    $rejectionreason = $companyapplicationdetail->getReasonForRejection();
                                    $dateposted = $companyapplicationdetail->getDate();

                                    echo "<strong><p>Name: </strong>". $documentname.'</p>';
                                    echo "<strong><p>Date Posted: </strong>". $dateposted.'</p>';
                                    echo "<strong><p>File Type: </strong>". strtoupper($fileextension).'</p>';
                                    echo "<strong><p>Verification Status: </strong>". $verificationstatus.'</p>';
                                    if(isset($rejectionreason)){
                                        echo "<strong><p>Reason for Rejection: </strong>". $rejectionreason.'</p>';
                                    }
                                }
                            }
                            else{
                                echo "Error getting document details.";
                            }
                        ?>
                        </div>
                        <div class="row card mb-2 py-3 px-2">
                              <h5 class="card-title">Verification</h5>
                              <button class="btn btn-success mb-2">Verify</button> <!-- make a function -->
                              <button class="btn btn-danger">Reject</button> <!-- make a function -->
                        </div>
                        <div class="row card py-3">
                          <form action="./viewcompany.php" method="post">
                            <input type="hidden" name="companyID" value="<?= $companyID; ?>">
                            <button type="submit" class="btn btn-secondary">Go Back</button>
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
              max: 100000, // Maximum of 10 digits
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
  </body>
</html>
