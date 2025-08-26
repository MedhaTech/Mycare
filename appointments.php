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
<!-- Tabs + New Appointment Button -->
<div class="container mt-2 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <ul class="nav nav-tabs tabs-bordered">
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-all">All Appointments</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-upcoming">Upcoming</a></li>
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-today">Today</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-completed">Completed</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-cancelled">Cancelled</a></li>
        </ul>
        <a href="add-appointment.php" class="btn btn-primary">+ New Appointment</a>
    </div>
</div>

<div class="container">
    <div class="tab-content">
        <div class="tab-pane fade" id="tab-all"><?php include 'tab-all.php'; ?></div>
        <div class="tab-pane fade" id="tab-upcoming"><?php include 'tab-upcoming.php'; ?></div>
        <div class="tab-pane fade show active" id="tab-today"><?php include 'tab-today.php'; ?></div>
        <div class="tab-pane fade" id="tab-completed"><?php include 'tab-completed.php'; ?></div>
        <div class="tab-pane fade" id="tab-cancelled"><?php include 'tab-cancelled.php'; ?></div>
    </div>
</div>

<!-- Appointment Slip Modal -->
<div class="modal fade" id="slipModal" tabindex="-1" role="dialog" aria-labelledby="slipModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title text-white" id="slipModalLabel">Appointment Slip</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="pdfSlipContent"><!-- Content will load here --></div>
      </div>
      <div class="modal-footer">
        <a id="downloadPdfBtn" class="btn btn-success" target="_blank">Download PDF</a>
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
function loadSlip(id, name) {
    fetch('view-appointment-slip.php?id=' + id)
        .then(res => res.text())
        .then(html => {
            document.getElementById('pdfSlipContent').innerHTML = html;
            document.getElementById('downloadPdfBtn').href = 'download-appointment-slip.php?id=' + id;
            $('#slipModal').modal('show');
        })
        .catch(err => {
            alert("Could not load appointment slip.");
            console.error(err);
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
<script>
  $(document).ready(function () {
      $('#appointmentTable').DataTable({
          responsive: true,
          pageLength: 10,
          lengthMenu: [5, 10, 25, 50, 100],
          columnDefs: [
              { targets: -1, orderable: false } // Make 'Actions' column not sortable
          ]
      });
  });
</script>


<?php include 'footer.php'; ?>
