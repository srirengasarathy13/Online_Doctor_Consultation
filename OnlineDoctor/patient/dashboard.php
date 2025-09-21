<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== "patient") {
    header("Location: ../auth/login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];


$patient_info = $conn->query("SELECT name, age, gender FROM patients WHERE patient_id=$patient_id")->fetch_assoc();

$appointments = $conn->query("SELECT a.appointment_id, d.name AS doctor_name, d.specialization, a.symptoms, a.days, a.reply
                              FROM appointment a
                              JOIN doctors d ON a.doctor_id = d.doctor_id
                              WHERE a.patient_id=$patient_id
                              ORDER BY a.appointment_id DESC
                              LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard</title>
    <style>
        * { box-sizing: border-box; margin:0; padding:0; font-family: 'Segoe UI', Arial, sans-serif; }
        body { 
            background: linear-gradient(to bottom, #FAF9EE, #E9E6D5);
            color:#333; 
            min-height:100vh; 
        }

        header {
            background:#A2AF9B;
            color:white;
            padding:20px 40px;
            display:flex;
            justify-content: space-between;
            align-items:center;
            flex-wrap:wrap;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
        }
        header h1 { font-size:1.9em; letter-spacing:1px; }

        nav a {
            text-decoration:none;
            background:#FFF;
            color:#A2AF9B;
            padding:10px 20px;
            border-radius:50px;
            margin-left:10px;
            font-weight:bold;
            transition:0.3s;
        }
        nav a:hover { background:#8c9b7f; color:white; }

        .container {
            max-width:1000px;
            margin:40px auto;
            padding:0 20px;
        }

        .profile {
            background:#FFF;
            padding:25px;
            border-radius:15px;
            box-shadow:0 8px 20px rgba(0,0,0,0.1);
            margin-bottom:30px;
            text-align:center;
            transition:0.3s;
        }
        .profile:hover { transform: translateY(-3px); }
        .profile h2 { font-size:1.9em; margin-bottom:10px; color:#4A4A4A; }
        .profile p { font-size:1.4em; color:#666; }

        .appointments {
            background:#FFF;
            padding:25px;
            border-radius:15px;
            box-shadow:0 8px 20px rgba(0,0,0,0.1);
            margin-bottom:30px;
        }
        .appointments h3 { margin-bottom:20px; color:#4A4A4A; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:14px 12px; text-align:left; }
        th { background:#A2AF9B; color:white; text-transform:uppercase; font-weight:600; }
        tr:nth-child(even) { background:#FAF9EE; }
        td { font-size:0.95em; }
        .status-pending { color:#FF8C00; font-weight:bold; }
        .status-replied { color:#228B22; font-weight:bold; }

        .about {
            background:#FFF;
            padding:25px;
            border-radius:15px;
            box-shadow:0 8px 20px rgba(0,0,0,0.1);
            text-align:center;
            font-size:1.05em;
            line-height:1.7;
            color:#555;
        }

        @media(max-width: 768px){
            header { flex-direction:column; align-items:flex-start; }
            nav { margin-top:15px; }
            .profile, .appointments, .about { padding:20px; }
            table th, table td { font-size:0.85em; }
        }
    </style>
</head>
<body>
    <header>
        <h1>Patient Dashboard</h1>
        <nav>
            <a href="book_appointment.php">Book Appointment</a>
            <a href="my_appointments.php">My Appointments</a>
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        
        <div class="profile">
            <h2><?= htmlspecialchars($patient_info['name']) ?></h2>
            <p>Age: <?= $patient_info['age'] ?> | Gender: <?= ucfirst($patient_info['gender']) ?></p>
        </div>

       
        <div class="appointments">
            <h3>Recent Appointments</h3>
            <?php if($appointments->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Symptoms</th>
                    <th>Days</th>
                    <th>Status</th>
                </tr>
                <?php while($row = $appointments->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($row['specialization']) ?></td>
                    <td><?= htmlspecialchars($row['symptoms']) ?></td>
                    <td><?= $row['days'] ?></td>
                    <td class="<?= $row['reply'] ? 'status-replied' : 'status-pending' ?>">
                        <?= $row['reply'] ? 'Replied' : 'Pending' ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php else: ?>
                <p>No appointments yet.</p>
            <?php endif; ?>
        </div>

       
        <div class="about">
            🌿 Online doctor consultations make healthcare accessible anytime, anywhere.<br><br>
            Every consultation is an opportunity to get personalized care and guidance.  
            By keeping track of your symptoms and following your doctor’s advice, you actively contribute to your well-being.<br><br>
            💡 Stay proactive, maintain your health records, and trust the process.  
            Your doctors are here to help you every step of the way.  
            🩺 Timely communication ensures better care outcomes.
        </div>
    </div>
</body>
</html>
