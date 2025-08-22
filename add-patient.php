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


<main class="main-wrapper clearfix">
    <div class="container">
      <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Add Patient</h6>
                        <p class="page-title-description mr-0 d-none d-md-inline-block">Fill below details to add the new patient</p>
                    </div>
                    <!-- /.page-title-left -->
                    <div class="page-title-right d-none d-sm-inline-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Add Patient</li>
                        </ol>
                    </div>
                    <!-- /.page-title-right -->
      </div>

        <div class="widget-holder col-md-12">
            <div class="widget-bg">
                <div class="widget-body">
                   <div>
                            <h6>Add Patient form</h6>
                        </div>
                    <form method="POST" action="save-patient.php">
                        <br>
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
                                <label>Date of Birth </label>
                                <input type="date" name="dob" class="form-control" id="dob" >
                            </div>
                            <div class="form-group col-md-3">
                                <label>Age</label>
                                <input type="text" name="age" class="form-control" id="age" style="color: #333; background-color: #f9f9f9;" readonly>
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
                                <label>ABHA Number </label>
                                <input type="text" name="abha_number" class="form-control" >
                            </div>
                            <div class="form-group col-md-4">
                                <label>Aadhar Number </label>
                                <input type="text" name="aadhar_number" class="form-control"  pattern="[0-9]{12}" maxlength="12" title="Enter a valid 12-digit Aadhar number">
                            </div>

                            <div class="form-group col-12">
                                <h6>Address Information</h6>
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
                                <h6>Health Information</h6>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Blood Group </label>
                                <select name="blood_group" class="form-control" >
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
                            <div class="form-group col-md-2">
                                <label>Height</label>
                                <input type="text" name="height" class="form-control">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Weight</label>
                                <input type="text" name="weight" class="form-control">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Sugar</label>
                                <input type="text" name="sugar" class="form-control">
                            </div>
                            <div class="form-group col-md-2">
                             <label>BP </label>
                             <input type="text" name="bp" class="form-control" pattern="\d{2,3}/\d{2,3}" title="Enter BP like 120/80">
                                </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 text-left">
                                <button type="submit" class="btn btn-primary">+ Add Patient</button>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="patient-list.php" class="btn btn-secondary">Back to List</a>
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
