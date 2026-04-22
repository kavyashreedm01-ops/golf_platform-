<?php
require_once __DIR__ . '/../config.php';
header("Content-Type: application/json");

$id = $_POST['id'] ?? 0;

if(!$id){
    echo json_encode(["status"=>"error","message"=>"Invalid ID"]);
    exit;
}

$conn->query("UPDATE winners SET status='paid' WHERE id=$id");

echo json_encode([
    "status"=>"success",
    "message"=>"Marked as paid"
]);