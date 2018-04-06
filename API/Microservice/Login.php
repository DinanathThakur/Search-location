<?php
require_once __DIR__ . '/DB/Table.php';

$userObject = new Table('users');
echo "<pre>";
print_r($userObject);
die("Test");

$data = $_POST;

$response = ['status' => 'error', 'data' => [], 'message' => 'Something went wrong.'];

header('Content-Type: application/json');
if (isset($response['status']) && $response['status'] === 'error') {
    header('HTTP/1.1 500 Internal Server error');
}

echo json_encode($response);
