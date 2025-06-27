<?php
include 'dbconnection.php';
include 'init.php';

$message = '';

// Handle form submission before any output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $type = $_POST['type'];
    $duration = $_POST['duration'];
    $reason = $_POST['reason'];
    $status = $_POST['status'];
    $fee = $_POST['fee'];

    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, type, duration, reason, status, fee)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssissi", $patient_id, $doctor_id, $appointment_date, $appointment_time, $type, $duration, $reason, $status, $fee);

    if ($stmt->execute()) {
        // Redirect BEFORE output
        header("Location: appointments.php?added=1");
        exit();
    } else {
        $message = '<div class="alert alert-danger">❌ Error adding appointment: ' . $conn->error . '</div>';
    }

    $stmt->close();
}

// Now include HTML-related files
include 'header.php';

// Fetch dropdown data
$patients = $conn->query("SELECT id, name FROM patients WHERE status='Active'");
$doctors = $conn->query("SELECT id, name FROM doctors WHERE status='Active'");
?>

<div class="container mt-5">
    <h3 class="mb-4">➕ Add New Appointment</h3>
    <?= $message ?>

    <form method="POST" class="bg-white p-4 rounded shadow-sm">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Patient</label>
                <select name="patient_id" class="form-control" required>
                    <option value="">Select Patient</option>
                    <?php while ($p = $patients->fetch_assoc()): ?>
                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label>Doctor</label>
                <select name="doctor_id" class="form-control" required>
                    <option value="">Select Doctor</option>
                    <?php while ($d = $doctors->fetch_assoc()): ?>
                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Date</label>
                <input type="date" name="appointment_date" class="form-control" required>
            </div>

            <div class="form-group col-md-4">
                <label>Time</label>
                <input type="time" name="appointment_time" class="form-control" required>
            </div>

            <div class="form-group col-md-4">
                <label>Appointment Type</label>
                <input type="text" name="type" class="form-control" placeholder="e.g. Check-up" required>
            </div>

            <div class="form-group col-md-3">
                <label>Duration (in minutes)</label>
                <input type="number" name="duration" class="form-control" required>
            </div>

            <div class="form-group col-md-3">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="SCHEDULED">Scheduled</option>
                    <option value="TENTATIVE">Tentative</option>
                    <option value="WAITLIST">Waitlist</option>
                    <option value="CONFIRMED">Confirmed</option>
                    <option value="IN PROGRESS">In Progress</option>
                    <option value="COMPLETED">Completed</option>
                    <option value="CANCELLED">Cancelled</option>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label>Fee (₹)</label>
                <input type="number" step="0.01" name="fee" class="form-control" required>
            </div>

            <div class="form-group col-md-12">
                <label>Reason for Appointment</label>
                <textarea name="reason" class="form-control" rows="3" placeholder="Describe reason..."></textarea>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-success">Save Appointment</button>
            <a href="appointments.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
