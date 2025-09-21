<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== "patient") {
    header("Location: ../auth/login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];


if(isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $conn->query("DELETE FROM appointment WHERE appointment_id=$delete_id AND patient_id=$patient_id");
    header("Location: my_appointments.php");
    exit();
}

$sql = "SELECT a.appointment_id, d.name AS doctor_name, d.specialization, a.symptoms, a.days, a.reply, a.created_at
        FROM appointment a
        JOIN doctors d ON a.doctor_id = d.doctor_id
        WHERE a.patient_id = $patient_id
        ORDER BY a.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments</title>
    <style>
        * { box-sizing: border-box; margin:0; padding:0; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background: linear-gradient(to bottom, #FAF9EE, #E9E6D5); color:#333; min-height:100vh; }
        header {
            background:#A2AF9B;
            color:white;
            padding:20px 40px;
            text-align:center;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
        }
        header h1 { font-size:1.8em; }

        .container {
            max-width:1000px;
            margin:40px auto;
            background:#FFF;
            padding:25px;
            border-radius:15px;
            box-shadow:0 6px 20px rgba(0,0,0,0.1);
        }

        table { width:100%; border-collapse:collapse; margin-bottom:20px; }
        th, td { padding:14px; text-align:left; }
        th { background:#A2AF9B; color:white; text-transform:uppercase; }
        tr:nth-child(even) { background:#FAF9EE; }
        td { font-size:0.95em; vertical-align:middle; }

        .reply { color:#228B22; font-weight:bold; }
        .pending { color:#FF4500; font-weight:bold; }
        .delete-btn {
            background:#FF4C4C;
            color:white;
            padding:6px 12px;
            border:none;
            border-radius:5px;
            cursor:pointer;
            transition:0.3s;
            text-decoration:none;
        }
        .delete-btn:hover { background:#d93636; }

        .back-btn {
            display:inline-block;
            background:#A2AF9B;
            color:white;
            padding:10px 20px;
            border-radius:50px;
            text-decoration:none;
            transition:0.3s;
        }
        .back-btn:hover { background:#8c9b7f; }

        @media(max-width:768px){
            .container { padding:20px; }
            table th, table td { font-size:0.85em; padding:10px; }
            .delete-btn { padding:5px 10px; }
        }
    </style>
</head>
<body>
    <header>
        <h1>My Appointments</h1>
    </header>
    <div class="container">
        <?php if($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Doctor</th>
                <th>Specialization</th>
                <th>Symptoms</th>
                <th>Days</th>
                <th>Reply</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                <td><?= htmlspecialchars($row['specialization']) ?></td>
                <td><?= htmlspecialchars($row['symptoms']) ?></td>
                <td><?= $row['days'] ?></td>
                <td class="<?= $row['reply'] ? 'reply' : 'pending' ?>">
                    <?= $row['reply'] ? $row['reply'] : 'Pending...' ?>
                </td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a class="delete-btn" href="?delete_id=<?= $row['appointment_id'] ?>" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p style="text-align:center; padding:20px;">No appointments found.</p>
        <?php endif; ?>
        <a class="back-btn" href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
</body>
</html>
