<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid JSON"]);
    exit;
}

$otp_id = $data['otp_id'] ?? null;
$auth_status = $data['auth_status'] ?? null;
$channel = $data['channel'] ?? null;
$otp_secret = $data['otp_secret'] ?? null;
$email = $data['email'] ?? null;
$ip_address = $data['ip_address'] ?? null;
$metadata = isset($data['metadata']) ? json_decode($data['metadata'], true) : null;
$risk_score = $data['risk_score'] ?? null;

if ($data && $data['auth_status'] === 'verified') {
    session_start();
    $_SESSION['verifiedemail'] = $email;
    $_SESSION['otpID'] = $otp_id;

    header("Location: https://hireme-app.online/company/registration.php");
    exit;
} else {
    header("Location: https://hireme-app.online/company/dashboard.php");
    exit;
}

?>