<?php
session_start();
$email = $_POST['email'] ?? '';

if (isset($_SESSION['verified_emails'][$email]) && $_SESSION['verified_emails'][$email] === true) {
    echo json_encode(['verified' => true]);
} else {
    echo json_encode(['verified' => false]);
}
