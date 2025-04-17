<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://otp.dev/api/verify/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, 'JOhuCLGPV0xaTm3QE4IWt1UMw6zXFZAD:y1xhoqaf86gtl94nzd3rkep5ic0vu2bw');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'channel' => 'email',
        'email' => $email,
        'callback_url' => "https://hireme-app.online/otptest.html/",
        'success_redirect_url' => 'https://hireme-app.online/otptest.html/',
        'fail_redirect_url' => 'https://hireme-app.online/otptest.html/',
        'captcha' => 'false',
        'hide' => 'true',
        'lang' => 'en'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
} else {
    echo "<script>alert('An error occurred. Please try again.');</script>";
}
