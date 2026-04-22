<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: auth.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<link rel="stylesheet" href="css/navbar.css">
<link rel="stylesheet" href="css/admin.css">

</head>

<body>

<!-- ✅ USE SAME NAVBAR -->
<?php include("navbar.php"); ?>

<div class="dashboard">

<h1>Admin Dashboard</h1>

<div class="stats">

    <div class="card">
        <h3>Total Users</h3>
        <p id="statUsers">0</p>
    </div>

    <div class="card">
        <h3>Total Winners</h3>
        <p id="statWinners">0</p>
    </div>

    <div class="card">
        <h3>Total Prize</h3>
        <p id="statPrize">0</p>
    </div>

</div>

<!-- INFO CARDS -->
<div class="cards">

    <div class="card info">
        <h3>🎯 Draw System</h3>
        <p>Monthly draw with 3, 4, 5 match reward tiers</p>
    </div>

    <div class="card info">
        <h3>💰 Prize Pool</h3>
        <p>Auto distributed across winners (40% / 35% / 25%)</p>
    </div>

    <div class="card info">
        <h3>❤️ Charity Impact</h3>
        <p>Minimum 10% contribution from every subscription</p>
    </div>

    <div class="card info">
        <h3>👤 User Control</h3>
        <p>Manage users, scores, and subscriptions</p>
    </div>

</div>

<!-- CHARITIES -->
<div class="section">
<h2>Manage Charities</h2>

<input id="cname" placeholder="Name">
<input id="cdesc" placeholder="Description">
<button onclick="addCharity()">Add</button>

<div id="charityAdmin"></div>
</div>

<!-- DRAW -->
<div class="section">
<h2>Run Monthly Draw</h2>
<button onclick="runDraw()" class="btn">Run Draw</button>
<button onclick="simulateDraw()">Preview Draw</button>
<p id="preview"></p>
<p id="drawMsg"></p>
</div>

<!-- WINNERS -->
<div class="section">
<h2>Winners Management</h2>

<table>
<thead>
<tr>
<th>User ID</th>
<th>Draw ID</th>
<th>Match</th>
<th>Prize</th>
<th>Status</th>
<th>Proof</th>
<th>Verify</th>
<th>Action</th>
</tr>
</thead>

<tbody id="winnerTable"></tbody>
</table>
</div>

<!-- USERS -->
<div class="section">
<h2>Users</h2>

<table>
<thead>
<tr>
<th>#</th>
<th>Name</th>
<th>Email</th>
<th>Plan</th>
</tr>
</thead>

<tbody id="userTable"></tbody>
</table>
</div>

</div>

<script src="js/admin.js"></script>

</body>
</html>