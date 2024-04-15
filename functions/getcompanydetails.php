<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once '../classes/user.php';
require_once '../classes/company.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $user = new User($conn);
    $userID = $user->getUserID($username);
    
    if($userID) {
        $company = new Company($conn);
        $companydetails = $company->getCompanyDetails($userID);

        if($companydetails){
            return $companydetails;
        } else {
            $companydetails = false;
            return $companydetails;
        }
    } else {
        echo "Error getting user ID.";
    }
} else {
    echo "Session username is not set.";
}
?>
