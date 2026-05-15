<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

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

$appointment_stmt = $conn->query("SELECT * FROM appointments");
$patient_stmt = $conn->query("SELECT * FROM users WHERE usertype = 'user'");
$service_stmt = $conn->query("SELECT * FROM services");
$doctor_stmt = $conn->query("SELECT * FROM doctors");

$appointments = [];
$patients = [];
$services = [];
$doctors = [];

while($row1 = $appointment_stmt->fetch_assoc()){
    $appointments[] = $row1;
}   

while($row2 = $patient_stmt->fetch_assoc()){
    $patients[] = $row2;
}   

while($row3 = $service_stmt->fetch_assoc()){
    $services[] = $row3;
}

while($row4 = $doctor_stmt->fetch_assoc()){
    $doctors[] = $row4;
}

http_response_code(200);
echo json_encode("Patients List ");
echo json_encode($patients);
echo json_encode("Appointments List ");
echo json_encode($appointments);
echo json_encode("Services List ");
echo json_encode($services);
echo json_encode("Doctors List ");
echo json_encode($doctors);
?>