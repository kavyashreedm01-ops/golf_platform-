<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("config.php");

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>

<nav class="navbar">

    <div class="logo">Digital Heroes</div>

    <ul class="nav-links">

        <?php if($isAdmin): ?>
            <li><a href="homepage.php">Home</a></li>
            <li><a href="charities.php">Charities</a></li>
            <li><a href="draws.php">Draws</a></li>
            <li><a href="admindashboard.php">Admin Dashboard</a></li>

        <?php else: ?>
            <li><a href="homepage.php">Home</a></li>
            <li><a href="homepage.php#how">How It Works</a></li>
            <li><a href="charities.php">Charities</a></li>
            <li><a href="draws.php">Draws</a></li>

            <?php if($isLoggedIn): ?>
                <li><a href="userdashboard.php">Dashboard</a></li>
            <?php endif; ?>
        <?php endif; ?>

    </ul>

    <div class="auth">

        <?php if($isLoggedIn): ?>

            <?php if($isAdmin): ?>
                <span class="admin-badge">Admin Panel</span>
            <?php else: ?>

                <?php
                $user_id = $_SESSION['user_id'];

                $stmt = $conn->prepare("
                    SELECT plan FROM subscriptions 
                    WHERE user_id=? AND status='active' 
                    ORDER BY id DESC LIMIT 1
                ");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->store_result();
                ?>

                <?php if($stmt->num_rows > 0): ?>
                    <span class="active-plan-label">Active Plan</span>
                    <a href="subscription.php" class="btn">Manage Plan</a>
                <?php else: ?>
                    <a href="subscription.php" class="btn">Subscribe</a>
                <?php endif; ?>

            <?php endif; ?>

            <a href="logout.php" class="logout-btn">Logout</a>

        <?php else: ?>

            <a href="auth.php" class="btn login-btn">Login</a>

            <!-- ✅ FIX: Guest goes to plans page -->
            <a href="subscription.php" class="btn">Subscribe</a>

        <?php endif; ?>

    </div>

</nav>