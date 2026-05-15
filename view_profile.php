<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    echo json_encode(['message' => 'Unauthorized, you must log in to view your profile']);
    exit();
}

$personal_id = $_SESSION['id'];
$result = $conn->query("SELECT firstname, lastname, username, password FROM users WHERE id = $personal_id");

$profile = [];

while($row = $result->fetch_assoc()) {
    $profile = $row;
}

echo json_encode($profile);

?>