<?php
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $specialization = $_POST['specialization'];

    $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";

    if (!preg_match($pattern, $password)) {
        $error = "Password must contain at least 1 uppercase, 1 lowercase, 1 number, 1 special character and be at least 8 characters long.";
    } else {
        
        if (stripos($name, "Dr ") !== 0) {
            $name = "Dr " . $name;
        }

        $sql = "INSERT INTO doctors (name, email, password, specialization) 
                VALUES ('$name', '$email', '$password', '$specialization')";
        if ($conn->query($sql) === TRUE) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Registration</title>
    <style>
        * { box-sizing: border-box; margin:0; padding:0; font-family:'Segoe UI', Arial, sans-serif; }
        body {
            background: #FAF9EE;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

      
        header {
            position: fixed;
            top:0; left:0; width:100%;
            background:#A2AF9B;
            color:white;
            text-align:center;
            padding:20px;
            font-size:1.6em;
            font-weight:bold;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
            z-index:1000;
        }

        .container {
            max-width:420px;
            margin:120px auto 40px;
            background:#fff;
            padding:30px 35px;
            border-radius:20px;
            box-shadow:0 6px 18px rgba(0,0,0,0.1);
            border-left:6px solid #A2AF9B;
        }

        h2 {
            text-align:center;
            margin-bottom:20px;
            font-size:1.5em;
            color:#4a4a4a;
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
        }
        input:focus, select:focus {
            border-color:#8c9b7f;
            outline:none;
            box-shadow:0 0 6px rgba(140,155,127,0.4);
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

        .error {
            color:#d9534f;
            text-align:center;
            margin-bottom:15px;
            font-size:0.9em;
        }

        p.back-link {
            text-align:center;
            margin-top:15px;
            font-size:0.9em;
        }
        p.back-link a {
            color:#333;
            text-decoration:none;
            font-weight:bold;
        }
        p.back-link a:hover { text-decoration:underline; }

        @media(max-width:480px){
            .container { margin:100px 15px 30px; padding:20px; }
            header { font-size:1.3em; padding:15px; }
        }
    </style>
</head>
<body>
    <header>Doctor Registration</header>

    <div class="container">
        <h2>Create Your Account</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label>Name</label>
            <input type="text" name="name" placeholder="Enter full name" required>

            <label>Email</label>
            <input type="email" name="email" placeholder="Enter email" required>

            <label>Password</label>
            <input type="password" name="password" required
                   pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                   title="Must contain at least 1 uppercase, 1 lowercase, 1 number, 1 special character, and be at least 8 characters long">

            <label>Specialization</label>
            <select name="specialization" required>
                <option value="">-- Select Specialization --</option>
                <option value="General Physician">General Physician</option>
                <option value="Pediatrics">Pediatrics</option>
                <option value="Dermatology">Dermatology</option>
                <option value="Psychiatry">Psychiatry</option>
                <option value="Gynecology">Gynecology</option>
                <option value="Nutrition & Dietetics">Nutrition & Dietetics</option>
                <option value="ENT">ENT (Ear, Nose, Throat)</option>
                <option value="Endocrinology">Endocrinology</option>
                <option value="Gastroenterology">Gastroenterology</option>
                <option value="Pulmonology">Pulmonology</option>
                <option value="Orthopedics">Orthopedics</option>
                <option value="Sexology">Sexology</option>
                <option value="Counseling">Counseling / Therapy</option>
            </select>

            <button type="submit">Register</button>
        </form>
        <p class="back-link"><a href="login.php">⬅ Back to Login</a></p>
    </div>
</body>
</html>
