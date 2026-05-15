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

$doctor_name = $data['doctor_name'] ?? '';
$doctor_contact = $data['doctor_contact'] ?? '';

if (empty($doctor_name) || empty($doctor_contact)) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing fields']);
    exit();
}

$stmt = $conn->prepare("INSERT INTO doctors (doctor_name, doctor_contact) VALUES (?, ?)");
$stmt->bind_param("ss", $doctor_name, $doctor_contact);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(['message' => 'Doctor added successfully']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Error adding doctor']);
}
?>