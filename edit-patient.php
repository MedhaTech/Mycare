<?php
include 'dbconnection.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Patient ID");
}

$patient_id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM patients WHERE id = $patient_id");

if ($result->num_rows !== 1) {
    die("Patient not found.");
}

$patient = $result->fetch_assoc();
include 'header.php';
?>
<style>
@media (min-width: 768px) {
  .col-md-2-3 {
    flex: 0 0 auto;
    width: 19%;
    margin-right: 1%;
  }
  .col-md-2-3:last-child {
    margin-right: 0;
  }
}
</style>

<main class="main-wrapper clearfix" style="margin-top: 30px;">
    <div class="container mt-4">
    <div class="row page-title clearfix">
        <div class="page-title-left">
            <h6 class="page-title-heading mr-0 mr-r-5"> Edit Patients</h6>
            <p class="page-title-description mr-0 d-none d-md-inline-block">Fill below details to edit the patient</p>
        </div>
        <div class="page-title-right d-none d-sm-inline-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit Patients</li>
            </ol>
        </div>
    </div>
</div>

        <div class="widget-holder col-md-9 mx-auto">
            <div class="widget-bg">
                <div class="widget-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="box-title">Edit Patient</h6>
                        </div>
                        <div class="text-muted font-weight-bold" style="font-size: 16px;">
                          Patient ID : <span style="color: #999;">#PT<?= str_pad($patient['id'], 3, '0', STR_PAD_LEFT); ?></span>
                        </div>
                    </div>
                    <form method="POST" action="update-patient.php">
                        <input type="hidden" name="id" value="<?= $patient['id'] ?>">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Name <span style="color:red">*</span></label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($patient['name']) ?>" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Phone Number <span style="color:red">*</span></label>
                                <input type="text" name="phone" class="form-control" value="<?= $patient['phone'] ?>" required pattern="[0-9]{10}" maxlength="10" title="Enter a 10-digit phone number">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Date of Birth <span style="color:red">*</span></label>
                                <input type="date" name="dob" class="form-control" id="dob" value="<?= $patient['dob'] ?>" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Age</label>
                                <input type="text" name="age" class="form-control" id="age" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Gender <span style="color:red">*</span></label>
                                <select name="gender" class="form-control" required>
                                    <option value="" disabled>Select</option>
                                    <option value="Male" <?= $patient['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= $patient['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                    <option value="Other" <?= $patient['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>ABHA Number <span style="color:red">*</span></label>
                                <input type="text" name="abha_number" class="form-control" value="<?= $patient['abha_number'] ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Aadhar Number <span style="color:red">*</span></label>
                                <input type="text" name="aadhar_number" class="form-control" value="<?= $patient['aadhar_number'] ?>" required pattern="[0-9]{12}" maxlength="12" title="Enter a 12-digit Aadhar number">
                            </div>

                            <div class="form-group col-12">
                                <h6 class="mt-4">Address Information</h6>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Address 1</label>
                                <input type="text" name="address1" class="form-control" value="<?= $patient['address1'] ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Address 2</label>
                                <input type="text" name="address2" class="form-control" value="<?= $patient['address2'] ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label>City</label>
                                <input type="text" name="city" class="form-control" value="<?= $patient['city'] ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label>State</label>
                                <select name="state" class="form-control">
                                    <option value="" disabled>Select</option>
                                    <?php
                                    $states = ["Karnataka", "Telangana", "Tamil Nadu", "Kerala", "Andhra Pradesh"];
                                    foreach ($states as $state) {
                                        $selected = ($patient['state'] == $state) ? 'selected' : '';
                                        echo "<option value='$state' $selected>$state</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Pincode</label>
                                <input type="text" name="pincode" class="form-control" value="<?= $patient['pincode'] ?>" pattern="[0-9]{6}" maxlength="6" title="Enter a 6-digit pincode">
                            </div>

                            <div class="form-group col-12">
                                <h6 class="mt-4">Health Information</h6>
                            </div>
                            <div class="form-group col-md-2-3">
                                <label>Blood Group <span style="color:red">*</span></label>
                                <select name="blood_group" class="form-control" required>
                                    <?php
                                    $bloodGroups = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"];
                                    foreach ($bloodGroups as $bg) {
                                        $selected = ($patient['blood_group'] == $bg) ? 'selected' : '';
                                        echo "<option value='$bg' $selected>$bg</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2-3">
                                <label>Height</label>
                                <input type="text" name="height_cm" class="form-control" value="<?= $patient['height_cm'] ?>">
                            </div>
                            <div class="form-group col-md-2-3">
                                <label>Weight</label>
                                <input type="text" name="weight_kg" class="form-control" value="<?= $patient['weight_kg'] ?>">
                            </div>
                            <div class="form-group col-md-2-3">
                                <label>Sugar</label>
                                <input type="text" name="sugar_level" class="form-control" value="<?= $patient['sugar_level'] ?>">
                            </div>
                            <div class="form-group col-md-2-3">
                                <label>BP</label>
                                <input type="text" name="bp" class="form-control" value="<?= $patient['bp'] ?>">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 text-left">
                                <button type="submit" class="btn btn-primary">Update Patient</button>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="patient-list.php" class="btn btn-secondary">Back to list</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('dob').addEventListener('change', function () {
        const dob = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        document.getElementById('age').value = age;
    });

    // Trigger change on load
    document.getElementById('dob').dispatchEvent(new Event('change'));
</script>

<?php include 'footer.php'; ?>
