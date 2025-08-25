<?php
ob_start(); // ✅ Start output buffering before any output

include 'header.php';
include 'dbconnection.php';
include 'init.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expense_name   = $_POST['expense_name'];
    $mobile         = $_POST['mobile'];
    $category       = $_POST['category'];
    $details        = $_POST['details'];
    $remarks        = $_POST['remarks'];
    $expense_date   = $_POST['expense_date'];
    $amount         = $_POST['amount'];
    $payment_mode   = $_POST['payment_mode'];
    $payment_status = "Paid"; // default

    $sql = "INSERT INTO expenses (expense_name, mobile, category, details, remarks, expense_date, amount, payment_mode, payment_status)
            VALUES ('$expense_name', '$mobile', '$category', '$details', '$remarks', '$expense_date', '$amount', '$payment_mode', '$payment_status')";

    if ($conn->query($sql) === TRUE) {
        $new_id = $conn->insert_id;
        $voucher_no = "VOU-" . str_pad($new_id, 4, "0", STR_PAD_LEFT);
        $conn->query("UPDATE expenses SET voucher_no = '$voucher_no' WHERE id = $new_id");
        $_SESSION['toast_success'] = "Expense added successfully. Voucher No: $voucher_no";
        header("Location: expenses-list.php");
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>


<!-- Breadcrumb -->
<main class="main-wrapper clearfix exp">
           <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Add Expense</h6>
                        <p class="page-title-description mr-0 d-none d-md-inline-block">Fill below details to add the new expense</p>
                    </div>
                    <!-- /.page-title-left -->
                    <div class="page-title-right d-none d-sm-inline-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Add Expense</li>
                        </ol>
                    </div>
                    <!-- /.page-title-right -->
                </div>
            </div>


        
            <div class="widget-holder col-md-10 mx-auto">
                <div class="widget-bg">
                    <div class="widget-body">
                            <!-- <div>
                              <h6>Add Expense form</h6>
                            </div> -->
                            <div>
                                <form method="POST" class="mt-4">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Voucher No</label>
                                            <input type="text" class="form-control" value="Auto-generated after save" readonly>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Expense Date</label>
                                            <input type="date" name="expense_date" class="form-control" required>
                                        </div>
                                        <!-- <div class="form-group col-md-3">
                                            <label>Person Name</label>
                                            <input type="text" name="expense_name" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Mobile No</label>
                                            <input type="text" name="mobile" class="form-control">
                                        </div> -->
                                        <div class="form-group col-md-4">
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
                                        <div class="form-group col-md-8">
                                            <label>Person Name</label>
                                            <input type="text" name="expense_name" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Mobile No</label>
                                            <input type="text" name="mobile" class="form-control">
                                        </div>
                                        <!-- <div class="form-group col-md-4">
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
                                        </div> -->
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>Details</label>
                                            <textarea type="text" name="details" class="form-control" rows="4"></textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                         <!-- <div class="form-group col-md-6">
                                            <label>Mode of Payment</label>
                                            <select name="payment_mode" class="form-control" required>
                                                <option value="">Select</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Card">Card</option>
                                                <option value="UPI">UPI</option>
                                                <option value="Bank Transfer">Bank Transfer</option>
                                            </select>
                                        </div> -->
                                        <div class="form-group col-md-6">
                                            <label>Mode of Payment</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="payment_mode" id="cash" value="Cash" required>
                                                    <label class="form-check-label" for="cash">Cash</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="payment_mode" id="card" value="Card">
                                                    <label class="form-check-label" for="card">Card</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="payment_mode" id="upi" value="UPI">
                                                    <label class="form-check-label" for="upi">UPI</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="payment_mode" id="bank" value="Bank Transfer">
                                                    <label class="form-check-label" for="bank">Bank Transfer</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="payment_mode" id="cheque" value="Cheque">
                                                    <label class="form-check-label" for="cheque">Cheque</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Amount</label>
                                            <input type="number" name="amount" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>Remarks</label>
                                            <textarea name="remarks" class="form-control" rows="4"></textarea>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="submit" class="btn btn-primary">Add Expense</button>
                                        <a href="expenses-list.php" class="btn btn-secondary">Back to list</a>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
</main>

<script>
document.querySelector("input[name='mobile']").addEventListener("input", function(e) {
    this.value = this.value.replace(/\D/g, '').slice(0, 10); // Remove non-digits and limit to 10
});
</script>

<?php ob_end_flush(); ?>

<?php include 'footer.php'; ?>
