<?php
header("Content-Type: application/json");

try {
    require_once __DIR__ . '/../config.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not logged in");
    }

    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        SELECT score, score_date 
        FROM scores 
        WHERE user_id=? 
        ORDER BY score_date DESC
    ");

    if (!$stmt) throw new Exception($conn->error);

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $data
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}