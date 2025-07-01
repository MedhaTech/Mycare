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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Expense Slip - <?= htmlspecialchars($row['voucher_no']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 30px;
            color: #000;
        }

        .slip-container {
            border: 2px solid #007bff;
            padding: 20px;
            max-width: 700px;
            margin: auto;
            page-break-inside: avoid;
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            vertical-align: top;
        }

        .btn-print {
            display: block;
            margin: 30px auto 0;
            padding: 10px 25px;
            background-color: #28a745;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        @media print {
            .btn-print {
                display: none;
            }

            body, html {
                margin: 0;
                padding: 0;
            }

            .slip-container {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>

<div class="slip-container">
    <h2>Expense Slip</h2>
    <table>
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

<button class="btn-print" onclick="window.print()">🖨️ Print / Save as PDF</button>

</body>
</html>
