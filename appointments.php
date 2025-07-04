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
            <p class="page-title-description mr-0 d-none d-md-inline-block">Manage your clinic's appointments and schedules.</p>
        </div>
        <div class="page-title-right d-none d-sm-inline-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Appointments</li>
            </ol>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php elseif (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<!-- Tabs + New Appointment Button -->
<div class="container mt-2 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <ul class="nav nav-tabs tabs-bordered">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-all">All Appointments</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-upcoming">Upcoming</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-today">Today</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-completed">Completed</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-cancelled">Cancelled</a></li>
        </ul>
        <a href="add-appointment.php" class="btn btn-primary">+ New Appointment</a>
    </div>
</div>

<div class="container">
    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-all"><?php include 'tab-all.php'; ?></div>
        <div class="tab-pane fade" id="tab-upcoming"><?php include 'tab-upcoming.php'; ?></div>
        <div class="tab-pane fade" id="tab-today"><?php include 'tab-today.php'; ?></div>
        <div class="tab-pane fade" id="tab-completed"><?php include 'tab-completed.php'; ?></div>
        <div class="tab-pane fade" id="tab-cancelled"><?php include 'tab-cancelled.php'; ?></div>
    </div>
</div>

<!-- Slip Modal -->
<div class="modal fade" id="slipModal" tabindex="-1" role="dialog" aria-labelledby="slipModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="slipModalLabel">Appointment Slip</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="pdfSlip"><!-- Content will be dynamically loaded here --></div>
      </div>
      <div class="modal-footer">
        <button id="savePdfBtn" class="btn btn-success">Download PDF</button>
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- DataTables + PDF Slip Modal Support -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- ✅ 1. jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> <!-- ✅ 2. Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- ✅ 3. Bootstrap -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> <!-- ✅ 4. DataTables -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    $(document).ready(function () {
        $("table").DataTable(); // Applies DataTables to all tables
    });

    function loadSlip(id, name) {
        fetch('slip-content.php?id=' + id)
            .then(res => res.text())
            .then(html => {
                document.getElementById('pdfSlip').innerHTML = html;
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
                    pdf.save(`${safeName}_Appointment_Slip.pdf`);
                };
            });
    }
</script>

<?php if (isset($_SESSION['success'])): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" />

<script>
    $(document).ready(function () {
        $.toast({
            heading: 'Success',
            text: "<?= $_SESSION['success'] ?>",
            position: 'top-right',
            loaderBg: '#51A351',
            icon: 'success',
            hideAfter: 4000,
            stack: 6
        });
    });
</script>
<?php unset($_SESSION['success']); endif; ?>

<?php include 'footer.php'; ?>
