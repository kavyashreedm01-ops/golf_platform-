<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config.php';

session_start();

if(!isset($_SESSION['user_id'])){
    echo json_encode(["status"=>"error", "message"=>"Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT c.name, uc.charity_id, uc.percentage
    FROM user_charity uc
    JOIN charities c ON uc.charity_id = c.id
    WHERE uc.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode([
    "status" => "success",
    "data" => $data ? $data : null
]);