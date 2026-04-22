<?php
require_once __DIR__ . '/../config.php';
header("Content-Type: application/json");

$id = $_POST['id'] ?? 0;
$status = $_POST['status'] ?? '';

if(!$id || !$status){
    echo json_encode(["status"=>"error","message"=>"Invalid request"]);
    exit;
}

$conn->query("
    UPDATE winners 
    SET verification_status='$status' 
    WHERE id=$id
");

echo json_encode([
    "status"=>"success",
    "message"=>"Verification updated"
]);