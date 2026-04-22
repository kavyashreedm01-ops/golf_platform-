<?php
require_once __DIR__ . '/../config.php';

$res = $conn->query("
    SELECT draw_date, numbers 
    FROM draws 
    ORDER BY id DESC
");

$data = [];

while($row = $res->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);