<?php
if (!isset($_SESSION)) {
    session_start();
} else {
    session_destroy();
    session_start();
}

require_once '../classes/user.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = isset($_POST['role']) ? $_POST['role'] : false;

    $user = new User($conn);
if($role !== false){
    if($user->addUser($username, $password, $email, $role)) {
        $response = array('status' => 'success', 'message' => 'Successfully registered. You will be redirected to the login page shortly.', 'redirect' => './login.php');
    } 
    else {
        $response = array('status' => 'error', 'message' => 'Username or Email is already taken. Please try again.', 'redirect' => './register.php');
    }
}
else{
    $response = array('status' => 'error', 'message' => 'W-wait.. you made it here without selecting a role??.', 'redirect' => './register.php');
}

    echo json_encode($response);
    exit();
}
?>
