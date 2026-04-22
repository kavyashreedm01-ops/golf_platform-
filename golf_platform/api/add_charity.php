<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config.php';

session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    echo json_encode(["status"=>"error","message"=>"Unauthorized"]);
    exit;
}

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');

if(!$name){
    echo json_encode(["status"=>"error","message"=>"Name is required"]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO charities (name, description)
    VALUES (?, ?)
");

if(!$stmt){
    echo json_encode(["status"=>"error","message"=>$conn->error]);
    exit;
}

$stmt->bind_param("ss", $name, $description);

if(!$stmt->execute()){
    echo json_encode(["status"=>"error","message"=>$stmt->error]);
    exit;
}

echo json_encode([
    "status"=>"success",
    "message"=>"Charity added successfully"
]);