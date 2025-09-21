<?php
session_start();
include("../config/db.php");


require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== "patient") {
    header("Location: ../auth/login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

$sql = "SELECT doctor_id, name, specialization, email FROM doctors";
$doctors = $conn->query($sql);

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $doctor_id = $_POST['doctor_id'];
    $symptoms = $conn->real_escape_string($_POST['symptoms']);
    $days = (int)$_POST['days'];

    $sql = "INSERT INTO appointment (patient_id, doctor_id, symptoms, days)
            VALUES ($patient_id, $doctor_id, '$symptoms', $days)";
    if($conn->query($sql)) {
        
        $patient_sql = "SELECT name FROM patients WHERE patient_id = $patient_id";
        $patient_result = $conn->query($patient_sql);
        $patient = $patient_result->fetch_assoc();

        $doctor_sql = "SELECT name, email FROM doctors WHERE doctor_id = $doctor_id";
        $doctor_result = $conn->query($doctor_sql);
        $doctor = $doctor_result->fetch_assoc();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'zoro13122003@gmail.com';         
            $mail->Password   = 'cvjnwgnnleisikus';               
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('zoro13122003@gmail.com', 'Online Doctor App');
            $mail->addAddress($doctor['email'], $doctor['name']);

            $mail->isHTML(true);
            $mail->Subject = $patient['name'] . " - Appointment";
            $mail->Body    = "<p>Hello " . $doctor['name'] . ",</p>
                              <p>" . $patient['name'] . " has booked an appointment with you.</p>
                              <ul>
                                  <li>Symptoms: " . $symptoms . "</li>
                                  <li>Days of illness: " . $days . "</li>
                              </ul>
                              <p>Please check your dashboard for more details.</p>";

            $mail->send();
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
        }

        header("Location: my_appointments.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <style>
        * { box-sizing: border-box; margin:0; padding:0; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background: linear-gradient(to bottom, #FAF9EE, #E9E6D5); color:#333; min-height:100vh; }
        header {
            background:#A2AF9B;
            color:white;
            padding:25px 20px;
            text-align:center;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
        }
        header h1 { font-size:1.8em; }

        .container {
            max-width:600px;
            margin:40px auto;
            background:#FFF;
            padding:30px 25px;
            border-radius:15px;
            box-shadow:0 6px 20px rgba(0,0,0,0.1);
        }

        label {
            font-weight:bold;
            display:block;
            margin-bottom:8px;
            font-size:1.05em;
        }

        select, textarea, input {
            width:100%;
            padding:12px 15px;
            border:1px solid #A2AF9B;
            border-radius:8px;
            margin-bottom:18px;
            font-size:1em;
            transition:0.3s;
        }
        select:focus, textarea:focus, input:focus { border-color:#8c9b7f; outline:none; }

        button {
            background:#A2AF9B;
            color:white;
            padding:12px 25px;
            border:none;
            border-radius:8px;
            cursor:pointer;
            font-size:1em;
            transition:0.3s;
        }
        button:hover { background:#8c9b7f; }

        a.back-btn {
            display:inline-block;
            margin-top:20px;
            color:#333;
            text-decoration:none;
            background:#FFF;
            border:1px solid #A2AF9B;
            padding:10px 20px;
            border-radius:8px;
            transition:0.3s;
        }
        a.back-btn:hover { background:#A2AF9B; color:white; }

        @media(max-width:600px){
            .container { padding:20px; margin:20px; }
            select, textarea, input { font-size:0.95em; }
            button, a.back-btn { width:100%; text-align:center; }
        }
    </style>
</head>
<body>
    <header>
        <h1>Book Appointment</h1>
    </header>
    <div class="container">
        <form method="POST">
            <label>Select Doctor:</label>
            <select name="doctor_id" required>
                <option value="">-- Choose Doctor --</option>
                <?php while($doc = $doctors->fetch_assoc()): ?>
                    <option value="<?= $doc['doctor_id'] ?>">
                        <?= htmlspecialchars($doc['name']) ?> (<?= htmlspecialchars($doc['specialization']) ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Symptoms:</label>
            <textarea name="symptoms" placeholder="Describe your symptoms..." required></textarea>

            <label>Days of illness:</label>
            <input type="number" name="days" min="1" placeholder="Number of days" required>

            <button type="submit">Book Appointment</button>
        </form>
        <a class="back-btn" href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
</body>
</html>
