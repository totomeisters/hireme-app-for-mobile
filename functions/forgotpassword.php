<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/user.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($conn);

    $email = $_POST['email'];

    if($email == null || empty($email)){
        $response = array('status' => 'error', 'message' => 'W-wait.. your email is empty or invalid?', 'redirect' => './forgot-password.php');
    }else{
        $forgotpassword = $user->sendEmailForgotPassword($email);
        if($forgotpassword){
            $response = array('status' => 'success', 'message' => 'Email sent! Please check your email for instructions.', 'redirect' => './login.php');
        }
        else{
            $response = array('status' => 'error', 'message' => $forgotpassword, 'redirect' => './forgot-password.php');
        }
    }

    echo json_encode($response);
    exit();
}
?>