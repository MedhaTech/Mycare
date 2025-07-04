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
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" onclick="loadSlip(<?= $row['id'] ?>, '<?= htmlspecialchars($row['patient_name']) ?>')">
                                <i class="fa fa-file-pdf text-danger mr-1"></i> Slip
                            </a>
                            <a class="dropdown-item" href="view-procedure.php?id=<?= $row['id'] ?>">
                                <i class="fa fa-eye text-info mr-1"></i> View
                            </a>
                            <?php if ($row['status'] != 'Cancelled'): ?>
                                <a class="dropdown-item" href="edit-procedure.php?id=<?= $row['id'] ?>">
                                    <i class="fa fa-edit text-primary mr-1"></i> Edit
                                </a>
                                <a class="dropdown-item" href="#" onclick="openCancelModal(<?= $row['id'] ?>)">
                                    <i class="fa fa-times-circle text-danger mr-1"></i> Cancel
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="9" class="text-center">No procedures found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
