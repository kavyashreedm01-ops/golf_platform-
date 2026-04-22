<?php
$conn = new mysqli("localhost", "root", "", "golf_platform");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
