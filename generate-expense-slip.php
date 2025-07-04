<?php
include 'dbconnection.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid expense ID.");
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM expenses WHERE id = $id");

if ($result->num_rows !== 1) {
    die("Expense not found.");
}

$row = $result->fetch_assoc();
$voucherNo = 'VCH' . str_pad($row['id'], 3, '0', STR_PAD_LEFT);
$date = date('Y-m-d', strtotime($row['expense_date']));
?>

<div class="slip-container" style="width: 720px; font-family: 'Segoe UI', sans-serif; border: 1px solid #ccc; padding: 20px;">
    <div style="background-color: #007bff; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 20px; font-weight: bold;">Money Receipt</div>
        <div style="text-align: right; font-size: 12px;">
            <div>Voucher No: <strong><?= $voucherNo ?></strong></div>
            <div>Date: <strong><?= $date ?></strong></div>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <div><strong>Name:</strong></div>
            <div><?= $row['expense_name'] ?></div>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <div><strong>Category:</strong></div>
            <div><?= $row['category'] ?></div>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <div><strong>Details:</strong></div>
            <div><?= $row['details'] ?: '–' ?></div>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <div><strong>Payment Mode:</strong></div>
            <div><?= $row['payment_mode'] ?></div>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <div><strong>Amount:</strong></div>
            <div>₹ <?= number_format($row['amount'], 2) ?></div>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <div><strong>Payment Status:</strong></div>
            <div><?= ucfirst($row['payment_status']) ?></div>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <div><strong>Remarks:</strong></div>
            <div><?= $row['remarks'] ?: '–' ?></div>
        </div>
    </div>

    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
        Thank you for your payment. This is a system-generated receipt.
    </div>
</div>
