<?php
require 'dbconnection.php';
$id = intval($_GET['id']);
$sql = "SELECT 
            a.*, 
            a.source,
            p.name AS patient_name, 
            p.phone, 
            p.gender, 
            p.dob,
            d.name AS doctor_name,
            d.designation,
            d.department
        FROM appointments a
        LEFT JOIN patients p ON a.patient_id = p.id
        LEFT JOIN doctors d ON a.doctor_id = d.id
        WHERE a.id = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) exit('Invalid slip.');

function badge($s) {
    $status = strtolower(trim($s));
    switch ($status) {
        case 'confirmed':
            $color = 'success';
            break;
        case 'in progress':
            $color = 'warning';
            break;
        case 'completed':
            $color = 'primary';
            break;
        default:
            $color = 'secondary';
    }

    return "<span class='badge badge-{$color}'>".htmlspecialchars(ucwords($s))."</span>";
}

?>
<div style="font-family: 'Segoe UI', sans-serif; background: white; padding: 30px; border: 2px solid #007bff; border-radius: 10px;">
    <h2 style="color: #007bff; text-align:center; margin-top: 0;">MyCare Clinic</h2>
    
    <h4 style="margin-top: 30px; font-size: 18px;">Appointment Slip</h4>
    <hr>
    <table style="width: 100%; margin-bottom: 25px;">
        <tr>
            <td><strong>Appointment ID:</strong></td>
            <td><?= htmlspecialchars($data['appointment_id']) ?></td>
        </tr>
        <tr>
            <td><strong>Date & Time:</strong></td>
            <td><?= date("Y-m-d", strtotime($data['appointment_date'])) . " " . date("h:i A", strtotime($data['appointment_time'])) ?></td>
        </tr>
    </table>

    <h5 style="font-size: 16px; margin-bottom: 10px;">Patient Details</h5>
    <table style="width: 100%; margin-bottom: 25px;">
        <tr><td><strong>Name:</strong></td><td><?= htmlspecialchars($data['patient_name']) ?></td></tr>
        <tr><td><strong>Phone:</strong></td><td><?= $data['phone'] ?: '—' ?></td></tr>
        <tr><td><strong>Gender:</strong></td><td><?= $data['gender'] ?: '—' ?></td></tr>
        <tr><td><strong>DOB:</strong></td><td><?= $data['dob'] ?: '—' ?></td></tr>
        <tr><td><strong>Source:</strong></td><td><?= $data['source'] ?: '—' ?></td></tr>
    </table>

    <h5 style="font-size: 16px; margin-bottom: 10px;">Doctor Details</h5>
    <table style="width: 100%; margin-bottom: 25px;">
        <tr><td><strong>Name:</strong></td><td><?= htmlspecialchars($data['doctor_name']) ?></td></tr>
        <tr><td><strong>Designation:</strong></td><td><?= $data['designation'] ?: '—' ?></td></tr>
        <tr><td><strong>Department:</strong></td><td><?= $data['department'] ?: '—' ?></td></tr>
    </table>

    <h5 style="font-size: 16px; margin-bottom: 10px;">Appointment Info</h5>
    <table style="width: 100%;">
        <tr><td><strong>Type:</strong></td><td><?= htmlspecialchars($data['type']) ?></td></tr>
        <tr><td><strong>Status:</strong></td><td><?= badge($data['status']) ?></td></tr>
        <tr><td><strong>Duration:</strong></td><td><?= intval($data['duration']) ?> minutes</td></tr>
        <tr><td><strong>Reason:</strong></td><td><?= nl2br(htmlspecialchars($data['reason'] ?? '—')) ?></td></tr>
        <tr><td><strong>Fee:</strong></td><td>Rs.<?= htmlspecialchars($data['fee']) ?></td></tr>
    </table>
</div>
