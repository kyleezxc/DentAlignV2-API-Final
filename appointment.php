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

$patient_name = $data['patient_name'] ?? '';
$service = $data['service'] ?? '';
$date = $data['date'] ?? '';
$dentist = $data['dentist'] ?? '';

if(empty($patient_name) || empty($service) || empty($date) || empty($dentist)){
    http_response_code(400);
    echo json_encode(['message' => 'Missing fields']);
    exit();
}
$stmt = $conn->prepare("INSERT INTO appointments (patient_name, service, date, dentist) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $patient_name, $service, $date, $dentist);

if($stmt->execute()){
    http_response_code(201);
    echo json_encode(['message' => 'Appointment created successfully']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Error creating appointment']);
}

?>