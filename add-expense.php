<?php
include 'header.php';
include 'dbconnection.php';
include 'init.php';

$errors = [];
$voucher_no = $expense_name = $mobile = $category = $details = $remarks = $expense_date = $amount = $payment_mode = "";

// Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voucher_no     = ($_POST['voucher_no']);
    $expense_name   = ($_POST['expense_name']);
    $mobile         = ($_POST['mobile']);
    $category       = ($_POST['category']);
    $details        = ($_POST['details']);
    $remarks        = ($_POST['remarks']);
    $expense_date   = ($_POST['expense_date']);
    $amount         = ($_POST['amount']);
    $payment_mode   = ($_POST['payment_mode']);
    $payment_status = "Paid";

    // Validation
    if (empty($voucher_no))   $errors['voucher_no'] = "Voucher number is required.";
    if (empty($expense_name)) $errors['expense_name'] = "Name is required.";
    if (empty($mobile))       $errors['mobile'] = "Mobile number is required.";
    elseif (!preg_match('/^[0-9]{10}$/', $mobile)) $errors['mobile'] = "Enter a valid 10-digit number.";

    if (empty($category))     $errors['category'] = "Category is required.";
    if (empty($details))      $errors['details'] = "Details are required.";
    if (empty($expense_date)) $errors['expense_date'] = "Date is required.";
    if (empty($amount))       $errors['amount'] = "Amount is required.";
    elseif (!is_numeric($amount) || $amount <= 0) $errors['amount'] = "Amount must be a positive number.";

    if (empty($payment_mode)) $errors['payment_mode'] = "Payment mode is required.";
    if (empty($remarks))      $errors['remarks'] = "Remarks are required.";

    // Insert into database
    if (empty($errors)) {
        $sql = "INSERT INTO expenses (voucher_no, expense_name, mobile, category, details, remarks, expense_date, amount, payment_mode, payment_status)
                VALUES ('$voucher_no', '$expense_name', '$mobile', '$category', '$details', '$remarks', '$expense_date', '$amount', '$payment_mode', '$payment_status')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Expense added successfully'); window.location='expenses-list.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
}
?>

<main class="main-wrapper clearfix" style="min-height: 597.025px;">
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">MyCare New Expense</h6>
                <p class="page-title-description mr-0 d-none d-md-inline-block">Fill out the form to log a new expense</p>
            </div>
            <div class="page-title-right d-none d-sm-inline-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                    <li class="breadcrumb-item active">Add Expense</li>
                </ol>
            </div>
        </div>

        <div class="widget-holder col-md-14">
            <div class="widget-bg">
                <div class="widget-body">
                    <h6>Log New Expense</h6>

                    <form method="POST" class="mt-4">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label>Voucher No<span style="color:red">*</span></label>
                                <input type="text" name="voucher_no" class="form-control" value="<?= htmlspecialchars($voucher_no) ?>">
                                <?php if (isset($errors['voucher_no'])): ?><small class="text-danger"><?= $errors['voucher_no'] ?></small><?php endif; ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Person Name <span style="color:red">*</span></label>
                                <input type="text" name="expense_name" class="form-control" value="<?= htmlspecialchars($expense_name) ?>">
                                <?php if (isset($errors['expense_name'])): ?><small class="text-danger"><?= $errors['expense_name'] ?></small><?php endif; ?>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Mobile No <span style="color:red">*</span></label>
                                <input type="text" name="mobile" class="form-control" value="<?= htmlspecialchars($mobile) ?>">
                                <?php if (isset($errors['mobile'])): ?><small class="text-danger"><?= $errors['mobile'] ?></small><?php endif; ?>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Expense Category <span style="color:red">*</span></label>
                                <select name="category" class="form-control">
                                    <option value="">Select</option>
                                    <option <?= $category == "Electricity" ? "selected" : "" ?>>Electricity</option>
                                    <option <?= $category == "Water" ? "selected" : "" ?>>Water</option>
                                    <option <?= $category == "Medical" ? "selected" : "" ?>>Medical</option>
                                    <option <?= $category == "Supplies" ? "selected" : "" ?>>Supplies</option>
                                    <option <?= $category == "Others" ? "selected" : "" ?>>Others</option>
                                </select>
                                <?php if (isset($errors['category'])): ?><small class="text-danger"><?= $errors['category'] ?></small><?php endif; ?>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Details <span style="color:red">*</span></label>
                                <input type="text" name="details" class="form-control" value="<?= htmlspecialchars($details) ?>">
                                <?php if (isset($errors['details'])): ?><small class="text-danger"><?= $errors['details'] ?></small><?php endif; ?>
                            </div>

                            <div class="form-group col-md-2">
                                <label>Expense Date <span style="color:red">*</span></label>
                                <input type="date" name="expense_date" class="form-control" value="<?= htmlspecialchars($expense_date) ?>">
                                <?php if (isset($errors['expense_date'])): ?><small class="text-danger"><?= $errors['expense_date'] ?></small><?php endif; ?>
                            </div>

                            <div class="form-group col-md-2">
                                <label>Amount <span style="color:red">*</span></label>
                                <input type="number" name="amount" class="form-control" value="<?= htmlspecialchars($amount) ?>">
                                <?php if (isset($errors['amount'])): ?><small class="text-danger"><?= $errors['amount'] ?></small><?php endif; ?>
                            </div>

                            <div class="form-group col-md-2">
                                <label>Mode of Payment <span style="color:red">*</span></label>
                                <select name="payment_mode" class="form-control">
                                    <option value="">Select</option>
                                    <option <?= $payment_mode == "Cash" ? "selected" : "" ?>>Cash</option>
                                    <option <?= $payment_mode == "Card" ? "selected" : "" ?>>Card</option>
                                    <option <?= $payment_mode == "UPI" ? "selected" : "" ?>>UPI</option>
                                    <option <?= $payment_mode == "Bank Transfer" ? "selected" : "" ?>>Bank Transfer</option>
                                </select>
                                <?php if (isset($errors['payment_mode'])): ?><small class="text-danger"><?= $errors['payment_mode'] ?></small><?php endif; ?>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Remarks <span style="color:red">*</span></label>
                                <textarea name="remarks" class="form-control" rows="3"><?= htmlspecialchars($remarks) ?></textarea>
                                <?php if (isset($errors['remarks'])): ?><small class="text-danger"><?= $errors['remarks'] ?></small><?php endif; ?>
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

<?php include 'footer.php'; ?>
