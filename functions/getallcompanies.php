<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once '../classes/company.php';

    $company = new Company($conn);
    echo $company->getAllCompanies();
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}