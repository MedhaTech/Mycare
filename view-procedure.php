<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';
include 'dbconnection.php';
include 'init.php';

$procedure_id = $_GET['id'] ?? null;

if (!$procedure_id) {
    echo "<script>alert('Invalid procedure ID'); window.location.href='procedure.php';</script>";
    exit();
}

$sql = "SELECT p.*, pt.name AS patient_name, pt.phone, pt.gender, pt.dob, pt.blood_group, d.name AS doctor_name 
        FROM procedures p 
        LEFT JOIN patients pt ON p.patient_id = pt.id 
        LEFT JOIN doctors d ON p.doctor_id = d.id 
        WHERE p.id = $procedure_id";

$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    echo "<script>alert('Procedure not found'); window.location.href='procedure.php';</script>";
    exit();
}

$row = $result->fetch_assoc();
function calculateAge($dob) {
    if (!$dob) return 'N/A';
    $dobDate = new DateTime($dob);
    $today = new DateTime();
    return $dobDate->diff($today)->y;
}

$proID = $row['procedure_id'];
?>

<!-- Breadcrumb -->
<div class="container mt-4">
    <div class="row page-title clearfix">
        <div class="col-12">
            <h6 class="page-title-heading mr-0 mr-r-5">View Procedure</h6>
            <p class="page-title-description">Detailed procedure information</p>
            <a href="procedure.php" class="btn btn-sm btn-secondary float-right">← Back to Procedures</a>
        </div>
    </div>
</div>

<!-- Procedure Details -->
<div class="container mt-3">
    <div class="card p-4">
        <div class="row">
            <div class="col-md-6">
                <h5 class="text-primary font-weight-bold mb-3">Procedure Info</h5>
                <p><strong>Procedure ID:</strong> <?= $proID ?></p>
                <p><strong>Date & Time:</strong> <?= $row['procedure_date'] ?> <?= date('h:i A', strtotime($row['procedure_time'])) ?></p>
                <p><strong>Type:</strong> <?= $row['type'] ?></p>
                <p><strong>Duration:</strong> <?= $row['duration'] ?> mins</p>
                <p><strong>Status:</strong> 
                    <span class="badge 
                        <?= $row['status'] == 'Cancelled' ? 'badge-danger' : 
                            ($row['status'] == 'Completed' ? 'badge-success' : 
                            ($row['status'] == 'Upcoming' ? 'badge-warning' : 'badge-primary')) ?>">
                        <?= $row['status'] ?>
                    </span>
                </p>
                <p><strong>Reason:</strong> <?= $row['reason'] ?: '—' ?></p>
                <?php if ($row['status'] == 'Cancelled' && $row['cancel_reason']): ?>
                    <p><strong>Cancel Reason:</strong> <?= htmlspecialchars($row['cancel_reason']) ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h5 class="text-primary font-weight-bold mb-3">Patient & Doctor Info</h5>
                <p><strong>Patient Name:</strong> <?= $row['patient_name'] ?></p>
                <p><strong>Mobile:</strong> <?= $row['phone'] ?></p>
                <p><strong>Gender:</strong> <?= $row['gender'] ?></p>
                <p><strong>Age:</strong> <?= calculateAge($row['dob']) ?></p>
                <p><strong>Blood Group:</strong> <?= $row['blood_group'] ?></p>
                <p><strong>Doctor:</strong> <?= $row['doctor_name'] ?: 'N/A' ?></p>
                <p><strong>Fee:</strong> ₹<?= number_format($row['fee'], 2) ?></p>
                <p><strong>Payment Mode:</strong> <?= $row['payment_mode'] ?></p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
