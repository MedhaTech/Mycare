<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';
include 'dbconnection.php';
include 'init.php';
?>

<!-- Breadcrumb -->
<div class="container mt-4">
    <div class="row page-title clearfix">
        <div class="page-title-left">
            <h6 class="page-title-heading mr-0 mr-r-5">Appointments</h6>
            <p class="page-title-description mr-0 d-none d-md-inline-block">Manage MyCare clinic's appointments</p>
        </div>
        <div class="page-title-right d-none d-sm-inline-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Appointments</li>
            </ol>
        </div>
    </div>
</div>

<!-- Button Row -->
<div class="container mt-2 mb-4">
    <div class="row align-items-center">
       <div class="col-md-12 text-right mb-3">
            <a href="add-appointment.php" class="btn btn-primary">+ Add New Appointment</a>
        </div>
    </div>



    <?php
    $sql = "SELECT a.*, p.name AS patient_name, d.name AS doctor_name 
            FROM appointments a
            LEFT JOIN patients p ON a.patient_id = p.id
            LEFT JOIN doctors d ON a.doctor_id = d.id
            ORDER BY a.id DESC";
    $result = $conn->query($sql);
    ?>

    <div class="table-responsive">
        <table id="appointmentTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()):
                        $apt_id = "APT" . str_pad($row['id'], 3, '0', STR_PAD_LEFT);
                        $status = strtoupper($row['status']);
                        $badgeClass = match ($status) {
                            'SCHEDULED' => 'badge-primary',
                            'TENTATIVE' => 'badge-warning',
                            'WAITLIST' => 'badge-info',
                            'CONFIRMED' => 'badge-success',
                            'IN PROGRESS' => 'badge-dark',
                            'COMPLETED' => 'badge-success',
                            'CANCELLED' => 'badge-danger',
                            default => 'badge-secondary'
                        };
                    ?>
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteAppointment<?= $row['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="post" action="delete-appointment.php">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Confirm Deletion</h5></div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete appointment <strong><?= $apt_id; ?></strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Status Change Modal -->
                    <div class="modal fade" id="statusModal<?= $row['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="post" action="change-status-appointment.php">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Change Status</h5></div>
                                    <div class="modal-body">
                                        <select name="status" class="form-control" required>
                                            <option value="">-- Select Status --</option>
                                            <?php foreach (['Scheduled', 'Tentative', 'Waitlist', 'Confirmed', 'In Progress', 'Completed', 'Cancelled'] as $option): ?>
                                                <option value="<?= $option ?>"><?= $option ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <tr>
                        <td>#<?= $apt_id; ?></td>
                        <td><?= htmlspecialchars($row['patient_name']) ?></td>
                        <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                        <td><?= $row['appointment_date'] . ' ' . date("h:i A", strtotime($row['appointment_time'])) ?></td>
                        <td><span class="badge <?= $badgeClass ?>"><?= $status ?></span></td>
                        <td><?= htmlspecialchars($row['type']) ?></td>
                        <td><?= intval($row['duration']) ?> min</td>
                        <td>
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="feather feather-more-vertical"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="view-appointment.php?id=<?= $row['id'] ?>">View</a>
                                    <a class="dropdown-item" href="edit-appointment.php?id=<?= $row['id'] ?>">Edit</a>
                                    <a class="dropdown-item text-danger" data-toggle="modal" data-target="#deleteAppointment<?= $row['id'] ?>">Delete</a>
                                    <a class="dropdown-item" data-toggle="modal" data-target="#statusModal<?= $row['id'] ?>">Change Status</a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="loadSlip(<?= $row['id'] ?>, '<?= htmlspecialchars($row['patient_name']) ?>')">Download Slip</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center text-danger">No appointments found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php $conn->close(); ?>
</div>

<!-- PDF Slip Modal -->
<div class="modal fade" id="slipModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Appointment Slip</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="slipContent"></div>
            <div class="modal-footer">
                <button class="btn btn-success" id="savePdfBtn">Save as PDF</button>
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- DataTables + PDF Scripts -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    $(document).ready(function () {
        $('#appointmentTable').DataTable();
    });

    function loadSlip(id, name) {
        fetch('slip-content.php?id=' + id)
            .then(res => res.text())
            .then(html => {
                document.getElementById('slipContent').innerHTML = html;
                $('#slipModal').modal('show');

                document.getElementById('savePdfBtn').onclick = async function () {
                    const { jsPDF } = window.jspdf;
                    const slip = document.querySelector("#pdfSlip");

                    const canvas = await html2canvas(slip);
                    const imgData = canvas.toDataURL("image/png");

                    const pdf = new jsPDF('p', 'mm', 'a4');
                    const width = pdf.internal.pageSize.getWidth();
                    const height = (canvas.height * width) / canvas.width;

                    pdf.addImage(imgData, 'PNG', 10, 10, width - 20, height);
                    const safeName = name.replace(/\s+/g, '_');
                    pdf.save(`${safeName}_APT${id}.pdf`);
                };
            });
    }
</script>

<?php include 'footer.php'; ?>
