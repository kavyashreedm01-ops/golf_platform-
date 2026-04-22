<?php
session_start();
header("Content-Type: application/json");

/* Prevent warnings breaking JSON */
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/../config.php';

/* Razorpay keys */
$keyId = "rzp_test_SfpnQubAWb1FEr";
$keySecret = "obEzJT1zerzwhqpN2lwugrJ8";

/* Read JSON input */
$data = json_decode(file_get_contents("php://input"), true);

if(!$data){
    echo json_encode([
        "status"=>"error",
        "message"=>"Invalid JSON input"
    ]);
    exit;
}

$order_id   = $data['razorpay_order_id'] ?? '';
$payment_id = $data['razorpay_payment_id'] ?? '';
$plan       = strtolower(trim($data['plan'] ?? ''));

$user_id = $_SESSION['user_id'] ?? 0;

/* Validate input */
if (!$order_id || !$payment_id || !$user_id || !$plan) {
    echo json_encode([
        "status"=>"error",
        "message"=>"Missing data"
    ]);
    exit;
}

/* Validate plan */
$valid_plans = ["basic", "pro", "premium"];

if(!in_array($plan, $valid_plans)){
    echo json_encode([
        "status"=>"error",
        "message"=>"Invalid plan selected"
    ]);
    exit;
}

/* ✅ PLAN LOGIC */
$db_plan = $plan; // store actual plan name

if($plan === "basic"){
    $days = 30; // monthly
}
else if($plan === "pro" || $plan === "premium"){
    $days = 365; // yearly
}

/* Verify payment from Razorpay */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/payments/" . $payment_id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $keyId . ":" . $keySecret);

$response = curl_exec($ch);

if(curl_errno($ch)){
    echo json_encode([
        "status"=>"error",
        "message"=>curl_error($ch)
    ]);
    exit;
}

curl_close($ch);

$result = json_decode($response, true);

/* ✅ PAYMENT SUCCESS */
if (isset($result['status']) && $result['status'] === "captured") {

    $renewal_date = date("Y-m-d", strtotime("+$days days"));

    $conn->begin_transaction();

    try {

        /* 1️⃣ Insert new subscription FIRST */
        $stmt = $conn->prepare("
            INSERT INTO subscriptions (user_id, plan, status, renewal_date)
            VALUES (?, ?, 'active', ?)
        ");
        $stmt->bind_param("iss", $user_id, $db_plan, $renewal_date);

        if(!$stmt->execute()){
            throw new Exception($stmt->error);
        }

        /* 2️⃣ Deactivate old subscriptions */
        $stmt = $conn->prepare("
            UPDATE subscriptions 
            SET status='inactive' 
            WHERE user_id = ? AND id != LAST_INSERT_ID()
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $conn->commit();

        echo json_encode([
            "status"=>"success",
            "message"=>"Payment successful 🎉 Subscription activated",
            "plan"=>$db_plan,
            "expiry"=>$renewal_date
        ]);

    } catch (Exception $e) {

        $conn->rollback();

        echo json_encode([
            "status"=>"error",
            "message"=>$e->getMessage()
        ]);
    }

} else {
    echo json_encode([
        "status"=>"error",
        "message"=>"Payment not captured"
    ]);
}