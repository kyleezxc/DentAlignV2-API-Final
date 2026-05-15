<?php
include 'config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$firstname = $data['firstname'] ?? '';
$lastname = $data['lastname'] ?? '';
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';
$usertype = $data['usertype'] ?? '';

if (empty($firstname) || empty($lastname) || empty($username) || empty($password) || empty($usertype)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, password, usertype) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $firstname, $lastname, $username, $hashedPassword, $usertype);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(["status" => "success", "message" => "User registered"]);
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Username already exists"]);
}

$stmt->close();
?>