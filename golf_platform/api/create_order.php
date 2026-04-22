<?php
session_start();

header("Content-Type: application/json");

// 🔴 YOUR KEYS
$keyId = "rzp_test_SfpnQubAWb1FEr";
$keySecret = "obEzJT1zerzwhqpN2lwugrJ8";

// GET PLAN
$plan = $_POST['plan'] ?? '';

// PLAN PRICES
$prices = [
    "basic" => 29900,
    "pro" => 59900,
    "premium" => 99900
];

if(!isset($prices[$plan])){
    echo json_encode([
        "status" => "error",
        "message" => "Invalid plan"
    ]);
    exit;
}

$amount = $prices[$plan];

// CREATE ORDER USING CURL
$data = [
    "amount" => $amount,
    "currency" => "INR",
    "receipt" => "order_" . time()
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/orders");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $keyId . ":" . $keySecret);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

$response = curl_exec($ch);

if(curl_errno($ch)){
    echo json_encode([
        "status" => "error",
        "message" => curl_error($ch)
    ]);
    exit;
}

curl_close($ch);

$result = json_decode($response, true);

// CHECK RESPONSE
if(isset($result['id'])){
    $_SESSION['plan'] = $plan;

    echo json_encode([
        "status" => "success",
        "id" => $result['id'],
        "amount" => $amount
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $result['error']['description'] ?? "Order failed"
    ]);
}