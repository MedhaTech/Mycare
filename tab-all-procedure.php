<?php
// tab-all-procedure.php
include 'dbconnection.php';

$sql = "SELECT pr.*, 
               p.name AS patient_name,
               p.phone AS patient_phone,
               p.gender,
               p.dob,
               p.blood_group,
               d.name AS doctor_name,
               a.appointment_id AS op_id
        FROM procedures pr
        LEFT JOIN patients p ON pr.patient_id = p.id
        LEFT JOIN doctors d ON pr.doctor_id = d.id
        LEFT JOIN appointments a ON pr.appointment_id = a.appointment_id
        ORDER BY pr.id DESC";





$result = $conn->query($sql);
?>

<div class="table-responsive mt-3">
    <table class="table table-striped table-bordered" id="procedureTable">
       <thead>
        <tr>
            <th>Pro ID</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Date & Time</th>
            <th>OP ID</th> <!-- ✅ New Column for OP ID -->
            <th>Status</th>
            <th>Type</th>
            <th>Duration</th>
            <th>Actions</th>
        </tr>
    </thead>


        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php 
                        $row['procedure_id'] = 'PR' . str_pad($row['id'], 4, '0', STR_PAD_LEFT); 
                        include 'row-procedure.php'; 
                    ?>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No procedures found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
