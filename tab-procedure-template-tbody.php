<table id="procedureTable" class="table table-striped table-bordered">
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
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['procedure_id']) ?></td>
                    <td><?= htmlspecialchars($row['patient_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                    <td>
                        <?= date("d M Y", strtotime($row['procedure_date'])) . " at " . date("h:i A", strtotime($row['procedure_time'])) ?>
                    </td>
                    <td><?= 'OP' . str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?></td>
                    <td>
                        <?php
                            $status = $row['status'];
                            $badgeClass = '';
                            switch ($status) {
                                case 'Confirmed':
                                    $badgeClass = 'badge badge-info';
                                    break;
                                case 'Completed':
                                    $badgeClass = 'badge badge-success';
                                    break;
                                case 'In Progress':
                                    $badgeClass = 'badge badge-warning';
                                    break;
                                case 'Cancelled':
                                    $badgeClass = 'badge badge-danger';
                                    break;
                                default:
                                    $badgeClass = 'badge badge-secondary';
                                    break;
                            }
                        ?>
                        <span class="<?= $badgeClass ?>" style="font-weight:500; font-size: 0.8rem; text-transform: capitalize;">
                            <?= htmlspecialchars($status) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td><?= htmlspecialchars($row['duration']) . ' min' ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenu<?= $row['id'] ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu<?= $row['id'] ?>">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="loadSlip('<?= $row['id'] ?>', '<?= $row['patient_name'] ?>')">Slip</a>
                                <a class="dropdown-item" href="edit-procedure.php?id=<?= $row['id'] ?>">Edit</a>
                                <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="openCancelModal('<?= $row['id'] ?>')">Cancel</a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center">No procedures found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#procedureTable')) {
            $('#procedureTable').DataTable().destroy();
        }
        $('#procedureTable').DataTable();
    });
</script>
