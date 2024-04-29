<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/user.php';

$user = new User($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $userIDErr = 0;

    if(empty($userID)){
        $userIDErr = 1;
        $response = array('status' => 'error', 'message' => 'Oops.. I think there was a problem. Please try again.', 'redirect' => '');
    }

    if($userIDErr == 0) {
        if($user->convertUserToManagerByUserID($userID)) {
            $response = array('status' => 'success', 'message' => 'Account successfully verified.', 'redirect' => '');
        } else {
            $response = array('status' => 'error', 'message' => 'Oops.. failed to update account.', 'redirect' => '');
        }
    }

} else {
    $response = array('status' => 'error', 'message' => 'Invalid request method.', 'redirect' => '');
}

echo json_encode($response);
?>
