<?php
include 'dbconnection.php';

$sql = "SELECT a.*, p.name AS patient_name, d.name AS doctor_name 
        FROM appointments a
        LEFT JOIN patients p ON a.patient_id = p.id
        LEFT JOIN doctors d ON a.doctor_id = d.id
        WHERE a.status IN ('CONFIRMED', 'IN PROGRESS')
        ORDER BY a.id DESC";

$result = $conn->query($sql);
?>

<div class="table-responsive mt-3">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Type</th>
                <th>Duration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php include 'row-appointment.php'; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No upcoming appointments.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
