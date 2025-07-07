<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';
include 'dbconnection.php';
include 'init.php';

$success = "";
$error = "";


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
    $doj = $_POST['doj'];
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

    $stmt = $conn->prepare("UPDATE doctors SET name=?, phone=?, email=?, department=?, designation=?, dob=?, gender=?, qualification=?, license=?, experience=?, date_of_joining=?, address1=?, address2=?, city=?, state=?, pincode=?, bank_name=?, account_name=?, account_number=?, branch=?, ifsc=?, status=? WHERE id=?");

    $stmt->bind_param("ssssssssisssssssssssssi", $name, $phone, $email, $department, $designation, $dob, $gender, $qualification, $license, $experience, $doj, $address1, $address2, $city, $state, $pincode, $bank_name, $account_name, $account_number, $branch, $ifsc, $status, $id);

    if ($stmt->execute()) {
        echo "<script>
            setTimeout(function() {
                $.toast({
                    heading: 'Success',
                    text: 'Doctor updated successfully!',
                    showHideTransition: 'slide',
                    icon: 'success',
                    position: 'top-right'
                });
            }, 300);
            setTimeout(function() {
                window.location.href = 'doctors-list.php';
            }, 2000);
        </script>";
    } else {
        echo "<script>
            setTimeout(function() {
                $.toast({
                    heading: 'Error',
                    text: 'Failed to update doctor.',
                    showHideTransition: 'fade',
                    icon: 'error',
                    position: 'top-right'
                });
            }, 300);
        </script>";
    }

    $stmt->close();
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
$conn->close();
?>


<main class="main-wrapper clearfix">
    <div class="container">
    <div class="row page-title clearfix">
        <div class="page-title-left">
            <h6 class="page-title-heading mr-0 mr-r-5"> Edit Doctor</h6>
            <p class="page-title-description mr-0 d-none d-md-inline-block">Fill the form to edit a doctor.</p>
        </div>
        <div class="page-title-right d-none d-sm-inline-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit Doctor</li>
            </ol>
        </div>
    </div>
    </div>
            <div class="row">
                <div class="widget-holder col-md-9 mx-auto">
                    <div class="widget-bg">
                        <div class="widget-body">
                            <form method="POST" action="">
                                <input type="hidden" name="id" value="<?= $doctor['id'] ?>">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Doctor Name<span style="color: red;">*</span></label>
                                        <input type="text" name="name" class="form-control" required value="<?= $doctor['name'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Phone<span style="color: red;">*</span></label>
                                        <input type="text" name="phone" class="form-control" required pattern="\d{10}" value="<?= $doctor['phone'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Email<span style="color: red;">*</span></label>
                                        <input type="email" name="email" class="form-control" required value="<?= $doctor['email'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Department<span style="color: red;">*</span></label>
                                        <select name="department" class="form-control" required>
                                            <option value="">Select</option>
                                            <?php
                                            $departments = ["General Practitioner", "Cardiologist", "Dermatologist", "Gastroenterologist", "Neurologist", "Orthopedic", "Pediatrician", "Psychiatrist", "Physician"];
                                            foreach ($departments as $dept) {
                                                $selected = ($doctor['department'] === $dept) ? 'selected' : '';
                                                echo "<option value=\"$dept\" $selected>$dept</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Designation<span style="color: red;">*</span></label>
                                        <input type="text" name="designation" class="form-control" required value="<?= $doctor['designation'] ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Gender<span style="color: red;">*</span></label>
                                        <select name="gender" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="Male" <?= $doctor['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                                            <option value="Female" <?= $doctor['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>DOB<span style="color: red;">*</span></label>
                                        <input type="date" name="dob" class="form-control" required value="<?= $doctor['dob'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Qualification<span style="color: red;">*</span></label>
                                        <input type="text" name="qualification" class="form-control" required value="<?= $doctor['qualification'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>License<span style="color: red;">*</span></label>
                                        <input type="text" name="license" class="form-control" required value="<?= $doctor['license'] ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Experience (Years)<span style="color: red;">*</span></label>
                                        <input type="number" name="experience" class="form-control" required value="<?= $doctor['experience'] ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>DOJ<span style="color: red;">*</span></label>
                                        <input type="date" name="doj" class="form-control" required value="<?= $doctor['date_of_joining'] ?? '' ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Address Line 1<span style="color: red;">*</span></label>
                                        <input type="text" name="address1" class="form-control" required value="<?= $doctor['address1'] ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Address Line 2</label>
                                        <input type="text" name="address2" class="form-control" value="<?= $doctor['address2'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>City<span style="color: red;">*</span></label>
                                        <input type="text" name="city" class="form-control" required value="<?= $doctor['city'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>State<span style="color: red;">*</span></label>
                                        <select name="state" class="form-control" required>
                                            <option value="">Select</option>
                                            <?php
                                            $states = ["Karnataka", "Andhra Pradesh", "Tamil Nadu", "Telangana", "Kerala"];
                                            foreach ($states as $state) {
                                                $selected = ($doctor['state'] === $state) ? 'selected' : '';
                                                echo "<option value=\"$state\" $selected>$state</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Pincode<span style="color: red;">*</span></label>
                                        <input type="text" name="pincode" class="form-control" required value="<?= $doctor['pincode'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Bank Name<span style="color: red;">*</span></label>
                                        <input type="text" name="bank_name" class="form-control" required value="<?= $doctor['bank_name'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Account Name<span style="color: red;">*</span></label>
                                        <input type="text" name="account_name" class="form-control" required value="<?= $doctor['account_name'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Account Number<span style="color: red;">*</span></label>
                                        <input type="text" name="account_number" class="form-control" required value="<?= $doctor['account_number'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Branch<span style="color: red;">*</span></label>
                                        <input type="text" name="branch" class="form-control" required value="<?= $doctor['branch'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>IFSC<span style="color: red;">*</span></label>
                                        <input type="text" name="ifsc" class="form-control" required value="<?= $doctor['ifsc'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Status<span style="color: red;">*</span></label>
                                        <select name="status" class="form-control" required>
                                            <option value="Active" <?= $doctor['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                                            <option value="Inactive" <?= $doctor['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-6 text-left">
                                        <button type="submit" name="updateDoctor" class="btn btn-primary">Update Doctor</button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="doctors-list.php" class="btn btn-secondary">Back to List</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- jQuery first (required for Toastr) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Toastr plugin -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>


<?php include 'footer.php'; ?>