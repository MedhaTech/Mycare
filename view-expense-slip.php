<?php
require 'dbconnection.php';
$id = intval($_GET['id']);
$sql = "SELECT * FROM expenses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) exit('Invalid slip.');

$voucher = 'VCH' . str_pad($data['id'], 3, '0', STR_PAD_LEFT);
?>

<div style="font-family: 'Segoe UI', sans-serif; background: white; padding: 30px; border: 2px solid #007bff; border-radius: 10px;">
    <h2 style="color: #007bff; text-align:center; margin-top: 0;">MyCare Clinic</h2>

    <h4 style="margin-top: 30px; font-size: 18px;">Expense Slip</h4>
    <hr>
    <table style="width: 100%; margin-bottom: 25px;">
        <tr><td><strong>Voucher No:</strong></td><td><?= $voucher ?></td></tr>
        <tr><td><strong>Date:</strong></td><td><?= htmlspecialchars($data['expense_date']) ?></td></tr>
    </table>

    <h5 style="font-size: 16px; margin-bottom: 10px;">Expense Details</h5>
    <table style="width: 100%; margin-bottom: 25px;">
        <tr><td><strong>Name:</strong></td><td><?= htmlspecialchars($data['expense_name']) ?></td></tr>
        <tr><td><strong>Category:</strong></td><td><?= htmlspecialchars($data['category']) ?></td></tr>
        <tr><td><strong>Details:</strong></td><td><?= htmlspecialchars($data['details'] ?: '—') ?></td></tr>
        <tr><td><strong>Remarks:</strong></td><td><?= htmlspecialchars($data['remarks'] ?: '—') ?></td></tr>
    </table>

    <h5 style="font-size: 16px; margin-bottom: 10px;">Payment Info</h5>
    <table style="width: 100%;">
        <tr><td><strong>Amount:</strong></td><td>Rs.<?= number_format($data['amount'], 2) ?></td></tr>
        <tr><td><strong>Payment Mode:</strong></td><td><?= htmlspecialchars($data['payment_mode']) ?></td></tr>
        <tr><td><strong>Status:</strong></td><td>
            <span class="badge badge-<?= strtolower($data['payment_status']) === 'paid' ? 'success' : 'warning' ?>">
                <?= ucfirst($data['payment_status']) ?>
            </span>
        </td></tr>
    </table>
</div>

<script>
document.getElementById("downloadExpenseSlipBtn").setAttribute("onclick", "window.location.href='download-expense-slip.php?id=<?= $data['id'] ?>'");
</script>

