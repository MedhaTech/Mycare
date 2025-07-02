<?php include 'header.php'; ?>
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
    <div class="container">
        <div class="col-12 d-flex justify-content-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-3">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Patient</li>
                </ol>
            </nav>
        </div>

        <div class="widget-holder col-md-12">
            <div class="widget-bg">
                <div class="widget-body">
                    <h4 class="box-title">Add Patient</h4>
                    <p>Fill below details to add the new patient</p>
                    <form method="POST" action="save-patient.php">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Name <span style="color:red">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Phone Number <span style="color:red">*</span></label>
                                <input type="text" name="phone" class="form-control" required pattern="[0-9]{10}" maxlength="10" title="Enter a 10-digit phone number">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Date of Birth <span style="color:red">*</span></label>
                                <input type="date" name="dob" class="form-control" id="dob" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Age</label>
                                <input type="text" name="age" class="form-control" id="age" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Gender <span style="color:red">*</span></label>
                                <select name="gender" class="form-control" required>
                                    <option value="" selected disabled>Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>ABHA Number <span style="color:red">*</span></label>
                                <input type="text" name="abha_number" class="form-control" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Aadhar Number <span style="color:red">*</span></label>
                                <input type="text" name="aadhar_number" class="form-control" required pattern="[0-9]{12}" maxlength="12" title="Enter a valid 12-digit Aadhar number">
                            </div>

                            <div class="form-group col-12">
                                <h5 class="mt-4">Address Information</h5>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Address 1</label>
                                <input type="text" name="address1" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Address 2</label>
                                <input type="text" name="address2" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label>City</label>
                                <input type="text" name="city" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label>State</label>
                                <select name="state" class="form-control">
                                    <option value="" selected disabled>Select</option>
                                    <option>Karnataka</option>
                                    <option>Telangana</option>
                                    <option>Tamil Nadu</option>
                                    <option>Kerala</option>
                                    <option>Andhra Pradesh</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Pincode</label>
                                <input type="text" name="pincode" class="form-control">
                            </div>

                            <div class="form-group col-12">
                                <h5 class="mt-4">Health Information</h5>
                            </div>
                            <div class="form-group col-md-2-3">
                                <label>Blood Group <span style="color:red">*</span></label>
                                <select name="blood_group" class="form-control" required>
                                    <option value="" selected disabled>Select</option>
                                    <option>A+</option>
                                    <option>A-</option>
                                    <option>B+</option>
                                    <option>B-</option>
                                    <option>AB+</option>
                                    <option>AB-</option>
                                    <option>O+</option>
                                    <option>O-</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2-3">
                                <label>Height</label>
                                <input type="text" name="height" class="form-control">
                            </div>
                            <div class="form-group col-md-2-3">
                                <label>Weight</label>
                                <input type="text" name="weight" class="form-control">
                            </div>
                            <div class="form-group col-md-2-3">
                                <label>Sugar</label>
                                <input type="text" name="sugar" class="form-control">
                            </div>
                            <div class="form-group col-md-2-3">
                             <label>BP </label>
                             <input type="text" name="bp" class="form-control" pattern="\d{2,3}/\d{2,3}" title="Enter BP like 120/80">
                                </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 text-left">
                                <button type="submit" class="btn btn-primary">+ Add Patient</button>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="patients-list.php" class="btn btn-secondary">Back to List</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Age Auto Calculate Script -->
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
</script>

<?php include 'footer.php'; ?>
