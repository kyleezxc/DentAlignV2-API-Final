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
$new_service_name = $data['new_service_name'] ?? '';
$service_duration = $data['service_duration'] ?? '';
$service_description = $data['service_description'] ?? '';

if (empty($service_name) || empty($new_service_name) || empty($service_duration) || empty($service_description)) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing fields']);
    exit();
}

$stmt = $conn->prepare("UPDATE services SET service_name = ?, service_duration = ?, service_desc = ? WHERE service_name = ?");
$stmt->bind_param("ssss", $new_service_name, $service_duration, $service_description, $service_name);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['message' => 'Service updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Error updating service']);
}
?>