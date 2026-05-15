<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    exit();
}

$personal_id = $_SESSION['id'];

$appointment_stmt = $conn->query("SELECT * FROM appointments WHERE user_id = $personal_id");
$history = [];

while($row = $appointment_stmt->fetch_assoc()){
    $history[] = $row;
}

http_response_code(200);
echo json_encode("Appointment History");
echo json_encode($history);
?>