<?php
$host = "localhost";      
$user = "root";           
$pass = "";               
$db   = "doctor_app";     

$conn = new mysqli($host, $user, null, $db,3307);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>
