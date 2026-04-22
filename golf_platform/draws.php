<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Draw History</title>
<link rel="stylesheet" href="css/draws.css">
<link rel="stylesheet" href="css/navbar.css">
<link rel="stylesheet" href="css/home.css">

</head>

<body>

<?php include("navbar.php"); ?>

<div style="padding:20px">

    <h1>Monthly Draw Results 🎯</h1>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Numbers</th>
            </tr>
        </thead>
        <tbody id="drawTable"></tbody>
    </table>

</div>

<script src="js/draws.js"></script>

</body>
</html>