<?php
require 'dbconnection.php';
$id = intval($_GET['id']);

$sql = "SELECT 
            pr.*, 
            p.name AS patient_name, 
            p.phone, 
            p.gender, 
            p.dob,
            d.name AS doctor_name,
            d.designation,
            d.department
        FROM procedures pr
        LEFT JOIN patients p ON pr.patient_id = p.id
        LEFT JOIN doctors d ON pr.doctor_id = d.id
        WHERE pr.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) exit('Invalid slip.');

function badge($s) {
    $status = strtolower(trim($s));
    return match ($status) {
        'confirmed' => "<span class='badge badge-success'>Confirmed</span>",
        'in progress' => "<span class='badge badge-warning'>In Progress</span>",
        'completed' => "<span class='badge badge-primary'>Completed</span>",
        'cancelled' => "<span class='badge badge-danger'>Cancelled</span>",
        default => "<span class='badge badge-secondary'>Unknown</span>"
    };
}

$opId = htmlspecialchars($data['appointment_id'] ?? '-');
?>

<div style="font-family: 'Segoe UI', sans-serif; background: white; padding: 30px; border: 2px solid #007bff; border-radius: 10px;">
    <h2 style="color: #007bff; text-align:center; margin-top: 0;">MyCare Clinic</h2>

    <h4 style="margin-top: 30px; font-size: 18px;">Procedure Slip</h4>
    <hr>
    <table style="width: 100%; margin-bottom: 25px;">
        <tr>
            <td><strong>Procedure ID:</strong></td>
            <td><?= htmlspecialchars($data['procedure_id']) ?: 'PR' . str_pad($data['id'], 4, '0', STR_PAD_LEFT) ?></td>
        </tr>

        <tr>
            <td><strong>OP ID:</strong></td>
            <td>#<?= $opId ?></td>
        </tr>
        <tr>
            <td><strong>Date & Time:</strong></td>
            <td><?= date("Y-m-d", strtotime($data['procedure_date'])) . " " . date("h:i A", strtotime($data['procedure_time'])) ?></td>
        </tr>
    </table>

    <h5 style="font-size: 16px; margin-bottom: 10px;">Patient Details</h5>
    <table style="width: 100%; margin-bottom: 25px;">
        <tr><td><strong>Name:</strong></td><td><?= htmlspecialchars($data['patient_name']) ?></td></tr>
        <tr><td><strong>Phone:</strong></td><td><?= $data['phone'] ?: '—' ?></td></tr>
        <tr><td><strong>Gender:</strong></td><td><?= $data['gender'] ?: '—' ?></td></tr>
        <tr><td><strong>DOB:</strong></td><td><?= $data['dob'] ?: '—' ?></td></tr>
    </table>

    <h5 style="font-size: 16px; margin-bottom: 10px;">Doctor Details</h5>
    <table style="width: 100%; margin-bottom: 25px;">
        <tr><td><strong>Name:</strong></td><td>Dr. <?= htmlspecialchars($data['doctor_name']) ?></td></tr>
        <tr><td><strong>Designation:</strong></td><td><?= $data['designation'] ?: '—' ?></td></tr>
        <tr><td><strong>Department:</strong></td><td><?= $data['department'] ?: '—' ?></td></tr>
    </table>

    <h5 style="font-size: 16px; margin-bottom: 10px;">Procedure Info</h5>
    <table style="width: 100%;">
        <tr><td><strong>Type:</strong></td><td><?= htmlspecialchars($data['type']) ?></td></tr>
        <tr><td><strong>Status:</strong></td><td><?= badge($data['status']) ?></td></tr>
        <tr><td><strong>Duration:</strong></td><td><?= intval($data['duration']) ?> minutes</td></tr>
        <tr><td><strong>Reason:</strong></td><td><?= nl2br(htmlspecialchars($data['reason'] ?? '—')) ?></td></tr>
        <tr><td><strong>Fee:</strong></td><td>Rs.<?= htmlspecialchars($data['fee']) ?></td></tr>
        <tr><td><strong>Payment Mode:</strong></td><td><?= htmlspecialchars($data['payment_mode']) ?></td></tr>
        <?php if (strtolower($data['status']) === 'cancelled' && !empty($data['cancellation_reason'])): ?>
        <tr><td><strong>Cancellation Reason:</strong></td><td class="text-danger"><?= nl2br(htmlspecialchars($data['cancellation_reason'])) ?></td></tr>
        <?php endif; ?>
    </table>
</div>
<div style="margin-top: 30px; text-align: center;">
    <a href="download-procedure-slip.php?id=<?= $data['id'] ?>" class="btn btn-primary">
        <i class="fa fa-download"></i> Download PDF
    </a>
</div>

