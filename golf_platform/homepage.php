<?php 
session_start(); 
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>

<!DOCTYPE html>
<html>
<head>
<title>Digital Heroes Platform</title>

<link rel="stylesheet" href="css/navbar.css">
<link rel="stylesheet" href="css/home.css">

</head>

<body>

<?php include("navbar.php"); ?>

<!-- HERO -->
<section class="hero">
    <h1>Play with Purpose</h1>
    <p>Track scores. Win rewards. Change lives through charity.</p>

    <?php if(!$isLoggedIn): ?>
        <a href="auth.php" class="cta">Start Playing</a>

    <?php elseif($isAdmin): ?>
        <a href="admindashboard.php" class="cta">Admin Dashboard</a>

    <?php else: ?>
        <a href="charities.php" class="cta">Support a Charity</a>
    <?php endif; ?>
</section>

<!-- HOW -->
<section id="how" class="how">
    <h2>How It Works</h2>
    <div class="steps">
        <div class="step">
            <h3>Subscribe</h3>
            <p>Join with a monthly or yearly plan</p>
        </div>
        <div class="step">
            <h3>Enter Scores</h3>
            <p>Keep your last 5 scores updated</p>
        </div>
        <div class="step">
            <h3>Win & Give</h3>
            <p>Win rewards while supporting charities</p>
        </div>
    </div>
</section>

<!-- CHARITY -->
<section class="charity">
    <h2>Your Game. Their Future.</h2>
    <p>At least 10% of your subscription goes to a cause you choose.</p>

    <div class="charity-box">
        <h3>Making an Impact</h3>
        <p>Support communities through sports and development.</p>
        <a href="charities.php" class="cta small">Explore Charities</a>
    </div>
</section>

<!-- DRAW -->
<section class="draw">
    <h2>Monthly Prize Draw</h2>
    <p>Match numbers and win big every month.</p>

    <div class="draw-cards">
        <div>🎯 5 Match → Jackpot</div>
        <div>🔥 4 Match → Big Rewards</div>
        <div>✨ 3 Match → Small Wins</div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">

<?php if($isLoggedIn && !$isAdmin): ?>

    <h2>Ready for this month’s draw?</h2>
    <p>Update your scores and increase your chances of winning.</p>
    <a href="userdashboard.php" class="cta">Update Scores</a>

<?php elseif($isAdmin): ?>

    <h2>Platform Overview</h2>
    <p>Manage users, draws, and charities from the Admin Dashboard.</p>

<?php else: ?>

    <!-- 🔥 GUEST: DESCRIPTION ONLY -->
    <h2>Join. Play. Make Impact.</h2>
    <p>Login to subscribe and start your journey.</p>

<?php endif; ?>

</section>

</body>
</html>