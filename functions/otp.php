<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://otp.dev/api/verify/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, 'yq6b5zQ1lWJMDc7mFNHfYji8rvePIKx2:c8ixvo40mf1lkj2hyr5t9su7wqze6dga');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'channel' => 'email',
        'email' => $email,
        'callback_url' => "https://hireme-app.online/functions/verify-otp-callback.php",
        'success_redirect_url' => 'https://hireme-app.online/company/registration.php?otp_secret=mpktanosh3byzf4c81e3bydjl76ixr9c81edjl76ix3bydjl76ixrwugv&email='.$email,
        'fail_redirect_url' => 'https://hireme-app.online/company/dashboard.php',
        'captcha' => 'true',
        'hide' => 'true',
        'lang' => 'en'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
} else {
    echo "<script>alert('An error occurred. Please try again.');</script>";
}
