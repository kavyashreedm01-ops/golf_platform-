<?php
require_once __DIR__ . '/../config.php';
header("Content-Type: application/json");

$res = $conn->query("
    SELECT u.id, u.name, u.email, s.plan
    FROM users u
    LEFT JOIN subscriptions s 
        ON u.id = s.user_id AND s.status='active'
    WHERE u.role != 'admin'
    ORDER BY u.id DESC
");

$users = [];

while($row = $res->fetch_assoc()){
    $users[] = $row;
}

echo json_encode($users);