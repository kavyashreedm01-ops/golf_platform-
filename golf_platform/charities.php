<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Charities</title>

<link rel="stylesheet" href="css/navbar.css">
<link rel="stylesheet" href="css/charities.css">

</head>

<body>

<?php include("navbar.php"); ?>

<h1>Charities ❤️</h1>

<input type="text" id="search" placeholder="Search charity..." onkeyup="filterCharities()">

<div class="container">

    <h1>Choose a Charity ❤️</h1>

    <div id="charityList" class="charity-grid"></div>

</div>

<script src="js/charities.js"></script>

</body>
</html>