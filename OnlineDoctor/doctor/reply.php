<?php
session_start();
include("../config/db.php");

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== "doctor") {
    header("Location: ../auth/login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];
$appointment_id = $_GET['id'] ?? null;

if(!$appointment_id) {
    header("Location: appointments.php");
    exit();
}


if($_SERVER['REQUEST_METHOD'] == "POST") {
    $reply = $conn->real_escape_string($_POST['reply']);
    $sql = "UPDATE appointment SET reply='$reply' WHERE appointment_id=$appointment_id";
    
    if($conn->query($sql)) {

       
        $patient_result = $conn->query("SELECT p.name, p.email FROM patients p
                                        JOIN appointment a ON p.patient_id = a.patient_id
                                        WHERE a.appointment_id=$appointment_id");
        $patient = $patient_result->fetch_assoc();

        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'zoro13122003@gmail.com'; 
            $mail->Password = 'cvjnwgnnleisikus';   
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('zoro13122003@gmail.com', 'Online Doctor Portal');
            $mail->addAddress($patient['email'], $patient['name']);

            $mail->isHTML(true);
            $mail->Subject = "Your doctor has responded!";
            $mail->Body    = "Hello " . htmlspecialchars($patient['name']) . ",<br><br>
                              Your doctor has responded to your appointment.<br>
                              Please login to your dashboard to view the details.<br><br>
                              Regards,<br>Online Doctor Consultation";

            $mail->send();
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
        }

        
        header("Location: appointments.php");
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
<title>Reply to Appointment</title>
<style>
    * { box-sizing: border-box; margin:0; padding:0; font-family:'Segoe UI', Arial, sans-serif; }
    body { background: linear-gradient(to bottom, #FAF9EE, #E9E6D5); color:#333; min-height:100vh; }

   
    header {
        background: linear-gradient(135deg,#A2AF9B,#8c9b7f);
        color:white;
        padding:25px 20px;
        text-align:center;
        border-bottom-left-radius: 15px;
        border-bottom-right-radius: 15px;
        box-shadow:0 4px 12px rgba(0,0,0,0.1);
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index:1000;
    }
    header h1 { font-size:1.8em; }

    .container {
        max-width: 650px;
        margin: 140px auto 40px; 
        background: #FFF;
        padding: 30px 35px;
        border-radius: 20px;
        box-shadow:0 8px 20px rgba(0,0,0,0.1);
        border-left: 6px solid #A2AF9B;
    }

    label {
        font-weight:bold;
        display:block;
        margin-bottom:12px;
        font-size:1.1em;
    }

    textarea {
        width:100%;
        height:150px;
        padding:15px;
        border-radius:10px;
        border:1px solid #A2AF9B;
        font-size:1em;
        resize: vertical;
        transition:0.3s;
    }
    textarea:focus {
        border-color:#8c9b7f;
        box-shadow:0 0 8px rgba(140,155,127,0.3);
        outline:none;
    }

    button {
        background: #A2AF9B;
        color:white;
        padding:12px 30px;
        border:none;
        border-radius:10px;
        margin-top:20px;
        cursor:pointer;
        font-size:1em;
        transition:0.3s;
    }
    button:hover { 
        background: linear-gradient(135deg,#8c9b7f,#c5bba8);
        transform:translateY(-2px);
    }

   .back-btn {
    display:inline-block;
    margin-top:25px;
    color:white;
    text-decoration:none;
    background:#333;  
    padding:12px 25px;
    border-radius:10px;
    transition:0.3s;
    }
   .back-btn:hover {
    background:#555;  
   }

    @media(max-width:600px){
        .container { padding:20px; margin:120px 15px 30px; }
        textarea { height:120px; }
        button, .back-btn { width:100%; text-align:center; }
    }
</style>
</head>
<body>
<header>
    <h1>Reply to Patient</h1>
</header>

<div class="container">
    <form method="POST">
        <label>Write your reply (medicines, remedies, advice):</label>
        <textarea name="reply" placeholder="Type your response here..." required></textarea>
        <button type="submit">Submit Reply</button>
    </form>
        <div>
    <a class="back-btn" href="appointments.php">⬅ Back to Appointments</a>
</div>
</div>
</body>
</html>
