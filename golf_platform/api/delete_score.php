<?php
require_once __DIR__ . '/../config.php';
session_start();

$user_id = $_SESSION['user_id'];
$date = $_POST['date'] ?? '';

$conn->query("DELETE FROM scores WHERE user_id=$user_id AND score_date='$date'");

echo json_encode(["status"=>"success"]);