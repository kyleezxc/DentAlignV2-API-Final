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

$patient_name = $_SESSION['firstname'];
$service = $data['service'] ?? '';
$date = $data['date'] ?? '';
$dentist = $data['dentist'] ?? '';
$patient_id = $_SESSION['id'];

$doctor_stmt = $conn->query("SELECT doctor_name FROM doctors");
$service_stmt = $conn->query("SELECT service_name, service_desc FROM services");

$doctors = [];
$services = [];


while($row = $doctor_stmt->fetch_assoc()){
    $doctors[] = $row;
}

while($row = $service_stmt->fetch_assoc()){
    $services[] = $row;
}

echo json_encode([
    'available_doctors' => $doctors,
    'available_services' => $services
]);

if(empty($patient_name) || empty($service) || empty($date) || empty($dentist)){
    http_response_code(400);
    echo json_encode(['message' => 'Missing fields']);
    exit();
}
$stmt = $conn->prepare("INSERT INTO appointments (patient_name, service, date, dentist, user_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $patient_name, $service, $date, $dentist, $patient_id);

if($stmt->execute()){
    http_response_code(201);
    echo json_encode(['message' => 'Appointment created successfully']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Error creating appointment']);
}

?>