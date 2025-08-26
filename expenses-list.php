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
            <div class="col-md-6 col-sm-12">
                <label class="form-control-label d-block mb-1">PRE SELECTED DATE RANGE</label>
                <div class="d-flex align-items-center">
                    <div id="daterange" style="padding: 10px 15px; border: 1px solid #ccc; border-radius: 5px; width: 250px; cursor: pointer; margin-right: 10px;">
                        <span id="selected-range">Last 30 Days</span> <i class="fa fa-caret-down ml-2"></i>
                    </div>
                    <button class="btn btn-secondary" onclick="filterTable()">Get Details</button>
                </div>
            </div>
            <div class="col-md-6 text-md-right mt-3 mt-md-0">
                <button class="btn btn-danger mr-2" onclick="downloadExcel()">Download</button>
                <a href="add-expense.php" class="btn btn-primary">Add New Expense</a>
            </div>
        </div>

        <!-- Hidden form to submit date range -->
        <form id="dateRangeForm" method="GET" class="d-none">
            <input type="hidden" name="start_date" id="formStartDate">
            <input type="hidden" name="end_date" id="formEndDate">
        </form>

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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Fetch date range from GET parameters
                $start = $_GET['start_date'] ?? null;
                $end = $_GET['end_date'] ?? null;

                if ($start && $end) {
                    // Prepare query with date range filter
                    $stmt = $conn->prepare("SELECT * FROM expenses WHERE expense_date BETWEEN ? AND ? ORDER BY expense_date DESC");
                    $stmt->bind_param("ss", $start, $end);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    // Default: show today's expenses
                    $result = $conn->query("SELECT * FROM expenses WHERE expense_date = CURDATE() ORDER BY expense_date DESC");
                }

                $sl = 1;
                while ($row = $result->fetch_assoc()):
                ?>
                    <tr data-date="<?= date('Y-m-d', strtotime($row['expense_date'])) ?>">
                        <td><?= htmlspecialchars($row['voucher_no']); ?></td>
                        <td><?= htmlspecialchars($row['expense_date']); ?></td>
                        <td><?= htmlspecialchars($row['expense_name']); ?></td>
                        <td><?= htmlspecialchars($row['category']); ?></td>
                        <td><?= htmlspecialchars($row['details']); ?></td>
                        <td><?= htmlspecialchars($row['payment_mode']); ?></td>
                        <td>₹ <?= number_format($row['amount'], 2); ?></td>
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
                                            <div><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></div>
                                            <div><strong>Date:</strong> <?= date('Y-m-d', strtotime($row['expense_date'])) ?></div>
                                            <div><strong>Amount:</strong> ₹ <?= number_format($row['amount'], 2) ?></div>
                                            <div><strong>Payment Mode:</strong> <?= htmlspecialchars($row['payment_mode']) ?></div>
                                            <div><strong>Status:</strong>
                                                <span class="badge badge-<?= strtolower($row['payment_status']) === 'paid' ? 'success' : 'secondary' ?>">
                                                    <?= ucfirst($row['payment_status']) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-secondary">
                                            <div><strong>Name:</strong> <?= htmlspecialchars($row['expense_name']) ?></div>
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

    // Get URL params
    let urlParams = new URLSearchParams(window.location.search);
    let startParam = urlParams.get('start_date');
    let endParam = urlParams.get('end_date');

    if (startParam && endParam) {
        startDate = moment(startParam);
        endDate = moment(endParam);
        $('#selected-range').text(startDate.format('MMM D') + ' – ' + endDate.format('MMM D'));
    } else {
        // Default range: today
        startDate = moment();
        endDate = moment();
        $('#selected-range').text('Today');
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
});

function filterTable() {
    if (!startDate || !endDate) return;

    $('#formStartDate').val(startDate.format('YYYY-MM-DD'));
    $('#formEndDate').val(endDate.format('YYYY-MM-DD'));

    // Submit form to reload page with date range parameters
    $('#dateRangeForm').submit();
}

// function downloadExcel() {
//     const wb = XLSX.utils.book_new();
//     const table = document.getElementById('expenseTable');
//     const ws = XLSX.utils.table_to_sheet(table);
//     XLSX.utils.book_append_sheet(wb, ws, 'Expenses');

//     let rangeText = $('#selected-range').text().trim();
//     rangeText = rangeText.replace(/\s+/g, '_');
//     XLSX.writeFile(wb, `Expenses_${rangeText}.xlsx`);
// }

function downloadExcel() {
    const table = document.getElementById('expenseTable');
    const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText);

    const rows = [];
    const trs = table.querySelectorAll('tbody tr');

    trs.forEach(tr => {
        const rowDate = tr.getAttribute('data-date');
        const rowMoment = moment(rowDate);

        if (rowMoment.isSameOrAfter(startDate, 'day') && rowMoment.isSameOrBefore(endDate, 'day')) {
            const cells = Array.from(tr.querySelectorAll('td')).map(td => td.innerText.trim());
            rows.push(cells);
        }
    });

    if (rows.length === 0) {
        alert('No expenses found for the selected date range.');
        return;
    }

    const wsData = [headers, ...rows];
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(wsData);
    XLSX.utils.book_append_sheet(wb, ws, 'Expenses');

    let rangeText = $('#selected-range').text().trim().replace(/\s+/g, '_');
    XLSX.writeFile(wb, `Expenses_${rangeText}.xlsx`);
}

function openExpenseSlip(id) {
    fetch(`expense-slip.php?id=${id}`)
        .then(res => res.text())
        .then(html => {
            $('#slipModalContent').html(html);
            $('#expenseSlipModal').modal('show');
        });
}
</script>

<?php include 'footer.php'; ?>
