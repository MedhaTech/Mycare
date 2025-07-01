<?php

include 'header.php';
include 'init.php';


if (!isset($_GET['id'])) {
    echo "<p class='text-danger'>No patient ID specified.</p>";
    exit();
}

$patientId = $_GET['id'];
include 'dbconnection.php';

$sql = "SELECT * FROM patients WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patientId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<p class='text-danger'>Patient not found.</p>";
    exit();
}

$patient = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<main class="main-wrapper clearfix">
    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="widget-holder col-md-12">
                    <div class="widget-bg">
                        <div class="widget-body">
                            <h4 class="mt-0">Edit Patient</h4>
                            <form action="update-patient.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo $patient['id']; ?>">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($patient['name']); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="dob">Date of Birth</label>
                                        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($patient['dob']); ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="gender">Gender</label>
                                        <select class="form-control" id="gender" name="gender">
                                            <option value="Male" <?php if ($patient['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                            <option value="Female" <?php if ($patient['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                            <option value="Other" <?php if ($patient['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="abha_number">ABHA Number</label>
                                        <input type="text" class="form-control" id="abha" name="abha" value="<?php echo htmlspecialchars($patient['abha_number']); ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="aadhar_number">AADHAR Number</label>
                                        <input type="text" class="form-control" id="aadhar" name="aadhar" value="<?php echo htmlspecialchars($patient['aadhar_number']); ?>">
                                    </div>
                                </div>

                                <h5 class="mt-4">Address Information</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="address1">Address 1</label>
                                        <input type="text" class="form-control" id="address1" name="address1" value="<?php echo htmlspecialchars($patient['address1']); ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="address2">Address 2</label>
                                        <input type="text" class="form-control" id="address2" name="address2" value="<?php echo htmlspecialchars($patient['address2']); ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($patient['city']); ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control" id="state" name="state" value="<?php echo htmlspecialchars($patient['state']); ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="pincode">Pincode</label>
                                        <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo htmlspecialchars($patient['pincode']); ?>">
                                    </div>
                                </div>

                                <h5 class="mt-4">Health Information</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="blood_group">Blood Group</label>
                                        <input type="text" class="form-control" id="blood_group" name="blood_group" value="<?php echo htmlspecialchars($patient['blood_group']); ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="height_cm">Height</label>
                                        <input type="text" class="form-control" id="height" name="height" value="<?php echo htmlspecialchars($patient['height_cm']); ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="weight_kg">Weight</label>
                                        <input type="text" class="form-control" id="weight" name="weight" value="<?php echo htmlspecialchars($patient['weight_kg']); ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="sugar">Sugar</label>
                                        <input type="text" class="form-control" id="sugar" name="sugar" value="<?php echo htmlspecialchars($patient['sugar_level']); ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="bp">BP</label>
                                        <input type="text" class="form-control" id="bp" name="bp" value="<?php echo htmlspecialchars($patient['bp']); ?>">
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">Update Patient</button>
                                    <a href="patient-list.php" class="btn btn-secondary">Cancel</a>
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