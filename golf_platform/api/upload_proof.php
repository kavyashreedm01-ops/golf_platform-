<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config.php';

session_start();

try {

    if(!isset($_SESSION['user_id'])){
        throw new Exception("Unauthorized");
    }

    if(!isset($_POST['winner_id']) || !isset($_FILES['proof'])){
        throw new Exception("Missing data");
    }

    $winner_id = intval($_POST['winner_id']);
    $user_id = $_SESSION['user_id'];

    // 📁 Upload folder
    $uploadDir = "../uploads/";
    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . $_FILES['proof']['name'];
    $filePath = $uploadDir . $fileName;

    move_uploaded_file($_FILES['proof']['tmp_name'], $filePath);

    // 💾 Save to DB
    $stmt = $conn->prepare("
        UPDATE winners 
        SET proof=?, verification_status='submitted' 
        WHERE id=? AND user_id=?
    ");

    $stmt->bind_param("sii", $fileName, $winner_id, $user_id);
    $stmt->execute();

    echo json_encode([
        "status" => "success",
        "message" => "Proof uploaded"
    ]);

} catch(Exception $e){
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}