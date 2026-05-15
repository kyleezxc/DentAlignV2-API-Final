<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
    exit();
}

if ($_SESSION['usertype'] != 'admin') {
    http_response_code(403);
    echo json_encode(["message" => "Forbidden"]);
    exit();
}

$target = $data['service_name'] ?? null;

if (!$target) {
    http_response_code(400);
    echo json_encode(["message" => "Missing Service Name"]);
    exit();
}

$stmt = $conn->prepare("DELETE FROM services WHERE service_name = ?");
$stmt->bind_param("s", $target);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(["message" => "Service deleted"]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Error deleting service"]);
}

$stmt->close();
?>