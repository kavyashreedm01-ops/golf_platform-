<?php
header("Content-Type: application/json");

/* Prevent warnings breaking JSON */
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/../config.php';

session_start();

if(!isset($_SESSION['user_id'])){
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = [];

/* 🔥 AUTO EXPIRE OLD SUBSCRIPTIONS */
$conn->query("
    UPDATE subscriptions 
    SET status='inactive' 
    WHERE renewal_date < CURDATE()
");

/* ---------------- SUBSCRIPTION (FIXED) ---------------- */
$stmt = $conn->prepare("
    SELECT plan, renewal_date, status
    FROM subscriptions 
    WHERE user_id = ?
    ORDER BY id DESC 
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

if($sub = $res->fetch_assoc()){

    $today = date("Y-m-d");

    if($sub['status'] !== 'active'){
        $data['subscription'] = "Inactive";
    }
    else if(!empty($sub['renewal_date']) && $sub['renewal_date'] < $today){
        $data['subscription'] = "Expired";
    }
    else {
        $data['subscription'] = strtoupper($sub['plan']);
    }

    $data['renewal_date'] = $sub['renewal_date'] ?? "--";

} else {
    $data['subscription'] = "Inactive";
    $data['renewal_date'] = "--";
}


/* ---------------- SCORES ---------------- */
$stmt = $conn->prepare("
    SELECT score, score_date 
    FROM scores 
    WHERE user_id = ? 
    ORDER BY score_date DESC 
    LIMIT 5
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

$scores = [];
while($row = $res->fetch_assoc()){
    $scores[] = [
        "score" => $row["score"],
        "score_date" => $row["score_date"]
    ];
}
$data['scores'] = $scores;


/* ---------------- TOTAL WINNINGS ---------------- */
$stmt = $conn->prepare("
    SELECT COALESCE(SUM(prize),0) as total,
           COUNT(*) as wins
    FROM winners 
    WHERE user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

$win = $res->fetch_assoc();

$data['winnings'] = $win['total'] ?? 0;
$data['total_wins'] = $win['wins'] ?? 0;


/* ---------------- PAYMENT STATUS ---------------- */
$stmt = $conn->prepare("
    SELECT status 
    FROM winners 
    WHERE user_id = ? 
    ORDER BY id DESC 
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

$latest = $res->fetch_assoc();
$data['payment_status'] = $latest['status'] ?? "No winnings yet";


/* ---------------- DRAW ENTRIES ---------------- */
$stmt = $conn->prepare("
    SELECT COUNT(*) as entries 
    FROM scores 
    WHERE user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

$entries = $res->fetch_assoc();
$data['entries'] = $entries['entries'] ?? 0;


/* ---------------- WINNINGS LIST ---------------- */
$stmt = $conn->prepare("
    SELECT id, draw_id, prize, status, proof, verification_status
    FROM winners 
    WHERE user_id = ?
    ORDER BY id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

$winnings = [];

while($row = $res->fetch_assoc()){

    $verification = strtolower(trim($row['verification_status'] ?? ''));
    if($verification === '' || $verification === null){
        $verification = 'pending';
    }

    $status = strtolower(trim($row['status'] ?? 'pending'));

    $winnings[] = [
        "id" => $row["id"],
        "draw_id" => $row["draw_id"],
        "prize" => $row["prize"],
        "status" => $status,
        "proof" => $row["proof"],
        "verification_status" => $verification
    ];
}

/* OPTIONAL DEMO FALLBACK */
if(empty($winnings)){
    $winnings[] = [
        "id" => 0,
        "draw_id" => 1,
        "prize" => 1000,
        "status" => "pending",
        "proof" => null,
        "verification_status" => "pending"
    ];
}

$data['winnings_list'] = $winnings;


/* ---------------- FINAL ---------------- */
echo json_encode($data);