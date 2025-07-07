<?php
$apt_id = $row['appointment_id']; // Already contains OPXXX
$status = strtoupper($row['status']);
$badgeClass = match ($status) {
    'SCHEDULED'   => 'badge-secondary',
    'TENTATIVE'   => 'badge-warning',
    'WAITLIST'    => 'badge-info',
    'CONFIRMED'   => 'badge-primary',
    'IN PROGRESS' => 'badge-warning text-dark',
    'COMPLETED'   => 'badge-success',
    'CANCELLED'   => 'badge-danger',
    default       => 'badge-secondary'
};
?>

<tr>
    <td><?= $apt_id ?></td>
    <td><?= htmlspecialchars($row['patient_name'] ?? '') ?></td>
    <td><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
    <td><?= $row['appointment_date'] . ' ' . date("h:i A", strtotime($row['appointment_time'])) ?></td>
    <td><span class="badge <?= $badgeClass ?>"><?= $status ?></span></td>
    <td><?= htmlspecialchars($row['type'] ?? '') ?></td>
    <td><?= intval($row['duration']) ?> min</td>
    <td>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" id="dropdownMenu<?= $row['id'] ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ⋮
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu<?= $row['id'] ?>">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#viewAppointment<?= $row['id'] ?>">View</a>

                <?php if (in_array($status, ['CONFIRMED', 'IN PROGRESS'])): ?>
                    <a class="dropdown-item" href="edit-appointment.php?id=<?= $row['id'] ?>">Edit Appointment</a>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="loadSlip(<?= $row['id'] ?>)">Appointment Slip</a>
                    <a class="dropdown-item" href="add-procedure.php?appointment_id=<?= $apt_id ?>">Add Procedure</a>
                    <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#cancelModal<?= $row['id'] ?>">Cancel Appointment</a>

                <?php elseif ($status === 'COMPLETED'): ?>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="loadSlip('<?= $row['id'] ?>', '<?= $row['patient_name'] ?>')">Appointment Slip</a>
                    <a class="dropdown-item" href="add-procedure.php?appointment_id=<?= $apt_id ?>">Add Procedure</a>
                    <!--<a class="dropdown-item" href="medical-records.php?appointment_id=<?= $apt_id ?>">Medical Records</a>-->
                <?php endif; ?>
            </div>
        </div>
    </td>
</tr>

<!-- Appointment View Modal -->
<div class="modal fade" id="viewAppointment<?= $row['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title font-weight-bold mb-0 text-white">Appointment Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body px-4 pt-3 pb-4">
                <div class="row">
                    <!-- LEFT COLUMN -->
                    <div class="col-md-6">
                        <div><strong>OP ID:</strong> <?= $row['appointment_id']; ?></div>
                        <div><strong>Patient:</strong> <?= htmlspecialchars($row['patient_name'] ?? ''); ?></div>
                        <div><strong>Doctor:</strong> Dr. <?= htmlspecialchars($row['doctor_name'] ?? ''); ?></div>
                        <div><strong>Department:</strong> <?= htmlspecialchars($row['department'] ?? ''); ?></div>
                        <div><strong>Date:</strong> <?= $row['appointment_date']; ?></div>
                        <div><strong>Time:</strong> <?= date("h:i A", strtotime($row['appointment_time'])); ?></div>
                        <div><strong>Duration:</strong> <?= intval($row['duration']) ?> minutes</div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col-md-6">
                        <div><strong>Status:</strong> 
                            <span class="badge badge-pill 
                                <?= match(strtoupper($row['status'])) {
                                    'CONFIRMED'   => 'badge-primary',
                                    'IN PROGRESS' => 'badge-warning text-dark',
                                    'COMPLETED'   => 'badge-success',
                                    'CANCELLED'   => 'badge-danger',
                                    'WAITLIST'    => 'badge-info',
                                    'TENTATIVE'   => 'badge-warning',
                                    default       => 'badge-secondary'
                                } ?>">
                                <?= strtoupper($row['status']) ?>
                            </span>
                        </div>
                        <div><strong>Fee:</strong> ₹<?= number_format($row['fee'], 2); ?></div>
                        <div><strong>Type:</strong> <?= htmlspecialchars($row['type']); ?></div>
                        <div><strong>Reason:</strong><br><?= nl2br(htmlspecialchars($row['reason'])); ?></div>

                        <?php if (!empty($row['cancel_reason']) && strtoupper($row['status']) == 'CANCELLED'): ?>
                            <div class="mt-2"><strong>Cancel Reason:</strong><br>
                                <span class="text-danger"><?= nl2br(htmlspecialchars($row['cancel_reason'])); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if (in_array($status, ['CONFIRMED', 'IN PROGRESS'])): ?>
<!-- Cancel Appointment Modal -->
<div class="modal fade" id="cancelModal<?= $row['id'] ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="cancel-appointment.php">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Appointment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="reason<?= $row['id'] ?>">Reason for cancellation:</label>
                    <textarea name="cancel_reason" id="reason<?= $row['id'] ?>" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="cancel" class="btn btn-danger">Cancel Appointment</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
