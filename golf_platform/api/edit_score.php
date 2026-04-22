<?php
require_once __DIR__ . '/../config.php';
session_start();

$user_id = $_SESSION['user_id'];

$date = $_POST['date'];
$score = intval($_POST['score']);

if($score < 1 || $score > 45){
    echo json_encode(["status"=>"error","message"=>"Invalid score"]);
    exit;
}

$stmt = $conn->prepare("
    UPDATE scores 
    SET score=? 
    WHERE user_id=? AND score_date=?
");

$stmt->bind_param("iis", $score, $user_id, $date);
$stmt->execute();

echo json_encode(["status"=>"success"]);