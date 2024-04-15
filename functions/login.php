<?php
if (!isset($_SESSION)) {
    session_start();
} 
else {
    session_destroy();
    session_start();
}

require_once __DIR__ . '/../classes/user.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract form data
    $emailusername = $_POST['email-username'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember-me']) ? true : false;

    $user = new User($conn);
    $logincheck = $user->login($emailusername, $password);

    if (!$logincheck == null) {
        if (filter_var($emailusername, FILTER_VALIDATE_EMAIL)) {
            $userDetails = $user->getUserDetailsUsingEmail($emailusername);
        } else {
            $userDetails = $user->getUserDetails($emailusername);
        }
        

        if ($rememberMe) {
            if (!$user->rememberUser($emailusername)) {
                $response = array('status' => 'error', 'message' => 'Error remembering user, try again.');
                echo json_encode($response);
                exit();
            }
        }

        if ($userDetails) {
            $role = $userDetails->getRole();

            if ($role != null) {
                switch ($role) {
                    case "Admin":
                        $response = array('status' => 'success', 'message' => 'Welcome Admin. You will be redirected shortly.', 'redirect' => './admin/dashboard.php');
                        break;
                    case "Manager":
                        $response = array('status' => 'success', 'message' => 'Welcome Manager. You will be redirected shortly.', 'redirect' => './manager/dashboard.php');
                        break;
                    case "Company":
                        $response = array('status' => 'success', 'message' => 'Welcome Company. You will be redirected shortly.', 'redirect' => './company/dashboard.php');
                        break;
                    case "Job Seeker":
                        $response = array('status' => 'success', 'message' => 'Welcome Job Seeker. You will be redirected shortly.', 'redirect' => './jobseeker/dashboard.php');
                        break;
                    default:
                        $response = array('status' => 'error', 'message' => 'Access denied. Please contact the support team or the managers.');
                        break;
                }
            } else {
                $response = array('status' => 'error', 'message' => 'Error: User details are incomplete. Please contact the support team if the issue persists.');
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Error getting user details. Please contact the support team if the issue persists.');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Login failed. Please contact the support team if the issue persists.');
    }

    echo json_encode($response);
    exit();
}
?>