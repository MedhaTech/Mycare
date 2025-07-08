<?php
$proc_id = $row['procedure_id']; // e.g. PR0022
$status = strtoupper($row['status'] ?? '');
$badgeClass = match ($status) {
    'CONFIRMED' => 'badge-primary',
    'IN PROGRESS' => 'badge-warning',
    'COMPLETED' => 'badge-success',
    'CANCELLED' => 'badge-danger',
    default => 'badge-secondary'
};
?>

<tr>
    <td><?= 'PR' . str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
    <td><?= htmlspecialchars($row['patient_name'] ?? '') ?></td>
    <td><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
    <td>
        <?= date("d M Y", strtotime($row['procedure_date'])) ?>
        <br><small class="text-muted"><?= date("h:i A", strtotime($row['procedure_time'])) ?></small>
    </td>
    <td>
        <?= !empty($row['op_id']) ? $row['op_id'] : '<span class="text-muted">-</span>' ?>
    </td>
    <td>
        <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
    </td>
    <td><?= htmlspecialchars($row['type'] ?? '') ?></td>
    <td><?= intval($row['duration']) ?> min</td>
    <td>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" id="dropdownMenu<?= $row['id'] ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ⋮ 
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $row['id'] ?>">
                <!-- Always show View -->
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#viewProcedure<?= $row['id'] ?>">View</a>

                <!-- Show Edit and Slip only if not CANCELLED -->
                <?php if (!in_array($status, ['CANCELLED'])): ?>
                    <a class="dropdown-item" href="edit-procedure.php?id=<?= $row['id'] ?>">Edit</a>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="openProcedureSlip(<?= $row['id'] ?>)">View Procedure Slip</a>
                <?php endif; ?>

                <!-- Show Cancel only for CONFIRMED or IN PROGRESS -->
                <?php if (in_array($status, ['CONFIRMED', 'IN PROGRESS'])): ?>
                    <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#cancelProcedure<?= $row['id'] ?>">Cancel</a>
                <?php endif; ?>
            </div>

        </div>
    </td>
</tr>

<!-- View Procedure Modal -->
<div class="modal fade" id="viewProcedure<?= $row['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title font-weight-bold mb-0 text-white">Procedure Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body px-4 pt-3 pb-4">
                <div class="row">
                    <!-- LEFT COLUMN -->
                    <div class="col-md-6">
                        <div><strong>Patient Name:</strong> <?= htmlspecialchars($row['patient_name'] ?? '') ?></div>
                        <div><strong>Mobile No:</strong> <?= htmlspecialchars($row['patient_phone'] ?? 'N/A') ?></div>
                        <div><strong>Gender:</strong> <?= htmlspecialchars($row['gender'] ?? 'N/A') ?></div>
                        <div><strong>Age:</strong> <?= htmlspecialchars($row['age'] ?? 'N/A') ?> years</div>
                        <div><strong>Blood Group:</strong> <?= htmlspecialchars($row['blood_group'] ?? 'N/A') ?></div>
                        <div><strong>Status:</strong>
                            <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                        </div>
                        <div><strong>Fee:</strong> Rs.<?= number_format($row['fee'] ?? 0, 2) ?></div>
                        <div><strong>Payment Mode:</strong> <?= htmlspecialchars($row['payment_mode'] ?? '') ?></div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col-md-6">
                        <div><strong>Procedure ID:</strong> <?= htmlspecialchars($proc_id ?? '') ?></div>
                        <div><strong>Date & Time:</strong> <?= date("d M Y", strtotime($row['procedure_date'])) . ', ' . date("h:i A", strtotime($row['procedure_time'])) ?></div>
                        <div><strong>Type:</strong> <?= htmlspecialchars($row['type'] ?? '') ?></div>
                        <div><strong>Duration:</strong> <?= intval($row['duration'] ?? 0) ?> mins</div>
                        <div><strong>Doctor:</strong> Dr. <?= htmlspecialchars($row['doctor_name'] ?? '') ?></div>
                        <div><strong>Department:</strong> <?= htmlspecialchars($row['department'] ?? 'N/A') ?></div>
                        <div><strong>Reason:</strong><br><?= nl2br(htmlspecialchars($row['reason'] ?? '')) ?></div>

                        <?php if (!empty($row['cancellation_reason']) && $status === 'CANCELLED'): ?>
                            <div class="mt-2">
                                <strong>Cancellation Reason:</strong><br>
                                <span class="text-danger"><?= nl2br(htmlspecialchars($row['cancellation_reason'])) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Cancel Procedure Modal -->
<?php if (in_array($status, ['CONFIRMED', 'IN PROGRESS'])): ?>
<div class="modal fade" id="cancelProcedure<?= $row['id'] ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="cancel-procedure.php">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Procedure</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <label for="cancel_reason_<?= $row['id'] ?>">Reason for cancellation:</label>
                    <textarea name="cancellation_reason" id="cancel_reason_<?= $row['id'] ?>" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="cancel" class="btn btn-danger">Cancel Procedure</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php endif; ?>
