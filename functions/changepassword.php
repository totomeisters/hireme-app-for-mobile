<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/user.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($conn);

    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $token = $_POST['token'];

    if (empty($password) || empty($confirmPassword) || empty($token)) {
        $response = array('status' => 'error', 'message' => 'W-wait.. your response was blank? Please try again.', 'redirect' => './resetpassword.php?token='.$token);
    } else {
        if($password === $confirmPassword){
            $resetpassword = $user->changePasswordByToken($password, $token);

            if($resetpassword){
                $response = array('status' => 'success', 'message' => 'Password changed! You can now use it to log in.', 'redirect' => './login.php');
            }
            else{
                $response = array('status' => 'error', 'message' => 'Sorry there was an error.', 'redirect' => './resetpassword.php?token='.$token);
            }
        }
        else{
            $response = array('status' => 'error', 'message' => 'W-wait.. your passwords do not match? Sorry, but I will redirect you to a new page.', 'redirect' => './resetpassword.php?token='.$token);

        }
    }

    echo json_encode($response);
    exit();
}
?>