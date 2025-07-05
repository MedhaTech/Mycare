<?php
    include 'init.php';
    session_start();
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
        exit();
    }
    include 'header.php';
    include 'dbconnection.php';
    
    ?>

    <!-- Breadcrumb Heading -->
    <div class="container">
    <div class="row page-title clearfix">
        <div class="page-title-left">
            <h6 class="page-title-heading mr-0 mr-r-5">Doctors List</h6>
            <p class="page-title-description mr-0 d-none d-md-inline-block">Record of Doctors in MyCare Clinic.</p>
        </div>
        <div class="page-title-right d-none d-sm-inline-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Doctors List</li>
            </ol>
        </div>
    </div>
</div>

    <!-- Top Action Bar -->
    <!-- Top Buttons Row (Add + Search aligned horizontally) -->
   <div class="container">
    <div class="card p-4">
        <div class="page-title-left">
                            <div class="row align-items-center mb-3">
                            <div class="col-md-6">
                                <h4 class="page-title-heading mb-1">Doctors List</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="add-doctor.php" class="btn btn-primary">+ Add New Doctor</a>
                            </div>
                        </div>
                    </div>
        <!-- Top Buttons Row -->
        <!-- Session Alerts (Toastr or Bootstrap) -->
        <?php if (isset($_SESSION['success'])): ?>
            <script>
                $(document).ready(function () {
                    $.toast({
                        heading: 'Success',
                        text: '<?= $_SESSION['success']; ?>',
                        showHideTransition: 'slide',
                        icon: 'success',
                        loaderBg: '#f96868',
                        position: 'top-right'
                    });
                });
            </script>
            <?php unset($_SESSION['success']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            <script>
                $(document).ready(function () {
                    $.toast({
                        heading: 'Error',
                        text: '<?= $_SESSION['error']; ?>',
                        showHideTransition: 'fade',
                        icon: 'error',
                        loaderBg: '#f2a654',
                        position: 'top-right'
                    });
                });
            </script>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Doctors Table -->
         <?php
        $sql = "SELECT * FROM doctors ORDER BY id DESC";
        $result = $conn->query($sql);
        ?>

        <div class="table-responsive">
            <table id="doctorTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Doctor ID</th>
                        <th>Doctor Name</th>
                        <th>Designation</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()):
                        $row_id = $row['id'];
                        $formatted_id = str_pad($row_id, 3, '0', STR_PAD_LEFT);
                        $doctor_id = "MC" . $formatted_id;
                    ?>
                    <!-- View Modal -->
                        <div class="modal fade" id="viewDoctor<?= $row['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0">
                                    <div class="modal-header bg-primary text-white py-2">
                                        <h5 class="modal-title font-weight-bold mb-0 text-white">Doctor Details</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body px-4 pt-3 pb-4">
                                        <div class="row">
                                            <!-- LEFT COLUMN -->
                                            <div class="col-md-6">
                                                <div><strong>Doctor ID :</strong> #<?= $row['id']; ?></div>
                                                <div><strong>Name :</strong> Dr. <?= $row['name']; ?></div>
                                                <div><strong>Department :</strong> <?= $row['department']; ?></div>
                                                <div><strong>Designation :</strong> <?= $row['designation']; ?></div>
                                                <div><strong>Qualification :</strong> <?= $row['qualification']; ?></div>
                                                <div><strong>Experience :</strong> <?= $row['experience']; ?> years</div>

                                                <div class="mt-2"><strong>Account Info :</strong></div>
                                                <div class="ml-2"><?= $row['bank_name']; ?> - <?= $row['account_name']; ?></div>
                                                <div class="ml-2">A/C : <?= $row['account_number']; ?></div>
                                                <div class="ml-2">Branch : <?= $row['branch']; ?></div>
                                                <div class="ml-2">IFSC : <?= $row['ifsc']; ?></div>
                                            </div>

                                            <!-- RIGHT COLUMN -->
                                            <div class="col-md-6">
                                                <div><strong>Email ID :</strong> <?= $row['email']; ?></div>
                                                <div><strong>Phone No :</strong> <?= $row['phone']; ?></div>
                                                <div><strong>Gender :</strong> <?= $row['gender']; ?></div>
                                                <div><strong>DOB :</strong> <?= $row['dob']; ?></div>
                                                <div><strong>License :</strong> <?= $row['license']; ?></div>
                                                <div>
                                                    <strong>Status :</strong>
                                                    <span class="badge badge-<?= strtolower($row['status']) === 'active' ? 'success' : 'danger'; ?>">
                                                        <?= $row['status']; ?>
                                                    </span>
                                                </div>
                                                <div><strong>DOJ :</strong> <?= $row['date_of_joining']; ?></div>

                                                <div class="mt-2"><strong>Address :</strong></div>
                                                <div class="ml-2"><?= $row['address1']; ?></div>
                                                <div class="ml-2"><?= $row['address2']; ?></div>
                                                <div class="ml-2"><?= $row['city']; ?> - <?= $row['pincode']; ?>, <?= $row['state']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Delete Modal -->
                            <div class="modal fade" id="deleteDoctor<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteDoctorLabel<?= $row['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form method="post" action="delete-doctor.php">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                        <div class="modal-content border-0 p-3 text-center">
                                            <div class="modal-header border-0 justify-content-center">
                                                <h5 class="modal-title text-danger font-weight-bold w-100" id="deleteDoctorLabel<?= $row['id']; ?>">Confirm Deletion</h5>
                                                <button type="button" class="close position-absolute" style="top: 10px; right: 15px;" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete <strong>Dr. <?= $row['name']; ?></strong>?</p>
                                            </div>
                                            <div class="modal-footer border-0 justify-content-center">
                                                <button type="submit" class="btn btn-danger px-4 mr-2">Delete</button>
                                                <button type="button" class="btn btn-secondary px-4 ml-2" data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        <td>#<?= $doctor_id; ?></td>
                        <td>Dr. <?= $row['name']; ?></td>
                        <td><?= $row['designation']; ?></td>
                        <td><?= $row['email']; ?><br><?= $row['phone']; ?></td>
                        <td>
                            <span class="badge badge-<?= strtolower($row['status']) === 'active' ? 'success' : 'danger'; ?>">
                                <?= $row['status']; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a data-toggle="modal" data-target="#viewDoctor<?= $row['id']; ?>" class="btn btn-sm btn-light" title="View">
                                <i class="fa fa-eye text-info"></i>
                            </a>
                            <a href="edit-doctor.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-light" title="Edit">
                                <i class="fa fa-edit text-primary"></i>
                            </a>
                            <a data-toggle="modal" data-target="#deleteDoctor<?= $row['id']; ?>" class="btn btn-sm btn-light" title="Delete">
                                <i class="fa fa-trash text-danger"></i>
                            </a>
                            <form method="post" action="toggle-status.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <input type="hidden" name="currentStatus" value="<?= $row['status']; ?>">
                                <button type="submit" name="toggleStatus" class="btn btn-sm btn-light" title="<?= $row['status'] === 'Active' ? 'Inactivate' : 'Activate'; ?>">
                                    <i class="fa <?= $row['status'] === 'Active' ? 'fa-ban text-warning' : 'fa-check text-success'; ?>"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No doctors found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script>
        $(document).ready(function () {
            $('#doctorTable').DataTable();
        });
    </script>
    <!-- jQuery and Toast JS (already present in your template) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">

    <?php include 'footer.php'; ?>
