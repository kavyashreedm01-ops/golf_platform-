<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<?php include("navbar.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Subscription Plans</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/subscription.css">

    <style>
        .fake-disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

<section class="plans">

<h1>Choose Your Plan</h1>
<p>Subscribe and start playing, winning, and giving back.</p>

<div class="plan-container">

    <!-- BASIC -->
    <div class="plan">
        <h2>Basic</h2>
        <h3>₹299 / month</h3>

        <ul>
            <li>✔ Enter monthly draw</li>
            <li>✔ Track scores</li>
            <li>✔ Charity contribution</li>
        </ul>

        <button class="btn <?= !$isLoggedIn ? 'fake-disabled' : '' ?>"
            onclick="<?= $isLoggedIn ? "subscribe('basic')" : "loginRequired()" ?>">
            Subscribe
        </button>
    </div>

    <!-- PRO -->
    <div class="plan featured">
        <h2>Pro</h2>
        <h3>₹599 / month</h3>

        <ul>
            <li>✔ Higher reward chances</li>
            <li>✔ Advanced stats</li>
            <li>✔ Priority draws</li>
        </ul>

        <button class="btn <?= !$isLoggedIn ? 'fake-disabled' : '' ?>"
            onclick="<?= $isLoggedIn ? "subscribe('pro')" : "loginRequired()" ?>">
            Subscribe
        </button>
    </div>

    <!-- PREMIUM -->
    <div class="plan">
        <h2>Premium</h2>
        <h3>₹999 / month</h3>

        <ul>
            <li>✔ Max reward eligibility</li>
            <li>✔ Exclusive prizes</li>
            <li>✔ VIP support</li>
        </ul>

        <button class="btn <?= !$isLoggedIn ? 'fake-disabled' : '' ?>"
            onclick="<?= $isLoggedIn ? "subscribe('premium')" : "loginRequired()" ?>">
            Subscribe
        </button>
    </div>

</div>

</section>

<!-- Razorpay -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- YOUR PAYMENT JS -->
<script src="js/subscription.js"></script>

<!-- LOGIN ALERT -->
<script>
function loginRequired(){
    alert("Please login first");
    window.location.href = "auth.php";
}
</script>

</body>
</html>