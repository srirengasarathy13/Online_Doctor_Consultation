<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== "doctor") {
    header("Location: ../auth/login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];

$sql = "SELECT a.appointment_id, p.name AS patient_name, p.age, a.symptoms, a.days, a.reply
        FROM appointment a
        JOIN patients p ON a.patient_id = p.patient_id
        WHERE a.doctor_id = $doctor_id
        ORDER BY a.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointments</title>
    <style>
* { box-sizing: border-box; margin:0; padding:0; font-family: 'Segoe UI', Arial, sans-serif; }
body { background:#FAF9EE; color:#333; min-height:100vh; }

header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background:#A2AF9B; 
    color:white;
    text-align:center;
    padding:20px 30px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
    z-index:1000;
}
header h1 { font-size:1.8em; }


.container {
    max-width: 1000px;
    margin: 120px auto 30px; 
    padding:20px;
}


.table-card {
    background: #FFF;
    padding:20px;
    border-radius:15px;
    box-shadow:0 6px 18px rgba(0,0,0,0.08);
    overflow-x:auto;
}

table {
    width:100%;
    border-collapse: collapse;
}
th, td {
    padding:12px 15px;
    text-align:left;
}
th {
    background:#A2AF9B; 
    color:white;
    font-weight:bold;
}
tr:nth-child(even) { background:#EFEFEF; }
tr:hover { background:#E0D6C6; }

td .btn {
    display:inline-block;
    background:#A2AF9B; 
    color:white;
    padding:6px 12px;
    border-radius:5px;
    text-decoration:none;
    font-size:0.9em;
    transition:0.3s;
}
td .btn:hover { 
    background:#8c9b7f;
    transform:translateY(-2px);
}


.back-btn {
    display:inline-block;
    background:#333; 
    color:white;
    padding:10px 20px;
    border-radius:5px;
    text-decoration:none;
    transition:0.3s;
}
.back-btn:hover { 
    background:#555;
    transform:translateY(-2px);
}


@media(max-width:768px){
    table, thead, tbody, th, td, tr { display:block; }
    tr { margin-bottom:15px; border-bottom:2px solid #ddd; }
    th { display:none; }
    td { display:flex; justify-content:space-between; padding:10px; }
    td::before { content: attr(data-label); font-weight:bold; }
}
</style>
</head>
<body>
    <header>
        <h1>Patient Appointments</h1>
    </header>

    <div class="container">
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Age</th>
                        <th>Symptoms</th>
                        <th>Days</th>
                        <th>Reply</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="Patient"><?= htmlspecialchars($row['patient_name']) ?></td>
                            <td data-label="Age"><?= $row['age'] ?></td>
                            <td data-label="Symptoms"><?= htmlspecialchars($row['symptoms']) ?></td>
                            <td data-label="Days"><?= $row['days'] ?></td>
                            <td data-label="Reply"><?= $row['reply'] ? htmlspecialchars($row['reply']) : 'Pending' ?></td>
                            <td data-label="Action">
                                <a class="btn" href="reply.php?id=<?= $row['appointment_id'] ?>">Reply</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center; padding:20px;">No appointments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <br>
        <a class="back-btn" href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
</body>
</html>
