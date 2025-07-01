<?php
include 'dbconnection.php';
include 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $type = $_POST['appointment_type'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $duration = $_POST['duration'];
    $reason = $_POST['reason'];
    $status = $_POST['status'];
    $fee = $_POST['consultation_fee'];

    $stmt = $conn->prepare("INSERT INTO appointments 
        (patient_id, doctor_id, appointment_type, date, time, duration, reason, status, consultation_fee) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssissi", $patient_id, $doctor_id, $type, $date, $time, $duration, $reason, $status, $fee);

    if ($stmt->execute()) {
        header("Location: appointments.php?success=1");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
