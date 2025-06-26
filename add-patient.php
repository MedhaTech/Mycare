

<?php include 'header.php'; ?>

<main class="main-wrapper clearfix" style="margin-top: 30px;">
    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="widget-holder col-md-12">
                    <div class="widget-bg">
                        <div class="widget-body" style="background-color: white; color: black;">
                            <h2 class="box-title">Add Patient</h2>
                            <p>Register a new patient in your clinic.</p>

                            <form action="insert-patient.php" method="POST">
                                <h5 class="mt-4 mb-3">Patient Information</h5>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Patient Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control" name="phone" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Date of Birth</label>
                                        <input type="date" class="form-control" name="dob" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Gender</label>
                                        <select name="gender" class="form-control" required>
                                            <option value="">Select gender</option>
                                            <option>Male</option>
                                            <option>Female</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>ABHA Number</label>
                                        <input type="text" class="form-control" name="abha">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>AADHAR Number</label>
                                        <input type="text" class="form-control" name="aadhar">
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Address Information</h5>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Address 1</label>
                                        <input type="text" class="form-control" name="address1">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Address 2</label>
                                        <input type="text" class="form-control" name="address2">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>City</label>
                                        <input type="text" class="form-control" name="city">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>State</label>
                                        <input type="text" class="form-control" name="state">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Pincode</label>
                                        <input type="text" class="form-control" name="pincode">
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Health Information</h5>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>Blood Group</label>
                                        <input type="text" class="form-control" name="blood_group">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Height (cm)</label>
                                        <input type="text" class="form-control" name="height">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Weight (kg)</label>
                                        <input type="text" class="form-control" name="weight">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Sugar</label>
                                        <input type="text" class="form-control" name="sugar">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>BP</label>
                                        <input type="text" class="form-control" name="bp">
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Submit Patient Info</button>
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
