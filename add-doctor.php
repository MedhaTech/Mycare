
<?php include 'header.php'; ?>

<?php
include 'dbconnection.php';

function generateDoctorID($conn) {
    $result = $conn->query("SELECT MAX(CAST(SUBSTRING(doctor_id, 3) AS UNSIGNED)) AS max_id FROM doctors");
    $row = $result->fetch_assoc();
    $next = $row['max_id'] + 1;
    return 'MC' . str_pad($next, 3, '0', STR_PAD_LEFT);
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = generateDoctorID($conn);
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $qualification = $_POST['qualification'];
    $license = $_POST['license'];
    $experience = $_POST['experience'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $bank_name = $_POST['bank_name'];
    $account_name = $_POST['account_name'];
    $account_number = $_POST['account_number'];
    $branch = $_POST['branch'];
    $ifsc = $_POST['ifsc'];
    $status = 'Active';

    $stmt = $conn->prepare("INSERT INTO doctors (doctor_id, name, phone, email, department, designation, dob, gender, qualification, license, experience, address1, address2, city, state, pincode, bank_name, account_name, account_number, branch, ifsc, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssssssssisissssssssss", $doctor_id, $name, $phone, $email, $department, $designation, $dob, $gender, $qualification, $license, $experience, $address1, $address2, $city, $state, $pincode, $bank_name, $account_name, $account_number, $branch, $ifsc, $status);

    if ($stmt->execute()) {
        $success = "Doctor added successfully with ID <strong>$doctor_id</strong>.";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<main class="main-wrapper clearfix" style="margin-top: 30px;">
    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="widget-holder col-md-12">
                    <div class="widget-bg">
                        <div class="widget-body">
                            <h4 class="box-title">Add New Doctor</h4>
                            <p>Fill out the form to add a new doctor.</p>

                            <?php if ($success): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                            <?php elseif ($error): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Doctor Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Department</label>
                                        <input type="text" name="department" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Designation</label>
                                        <input type="text" name="designation" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>DOB</label>
                                        <input type="date" name="dob" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Gender</label>
                                        <select name="gender" class="form-control">
                                            <option>Male</option>
                                            <option>Female</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Qualification</label>
                                        <input type="text" name="qualification" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>License</label>
                                        <input type="text" name="license" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Experience (Years)</label>
                                        <input type="number" name="experience" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Address Line 1</label>
                                        <input type="text" name="address1" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Address Line 2</label>
                                        <input type="text" name="address2" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>City</label>
                                        <input type="text" name="city" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>State</label>
                                        <input type="text" name="state" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Pincode</label>
                                        <input type="text" name="pincode" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Bank Name</label>
                                        <input type="text" name="bank_name" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Account Name</label>
                                        <input type="text" name="account_name" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Account Number</label>
                                        <input type="text" name="account_number" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Branch</label>
                                        <input type="text" name="branch" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>IFSC</label>
                                        <input type="text" name="ifsc" class="form-control">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-rounded">Add Doctor</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
