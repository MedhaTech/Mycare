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


<?php if (isset($_SESSION['success'])): ?>

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
<!-- Dummy elements to prevent JS error from template.js -->
<div class="right-sidebar" style="display: none;"></div>
<div class="chat-panel" hidden></div>
<!-- Procedure Slip Modal -->
<div class="modal fade" id="procedureSlipModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Procedure Slip</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="procedureSlipContent"></div>
      <div class="modal-footer">
        <button class="btn btn-success" id="downloadProcedureSlipBtn">Download PDF</button>
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
function openProcedureSlip(id, name, procedureCode) {
    fetch('view-procedure-slip.php?id=' + id)
        .then(res => res.text())
        .then(html => {
            document.getElementById("procedureSlipContent").innerHTML = html;

            // Attach click to download button
            const btn = document.getElementById("downloadProcedureSlipBtn");
            if (btn) {
                btn.onclick = () => {
                    window.location.href = 'download-procedure-slip.php?id=' + id;
                };
            }

            $('#procedureSlipModal').modal('show');
        })
        .catch(err => {
            alert("Failed to load slip.");
            console.error(err);
        });
}
</script>

<script>
    $(document).ready(function () {
        $('#procedureTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            columnDefs: [
                { targets: -1, orderable: false } // Make 'Actions' column not sortable
            ]
        });
    });
</script>
<?php if (isset($_SESSION['toast_success'])): ?>
    <script>
        $(function () {
            $.toast({
                heading: 'Success',
                text: '<?= $_SESSION['toast_success'] ?>',
                showHideTransition: 'slide',
                icon: 'success',
                position: 'top-right'
            });
        });
    </script>
    <?php unset($_SESSION['toast_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['toast_error'])): ?>
    <script>
        $(function () {
            $.toast({
                heading: 'Error',
                text: '<?= $_SESSION['toast_error'] ?>',
                showHideTransition: 'fade',
                icon: 'error',
                position: 'top-right'
            });
        });
    </script>
    <?php unset($_SESSION['toast_error']); ?>
<?php endif; ?>


<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" />

<!-- DataTables CSS + JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>


<?php include 'footer.php'; ?>
