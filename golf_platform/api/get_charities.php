<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config.php';

$result = $conn->query("SELECT id, name, description FROM charities");

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = [
        "id" => $row["id"],
        "name" => $row["name"],
        "description" => $row["description"] // ✅ MUST BE INCLUDED
    ];
}

echo json_encode([
    "status" => "success",
    "data" => $data
]);