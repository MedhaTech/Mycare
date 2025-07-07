<?php include 'header.php'; ?>
<?php include 'dbconnection.php'; include 'init.php'; ?>

<!-- Styles -->
<link href="https://cdn.jsdelivr.net/npm/metismenu/dist/metisMenu.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />

<style>
.daterangepicker {
    border-radius: 6px;
    font-family: 'Segoe UI', sans-serif;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}
.daterangepicker .ranges li {
    padding: 8px 12px;
    font-size: 14px;
    color: #333;
    border-radius: 4px;
    margin-bottom: 4px;
}
.daterangepicker .ranges li.active,
.daterangepicker .ranges li:hover {
    background-color: #007bff !important;
    color: white !important;
}
.daterangepicker .range_inputs {
    padding: 10px;
    text-align: center;
    border-top: 1px solid #eee;
}
.daterangepicker .range_inputs .applyBtn {
    background-color:rgb(0, 208, 166) !important;
    color: white;
    border: none;
    padding: 6px 20px;
    border-radius: 4px;
    margin-right: 10px;
}
.daterangepicker .range_inputs .cancelBtn {
    background-color: #ccc !important;
    color: #333;
    border: none;
    padding: 6px 20px;
    border-radius: 4px;
}
</style>


<div class="container">
    <div class="row page-title clearfix">
        <div class="page-title-left">
            <h6 class="page-title-heading mr-0 mr-r-5">Expenses</h6>
            <p class="page-title-description mr-0 d-none d-md-inline-block">Manage MyCare clinic's expenses</p>
        </div>
        <div class="page-title-right d-none d-sm-inline-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Expenses</li>
            </ol>
        </div>
    </div>
</div>

<div class="container ">
    <div class="card p-4">
        <div class="row align-items-center justify-content-between mb-3">
            <div class="col-md-6">
                <label class="form-control-label d-block mb-1">PRE SELECTED DATE RANGE</label>
                <div id="daterange" style="padding: 10px 15px; border: 1px solid #ccc; border-radius: 5px; width: 250px; cursor: pointer;">
                    <span id="selected-range">Last 30 Days</span> <i class="fa fa-caret-down ml-2"></i>
                </div>
            </div>
            <div class="col-md-6 text-md-right mt-3 mt-md-0">
                <button class="btn btn-secondary mr-2" onclick="filterTable()">Get Details</button>
                <button class="btn btn-danger mr-2" onclick="downloadExcel()">Download</button>
                <a href="add-expense.php" class="btn btn-primary">Add New Expense</a>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table id="expenseTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Voucher No</th>
                        <th>Expense Date</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Details</th>
                        <th>Mode of Payment</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $result = $conn->query("SELECT * FROM expenses ORDER BY expense_date DESC");
                $sl = 1;
                while ($row = $result->fetch_assoc()):
                ?>
                    <tr data-date="<?= $row['expense_date']; ?>">
                        <td><?= 'VCH' . str_pad($sl, 3, '0', STR_PAD_LEFT); ?></td>
                        <td><?= $row['expense_date']; ?></td>
                        <td><?= $row['expense_name']; ?></td>
                        <td><?= $row['category']; ?></td>
                        <td><?= $row['details']; ?></td>
                        <td><?= $row['payment_mode']; ?></td>
                        <td>₹ <?= number_format($row['amount'], 2); ?></td>
                        <td>
                            <span class="badge badge-<?= strtolower($row['payment_status']) === 'paid' ? 'success' : 'warning' ?>">
                                <?= ucfirst($row['payment_status']) ?>
                            </span>
                        </td>
                        <td class="text-center">
                             <button class="btn btn-sm btn-light" onclick="openExpenseSlip(<?= $row['id'] ?>)">
                                <i class="fa fa-download text-dark"></i>
                                </button>

                            <button class="btn btn-sm btn-light" data-toggle="modal" data-target="#viewExpense<?= $row['id'] ?>"><i class="fa fa-eye text-primary"></i></button>

                        </td>
                    </tr> 

                    <!-- Modal -->
                    <div class="modal fade" id="viewExpense<?= $row['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0" style="background-color: #fff;">
                                <div class="modal-header bg-primary text-white py-2">
                                    <h5 class="modal-title font-weight-bold mb-0 text-white">Expense Details</h5>
                                    <button class="close text-white" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body px-4 pt-3 pb-4">
                                    <div class="row">
                                        <div class="col-md-6 text-secondary">
                                            <div><strong>Voucher No:</strong> <?= 'VCH' . str_pad($sl, 3, '0', STR_PAD_LEFT); ?></div>
                                            <div><strong>Category:</strong> <?= $row['category'] ?></div>
                                            <div><strong>Date:</strong> <?= date('Y-m-d', strtotime($row['expense_date'])) ?></div>
                                            <div><strong>Amount:</strong> ₹ <?= number_format($row['amount'], 2) ?></div>
                                            <div><strong>Payment Mode:</strong> <?= $row['payment_mode'] ?></div>
                                            <div><strong>Status:</strong>
                                                <span class="badge badge-<?= strtolower($row['payment_status']) === 'paid' ? 'success' : 'secondary' ?>">
                                                    <?= ucfirst($row['payment_status']) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-secondary">
                                            <div><strong>Name:</strong> <?= $row['expense_name'] ?></div>
                                            <div><strong>Details:</strong> <?= $row['details'] ?: '–' ?></div>
                                            <div><strong>Remarks:</strong> <?= $row['remarks'] ?: '–' ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $sl++; endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="slipModalContent" style="display: none;"></div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> <!-- ✅ Add this -->
<script src="https://cdn.jsdelivr.net/npm/metismenu"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


<script>
let startDate, endDate;

$(document).ready(function () {
    $('#expenseTable').DataTable();

    if ($.fn.metisMenu && $('#side-menu').length) {
        $('#side-menu').metisMenu();
    }

    $('#daterange').daterangepicker({
        opens: 'left',
        autoApply: false,
        alwaysShowCalendars: false,
        showCustomRangeLabel: true,
        autoUpdateInput: false,
        linkedCalendars: false,
        locale: {
            format: 'MMM D',
            applyLabel: 'Apply',
            cancelLabel: 'Cancel'
        },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, function (start, end) {
        startDate = start;
        endDate = end;
        $('#selected-range').text(start.format('MMM D') + ' – ' + end.format('MMM D'));
    });

    startDate = moment().subtract(29, 'days');
    endDate = moment();
    $('#selected-range').text(startDate.format('MMM D') + ' – ' + endDate.format('MMM D'));

   
});



function filterTable() {
    $('#expenseTable tbody tr').each(function () {
        const rowDate = new Date($(this).data('date'));
        if (rowDate >= startDate.toDate() && rowDate <= endDate.toDate()) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function downloadExcel() {
    const wb = XLSX.utils.book_new();
    const table = document.getElementById('expenseTable');
    const ws = XLSX.utils.table_to_sheet(table);
    XLSX.utils.book_append_sheet(wb, ws, 'Expenses');

    let rangeText = $('#selected-range').text().trim();
    rangeText = rangeText.replace(/\s*–\s*/, '-').replace(/\s+/g, '').toLowerCase();
    XLSX.writeFile(wb, `expenses_${rangeText}.xlsx`);
}
</script>
<!-- Dummy elements to prevent template.js error -->
<div class="chat-panel" style="display: none;"></div>
<div class="right-sidebar" style="display: none;"></div>
<div class="sidebar-wrapper" style="display: none;"></div>

<!-- Expense Slip Modal -->
<div class="modal fade" id="expenseSlipModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Expense Slip</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="expenseSlipContent" style="font-family: 'Segoe UI', sans-serif;"></div>
      <div class="modal-footer">
        <button class="btn btn-success" id="downloadExpenseSlipBtn">Download PDF</button>
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById("downloadExpenseSlipBtn").addEventListener("click", function () {
    const content = document.getElementById("expenseSlipContent");
    if (!content) return;

    let voucher = '';
    let name = '';

    // Search all <strong> tags inside #expenseSlipContent
    const labels = content.querySelectorAll('strong');

    labels.forEach(label => {
        const labelText = label.textContent.trim().toLowerCase();

        if (labelText === 'voucher no:') {
            const td = label.parentElement.nextElementSibling;
            voucher = td ? td.textContent.trim() : '';
        }

        if (labelText === 'name:') {
            const td = label.parentElement.nextElementSibling;
            name = td ? td.textContent.trim() : '';
        }
    });

    if (!voucher) voucher = 'VCH000';
    if (!name) name = 'Expense';

    const safeVoucher = voucher.replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
    const safeName = name.replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
    const filename = `${safeName}_${safeVoucher}.pdf`;

    html2canvas(content).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');

        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();
        const ratio = Math.min(pageWidth / canvas.width, pageHeight / canvas.height);

        const imgWidth = canvas.width * ratio;
        const imgHeight = canvas.height * ratio;

        pdf.addImage(imgData, 'PNG', 10, 10, imgWidth - 20, imgHeight);
        pdf.save(filename);
    });
});
</script>




<script>
function openExpenseSlip(id) {
    fetch('view-expense-slip.php?id=' + id)
        .then(res => res.text())
        .then(html => {
            document.getElementById("expenseSlipContent").innerHTML = html;
            $('#expenseSlipModal').modal('show');
        })
        .catch(err => {
            alert("Failed to load expense slip.");
            console.error(err);
        });
}
</script>




<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" />
<!-- Toast Messages -->
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
<?php unset($_SESSION['toast_success']); endif; ?>

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
<?php unset($_SESSION['toast_error']); endif; ?>

<!-- DataTables CSS + JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<?php include 'footer.php'; ?>
