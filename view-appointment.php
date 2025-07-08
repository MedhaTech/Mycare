<?php
include 'header.php';
include 'dbconnection.php';
include 'init.php';

$appointment_id = $_GET['id'] ?? null;

if (!$appointment_id) {
    echo "<script>alert('Invalid appointment ID'); window.location.href='appointments.php';</script>";
    exit();
}

// Fetch appointment with patient and doctor names
$stmt = $conn->prepare("
    SELECT a.*, p.name AS patient_name, d.name AS doctor_name
    FROM appointments a
    LEFT JOIN patients p ON a.patient_id = p.id
    LEFT JOIN doctors d ON a.doctor_id = d.id
    WHERE a.id = ?
");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();
$stmt->close();

if (!$appointment) {
    echo "<script>alert('Appointment not found'); window.location.href='appointments.php';</script>";
    exit();
}
?>

<div class="container mt-5">
    <h2>Appointment Details</h2>
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Patient:</strong><br><?= htmlspecialchars($appointment['patient_name']) ?>
                </div>
                <div class="col-md-6">
                    <strong>Doctor:</strong><br><?= htmlspecialchars($appointment['doctor_name']) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Date:</strong><br><?= $appointment['appointment_date'] ?>
                </div>
                <div class="col-md-4">
                    <strong>Time:</strong><br><?= date("h:i A", strtotime($appointment['appointment_time'])) ?>
                </div>
                <div class="col-md-4">
                    <strong>Type:</strong><br><?= htmlspecialchars($appointment['type']) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Duration:</strong><br><?= $appointment['duration'] ?> minutes
                </div>
                <div class="col-md-4">
                    <strong>Status:</strong><br>
                    <?php
                    $status = $appointment['status'];
                    $badgeClass = match ($status) {
                        'Scheduled' => 'badge-primary',
                        'Tentative' => 'badge-warning',
                        'Add to Waitlist' => 'badge-info',
                        default => 'badge-secondary'
                    };
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                </div>
                <div class="col-md-4">
                    <strong>Consultation Fee:</strong><br>Rs.<?= number_format($appointment['fee'], 2) ?>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <strong>Reason for Visit:</strong><br><?= htmlspecialchars($appointment['reason']) ?>
                </div>
            </div>

            <a href="appointments.php" class="btn btn-secondary">← Back to Appointments</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
