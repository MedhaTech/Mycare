<?php
require 'vendor/autoload.php';
include 'dbconnection.php';
include 'init.php';

use Dompdf\Dompdf;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid appointment ID.");
}

$id = intval($_GET['id']);

$sql = "SELECT a.*, 
               p.name AS patient_name, p.gender, p.dob, p.phone,
               d.name AS doctor_name, d.designation, d.department
        FROM appointments a
        LEFT JOIN patients p ON a.patient_id = p.id
        LEFT JOIN doctors d ON a.doctor_id = d.id
        WHERE a.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) die("Appointment not found.");

$opId = 'OP' . str_pad($data['patient_id'], 4, '0', STR_PAD_LEFT);
$patientName = preg_replace('/[^a-zA-Z0-9]/', '_', $data['patient_name']);
$filename = "{$patientName}_{$opId}.pdf";

$html = '
<style>
    body { font-family: "Segoe UI", sans-serif; padding: 30px; color: #000; }
    h2 { text-align: center; color: #007bff; }
    h4 { color: #555; }
    p { margin: 4px 0; }
    hr { margin: 20px 0; }
</style>
<h2>MyCare Clinic</h2>
<hr>
<h3>Appointment Slip</h3>
<p><strong>Appointment ID:</strong> ' . htmlspecialchars($data['appointment_id']) . '</p>
<p><strong>Date & Time:</strong> ' . $data['appointment_date'] . ' ' . date('h:i A', strtotime($data['appointment_time'])) . '</p>
<hr>
<h4>Patient Details</h4>
<p><strong>Name:</strong> ' . $data['patient_name'] . '</p>
<p><strong>Phone:</strong> ' . $data['phone'] . '</p>
<p><strong>Gender:</strong> ' . $data['gender'] . '</p>
<p><strong>DOB:</strong> ' . $data['dob'] . '</p>
<hr>
<h4>Doctor Details</h4>
<p><strong>Name:</strong> ' . $data['doctor_name'] . '</p>
<p><strong>Designation:</strong> ' . $data['designation'] . '</p>
<p><strong>Department:</strong> ' . $data['department'] . '</p>
<hr>
<h4>Appointment Info</h4>
<p><strong>Type:</strong> ' . $data['type'] . '</p>
<p><strong>Status:</strong> ' . ucfirst($data['status']) . '</p>
<p><strong>Duration:</strong> ' . $data['duration'] . ' minutes</p>
<p><strong>Reason:</strong> ' . ($data['reason'] ?: 'N/A') . '</p>
<p><strong>Fee:</strong> ₹' . $data['fee'] . '</p>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream($filename, ['Attachment' => true]);
exit;
