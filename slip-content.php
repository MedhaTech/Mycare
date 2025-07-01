<?php
include 'dbconnection.php';

if (!isset($_GET['id'])) {
    echo "<p class='text-danger'>Invalid appointment ID.</p>";
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT a.*, p.name AS patient_name, p.phone, p.gender, p.dob,
               d.name AS doctor_name, d.designation, d.department
        FROM appointments a
        LEFT JOIN patients p ON a.patient_id = p.id
        LEFT JOIN doctors d ON a.doctor_id = d.id
        WHERE a.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p class='text-danger'>No slip found.</p>";
    exit;
}

$row = $result->fetch_assoc();
?>

<div id="pdfSlip" style="font-family: Arial, sans-serif;">
    <div style="border: 2px solid #007bff; padding: 20px; border-radius: 10px;">
        <h2 style="text-align:center; color:#007bff;">MyCare Clinic</h2>
        <hr>
        <h4>Appointment Slip</h4>
        <p><strong>Appointment ID:</strong> <?= htmlspecialchars($row['appointment_id']) ?></p>
        <p><strong>Date & Time:</strong> <?= htmlspecialchars($row['appointment_date']) . ' ' . date("h:i A", strtotime($row['appointment_time'])) ?></p>
        <hr>
        <h5>Patient Details</h5>
        <p><strong>Name:</strong> <?= htmlspecialchars($row['patient_name']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($row['gender']) ?></p>
        <p><strong>DOB:</strong> <?= htmlspecialchars($row['dob']) ?></p>
        <hr>
        <h5>Doctor Details</h5>
        <p><strong>Name:</strong> Dr. <?= htmlspecialchars($row['doctor_name']) ?></p>
        <p><strong>Designation:</strong> <?= htmlspecialchars($row['designation']) ?></p>
        <p><strong>Department:</strong> <?= htmlspecialchars($row['department']) ?></p>
        <hr>
        <h5>Appointment Info</h5>
        <p><strong>Type:</strong> <?= htmlspecialchars($row['type']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></p>
        <p><strong>Duration:</strong> <?= htmlspecialchars($row['duration']) ?> minutes</p>
        <p><strong>Reason:</strong> <?= htmlspecialchars($row['reason']) ?></p>
        <p><strong>Fee:</strong> ₹<?= htmlspecialchars($row['fee']) ?></p>
    </div>
</div>
