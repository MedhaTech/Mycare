<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
include 'header.php';

$conn = new mysqli("localhost", "root", "", "medical");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = $error = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateDoctor'])) {
    $id = $_POST['id'];
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
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE doctors SET name=?, phone=?, email=?, department=?, designation=?, dob=?, gender=?, qualification=?, license=?, experience=?, address1=?, address2=?, city=?, state=?, pincode=?, bank_name=?, account_name=?, account_number=?, branch=?, ifsc=?, status=? WHERE id=?");

    $stmt->bind_param("ssssssssissssssssssssi", $name, $phone, $email, $department, $designation, $dob, $gender, $qualification, $license, $experience, $address1, $address2, $city, $state, $pincode, $bank_name, $account_name, $account_number, $branch, $ifsc, $status, $id);

    if ($stmt->execute()) {
        $success = "Doctor updated successfully!";
    } else {
        $error = "Error updating doctor: " . $stmt->error;
    }
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM doctors WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $doctor = $result->fetch_assoc();
    } else {
        die("Doctor not found.");
    }
} else {
    die("Invalid request.");
}
?>

<main class="main-wrapper clearfix" style="margin-top: 30px;">
    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="widget-holder col-md-10 mx-auto">
                    <div class="widget-bg">
                        <div class="widget-body">
                            <h4 class="box-title">Edit Doctor Details</h4>
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                            <?php elseif ($error): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <input type="hidden" name="id" value="<?php echo $doctor['id']; ?>">

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Doctor ID</label>
                                        <input type="text" class="form-control" value="<?php echo $doctor['doctor_id']; ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="<?php echo $doctor['name']; ?>" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control" value="<?php echo $doctor['phone']; ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="<?php echo $doctor['email']; ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Department</label>
                                        <input type="text" name="department" class="form-control" value="<?php echo $doctor['department']; ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Designation</label>
                                        <input type="text" name="designation" class="form-control" value="<?php echo $doctor['designation']; ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>DOB</label>
                                        <input type="date" name="dob" class="form-control" value="<?php echo $doctor['dob']; ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Gender</label>
                                        <select name="gender" class="form-control">
                                            <option <?php if ($doctor['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                            <option <?php if ($doctor['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                            <option <?php if ($doctor['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Qualification</label>
                                        <input type="text" name="qualification" class="form-control" value="<?php echo $doctor['qualification']; ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>License</label>
                                        <input type="text" name="license" class="form-control" value="<?php echo $doctor['license']; ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Experience (in years)</label>
                                        <input type="number" name="experience" class="form-control" value="<?php echo $doctor['experience']; ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="Active" <?php if ($doctor['status'] == 'Active') echo 'selected'; ?>>Active</option>
                                            <option value="Inactive" <?php if ($doctor['status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Address Line 1</label>
                                        <input type="text" name="address1" class="form-control" value="<?php echo $doctor['address1']; ?>">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Address Line 2</label>
                                        <input type="text" name="address2" class="form-control" value="<?php echo $doctor['address2']; ?>">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>City</label>
                                        <input type="text" name="city" class="form-control" value="<?php echo $doctor['city']; ?>">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>State</label>
                                        <input type="text" name="state" class="form-control" value="<?php echo $doctor['state']; ?>">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Pincode</label>
                                        <input type="text" name="pincode" class="form-control" value="<?php echo $doctor['pincode']; ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Bank Name</label>
                                        <input type="text" name="bank_name" class="form-control" value="<?php echo $doctor['bank_name']; ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Account Name</label>
                                        <input type="text" name="account_name" class="form-control" value="<?php echo $doctor['account_name']; ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Account Number</label>
                                        <input type="text" name="account_number" class="form-control" value="<?php echo $doctor['account_number']; ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Branch</label>
                                        <input type="text" name="branch" class="form-control" value="<?php echo $doctor['branch']; ?>">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>IFSC</label>
                                        <input type="text" name="ifsc" class="form-control" value="<?php echo $doctor['ifsc']; ?>">
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <a href="doctors-list.php" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" name="updateDoctor" class="btn btn-primary">Update Doctor</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</main>

<?php include 'footer.php'; ?>
