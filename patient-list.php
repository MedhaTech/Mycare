<?php include 'header.php'; ?>

<main class="main-wrapper clearfix" style="margin-top: 30px;">
    <div class="container">

        <!-- Breadcrumb -->
        <div class="container mt-4">
            <div class="row page-title clearfix">
                <div class="page-title-left">
                    <h6 class="page-title-heading mr-0 mr-r-5">Patients</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">Manage MyCare clinic's patients</p>
                </div>
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Patients</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Buttons Row -->
        <div class="row align-items-center mb-3">
            <div class="col-md-6">
                <a href="add-patient.php" class="btn btn-outline-primary">+ Add New Patient</a>
                <a href="add-appointment.php" class="btn btn-primary ml-2">+ Quick Book Appointment</a>
            </div>
            <div class="col-md-6 text-right">
                <!-- Optional future search -->
                <!-- <input type="text" class="form-control" placeholder="Search patients..."> -->
            </div>
        </div>

        <?php
        include 'dbconnection.php';
        $sql = "SELECT patients.*, doctors.name AS doctor_name
                FROM patients
                LEFT JOIN doctors ON patients.doctor_id = doctors.id
                ORDER BY patients.id DESC";
        $result = $conn->query($sql);
        ?>

        <!-- Table -->
        <div class="table-responsive">
            <table id="patientTable" class="table table-striped table-bordered">

                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Assigned Doctor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()):
                            $patientID = 'PT' . str_pad($row['id'], 4, '0', STR_PAD_LEFT);
                        ?>
                        <!-- View Modal -->
                        <div class="modal fade" id="viewPatient<?= $row['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Patient Details</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="row">
                                            <div class="col-md-6"><strong>Patient ID:</strong> #<?= $patientID; ?></div>
                                            <div class="col-md-6"><strong>Name:</strong> <?= $row['name']; ?></div>
                                            <div class="col-md-6"><strong>Phone:</strong> <?= $row['phone']; ?></div>
                                            <div class="col-md-6"><strong>Email:</strong> <?= $row['email']; ?></div>
                                            <div class="col-md-6"><strong>Gender:</strong> <?= $row['gender']; ?></div>
                                            <div class="col-md-6"><strong>Date of Birth:</strong> <?= $row['dob']; ?></div>
                                            <div class="col-md-6"><strong>Assigned Doctor:</strong> <?= $row['doctor_name']; ?></div>
                                            <div class="col-md-6"><strong>Address:</strong> <?= $row['address']; ?></div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light py-2">
                                        <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deletePatient<?= $row['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="post" action="delete-patient.php">
                                    <div class="modal-content">
                                        <div class="modal-header"><h5 class="modal-title">Confirm Deletion</h5></div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                            <p>Are you sure you want to delete <strong><?= $row['name']; ?></strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="deletePatient" class="btn btn-danger">Delete</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <tr>
                            <td>#<?= $patientID; ?></td>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['phone']); ?></td>
                            <td><?= htmlspecialchars($row['doctor_name']); ?></td>
                            <td class="text-center">
                                <a data-toggle="modal" data-target="#viewPatient<?= $row['id']; ?>" class="btn btn-sm btn-light" title="View">
                                    <i class="fa fa-eye text-info"></i>
                                </a>
                                <a href="edit-patient.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-light" title="Edit">
                                    <i class="fa fa-edit text-primary"></i>
                                </a>
                                <a data-toggle="modal" data-target="#deletePatient<?= $row['id']; ?>" class="btn btn-sm btn-light" title="Delete">
                                    <i class="fa fa-trash text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">No patients found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php $conn->close(); ?>
    </div>
</main>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
<script>
    $(document).ready(function () {
        $('#patientTable').DataTable();
    });
</script>

<?php include 'footer.php'; ?>
