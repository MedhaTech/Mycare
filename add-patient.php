<?php
include 'header.php';
include 'dbconnection.php';
include 'init.php';

$message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $abha = $_POST['abha_number'];
    $aadhar = $_POST['aadhar_number'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $blood = $_POST['blood_group'];
    $height = $_POST['height_cm'];
    $weight = $_POST['weight_kg'];
    $sugar = $_POST['sugar_level'];
    $bp = $_POST['bp'];
    $doctor_id = $_POST['doctor_id'];

    $stmt = $conn->prepare("INSERT INTO patients (
        name, phone, dob, gender, abha_number, aadhar_number,
        address1, address2, city, state, pincode, blood_group,
        height_cm, weight_kg, sugar_level, bp, doctor_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssssssssssssddssi",
        $name, $phone, $dob, $gender, $abha, $aadhar,
        $address1, $address2, $city, $state, $pincode, $blood,
        $height, $weight, $sugar, $bp, $doctor_id
    );

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'> Patient added successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'> Failed to add patient: " . $conn->error . "</div>";
    }

    $stmt->close();
}

// Fetch doctor list
$doctors = $conn->query("SELECT id, name FROM doctors WHERE status='Active'");
?>

<div class="container mt-5">
    <h3 class="mb-4"> Add New Patient</h3>
    <?= $message ?>
    <form method="POST" class="bg-white p-4 shadow rounded">

        <div class="row">
            <div class="form-group col-md-6">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group col-md-6">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" required>
            </div>

            <div class="form-group col-md-4">
                <label>Date of Birth</label>
                <input type="date" name="dob" class="form-control" required>
            </div>

            <div class="form-group col-md-4">
                <label>Gender</label>
                <select name="gender" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Blood Group</label>
                <input type="text" name="blood_group" class="form-control">
            </div>

            <div class="form-group col-md-4">
                <label>ABHA Number</label>
                <input type="text" name="abha_number" class="form-control">
            </div>

            <div class="form-group col-md-4">
                <label>Aadhar Number</label>
                <input type="text" name="aadhar_number" class="form-control">
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
                <label>Height (cm)</label>
                <input type="number" step="0.01" name="height_cm" class="form-control">
            </div>

            <div class="form-group col-md-4">
                <label>Weight (kg)</label>
                <input type="number" step="0.01" name="weight_kg" class="form-control">
            </div>

            <div class="form-group col-md-4">
                <label>Sugar Level</label>
                <input type="number" step="0.01" name="sugar_level" class="form-control">
            </div>

            <div class="form-group col-md-6">
                <label>Blood Pressure (BP)</label>
                <input type="text" name="bp" class="form-control">
            </div>

            <div class="form-group col-md-6">
                <label>Consulting Doctor</label>
                <select name="doctor_id" class="form-control">
                    <option value="">-- Select Doctor --</option>
                    <?php while ($d = $doctors->fetch_assoc()): ?>
                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div class="text-right mt-3">
            <button type="submit" class="btn btn-primary">Save Patient</button>
            <a href="patient-list.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
