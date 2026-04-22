<?php
require_once __DIR__ . '/../config.php';
header("Content-Type: application/json");

$users = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];

$winners = $conn->query("SELECT COUNT(*) as c FROM winners")->fetch_assoc()['c'];

$prize = $conn->query("SELECT COALESCE(SUM(prize),0) as t FROM winners")->fetch_assoc()['t'];

echo json_encode([
    "users"=>$users,
    "winners"=>$winners,
    "prize"=>$prize
]);