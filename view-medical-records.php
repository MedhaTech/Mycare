<?php
session_start();
include 'header.php';

$appointment_id = $_GET['appointment_id'] ?? null;

if (!$appointment_id) {
    echo "<div class='alert alert-danger m-4'>Invalid appointment ID.</div>";
    exit;
}
?>

<div class="container mt-5">
    <h4>Medical Records for Appointment #APT<?= str_pad($appointment_id, 3, '0', STR_PAD_LEFT) ?></h4>

    <div class="alert alert-info mt-4">
        <strong>Placeholder:</strong> Medical records for this appointment will be displayed here in the future.
    </div>

    <a href="appointments.php" class="btn btn-secondary mt-3">← Back to Appointments</a>
</div>

<?php include 'footer.php'; ?>
