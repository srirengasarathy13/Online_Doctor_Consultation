<?php
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";

    if (!preg_match($pattern, $password)) {
        $error = "Password must contain at least 1 uppercase, 1 lowercase, 1 number, 1 special character and be at least 8 characters long.";
    } else {
        $sql = "INSERT INTO patients (name, email, password, age, gender) 
                VALUES ('$name', '$email', '$password', '$age', '$gender')";
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
    <title>Patient Registration</title>
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
            font-size:1.6em;
            color:#4a4a4a;
        }

        label {
            display:block;
            margin-top:18px;
            font-weight:bold;
            font-size:1em;
        }

        input, select {
            width:100%;
            padding:14px;
            margin-top:8px;
            border:1px solid #A2AF9B;
            border-radius:10px;
            font-size:1em;
            transition:0.3s;
        }
        input:focus, select:focus {
            border-color:#8c9b7f;
            outline:none;
            box-shadow:0 0 6px rgba(140,155,127,0.4);
        }

        button {
            width:100%;
            margin-top:25px;
            background:#A2AF9B;
            color:white;
            padding:14px;
            border:none;
            border-radius:10px;
            font-size:1.1em;
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
            font-size:0.95em;
        }

        p.back-link {
            text-align:center;
            margin-top:18px;
            font-size:0.95em;
        }
        p.back-link a {
            color:#333;
            text-decoration:none;
            font-weight:bold;
        }
        p.back-link a:hover { text-decoration:underline; }

        @media(max-width:480px){
            .container { max-width:95%; padding:20px; margin:100px auto 30px; }
            header { font-size:1.3em; padding:15px; }
        }
    </style>
</head>
<body>
    <header>Patient Registration</header>

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

            <label>Age</label>
            <input type="number" name="age" min="1" max="120" required>

            <label>Gender</label>
            <select name="gender" required>
                <option value="">-- Select Gender --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <button type="submit">Register</button>
        </form>
        <p class="back-link"><a href="login.php">⬅ Back to Login</a></p>
    </div>
</body>
</html>
