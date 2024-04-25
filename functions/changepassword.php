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
                $response = array('status' => 'error', 'message' => 'Sorry there was an error.' . $resetpassword, 'redirect' => './resetpassword.php?token='.$token);
            }
        }
        else{
            $response = array('status' => 'error', 'message' => 'Your passwords did not match. Try typing slower next time.', 'redirect' => './resetpassword.php?token='.$token);

        }
    }

    echo json_encode($response);
    exit();
}
?>