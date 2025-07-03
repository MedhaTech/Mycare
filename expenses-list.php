<?php include 'header.php'; ?>
<?php include 'dbconnection.php';
include 'init.php'; ?>

<!-- Breadcrumb + Heading -->

 <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Expenses</h6>
                        <p class="page-title-description mr-0 d-none d-md-inline-block">Manage MyCare clinic's expenses</p>
                    </div>
                    <!-- /.page-title-left -->
                    <div class="page-title-right d-none d-sm-inline-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Expenses</li>
                        </ol>
                    </div>
                    <!-- /.page-title-right -->
                </div>
                <!-- /.page-title -->
            </div>

<!-- Date Filter and Buttons -->
<div class="container mb-3">
    <div class="card p-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <label class="font-weight-bold">Select Date Range</label>
                <input type="text" id="daterange" class="form-control">
            </div>
            <div class="col-md-8 text-right mt-3 mt-md-0">
                <button class="btn btn-secondary" onclick="filterTable()">Get Details</button>
                <button class="btn btn-danger" onclick="downloadExcel()">Download</button>
                <a href="add-expense.php" class="btn btn-primary">Add New Expense</a>
            </div>
        </div>
    </div>
</div>

<!-- Expenses Table -->
<div class="container">
    <div class="table-responsive">
        <table id="expenseTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Sl No</th>
                    <th>Expense Date</th>
                    <th>Voucher No</th>
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
                    <td><?= $sl++; ?></td>
                    <td><?= $row['expense_date']; ?></td>
                    <td><?= $row['voucher_no']; ?></td>
                    <td><?= $row['expense_name']; ?></td>
                    <td><?= $row['category']; ?></td>
                    <td><?= $row['details']; ?></td>
                    <td><?= $row['payment_mode']; ?></td>
                    <td>₹ <?= number_format($row['amount'], 2); ?></td>
                    <td><span class="badge badge-success">Paid</span></td>
                    <td class="text-center">
                        <a href="view-expense.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-light" title="View">
                            <i class="fa fa-eye text-primary"></i>
                        </a>
                        <button class="btn btn-sm btn-light download-slip" data-id="<?= $row['id']; ?>" title="Download PDF">
                            <i class="fa fa-download text-dark"></i>
                             </button>

                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script>
$(document).ready(function () {
    $('#expenseTable').DataTable();

    $('#daterange').daterangepicker({
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [
                moment().subtract(1, 'month').startOf('month'),
                moment().subtract(1, 'month').endOf('month')
            ]
        }
    });
});

// Filter table by selected date range
function filterTable() {
    const range = $('#daterange').val();
    const [start, end] = range.split(' - ').map(d => new Date(d));

    $('#expenseTable tbody tr').each(function () {
        const rowDate = new Date($(this).data('date'));
        if (rowDate >= start && rowDate <= end) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

// Download table as Excel
function downloadExcel() {
    const wb = XLSX.utils.book_new();
    const table = document.getElementById('expenseTable');
    const ws = XLSX.utils.table_to_sheet(table);
    XLSX.utils.book_append_sheet(wb, ws, 'Expenses');
    XLSX.writeFile(wb, 'Expenses_List.xlsx');
}
</script>
<!-- jsPDF & html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
document.querySelectorAll(".download-slip").forEach(button => {
    button.addEventListener("click", function () {
        const id = this.dataset.id;

        fetch('generate-expense-slip.php?id=' + id)
            .then(res => res.text())
            .then(html => {
                const slipContainer = document.getElementById("slipModalContent");
                slipContainer.innerHTML = html;

                const pdfTarget = slipContainer.querySelector(".slip-container");
                
                html2canvas(pdfTarget, {
                    scale: 2,
                    useCORS: true
                }).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF('p', 'pt', 'a4');

                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const imgWidth = pageWidth;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;

                    pdf.addImage(imgData, 'PNG', 20, 20, imgWidth - 40, imgHeight);
                    pdf.save("Expense_Slip_" + id + ".pdf");
                });
            });
    });
});
</script>
<div id="slipModalContent" style="display: none;"></div>
<?php include 'footer.php'; ?>