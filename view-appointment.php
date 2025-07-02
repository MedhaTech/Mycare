<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';
include 'dbconnection.php';
include 'init.php';

$appointment_id = $_GET['id'] ?? null;

if (!$appointment_id || !is_numeric($appointment_id)) {
    echo "<script>alert('Invalid appointment ID'); window.location.href='appointments.php';</script>";
    exit();
}

$stmt = $conn->prepare("
    SELECT a.*, p.name AS patient_name, d.name AS doctor_name
    FROM appointments a
    LEFT JOIN patients p ON a.patient_id = p.id
    LEFT JOIN doctors d ON a.doctor_id = d.id
    WHERE a.id = ?
");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();
$stmt->close();

if (!$appointment) {
    echo "<script>alert('Appointment not found'); window.location.href='appointments.php';</script>";
    exit();
}

$apt_id = "APT" . str_pad($appointment['id'], 3, '0', STR_PAD_LEFT);
?>

<div class="container mt-5">
    <h3>Appointment Profile: #<?= $apt_id ?></h3>
    <div class="card mt-3 shadow-sm">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Patient</th>
                    <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                </tr>
                <tr>
                    <th>Doctor</th>
                    <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td><?= $appointment['appointment_date'] ?></td>
                </tr>
                <tr>
                    <th>Time</th>
                    <td><?= date("h:i A", strtotime($appointment['appointment_time'])) ?></td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td><?= htmlspecialchars($appointment['type']) ?></td>
                </tr>
                <tr>
                    <th>Duration</th>
                    <td><?= $appointment['duration'] ?> minutes</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <?php
                        $status = $appointment['status'];
                        $badgeClass = match ($status) {
                            'Scheduled' => 'badge-primary',
                            'Tentative' => 'badge-warning',
                            'Add to Waitlist', 'WAITLIST' => 'badge-info',
                            'Confirmed' => 'badge-success',
                            'In Progress' => 'badge-dark',
                            'Completed' => 'badge-success',
                            'Cancelled' => 'badge-danger',
                            default => 'badge-secondary'
                        };
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                    </td>
                </tr>
                <tr>
                    <th>Consultation Fee</th>
                    <td>₹<?= number_format($appointment['fee'], 2) ?></td>
                </tr>
                <tr>
                    <th>Reason for Visit</th>
                    <td><?= htmlspecialchars($appointment['reason']) ?></td>
                </tr>
            </table>
            <a href="appointments.php" class="btn btn-secondary">← Back to Appointments</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
