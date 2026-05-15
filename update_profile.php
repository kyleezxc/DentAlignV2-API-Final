<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    echo json_encode(['message' => 'Unauthorized, you must log in to view your profile']);
    exit();
}

$new_firstname = $_POST['firstname'] ?? null;
$new_lastname = $_POST['lastname'] ?? null;
$new_username = $_POST['username'] ?? null;
$new_password = $_POST['password'] ?? null;

$personal_id = $_SESSION['id'];
$result = $conn->query("SELECT firstname, lastname, username, password FROM users WHERE id = $personal_id");

$profile = [];

while($row = $result->fetch_assoc()) {
    $profile = $row;
}

echo json_encode("Update Profile");
echo json_encode($profile);

if(empty($new_firstname) || empty($new_lastname) || empty($new_username) || empty($new_password)){
    http_response_code(400);
    echo json_encode(['message' => 'All fields are required']);
    exit();
}

$update_stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, username = ?, password = ? WHERE id = ?");
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$update_stmt->bind_param("ssssi", $new_firstname, $new_lastname, $new_username, $hashed_password, $personal_id);

if($update_stmt->execute()){
    http_response_code(200);
    echo json_encode(['message' => 'Profile updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to update profile']);
}
?>