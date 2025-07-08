<?php
include 'dbconnection.php';
$sql = "SELECT pr.*, 
               p.name AS patient_name, 
               p.phone AS patient_phone,
               p.gender,
               p.dob,
               TIMESTAMPDIFF(YEAR, p.dob, CURDATE()) AS age,  -- ✅ Calculate Age
               p.blood_group,
               d.name AS doctor_name,
               d.department,
               a.appointment_id AS op_id
        FROM procedures pr
        LEFT JOIN patients p ON pr.patient_id = p.id
        LEFT JOIN doctors d ON pr.doctor_id = d.id
        LEFT JOIN appointments a ON pr.appointment_id = a.appointment_id
        WHERE pr.status = 'Cancelled'
        ORDER BY pr.procedure_date DESC";



$result = $conn->query($sql);
$modals = '';
?>

<div class="table-responsive mt-3">
    <table class="table table-striped table-bordered" id="procedureTable">
        <thead>
            <tr>
                <th>Pro ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date & Time</th>
                <th>OP ID</th>
                <th>Status</th>
                <th>Type</th>
                <th>Duration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php include 'row-procedure.php'; ?>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="9" class="text-center">No procedures found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
