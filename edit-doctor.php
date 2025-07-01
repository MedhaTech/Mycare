<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
include 'header.php';
include 'dbconnection.php';
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

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5><strong>Edit Doctor Info</strong></h5>
                                    <?php if (isset($doctor['id'])): ?>
                                     <span><strong>Doctor ID :</strong> #<?php echo "MC" . str_pad($doctor['id'], 3, "0", STR_PAD_LEFT); ?></span>
                                     <?php endif; ?>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Doctor Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="<?php echo $doctor['name']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" value="<?php echo $doctor['phone']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="<?php echo $doctor['email']; ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Department<span style="color: red;">  *  </span></label>
                                        <select name="department" class="form-control">
                                            <option>General Practitioner</option>
                                            <option>Cardiologist </option>
                                            <option>Dermatologist r</option>
                                            <option>Gastroenterologist</option>
                                            <option>Neurologist </option>
                                            <option>Orthopedic </option>
                                            <option>Pediatrician </option>
                                            <option>Psychiatrist </option>
                                            <option>Physician </option>
                                            </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Designation <span class="text-danger">*</span></label>
                                        <input type="text" name="designation" class="form-control" value="<?php echo $doctor['designation']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Gender <span class="text-danger">*</span></label>
                                        <select name="gender" class="form-control" required>
                                            <option value="">Select</option>
                                            <option <?php if ($doctor['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                            <option <?php if ($doctor['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" name="dob" class="form-control" value="<?php echo $doctor['dob']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Qualification <span class="text-danger">*</span></label>
                                        <input type="text" name="qualification" class="form-control" value="<?php echo $doctor['qualification']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>License <span class="text-danger">*</span></label>
                                        <input type="text" name="license" class="form-control" value="<?php echo $doctor['license']; ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Experience (Years) <span class="text-danger">*</span></label>
                                        <input type="number" name="experience" class="form-control" value="<?php echo $doctor['experience']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Date of Joining <span class="text-danger">*</span></label>
                                        <input type="date" name="doj" class="form-control" value="<?php echo $doctor['doj'] ?? ''; ?>" required>
                                    </div>
                                
                                <div class="form-group col-md-4">
                                    <label>Status<span class="text-danger"> * </span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="Active" <?= ($doctor['status'] === 'Active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="Inactive" <?= ($doctor['status'] === 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                                <h5 class="mt-4"><strong>Address Info</strong></h5>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Address Line 1 <span class="text-danger">*</span></label>
                                        <input type="text" name="address1" class="form-control" value="<?php echo $doctor['address1']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Address Line 2</label>
                                        <input type="text" name="address2" class="form-control" value="<?php echo $doctor['address2']; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>City <span class="text-danger">*</span></label>
                                        <input type="text" name="city" class="form-control" value="<?php echo $doctor['city']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>State<span style="color: red;">  *  </span></label>
                                        <select name="state" class="form-control">
                                            <option>Karnataka</option>
                                            <option>Andhra Pradesh</option>
                                            <option>Tamil Nadu</option>
                                            <option>Telangana</option>
                                            <option>Kerala</option>
                                            </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Pincode <span class="text-danger">*</span></label>
                                        <input type="text" name="pincode" class="form-control" value="<?php echo $doctor['pincode']; ?>" required>
                                    </div>
                                </div>

                                <h5 class="mt-4"><strong>Account Info</strong></h5>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Bank Name <span class="text-danger">*</span></label>
                                        <input type="text" name="bank_name" class="form-control" value="<?php echo $doctor['bank_name']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Account Name <span class="text-danger">*</span></label>
                                        <input type="text" name="account_name" class="form-control" value="<?php echo $doctor['account_name']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Account Number <span class="text-danger">*</span></label>
                                        <input type="text" name="account_number" class="form-control" value="<?php echo $doctor['account_number']; ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Branch <span class="text-danger">*</span></label>
                                        <input type="text" name="branch" class="form-control" value="<?php echo $doctor['branch']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>IFSC <span class="text-danger">*</span></label>
                                        <input type="text" name="ifsc" class="form-control" value="<?php echo $doctor['ifsc']; ?>" required>
                                    </div>
                                </div>

                                <div class="form-group text-end mt-4">
                                    <button type="submit" name="updateDoctor" class="btn btn-primary">Update</button>
                                    <a href="doctors-list.php" class="btn btn-secondary">Back to List</a>
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
