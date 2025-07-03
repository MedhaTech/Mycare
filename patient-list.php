<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
include 'header.php';
include 'dbconnection.php';
include 'init.php';

function calculateAge($dob) {
    if (!$dob) return 'N/A';
    $dobDate = new DateTime($dob);
    $today = new DateTime();
    return $dobDate->diff($today)->y;
}
?>

<!-- Breadcrumb -->
<div class="container ">
    <div class="row page-title clearfix">
        <div class="page-title-left">
            <h6 class="page-title-heading mr-0 mr-r-5"> Patients List</h6>
            <p class="page-title-description mr-0 d-none d-md-inline-block">Record of patients served by MyCare Clinic.</p>
        </div>
        <div class="page-title-right d-none d-sm-inline-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Patients List</li>
            </ol>
        </div>
    </div>
</div>

<!-- Top Buttons -->
<div class="container">
    <div class="card p-4">
        <div class="page-title-left">
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <h6 class="mb-0 font-weight-bold">MyCare Patients List</h6>
                </div>
                <div class="col-md-6 text-right">
                    <a href="add-patient.php" class="btn btn-primary">+Add Patient</a>
                </div>
            </div>
        </div>

        <div class="container mt-2">
            <?php
            $sql = "SELECT patients.*, doctors.name AS doctor_name FROM patients 
                    LEFT JOIN doctors ON patients.doctor_id = doctors.id 
                    ORDER BY patients.id DESC";
            $result = $conn->query($sql);
            ?>
            <div class="table-responsive">
                <table id="patientTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Age</th>
                            <th>Blood Group</th>
                            <th>Appointments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()):
                                $id = $row['id'];
                                $patientID = 'PAT' . str_pad($id, 3, '0', STR_PAD_LEFT);
                            ?>
                            <tr>
                                <td><?= $patientID ?></td>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['phone'] ?></td>
                                <td><?= calculateAge($row['dob']) ?></td>
                                <td><?= $row['blood_group'] ?></td>
                                <td>
                                  <?php
                                        $apptQuery = "SELECT id, status FROM appointments WHERE patient_id = $id";
                                        $apptResult = $conn->query($apptQuery);
                                        if ($apptResult->num_rows > 0) {
                                            while ($appt = $apptResult->fetch_assoc()) {
                                                $aptID = 'APT' . str_pad($appt['id'], 3, '0', STR_PAD_LEFT);
                                                $status = strtolower($appt['status']);
                                                
                                                $badgeClass = in_array($status, ['confirmed', 'in progress']) ? 'success' : 'secondary';
                                                echo "<span class='badge badge-$badgeClass m-1' style='cursor:pointer;' onclick='viewAppointmentModal({$appt['id']})'>$aptID</span>";
                                            }
                                        } else {
                                            echo "<span class='text-muted'>No Appointments</span>";
                                        }
                                        ?>

                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-start gap-2 flex-nowrap">
                                        <a data-toggle="modal" data-target="#viewPatient<?= $id ?>" class="btn btn-sm btn-light" title="View">
                                            <i class="fa fa-eye text-info"></i>
                                        </a>
                                        <a href="edit-patient.php?id=<?= $id ?>" class="btn btn-sm btn-light" title="Edit">
                                            <i class="fa fa-edit text-primary"></i>
                                        </a>
                                        <a href="#" data-toggle="modal" data-target="#deletePatient<?= $id ?>" class="btn btn-sm btn-light" title="Delete">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                        <a href="add-appointment.php?patient_id=<?= $id ?>" class="btn btn-sm btn-light" title="Book Appointment">
                                            <i class="fa fa-calendar-plus text-success"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- View Patient Modal -->
                            <div class="modal fade" id="viewPatient<?= $id ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0">
                                        <div class="modal-header bg-primary text-white py-2">
                                            <h5 class="modal-title font-weight-bold mb-0 text-white">Patient Details</h5>
                                            <button class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body px-4 pt-3 pb-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div><strong>Patient ID:</strong> <?= $patientID ?></div>
                                                    <div><strong>Name:</strong> <?= $row['name'] ?></div>
                                                    <div><strong>Phone:</strong> <?= $row['phone'] ?></div>
                                                    <div><strong>Gender:</strong> <?= $row['gender'] ?></div>
                                                    <div><strong>DOB:</strong> <?= $row['dob'] ?></div>
                                                    <div><strong>ABHA Number:</strong> <?= $row['abha_number'] ?></div>
                                                    <div><strong>Aadhar Number:</strong> <?= $row['aadhar_number'] ?></div>
                                                    <div><strong>Address:</strong> <?= $row['address1'] ?>, <?= $row['address2'] ?></div>
                                                    <div><strong>City:</strong> <?= $row['city'] ?></div>
                                                    <div><strong>State:</strong> <?= $row['state'] ?></div>
                                                    <div><strong>Pincode:</strong> <?= $row['pincode'] ?></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div><strong>Age:</strong> <?= calculateAge($row['dob']) ?></div>
                                                    <div><strong>Blood Group:</strong> <?= $row['blood_group'] ?></div>
                                                    <div><strong>Height:</strong> <?= $row['height_cm'] ?> cm</div>
                                                    <div><strong>Weight:</strong> <?= $row['weight_kg'] ?> kg</div>
                                                    <div><strong>Sugar:</strong> <?= $row['sugar_level'] ?></div>
                                                    <div><strong>BP:</strong> <?= $row['bp'] ?></div>
                                                    <div><strong>Doctor:</strong> <?= $row['doctor_name'] ?? 'N/A' ?></div>
                                                    <div class="mt-3">
                                                        <div><strong>Appointments:</strong><br>
                                                          <?php
                                                                $apptQuery = "SELECT id, status FROM appointments WHERE patient_id = $id";
                                                                $apptResult = $conn->query($apptQuery);
                                                                if ($apptResult->num_rows > 0) {
                                                                    while ($appt = $apptResult->fetch_assoc()) {
                                                                        $aptID = 'APT' . str_pad($appt['id'], 3, '0', STR_PAD_LEFT);
                                                                        $status = strtolower($appt['status']);
                                                                        
                                                                        $badgeClass = in_array($status, ['confirmed', 'in progress']) ? 'success' : 'secondary';
                                                                        echo "<span class='badge badge-$badgeClass m-1' style='cursor:pointer;' onclick='viewAppointmentModal({$appt['id']})'>$aptID</span>";
                                                                    }
                                                                } else {
                                                                    echo "<span class='text-muted'>No Appointments</span>";
                                                                }
                                                                ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal (fixed version) -->
                            <div class="modal fade" id="deletePatient<?= $row['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="delete-patient.php" method="post">
                                        <div class="modal-content border-0 text-center p-3">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <div class="modal-header border-0 justify-content-center">
                                                <h5 class="modal-title text-danger font-weight-bold">Confirm Deletion</h5>
                                                <button type="button" class="close position-absolute" style="top:10px; right:15px;" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete <strong><?= htmlspecialchars($row['name']) ?></strong>?
                                            </div>
                                            <div class="modal-footer border-0 justify-content-center">
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center">No patients found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Appointment Not Found -->
<div class="modal fade" id="appointmentNotFoundModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Appointment Not Found</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-0">The appointment you are trying to view does not exist or was deleted.</p>
            </div>
        </div>
    </div>
</div>

<?php $conn->close(); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">

<script>
    $(document).ready(function () {
        $('#patientTable').DataTable();
    });

    function viewAppointmentModal(appointmentId) {
        $.ajax({
            url: 'check-appointment.php',
            type: 'POST',
            data: { id: appointmentId },
            success: function(response) {
                if (response === 'exists') {
                    window.location.href = 'view-appointment.php?id=' + appointmentId;
                } else {
                    $('#appointmentNotFoundModal').modal('show');
                }
            }
        });
    }
</script>

<?php include 'footer.php'; ?>
