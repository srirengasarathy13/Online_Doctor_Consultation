<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== "doctor") {
    header("Location: ../auth/login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];
$doctor_info = $conn->query("SELECT name, specialization FROM doctors WHERE doctor_id='$doctor_id'")->fetch_assoc();
$doctor_name = $doctor_info['name'];
$specialization = $doctor_info['specialization'];

$total = $conn->query("SELECT COUNT(*) AS total FROM appointment WHERE doctor_id='$doctor_id'")->fetch_assoc()['total'];
$responded = $conn->query("SELECT COUNT(*) AS responded FROM appointment WHERE doctor_id='$doctor_id' AND reply IS NOT NULL")->fetch_assoc()['responded'];
$not_responded = $conn->query("SELECT COUNT(*) AS not_responded FROM appointment WHERE doctor_id='$doctor_id' AND reply IS NULL")->fetch_assoc()['not_responded'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Doctor Dashboard</title>
<style>
* { box-sizing:border-box; margin:0; padding:0; font-family:'Segoe UI', Arial, sans-serif; }
body { background:#FAF9EE; color:#333; line-height:1.6; }

header {
    background:#A2AF9B; 
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 40px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
header h1 { font-size:2em; }
nav a { color:white; text-decoration:none; margin-left:15px; padding:8px 16px; border-radius:8px; font-weight:bold; transition:0.3s; }
nav a:hover { background-color:#8c9b7f; }

.container { max-width:1200px; margin:30px auto; padding:0 20px; }

.profile {
    background:#FFF;
    padding:25px 30px;
    border-radius:15px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
    text-align:center;
    margin-bottom:25px;
    border-left:6px solid #A2AF9B;
}
.profile h3 { font-size:2em; color:#4a4a4a; margin-bottom:10px; }
.profile p { font-size:1.5em; color:#555; }

.stats {
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    margin-bottom:25px;
}
.card {
    background:#A2AF9B; 
    color:white;
    flex:1 1 30%;
    margin:10px;
    padding:25px 15px;
    border-radius:15px;
    font-size:1.3em;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
    transition:0.3s;
}
.card:hover { background:#8c9b7f; transform:translateY(-5px); }
.card strong { display:block; font-size:2.2em; margin-top:10px; }

.about {
    background:#FFF;
    color:#333;
    padding:25px 30px;
    border-radius:15px;
    font-size:1.05em;
    line-height:1.7;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
    border-left:6px solid #8c9b7f;
}

@media(max-width:800px){
    .stats { flex-direction:column; }
    .card { flex:1 1 100%; margin:10px 0; }
    header { flex-direction:column; align-items:flex-start; }
    nav { margin-top:10px; }
}
</style>
</head>
<body>
<header>
    <h1>Doctor Dashboard</h1>
    <nav>
        <a href="appointments.php">View Appointments</a>
        <a href="../auth/logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <div class="profile">
        <h3><?= $doctor_name ?></h3>
        <p><?= $specialization ?></p>
    </div>

    <div class="stats">
        <div class="card">Total Appointments<strong><?= $total ?></strong></div>
        <div class="card">Appointments Attended<strong><?= $responded ?></strong></div>
        <div class="card">Appointment Pending<strong><?= $not_responded ?></strong></div>
    </div>

    <div class="about">
        🌿 Online doctor consultations allow patients to reach you anytime, anywhere.<br><br>
        Every consultation is an opportunity to provide care, comfort, and guidance. 
        Your expertise can help patients manage symptoms, understand their health conditions, 
        and make informed decisions about treatment.<br><br>
        💡 By responding promptly, you build trust and a strong patient-doctor relationship. 
        Even a small piece of advice can greatly impact someone’s well-being.<br><br>
        📈 Telemedicine bridges the gap between healthcare and accessibility, enabling you 
        to reach patients who might otherwise face barriers to medical care.<br><br>
        🌟 Stay motivated, keep learning, and continue making a positive difference in your patients’ lives. 
        Every response counts and contributes to a healthier community.
    </div>
</div>
</body>
</html>
