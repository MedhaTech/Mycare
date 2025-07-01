<?php
include 'dbconnection.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid appointment ID.");
}

$id = intval($_GET['id']);

// Fetch appointment info
$sql = "SELECT a.*, p.name AS patient_name, d.name AS doctor_name
        FROM appointments a
        LEFT JOIN patients p ON a.patient_id = p.id
        LEFT JOIN doctors d ON a.doctor_id = d.id
        WHERE a.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Appointment not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointment Slip</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 30px;
            color: #000;
        }
        .slip-container {
            border: 2px solid #007bff;
            padding: 20px;
            max-width: 700px;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .btn-print {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #28a745;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        @media print {
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="slip-container">
    <h2>Appointment Slip</h2>
    <table>
        <tr><td><strong>Patient Name:</strong></td><td><?= htmlspecialchars($data['patient_name']) ?></td></tr>
        <tr><td><strong>Doctor Name:</strong></td><td><?= htmlspecialchars($data['doctor_name']) ?></td></tr>
        <tr><td><strong>Date:</strong></td><td><?= $data['appointment_date'] ?></td></tr>
        <tr><td><strong>Time:</strong></td><td><?= date("h:i A", strtotime($data['appointment_time'])) ?></td></tr>
        <tr><td><strong>Type:</strong></td><td><?= htmlspecialchars($data['type']) ?></td></tr>
        <tr><td><strong>Status:</strong></td><td><?= htmlspecialchars($data['status']) ?></td></tr>
        <tr><td><strong>Duration:</strong></td><td><?= intval($data['duration']) ?> minutes</td></tr>
        <tr><td><strong>Fee:</strong></td><td>₹<?= $data['fee'] ?></td></tr>
        <tr><td><strong>Reason:</strong></td><td><?= nl2br(htmlspecialchars($data['reason'])) ?></td></tr>
    </table>
</div>

<button class="btn-print" onclick="window.print()">🖨️ Print or Save as PDF</button>

</body>
</html>
