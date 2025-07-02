<?php include 'header.php'; ?>
<?php include 'dbconnection.php';
include 'init.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voucher_no     = $_POST['voucher_no'];
    $expense_name   = $_POST['expense_name'];
    $mobile         = $_POST['mobile'];
    $category       = $_POST['category'];
    $details        = $_POST['details'];
    $remarks        = $_POST['remarks'];
    $expense_date   = $_POST['expense_date'];
    $amount         = $_POST['amount'];
    $payment_mode   = $_POST['payment_mode'];
    $payment_status = "Paid"; // default

    $sql = "INSERT INTO expenses (voucher_no, expense_name, mobile, category, details, remarks, expense_date, amount, payment_mode, payment_status)
            VALUES ('$voucher_no', '$expense_name', '$mobile', '$category', '$details', '$remarks', '$expense_date', '$amount', '$payment_mode', '$payment_status')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Expense added successfully'); window.location='expenses-list.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<div class="container mt-4">
    <h4>Log New Expense</h4>
    <p class="text-muted">Fill out the form to log a new expense.</p>

    <form method="POST" class="mt-4">
        <div class="row">
            <div class="form-group col-md-3">
                <label>Voucher No</label>
                <input type="text" name="voucher_no" class="form-control" required>
            </div>
            <div class="form-group col-md-3">
                <label>Person Name</label>
                <input type="text" name="expense_name" class="form-control" required>
            </div>
            <div class="form-group col-md-3">
                <label>Mobile No</label>
                <input type="text" name="mobile" class="form-control">
            </div>
            <div class="form-group col-md-3">
                <label>Expense Category</label>
                <select name="category" class="form-control" required>
                    <option value="">Select</option>
                    <option value="Electricity">Electricity</option>
                    <option value="Water">Water</option>
                    <option value="Medical">Medical</option>
                    <option value="Supplies">Supplies</option>
                    <option value="Others">Others</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
                <label>Details</label>
                <input type="text" name="details" class="form-control" required>
            </div>
            <div class="form-group col-md-3">
                <label>Expense Date</label>
                <input type="date" name="expense_date" class="form-control" required>
            </div>
            <div class="form-group col-md-2">
                <label>Amount</label>
                <input type="number" name="amount" class="form-control" required>
            </div>
            <div class="form-group col-md-3">
                <label>Mode of Payment</label>
                <select name="payment_mode" class="form-control" required>
                    <option value="">Select</option>
                    <option value="Cash">Cash</option>
                    <option value="Card">Card</option>
                    <option value="UPI">UPI</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Remarks</label>
            <input type="text" name="remarks" class="form-control">
        </div>

        <div class="d-flex justify-content-between mt-3">
            <button type="submit" class="btn btn-primary">Add Expense</button>
            <a href="expenses-list.php" class="btn btn-secondary">Back to list</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
