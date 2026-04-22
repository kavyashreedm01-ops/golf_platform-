<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config.php';

session_start();

if(!isset($_SESSION['user_id'])){
    echo json_encode(["status"=>"error", "message"=>"Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$charity_id = intval($_POST['charity_id'] ?? 0);
$percentage = intval($_POST['percentage'] ?? 0);

// Validation
if($charity_id <= 0 || $percentage < 10){
    echo json_encode([
        "status"=>"error",
        "message"=>"Invalid input (min 10%)"
    ]);
    exit;
}

// Check if user already has a charity
$stmt = $conn->prepare("SELECT user_id FROM user_charity WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    // UPDATE
    $stmt = $conn->prepare("
        UPDATE user_charity 
        SET charity_id = ?, percentage = ? 
        WHERE user_id = ?
    ");
    $stmt->bind_param("iii", $charity_id, $percentage, $user_id);
    $stmt->execute();

} else {
    // INSERT
    $stmt = $conn->prepare("
        INSERT INTO user_charity (user_id, charity_id, percentage)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iii", $user_id, $charity_id, $percentage);
    $stmt->execute();
}

echo json_encode([
    "status"=>"success",
    "message"=>"Charity saved successfully"
]);