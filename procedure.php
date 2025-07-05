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
            <h6 class="page-title-heading mr-0 mr-r-5">Procedures</h6>
            <p class="page-title-description mr-0 d-none d-md-inline-block">Manage and view procedure records.</p>
        </div>
        <div class="page-title-right d-none d-sm-inline-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Procedures</li>
            </ol>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php elseif (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<!-- Tabs + New Procedure Button -->
<div class="container mt-2 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <ul class="nav nav-tabs tabs-bordered">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-all">All Procedures</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-upcoming">Upcoming</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-today">Today</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-completed">Completed</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-cancelled">Cancelled</a></li>
        </ul>
        <a href="add-procedure.php" class="btn btn-primary">+ New Procedure</a>
    </div>
</div>

<div class="container">
    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-all"><?php include 'tab-all-procedure.php'; ?></div>
        <div class="tab-pane fade" id="tab-upcoming"><?php include 'tab-procedure-upcoming.php'; ?></div>
        <div class="tab-pane fade" id="tab-today"><?php include 'tab-procedure-today.php'; ?></div>
        <div class="tab-pane fade" id="tab-completed"><?php include 'tab-procedure-completed.php'; ?></div>
        <div class="tab-pane fade" id="tab-cancelled"><?php include 'tab-procedure-cancelled.php'; ?></div>
    </div>
</div>




<!-- Slip Modal -->
<div class="modal fade" id="slipModal" tabindex="-1" role="dialog" aria-labelledby="slipModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="slipModalLabel">Procedure Slip</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="pdfSlip"></div>
      </div>
      <div class="modal-footer">
        <button id="savePdfBtn" class="btn btn-success">Download PDF</button>
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function () {
        $("#procedureTable").DataTable();
    });

    function loadSlip(id, name) {
        fetch('slip-content-procedure.php?id=' + id)
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
                    pdf.save(`${safeName}_Procedure_Slip.pdf`);
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
