<?php
session_start();
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $table = ($role == "doctor") ? "doctors" : "patients";
    $id_col = ($role == "doctor") ? "doctor_id" : "patient_id";

    $sql = "SELECT * FROM $table WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if ($password == $row['password']) {
            $_SESSION['user_id'] = $row[$id_col];
            $_SESSION['role'] = $role;

            header("Location: ../$role/dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No account found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        * { box-sizing: border-box; margin:0; padding:0; font-family:'Segoe UI', Arial, sans-serif; }
        body {
            background: #FAF9EE;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        
        header {
            background:#A2AF9B;
            color:white;
            text-align:center;
            padding:22px;
            font-size:1.6em;
            font-weight:bold;
            border-bottom-left-radius: 18px;
            border-bottom-right-radius: 18px;
            box-shadow:0 4px 12px rgba(0,0,0,0.1);
            position:fixed;
            top:0; left:0;
            width:100%;
            z-index:1000;
        }

        .container {
            max-width:480px;
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
            margin-bottom:25px;
            font-size:1.5em;
            color:#444;
        }

        label {
            display:block;
            margin-top:15px;
            font-weight:bold;
            font-size:0.95em;
        }

        input, select {
            width:100%;
            padding:12px;
            margin-top:6px;
            border:1px solid #A2AF9B;
            border-radius:10px;
            font-size:0.95em;
            transition:0.3s;
            background:#EEEEEE;
        }
        input:focus, select:focus {
            border-color:#8c9b7f;
            outline:none;
            box-shadow:0 0 6px rgba(140,155,127,0.4);
            background:#fff;
        }

        button {
            width:100%;
            margin-top:22px;
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

        .error {
            color:#d9534f;
            text-align:center;
            margin-bottom:15px;
            font-size:0.9em;
        }

        p.links {
            text-align:center;
            margin-top:15px;
            font-size:0.9em;
        }
        p.links a {
            color:#333;
            text-decoration:none;
            font-weight:bold;
            padding:4px 6px;
            border-radius:6px;
            transition:0.2s;
        }
        p.links a:hover { 
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
    <header>Login to Your Account</header>

    <div class="container">
        <h2>Welcome Back</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>

            <label>Login as</label>
            <select name="role" required>
                <option value="">-- Select Role --</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Patient</option>
            </select>

            <button type="submit">Login</button>
        </form>

        <p class="links">
            <a href="register_doctor.php">Register as Doctor</a> | 
            <a href="register_patient.php">Register as Patient</a>
        </p>
        <p class="links">
            <a href="forgot_password.php">Forgot Password?</a>
        </p>
    </div>
</body>
</html>
