<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config.php';

$res = $conn->query("
    SELECT w.*, d.numbers 
    FROM winners w
    JOIN draws d ON w.draw_id = d.id
    ORDER BY w.id DESC
");

$data = [];

while($row = $res->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);