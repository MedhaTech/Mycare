<?php
$query = "SELECT p.*, pt.name AS patient_name, d.name AS doctor_name 
          FROM `procedures` p
          JOIN patients pt ON p.patient_id = pt.id
          JOIN doctors d ON p.doctor_id = d.id
          ORDER BY p.procedure_date DESC";

$result = $conn->query($query);
?>

<table class="table table-bordered" id="procedureTable">
    <thead>
        <tr>
            <th>Pro ID</th>
            <th>Patient Name</th>
            <th>Doctor</th>
            <th>Date & Time</th>
            <th>OP ID</th>
            <th>Status</th>
            <th>Type</th>
            <th>Duration</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['procedure_id']) ?></td>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                <td><?= $row['procedure_date'] . ' ' . date('h:i A', strtotime($row['procedure_time'])) ?></td>
                <td><?= 'MCP' . str_pad($row['patient_id'], 4, '0', STR_PAD_LEFT) ?></td>
                <td>
                    <span class="badge 
                        <?= $row['status'] == 'Cancelled' ? 'badge-danger' : 
                            ($row['status'] == 'Completed' ? 'badge-success' : 
                            ($row['status'] == 'Upcoming' ? 'badge-warning' : 'badge-primary')) ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>
                <td><?= $row['type'] ?></td>
                <td><?= $row['duration'] ?> mins</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="loadSlip(<?= $row['id'] ?>, '<?= $row['patient_name'] ?>')">Slip</button>
                    <?php if ($row['status'] != 'Cancelled'): ?>
                        <button class="btn btn-sm btn-danger" onclick="openCancelModal(<?= $row['id'] ?>)">Cancel</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="9" class="text-center">No procedures found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
