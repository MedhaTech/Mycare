<?php
require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;

include 'dbconnection.php';
include 'init.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid procedure ID.");
}

$id = intval($_GET['id']);

$sql = "SELECT pr.*, p.name AS patient_name, d.name AS doctor_name
        FROM procedures pr
        LEFT JOIN patients p ON pr.patient_id = p.id
        LEFT JOIN doctors d ON pr.doctor_id = d.id
        WHERE pr.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Procedure not found.");
}

// Create HTML for PDF
$html = '
<style>
    body { font-family: Arial, sans-serif; font-size: 14px; }
    h2 { text-align: center; color: #007bff; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    td { padding: 8px; border-bottom: 1px solid #ccc; }
</style>

<h2>Procedure Slip</h2>
<table>
    <tr><td><strong>Patient Name:</strong></td><td>' . htmlspecialchars($data['patient_name']) . '</td></tr>
    <tr><td><strong>Doctor Name:</strong></td><td>' . htmlspecialchars($data['doctor_name']) . '</td></tr>
    <tr><td><strong>Date:</strong></td><td>' . $data['procedure_date'] . '</td></tr>
    <tr><td><strong>Time:</strong></td><td>' . date("h:i A", strtotime($data['procedure_time'])) . '</td></tr>
    <tr><td><strong>Type:</strong></td><td>' . htmlspecialchars($data['type']) . '</td></tr>
    <tr><td><strong>Status:</strong></td><td>' . htmlspecialchars($data['status']) . '</td></tr>
    <tr><td><strong>Duration:</strong></td><td>' . intval($data['duration']) . ' minutes</td></tr>
    <tr><td><strong>Fee:</strong></td><td>₹' . $data['fee'] . '</td></tr>
    <tr><td><strong>Reason:</strong></td><td>' . nl2br(htmlspecialchars($data['reason'])) . '</td></tr>
</table>';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$filename = $data['patient_name'] . '-' . 'PR' . str_pad($data['id'], 4, '0', STR_PAD_LEFT) . '.pdf';

// Send PDF as download
$dompdf->stream($filename, ["Attachment" => 1]);
exit;
