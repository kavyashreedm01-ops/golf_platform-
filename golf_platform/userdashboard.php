<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: auth.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<link rel="stylesheet" href="css/navbar.css">
<link rel="stylesheet" href="css/dashboard.css">

</head>

<body>

<?php include("navbar.php"); ?>

<div class="dashboard">

    <h1>My Dashboard</h1>

    <!-- SUBSCRIPTION -->
    <div class="card">
        <h2>Subscription</h2>
        <p id="subscriptionStatus">Loading...</p>
        <small>Next Renewal: <span id="renewalDate">--</span></small>
    </div>

    <!-- SCORES -->
    <div class="card">
        <h2>My Last 5 Scores</h2>

        <div class="score-input">
            <input type="number" id="score" placeholder="Score (1-45)">
            <input type="date" id="date">
            <button onclick="addScore()">Add Score</button>
        </div>

        <!-- ✅ FIXED -->
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody id="scoreTable"></tbody>
        </table>
    </div>

    <!-- CHARITY -->
    <div class="card">
        <h2>Select Charity</h2>

        <select id="charity"></select>

        <input type="number" id="percent" value="10" min="10">

        <button onclick="saveCharity()">Save</button>

        <p id="selectedCharity"></p>
    </div>

    <!-- DRAW -->
    <div class="card">
        <h2>Draw Participation</h2>
        <p>Next Draw: <b>End of Month</b></p>
        <p>Your Entries: <span id="entries">0</span></p>
    </div>

    <!-- WINNINGS -->
    <div class="card">
        <h2>Winnings</h2>

        <p>Total Won: ₹<span id="totalWon">0</span></p>

        <!-- ✅ FIXED -->
        <table>
            <thead>
                <tr>
                    <th>Draw</th>
                    <th>Prize</th>
                    <th>Status</th>
                    <th>Proof</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="winningsTable"></tbody>
        </table>
    </div>

</div>

<script src="js/dashboard.js"></script>

</body>
</html>