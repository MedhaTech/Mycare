<?php
require 'dompdf/autoload.inc.php';
include 'dbconnection.php';
include 'init.php';

use Dompdf\Dompdf;
$dompdf = new Dompdf();
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid procedure ID.");
}

$id = intval($_GET['id']);

$sql = "SELECT 
            p.*, 
            pat.name AS patient_name, 
            pat.phone, 
            pat.gender, 
            pat.dob,
            d.name AS doctor_name,
            d.designation,
            d.department,
            a.appointment_id AS op_id
            FROM procedures p
            LEFT JOIN patients pat ON p.patient_id = pat.id
            LEFT JOIN doctors d ON p.doctor_id = d.id
            LEFT JOIN appointments a ON p.appointment_id = a.id
            WHERE p.id = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) die("Procedure not found.");

$procedureId = $data['procedure_id'] ?? ('PR' . str_pad($id, 4, '0', STR_PAD_LEFT));
$opId = $data['op_id'] ?? '-';
$patientName = preg_replace('/[^a-zA-Z0-9]/', '_', $data['patient_name']);
$filename = "{$patientName}_{$procedureId}.pdf";

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
    <h3>Procedure Slip</h3>
    <p><strong>Procedure ID:</strong> ' . htmlspecialchars($procedureId) . '</p>
    <p><strong>OP ID:</strong> ' . htmlspecialchars($opId) . '</p>
    <p><strong>Date & Time:</strong> ' . $data['procedure_date'] . ' ' . date('h:i A', strtotime($data['procedure_time'])) . '</p>
    <hr>
    <h4>Patient Details</h4>
    <p><strong>Name:</strong> ' . $data['patient_name'] . '</p>
    <p><strong>Phone:</strong> ' . ($data['phone'] ?: '—') . '</p>
    <p><strong>Gender:</strong> ' . ($data['gender'] ?: '—') . '</p>
    <p><strong>DOB:</strong> ' . ($data['dob'] ?: '—') . '</p>
    <hr>
    <h4>Doctor Details</h4>
    <p><strong>Name:</strong> ' . $data['doctor_name'] . '</p>
    <p><strong>Designation:</strong> ' . ($data['designation'] ?: '—') . '</p>
    <p><strong>Department:</strong> ' . ($data['department'] ?: '—') . '</p>
    <hr>
    <h4>Procedure Info</h4>
    <p><strong>Type:</strong> ' . htmlspecialchars($data['type']) . '</p>
    <p><strong>Status:</strong> ' . ucfirst($data['status']) . '</p>
    <p><strong>Duration:</strong> ' . $data['duration'] . ' minutes</p>
    <p><strong>Reason:</strong> ' . nl2br(htmlspecialchars($data['reason'] ?: '—')) . '</p>
    <p><strong>Fee:</strong> Rs.' . htmlspecialchars($data['fee']) . '</p>
    <p><strong>Payment Mode:</strong> ' . htmlspecialchars($data['payment_mode']) . '</p>';

    if ($data['status'] === 'Cancelled' && !empty($data['cancellation_reason'])) {
        $html .= '<hr><h4>Cancellation Reason</h4><p class="text-danger">' . nl2br(htmlspecialchars($data['cancellation_reason'])) . '</p>';
    }

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4');
    $dompdf->render();
    $dompdf->stream($filename, ['Attachment' => true]);
    exit;
