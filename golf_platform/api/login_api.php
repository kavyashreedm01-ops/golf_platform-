<?php
session_start();
header("Content-Type: application/json");
include("../config.php");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if(!$email || !$password){
    echo json_encode(["status"=>"error","message"=>"Missing fields"]);
    exit;
}

$stmt = $conn->prepare("SELECT id,password,role FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0){
    $stmt->bind_result($id,$hash,$role);
    $stmt->fetch();

    if(password_verify($password,$hash)){
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;

        echo json_encode([
            "status"=>"success",
            "role"=>$role
        ]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Wrong password"]);
    }
}else{
    echo json_encode(["status"=>"error","message"=>"User not found"]);
}