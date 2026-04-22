<?php
header("Content-Type: application/json");

$numbers = [];

while(count($numbers) < 5){
    $n = rand(1,45);
    if(!in_array($n,$numbers)){
        $numbers[] = $n;
    }
}

echo json_encode([
    "status"=>"success",
    "numbers"=>$numbers
]);