<?php include 'header.php'; ?>
<?php include 'dbconnection.php'; 
include 'init.php';?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Appointments</h2>
        <div style="width: 350px;">
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control" placeholder="🔍 Live search patient or doctor..." style="border: 2px solid #007bff;">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped text-dark">
            <thead class="thead-dark">
                <tr>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="appointmentBody">
                <?php
                $sql = "SELECT a.*, p.name AS patient_name, d.name AS doctor_name 
                        FROM appointments a
                        LEFT JOIN patients p ON a.patient_id = p.id
                        LEFT JOIN doctors d ON a.doctor_id = d.id
                        ORDER BY a.appointment_date DESC, a.appointment_time DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        $status = strtoupper($row['status']);
                        $badgeClass = match ($status) {
                            'SCHEDULED' => 'badge-primary',
                            'TENTATIVE' => 'badge-warning',
                            'WAITLIST' => 'badge-info',
                            'CONFIRMED' => 'badge-success',
                            'IN PROGRESS' => 'badge-dark',
                            'COMPLETED' => 'badge-success',
                            'CANCELLED' => 'badge-danger',
                            default => 'badge-secondary'
                        };
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['patient_name']) ?></td>
                        <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                        <td><?= htmlspecialchars($row['appointment_date']) . ' ' . date("h:i A", strtotime($row['appointment_time'])) ?></td>
                        <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span></td>
                        <td><?= htmlspecialchars($row['type']) ?></td>
                        <td><?= intval($row['duration']) ?> min</td>
                        <td>
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="feather feather-more-vertical"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="view-appointment.php?id=<?= $row['id'] ?>">View</a>
                                    <a class="dropdown-item" href="edit-appointment.php?id=<?= $row['id'] ?>">Edit</a>
                                    <a class="dropdown-item text-danger" data-toggle="modal" data-target="#deleteModal<?= $row['id'] ?>">Delete</a>
                                    <!-- Button to trigger modal -->
                                    <a class="dropdown-item" data-toggle="modal" data-target="#statusModal<?= $row['id'] ?>">Change Status</a>

                                    <a class="dropdown-item" href="download-slip.php?id=<?= $row['id'] ?>">Download Slip</a>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal<?= $row['id'] ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <form action="delete-appointment.php" method="POST">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Deletion</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete appointment for <strong><?= htmlspecialchars($row['patient_name']) ?></strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                           <!-- Status Change Modal -->
<div class="modal fade" id="statusModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="statusLabel<?= $row['id'] ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="change-status-appointment.php">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Appointment Status</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <select name="status" class="form-control" required>
                        <option value="">-- Select Status --</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Tentative">Tentative</option>
                        <option value="Waitlist">Waitlist</option>
                        <option value="Confirmed">Confirmed</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Status</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>


                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            No appointments found.
                            <a href="add-appointment.php" class="btn btn-sm btn-success ml-2">+ Create Appointment</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Live Search Script -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById('searchInput');
    const appointmentBody = document.getElementById('appointmentBody');

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.trim();

        fetch(`search-appointments.php?search=${encodeURIComponent(query)}`)
            .then(response => response.text())
            .then(data => {
                appointmentBody.innerHTML = data.trim() !== '' ? data : `
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            No appointments found.
                            <a href="add-appointment.php" class="btn btn-sm btn-success ml-2">+ Create Appointment</a>
                        </td>
                    </tr>
                `;
            })
            .catch(error => console.error('Live search error:', error));
    });
});
</script>

<?php include 'footer.php'; ?>
