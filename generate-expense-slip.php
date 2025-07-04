<?php
include 'dbconnection.php';
include 'init.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid expense ID.");
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM expenses WHERE id = $id");

if ($result->num_rows !== 1) {
    die("Expense not found.");
}

$row = $result->fetch_assoc(); 
?>
<div class="slip-container" style="font-family: 'Segoe UI', sans-serif; padding: 20px; border: 2px solid #007bff; max-width: 700px; margin: auto; background: #fff;">
    <h6 style="text-align: center; color: #007bff; margin-top: 0;">Expense Slip</h6>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr><td><strong>Voucher No:</strong></td><td><?= htmlspecialchars($row['voucher_no']) ?></td></tr>
        <tr><td><strong>Name:</strong></td><td><?= htmlspecialchars($row['expense_name']) ?></td></tr>
        <tr><td><strong>Mobile:</strong></td><td><?= htmlspecialchars($row['mobile']) ?></td></tr>
        <tr><td><strong>Category:</strong></td><td><?= htmlspecialchars($row['category']) ?></td></tr>
        <tr><td><strong>Expense Date:</strong></td><td><?= htmlspecialchars($row['expense_date']) ?></td></tr>
        <tr><td><strong>Mode of Payment:</strong></td><td><?= htmlspecialchars($row['payment_mode']) ?></td></tr>
        <tr><td><strong>Amount:</strong></td><td>₹ <?= number_format($row['amount'], 2) ?></td></tr>
        <tr><td><strong>Status:</strong></td><td><?= htmlspecialchars($row['payment_status']) ?></td></tr>
        <tr><td><strong>Details:</strong></td><td><?= nl2br(htmlspecialchars($row['details'])) ?></td></tr>
        <tr><td><strong>Remarks:</strong></td><td><?= nl2br(htmlspecialchars($row['remarks'])) ?></td></tr>
    </table>
</div>
