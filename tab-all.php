<?php
include 'dbconnection.php';

$sql = "SELECT a.*, p.name AS patient_name, d.name AS doctor_name, d.department
        FROM appointments a
        LEFT JOIN patients p ON a.patient_id = p.id
        LEFT JOIN doctors d ON a.doctor_id = d.id
        ORDER BY a.id DESC";

$result = $conn->query($sql);

$modals = ''; // Store modals separately
?>

<div class="table-responsive mt-3">
    <table class="table table-striped table-bordered" id="appointmentTable" >
        <thead>
            <tr>
                <th>OP ID</th>
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
                <?php 
                ob_start(); 
                include 'row-appointment.php'; 
                echo ob_get_clean();

                ob_start();
                ?>
                <!-- View Appointment Modal -->
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
                                        <div><strong>OP ID:</strong> OP<?= str_pad($row['appointment_id'], 2, '0', STR_PAD_LEFT); ?></div>
                                        <div><strong>Patient:</strong> <?= htmlspecialchars($row['patient_name'] ?? ''); ?></div>
                                        <div><strong>Doctor:</strong> Dr. <?= htmlspecialchars($row['doctor_name'] ?? ''); ?></div>
                                        <div><strong>Date:</strong> <?= $row['appointment_date']; ?></div>
                                        <div><strong>Time:</strong> <?= date("h:i A", strtotime($row['appointment_time'])); ?></div>
                                        <div><strong>Duration:</strong> <?= intval($row['duration']) ?> minutes</div>
                                        <div><strong>Type:</strong> <?= htmlspecialchars($row['type']); ?></div>
                                    </div>

                                    <!-- RIGHT COLUMN -->
                                    <div class="col-md-6">
                                        <div><strong>Status:</strong> 
                                            <span class="badge badge-pill 
                                                <?= match(strtoupper($row['status'])) {
                                                    'CONFIRMED' => 'badge-success',
                                                    'IN PROGRESS' => 'badge-dark',
                                                    'COMPLETED' => 'badge-success',
                                                    'CANCELLED' => 'badge-danger',
                                                    'WAITLIST' => 'badge-info',
                                                    'TENTATIVE' => 'badge-warning',
                                                    default => 'badge-secondary'
                                                } ?>">
                                                <?= strtoupper($row['status']) ?>
                                            </span>
                                        </div>
                                        <div><strong>Fee:</strong> Rs.<?= number_format($row['fee'], 2); ?></div>
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
                <?php
                $modals .= ob_get_clean();
                ?>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8" class="text-center">No appointments found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Output all modals after the table -->
<?= $modals ?>
