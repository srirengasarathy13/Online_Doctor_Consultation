<?php
include("../config/db.php");
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];

    
    $sql = "SELECT name, email, password FROM doctors WHERE email='$email'
            UNION
            SELECT name, email, password FROM patients WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $name = $user['name'];
        $password = $user['password'];

        
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
            $mail->addAddress($email, $name);

            $mail->isHTML(false);
            $mail->Subject = 'Your Online Doctor Account Password';
            $mail->Body    = "Hello $name,\n\nYour password is: $password\n\nPlease login to your account.";

            $mail->send();
            $message = "✅ Password has been sent to your email!";
        } catch (Exception $e) {
            $message = "❌ Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        $message = "❌ Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        * { box-sizing: border-box; margin:0; padding:0; font-family:'Segoe UI', Arial, sans-serif; }
        body {
            background:#FAF9EE;
            color:#333;
            min-height:100vh;
            display:flex;
            flex-direction:column;
        }

        
        header {
            position:fixed;
            top:0; left:0;
            width:100%;
            background:#A2AF9B;
            color:white;
            text-align:center;
            padding:20px;
            font-size:1.6em;
            font-weight:bold;
            border-bottom-left-radius:15px;
            border-bottom-right-radius:15px;
            box-shadow:0 4px 12px rgba(0,0,0,0.1);
            z-index:1000;
        }

        .container {
            max-width:460px;
            width:90%;
            margin:130px auto 40px;
            background:#fff;
            padding:35px 40px;
            border-radius:18px;
            box-shadow:0 6px 20px rgba(0,0,0,0.1);
            border-left:6px solid #A2AF9B;
        }

        h2 {
            text-align:center;
            margin-bottom:20px;
            font-size:1.5em;
            color:#444;
        }

        label {
            display:block;
            font-weight:bold;
            margin-top:15px;
            font-size:0.95em;
        }

        input {
            width:100%;
            padding:12px;
            margin-top:6px;
            border:1px solid #A2AF9B;
            border-radius:10px;
            font-size:0.95em;
            transition:0.3s;
            background:#EEEEEE;
        }
        input:focus {
            border-color:#8c9b7f;
            outline:none;
            box-shadow:0 0 6px rgba(140,155,127,0.4);
            background:#fff;
        }

        button {
            width:100%;
            margin-top:20px;
            background:#A2AF9B;
            color:white;
            padding:12px;
            border:none;
            border-radius:10px;
            font-size:1em;
            font-weight:bold;
            cursor:pointer;
            transition:0.3s;
        }
        button:hover {
            background:#8c9b7f;
            transform:translateY(-2px);
        }

        .message {
            text-align:center;
            margin-bottom:15px;
            font-size:0.95em;
            font-weight:bold;
            color:#2c662d;
        }
        .message:empty { display:none; }

        p.back-link {
            text-align:center;
            margin-top:15px;
            font-size:0.9em;
        }
        p.back-link a {
            color:#333;
            text-decoration:none;
            font-weight:bold;
            padding:4px 6px;
            border-radius:6px;
            transition:0.2s;
        }
        p.back-link a:hover {
            text-decoration:underline;
            background:#DCCFC0;
        }

        @media(max-width:480px){
            .container { margin:110px 15px 30px; padding:25px; }
            header { font-size:1.3em; padding:16px; }
        }
    </style>
</head>
<body>
    <header>Forgot Password</header>

    <div class="container">
        <h2>Recover Your Account</h2>
        <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>
        <form method="POST">
            <label>Enter your registered email:</label>
            <input type="email" name="email" placeholder="example@mail.com" required>
            <button type="submit">Send Password</button>
        </form>
        <p class="back-link"><a href="login.php">⬅ Back to Login</a></p>
    </div>
</body>
</html>
