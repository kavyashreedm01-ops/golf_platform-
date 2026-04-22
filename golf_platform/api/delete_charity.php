<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config.php';

session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    echo json_encode(["status"=>"error","message"=>"Unauthorized"]);
    exit;
}

$id = intval($_POST['id'] ?? 0);

if(!$id){
    echo json_encode(["status"=>"error","message"=>"Invalid ID"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM charities WHERE id=?");

if(!$stmt){
    echo json_encode(["status"=>"error","message"=>$conn->error]);
    exit;
}

$stmt->bind_param("i", $id);

if(!$stmt->execute()){
    echo json_encode(["status"=>"error","message"=>$stmt->error]);
    exit;
}

echo json_encode([
    "status"=>"success",
    "message"=>"Charity deleted"
]);