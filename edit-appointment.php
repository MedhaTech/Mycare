<?php
include 'header.php';
include 'dbconnection.php';

$appointment_id = $_GET['id'] ?? null;
$message = '';

// Redirect if no ID
if (!$appointment_id) {
    echo "<script>alert('Invalid appointment ID'); window.location.href='appointments.php';</script>";
    exit();
}

// Fetch patients and doctors
$patients = $conn->query("SELECT id, name FROM patients WHERE status='Active'");
$doctors = $conn->query("SELECT id, name FROM doctors WHERE status='Active'");

// Get current appointment details
$stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$appointment = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$appointment) {
    echo "<script>alert('Appointment not found'); window.location.href='appointments.php';</script>";
    exit();
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];
    $type = $_POST['type'];
    $duration = $_POST['duration'];
    $reason = $_POST['reason'];
    $status = $_POST['status'];
    $fee = $_POST['fee'];

    $update = $conn->prepare("UPDATE appointments SET patient_id=?, doctor_id=?, appointment_date=?, appointment_time=?, type=?, duration=?, reason=?, status=?, fee=? WHERE id=?");
    $update->bind_param("iisssisssi", $patient_id, $doctor_id, $date, $time, $type, $duration, $reason, $status, $fee, $appointment_id);

    if ($update->execute()) {
        $message = "<div class='alert alert-success'>Appointment updated successfully!</div>";
        // Refresh appointment details
        $stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $appointment = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } else {
        $message = "<div class='alert alert-danger'>Update failed: " . $conn->error . "</div>";
    }
    $update->close();
}
?>

<div class="container mt-5">
    <h2>Edit Appointment</h2>
    <?= $message ?>

    <form method="POST">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Patient</label>
                <select name="patient_id" class="form-control" required>
                    <?php while ($p = $patients->fetch_assoc()): ?>
                        <option value="<?= $p['id'] ?>" <?= $p['id'] == $appointment['patient_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Doctor</label>
                <select name="doctor_id" class="form-control" required>
                    <?php while ($d = $doctors->fetch_assoc()): ?>
                        <option value="<?= $d['id'] ?>" <?= $d['id'] == $appointment['doctor_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Date</label>
                <input type="date" name="appointment_date" class="form-control" value="<?= $appointment['appointment_date'] ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label>Time</label>
                <input type="time" name="appointment_time" class="form-control" value="<?= $appointment['appointment_time'] ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label>Type</label>
                <select name="type" class="form-control" required>
                    <option value="General" <?= $appointment['type'] == 'General' ? 'selected' : '' ?>>General</option>
                    <option value="Follow-up" <?= $appointment['type'] == 'Follow-up' ? 'selected' : '' ?>>Follow-up</option>
                    <option value="Emergency" <?= $appointment['type'] == 'Emergency' ? 'selected' : '' ?>>Emergency</option>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label>Duration (minutes)</label>
                <input type="number" name="duration" class="form-control" value="<?= $appointment['duration'] ?>" required>
            </div>
            <div class="form-group col-md-6">
                <label>Reason for Visit</label>
                <input type="text" name="reason" class="form-control" value="<?= htmlspecialchars($appointment['reason']) ?>" required>
            </div>
            <div class="form-group col-md-3">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="Scheduled" <?= $appointment['status'] == 'Scheduled' ? 'selected' : '' ?>>Scheduled</option>
                    <option value="Tentative" <?= $appointment['status'] == 'Tentative' ? 'selected' : '' ?>>Tentative</option>
                    <option value="Add to Waitlist" <?= $appointment['status'] == 'Add to Waitlist' ? 'selected' : '' ?>>Add to Waitlist</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Consultation Fee</label>
                <input type="text" name="fee" class="form-control" value="<?= htmlspecialchars($appointment['fee']) ?>" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Appointment</button>
        <a href="appointments.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<?php include 'footer.php'; ?>
