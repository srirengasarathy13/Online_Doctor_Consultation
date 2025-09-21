<?php
session_start();
include("config/db.php");

$doctorCount = $conn->query("SELECT COUNT(*) AS count FROM doctors")->fetch_assoc()['count'];
$patientCount = $conn->query("SELECT COUNT(*) AS count FROM patients")->fetch_assoc()['count'];
$appointmentCount = $conn->query("SELECT COUNT(*) AS count FROM appointment")->fetch_assoc()['count'];
$respondedCount = $conn->query("SELECT COUNT(*) AS count FROM appointment WHERE reply IS NOT NULL")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Doctor Consulting</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            background-color: #FAF9EE;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        header {
            background-color: #A2AF9B;
            padding: 20px;
            text-align: center;
            color: white;
            position: relative;
        }
        .top-links {
            position: absolute;
            top: 15px;
            right: 20px;
            display: flex;
            flex-wrap: wrap;
        }
        .top-links a {
            margin-left: 10px;
            padding: 8px 15px;
            background-color: #DCCFC0;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 5px;
            transition: 0.3s;
        }
        .top-links a:hover { background-color: #C0B09A; }

        .hero {
            background: url('https://images.unsplash.com/photo-1588776814546-1c6c8f3b5cf1?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8ZG9jdG9yfGVufDB8fDB8fA') no-repeat center;
            background-size: cover;
            padding: 60px 20px;
            text-align: center;
            color: #A2AF9B;
            text-shadow: 1px 1px 5px rgba(0,0,0,0.2);
        }
        .hero h1 { font-size: 2.5em; margin-bottom: 15px; }
        .hero p { font-size: 1.5em; }

        .container {
            max-width: 95%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 6px 12px rgba(0,0,0,0.15);
            text-align: center;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .stat-box {
            background-color: #A2AF9B;
            color: white;
            padding: 20px;
            margin: 10px;
            border-radius: 12px;
            flex: 1 1 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .stat-box i { font-size: 2.5em; margin-bottom: 10px; }
        .stat-box:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.2); }

        .stat-number { font-size: 1.8em; font-weight: bold; margin-bottom: 5px; }
        .stat-label { font-size: 1em; }

        @media(max-width: 768px) {
            .hero h1 { font-size: 2em; }
            .hero p { font-size: 1em; }
            .top-links { position: static; justify-content: center; margin-top: 10px; }
            .stats { flex-direction: column; align-items: center; }
            .stat-box { width: 80%; }
        }
        @media(max-width: 480px) {
            .hero h1 { font-size: 1.5em; }
            .hero p { font-size: 0.95em; }
            .stat-box { width: 95%; padding: 15px; }
            .stat-box i { font-size: 2em; }
            .stat-number { font-size: 1.5em; }
        }
    </style>
</head>
<body>
    <header>
        <h1>Online Doctor Consultation</h1>
        <div class="top-links">
            <a href="auth/login.php">Login</a>
            <a href="auth/register_patient.php">Register as Patient</a>
            <a href="auth/register_doctor.php">Register as Doctor</a>
        </div>
    </header>

    <div class="hero">
        <h1>Welcome to Your Online Doctor Consultation!</h1>
        <p>Get free online consultation from our expert doctors, anytime, anywhere.</p>
    </div>

    <div class="container">
        <div class="stats">
            <div class="stat-box">
                <i class="fas fa-user-doctor"></i>
                <div class="stat-number" data-target="<?= $doctorCount ?>">0</div>
                <div class="stat-label">Doctors actively treating patients</div>
            </div>
            <div class="stat-box">
                <i class="fas fa-users"></i>
                <div class="stat-number" data-target="<?= $patientCount ?>">0</div>
                <div class="stat-label">Patients registered</div>
            </div>
            <div class="stat-box">
                <i class="fas fa-calendar-check"></i>
                <div class="stat-number" data-target="<?= $appointmentCount ?>">0</div>
                <div class="stat-label">Total appointments booked</div>
            </div>
            <div class="stat-box">
                <i class="fas fa-reply-all"></i>
                <div class="stat-number" data-target="<?= $respondedCount ?>">0</div>
                <div class="stat-label">Appointments responded by doctors</div>
            </div>
        </div>
    </div>

    <script>
        const counters = document.querySelectorAll('.stat-number');
        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const increment = target / 100;
                if(count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(updateCount, 20);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
        });
    </script>
</body>
</html>
