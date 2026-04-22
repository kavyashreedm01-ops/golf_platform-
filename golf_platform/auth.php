<?php
session_start();
require_once __DIR__ . '/config.php';

/* ---------------- HANDLE AJAX ONLY ---------------- */
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    header("Content-Type: application/json");

    /* LOGIN */
    if(isset($_POST['action']) && $_POST['action'] === 'login'){

        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if($res->num_rows > 0){
            $user = $res->fetch_assoc();

            if(password_verify($password, $user['password'])){

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                if($user['role'] === 'admin'){
                    echo json_encode([
                        "status"=>"success",
                        "message"=>"Admin login successful",
                        "redirect"=>"admindashboard.php"
                    ]);
                } else {
                    echo json_encode([
                        "status"=>"success",
                        "message"=>"Login successful",
                        "redirect"=>"homepage.php"
                    ]);
                }
            } else {
                echo json_encode(["status"=>"error","message"=>"Invalid password"]);
            }

        } else {
            echo json_encode(["status"=>"error","message"=>"User not found"]);
        }
        exit;
    }

    /* REGISTER */
    if(isset($_POST['action']) && $_POST['action'] === 'register'){

        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,'user')");
        $stmt->bind_param("sss", $name, $email, $password);

        if($stmt->execute()){
            echo json_encode(["status"=>"success","message"=>"Registered successfully"]);
        } else {
            echo json_encode(["status"=>"error","message"=>"Registration failed"]);
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Auth</title>
<link rel="stylesheet" href="css/auth.css">

<style>
.error{color:red;font-size:12px;text-align:left}
.input-group{position:relative}
.eye{position:absolute;right:10px;top:10px;cursor:pointer}
</style>
</head>

<body>

<div class="wrapper" id="wrapper">

<!-- REGISTER -->
<div class="form-container sign-up">
<form onsubmit="return false;">
<h2>Register</h2>

<input id="regName" placeholder="Name">
<div class="error" id="regNameError"></div>

<input id="regEmail" placeholder="Email">
<div class="error" id="regEmailError"></div>

<div class="input-group">
<input type="password" id="regPassword" placeholder="Password">
<span class="eye" onclick="togglePassword('regPassword')">👁️</span>
</div>
<div class="error" id="regPassError"></div>

<button type="button" onclick="register()">Register</button>
</form>
</div>

<!-- LOGIN -->
<div class="form-container sign-in">
<form onsubmit="return false;">
<h2>Login</h2>

<input id="loginEmail" placeholder="Email">
<div class="error" id="loginEmailError"></div>

<div class="input-group">
<input type="password" id="loginPassword" placeholder="Password">
<span class="eye" onclick="togglePassword('loginPassword')">👁️</span>
</div>
<div class="error" id="loginPassError"></div>

<button type="button" onclick="login()">Login</button>
</form>
</div>

<!-- OVERLAY -->
<div class="overlay-container">
<div class="overlay">

<div class="overlay-panel overlay-left">
<h2>Welcome Back</h2>
<button onclick="showLogin()">Login</button>
</div>

<div class="overlay-panel overlay-right">
<h2>New Here?</h2>
<button onclick="showRegister()">Register</button>
</div>

</div>
</div>

</div>

<script src="js/auth.js"></script>
</body>
</html>