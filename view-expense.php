<?php include 'header.php'; ?>
<?php include 'dbconnection.php';
include 'init.php'; ?>

<?php
if (!isset($_GET['id'])) {
    echo "<script>alert('No expense ID provided.'); window.location='expenses-list.php';</script>";
    exit;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM expenses WHERE id = $id");

if ($result->num_rows !== 1) {
    echo "<script>alert('Expense not found.'); window.location='expenses-list.php';</script>";
    exit;
}

$row = $result->fetch_assoc();
?>

<div class="container mt-4">
    <h4 class="mb-2">View Expense Details</h4>
    <div class="card p-4">
        <div class="row mb-3">
            <div class="col-md-3"><strong>Voucher No:</strong></div>
            <div class="col-md-9"><?= $row['voucher_no']; ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3"><strong>Person Name:</strong></div>
            <div class="col-md-9"><?= $row['expense_name']; ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3"><strong>Mobile No:</strong></div>
            <div class="col-md-9"><?= $row['mobile']; ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3"><strong>Category:</strong></div>
            <div class="col-md-9"><?= $row['category']; ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3"><strong>Details:</strong></div>
            <div class="col-md-9"><?= $row['details']; ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3"><strong>Expense Date:</strong></div>
            <div class="col-md-9"><?= $row['expense_date']; ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3"><strong>Amount:</strong></div>
            <div class="col-md-9">₹ <?= number_format($row['amount'], 2); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3"><strong>Mode of Payment:</strong></div>
            <div class="col-md-9"><?= $row['payment_mode']; ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3"><strong>Remarks:</strong></div>
            <div class="col-md-9"><?= $row['remarks']; ?></div>
        </div>
        <div class="row mb-2">
            <div class="col-md-3"><strong>Status:</strong></div>
            <div class="col-md-9"><span class="badge badge-success"><?= $row['payment_status']; ?></span></div>
        </div>
        <a href="expenses-list.php" class="btn btn-secondary mt-3">Back to List</a>
    </div>
</div>

<?php include 'footer.php'; ?>
