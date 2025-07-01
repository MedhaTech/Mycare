<?php include 'header.php'; ?>
<?php
include 'dbconnection.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Step 1: Capture POST data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $dob = $_POST['dob'];
    $doj = $_POST['doj'];
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

    // Step 2: Insert without doctor_id
    $stmt = $conn->prepare("INSERT INTO doctors (
        name, phone, email, department, designation, dob, date_of_joining, gender,
        qualification, license, experience, address1, address2, city, state, pincode,
        bank_name, account_name, account_number, branch, ifsc, status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssssssssssisssssssssss",
        $name, $phone, $email, $department, $designation, $dob, $doj,
        $gender, $qualification, $license, $experience, $address1, $address2, $city,
        $state, $pincode, $bank_name, $account_name, $account_number, $branch, $ifsc, $status
    );

    if ($stmt->execute()) {
        // Step 3: Generate doctor_id (like MC001)
        $last_id = $conn->insert_id;
        $formatted_id = str_pad($last_id, 3, "0", STR_PAD_LEFT);
        $doctor_code = "MC" . $formatted_id;

        // Step 4: Update the row with doctor_id
        $update = $conn->prepare("UPDATE doctors SET doctor_id = ? WHERE id = ?");
        $update->bind_param("si", $doctor_code, $last_id);
        $update->execute();
        $update->close();

        // Step 5: Show Toastr and redirect
        echo "<script>
            setTimeout(function() {
                $.toast({
                    heading: 'Success',
                    text: 'Doctor added successfully!',
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
                    text: 'Failed to add doctor.',
                    showHideTransition: 'fade',
                    icon: 'error',
                    position: 'top-right'
                });
            }, 300);
        </script>";
    }

    $stmt->close();
}
$conn->close();
?>
<main class="main-wrapper clearfix" style="margin-top: 30px;">
    <div class="container">
        <div class="widget-list">
           <div class="col-12 d-flex justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-3">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Doctor</li>
                    </ol>
                </nav>
            </div>
                <div class="widget-holder col-md-12">
                    <div class="widget-bg">
                        <div class="widget-body">
                            <h4 class="box-title">Add New Doctor</h4>
                            <p>Fill out the form to add a new doctor.</p>
                            <form method="POST" action="">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Doctor Name<span style="color: red;">*</span></label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Phone<span style="color: red;">*</span></label>
                                        <input type="text" name="phone" class="form-control" required pattern="\d{10}" title="Enter a valid 10-digit phone number">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Email<span style="color: red;">*</span></label>
                                        <input type="email" name="email" class="form-control" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.(com|in)$" title="Enter a valid email with .com or .in">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Department<span style="color: red;">*</span></label>
                                        <select name="department" class="form-control" required>
                                            <option value="" disabled selected>Select</option>
                                            <option>General Practitioner</option>
                                            <option>Cardiologist</option>
                                            <option>Dermatologist</option>
                                            <option>Gastroenterologist</option>
                                            <option>Neurologist</option>
                                            <option>Orthopedic</option>
                                            <option>Pediatrician</option>
                                            <option>Psychiatrist</option>
                                            <option>Physician</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Designation<span style="color: red;">*</span></label>
                                        <input type="text" name="designation" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Gender<span style="color: red;">*</span></label>
                                        <select name="gender" class="form-control" required>
                                            <option value="" disabled selected>Select</option>
                                            <option>Male</option>
                                            <option>Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>DOB<span style="color: red;">*</span></label>
                                        <input type="date" name="dob" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Qualification<span style="color: red;">*</span></label>
                                        <input type="text" name="qualification" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>License<span style="color: red;">*</span></label>
                                        <input type="text" name="license" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Experience (Years)<span style="color: red;">*</span></label>
                                        <input type="number" name="experience" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>DOJ<span style="color: red;">*</span></label>
                                        <input type="date" name="doj" class="form-control" required>
                                    </div>
                                    <div class="form-group col-12">
                                        <h4 class="box-title text-start mb-2">Address Info</h4>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Address Line 1<span style="color: red;">*</span></label>
                                        <input type="text" name="address1" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Address Line 2</label>
                                        <input type="text" name="address2" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>City<span style="color: red;">*</span></label>
                                        <input type="text" name="city" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>State<span style="color: red;">*</span></label>
                                        <select name="state" class="form-control" required>
                                            <option value="" disabled selected>Select</option>
                                            <option>Karnataka</option>
                                            <option>Andhra Pradesh</option>
                                            <option>Tamil Nadu</option>
                                            <option>Telangana</option>
                                            <option>Kerala</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Pincode<span style="color: red;">*</span></label>
                                        <input type="text" name="pincode" class="form-control" required>
                                    </div>
                                    <div class="form-group col-12">
                                        <h4 class="box-title text-start mb-2">Bank Info</h4>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Bank Name<span style="color: red;">*</span></label>
                                        <input type="text" name="bank_name" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Account Name<span style="color: red;">*</span></label>
                                        <input type="text" name="account_name" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Account Number<span style="color: red;">*</span></label>
                                        <input type="text" name="account_number" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Branch<span style="color: red;">*</span></label>
                                        <input type="text" name="branch" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>IFSC<span style="color: red;">*</span></label>
                                        <input type="text" name="ifsc" class="form-control" required>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6 text-left">
                                        <button type="submit" class="btn btn-primary">+ Add Doctor</button>
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

<!-- Client-side validation -->
<script>
document.querySelector("form").addEventListener("submit", function(e) {
    const phone = document.querySelector("input[name='phone']").value;
    const email = document.querySelector("input[name='email']").value;

    const phoneRegex = /^\d{10}$/;
    const emailRegex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.(com|in)$/i;

    if (!phoneRegex.test(phone)) {
        alert("Please enter a valid 10-digit phone number.");
        e.preventDefault();
    }

    if (!emailRegex.test(email)) {
        alert("Please enter a valid email ending in .com or .in");
        e.preventDefault();
    }
});
</script>

<?php include 'footer.php'; ?>
