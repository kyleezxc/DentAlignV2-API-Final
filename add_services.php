<?php
session_start();
include 'config.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if(!isset($_SESSION['id'])){
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    exit();
}

if($_SESSION['usertype'] !== 'admin'){
    http_response_code(403);
    echo json_encode(['message' => 'Forbidden - Admins only']);
    exit();
}

$service_name = $data['service_name'] ?? '';
$service_duration = $data['service_duration'] ?? '';
$service_description = $data['service_description'] ?? '';

if (empty($service_name) || empty($service_duration) || empty($service_description)) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing fields']);
    exit();
}

$stmt = $conn->prepare("INSERT INTO services (service_name, service_duration, service_desc) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $service_name, $service_duration, $service_description);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(['message' => 'Service added successfully']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Error adding service']);
}
?>