<?php
header("Content-Type: application/json");
include("../config.php");

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if(!$name || !$email || !$password){
    echo json_encode(["status"=>"error","message"=>"All fields required"]);
    exit;
}

/* CHECK EMAIL */
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0){
    echo json_encode(["status"=>"error","message"=>"Email already exists"]);
    exit;
}

/* INSERT */
$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,'user')");
$stmt->bind_param("sss",$name,$email,$hash);

if($stmt->execute()){
    echo json_encode(["status"=>"success"]);
}else{
    echo json_encode(["status"=>"error","message"=>$conn->error]);
}   