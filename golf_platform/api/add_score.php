<?php
header("Content-Type: application/json");

/* Prevent warnings breaking JSON */
error_reporting(0);
ini_set('display_errors', 0);

try {
    require_once __DIR__ . '/../config.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not logged in");
    }

    $user_id = $_SESSION['user_id'];

    /* ✅ SUPPORT JSON + FORM DATA */
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if ($data) {
        $score = intval($data['score'] ?? 0);
        $date  = $data['date'] ?? '';
    } else {
        $score = intval($_POST['score'] ?? 0);
        $date  = $_POST['date'] ?? '';
    }

    /* ---------------- VALIDATION ---------------- */
    if ($score < 1 || $score > 45) {
        throw new Exception("Score must be between 1–45");
    }

    if (!$date) {
        throw new Exception("Date required");
    }

    /* ---------------- DUPLICATE CHECK ---------------- */
    $check = $conn->prepare("SELECT id FROM scores WHERE user_id=? AND score_date=?");
    if (!$check) throw new Exception($conn->error);

    $check->bind_param("is", $user_id, $date);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        throw new Exception("Score already exists for this date");
    }

    /* ---------------- COUNT ---------------- */
    $countQ = $conn->prepare("SELECT COUNT(*) as total FROM scores WHERE user_id=?");
    $countQ->bind_param("i", $user_id);
    $countQ->execute();
    $count = $countQ->get_result()->fetch_assoc()['total'];

    /* ---------------- DELETE OLDEST (KEEP ONLY 5) ---------------- */
    if ($count >= 5) {
        $delete = $conn->prepare("
            DELETE FROM scores 
            WHERE id = (
                SELECT id FROM (
                    SELECT id FROM scores 
                    WHERE user_id=? 
                    ORDER BY score_date ASC LIMIT 1
                ) temp
            )
        ");
        $delete->bind_param("i", $user_id);
        $delete->execute();
    }

    /* ---------------- INSERT ---------------- */
    $stmt = $conn->prepare("INSERT INTO scores(user_id,score,score_date) VALUES(?,?,?)");
    if (!$stmt) throw new Exception($conn->error);

    $stmt->bind_param("iis", $user_id, $score, $date);

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    echo json_encode([
        "status" => "success",
        "message" => "Score added"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}