<?php
require 'vendor/autoload.php';
include 'dbconnection.php';
use Dompdf\Dompdf;

$id = intval($_GET['id']);
$sql = "SELECT * FROM expenses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) exit("Expense not found.");

$voucher = 'VCH' . str_pad($data['id'], 3, '0', STR_PAD_LEFT);
$filename = $voucher . "_ExpenseSlip.pdf";

$html = '
<style>
    body { font-family: "Segoe UI", sans-serif; padding: 30px; }
    h2 { text-align: center; color: #007bff; }
    h4, h5 { color: #333; }
    table { width: 100%; margin-bottom: 20px; }
    td { padding: 4px 0; }
</style>

<h2>MyCare Clinic</h2>
<hr>
<h4>Expense Slip</h4>
<table>
    <tr><td><strong>Voucher No:</strong></td><td>' . $voucher . '</td></tr>
    <tr><td><strong>Date:</strong></td><td>' . $data['expense_date'] . '</td></tr>
</table>

<h5>Expense Details</h5>
<table>
    <tr><td><strong>Name:</strong></td><td>' . $data['expense_name'] . '</td></tr>
    <tr><td><strong>Category:</strong></td><td>' . $data['category'] . '</td></tr>
    <tr><td><strong>Details:</strong></td><td>' . ($data['details'] ?: '—') . '</td></tr>
    <tr><td><strong>Remarks:</strong></td><td>' . ($data['remarks'] ?: '—') . '</td></tr>
</table>

<h5>Payment Info</h5>
<table>
    <tr><td><strong>Amount:</strong></td><td>₹' . number_format($data['amount'], 2) . '</td></tr>
    <tr><td><strong>Payment Mode:</strong></td><td>' . $data['payment_mode'] . '</td></tr>
    <tr><td><strong>Status:</strong></td><td>' . ucfirst($data['payment_status']) . '</td></tr>
</table>
';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream($filename, ["Attachment" => true]);
