<?php
include 'header.php';
include 'dbconnection.php';
include 'init.php';

$appointment_id = $_GET['id'] ?? null;
$message = '';

if (!$appointment_id) {
    echo "<script>alert('Invalid procedure ID'); window.location.href='procedure.php';</script>";
    exit();
}

$patients = $conn->query("SELECT id, name FROM patients WHERE status='Active'");
$doctors = $conn->query("SELECT id, name FROM doctors WHERE status='Active'");

$stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$appointment = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$appointment) {
    echo "<script>alert('Procedure not found'); window.location.href='procedure.php';</script>";
    exit();
}

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
        $message = "<div class='alert alert-success'>Procedure updated successfully!</div>";
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

<div class="container mt-4">
    <div class="row page-title clearfix">
        <div class="page-title-left">
            <h6 class="page-title-heading mr-0 mr-r-5">Edit Procedure</h6>
            <p class="page-title-description mr-0 d-none d-md-inline-block">Fill the form to edit a procedure.</p>
        </div>
        <div class="page-title-right d-none d-sm-inline-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit Procedure</li>
            </ol>
        </div>
    </div>
</div>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Select Patient</h5>
                    <p class="text-muted">Search and select a patient for this procedure</p>
                    <input type="text" class="form-control mb-2" id="searchPatient" placeholder="Search">
                    <ul class="list-group" id="patientResults"></ul>
                    <a href="add-patient.php" class="btn btn-secondary btn-block mt-2">Register New Patient</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <?= $message ?>
            <form method="POST" id="appointmentForm">
                <input type="hidden" name="patient_id" id="selectedPatientId" value="<?= $appointment['patient_id'] ?>">
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Patient Name<span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="p_name" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Patient Mobile No<span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="p_phone" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Patient ID<span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="p_id" readonly>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Department<span style="color: red;">*</span></label>
                                <select id="departmentSelect" class="form-control">
                                    <option>Select Department</option>
                                    <option value="General Practitioner">General Practitioner</option>
                                    <option value="Cardiologist">Cardiologist</option>
                                    <option value="Dermatologist">Dermatologist</option>
                                    <option value="Gastroenterologist">Gastroenterologist</option>
                                    <option value="Neurologist">Neurologist</option>
                                    <option value="Orthopedic">Orthopedic</option>
                                    <option value="Pediatrician">Pediatrician</option>
                                    <option value="Psychiatrist">Psychiatrist</option>
                                    <option value="Physician">Physician</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Doctor<span style="color: red;">*</span></label>
                                <select name="doctor_id" id="doctorSelect" class="form-control" required>
                                    <option value="">Select Doctor</option>
                                    <?php while ($d = $doctors->fetch_assoc()): ?>
                                        <option value="<?= $d['id'] ?>" <?= $appointment['doctor_id'] == $d['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($d['name']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Date<span style="color: red;">*</span></label>
                                <input type="date" name="appointment_date" class="form-control" value="<?= $appointment['appointment_date'] ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Time<span style="color: red;">*</span></label>
                                <input type="time" name="appointment_time" class="form-control" value="<?= $appointment['appointment_time'] ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Duration<span style="color: red;">*</span></label>
                                <select name="duration" class="form-control" required>
                                    <option value="30" <?= $appointment['duration'] == 30 ? 'selected' : '' ?>>30m</option>
                                    <option value="45" <?= $appointment['duration'] == 45 ? 'selected' : '' ?>>45m</option>
                                    <option value="60" <?= $appointment['duration'] == 60 ? 'selected' : '' ?>>1hr</option>
                                    <option value="90" <?= $appointment['duration'] == 90 ? 'selected' : '' ?>>1h 30m</option>
                                    <option value="120" <?= $appointment['duration'] == 120 ? 'selected' : '' ?>>2h</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Procedure Type<span style="color: red;">*</span></label>
                                <select name="type" class="form-control" required>
                                    <option value="Check-Up" <?= $appointment['type'] == 'Check-Up' ? 'selected' : '' ?>>Check-Up</option>
                                    <option value="Consultation" <?= $appointment['type'] == 'Consultation' ? 'selected' : '' ?>>Consultation</option>
                                    <option value="Follow-Up" <?= $appointment['type'] == 'Follow-Up' ? 'selected' : '' ?>>Follow-Up</option>
                                    <option value="Procedure" <?= $appointment['type'] == 'Procedure' ? 'selected' : '' ?>>Procedure</option>
                                    <option value="Emergency" <?= $appointment['type'] == 'Emergency' ? 'selected' : '' ?>>Emergency</option>
                                    <option value="Vaccination" <?= $appointment['type'] == 'Vaccination' ? 'selected' : '' ?>>Vaccination</option>
                                    <option value="Physical Therapy" <?= $appointment['type'] == 'Physical Therapy' ? 'selected' : '' ?>>Physical Therapy</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Fee (₹)<span style="color: red;">*</span></label>
                                <input type="number" step="0.01" name="fee" class="form-control" value="<?= $appointment['fee'] ?>" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Mode of Payment<span style="color: red;">*</span></label>
                                <select name="payment_mode" class="form-control">
                                    <option value="UPI">UPI</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Net Banking">Net Banking</option>
                                    <option value="Card">Card</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Fee Status</label>
                                <input type="text" class="form-control" value="Paid" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Procedure Status<span style="color: red;">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="Confirmed" <?= $appointment['status'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                    <option value="In Progress" <?= $appointment['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="Completed" <?= $appointment['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="Cancelled" <?= $appointment['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Reason for Procedure<span style="color: red;">*</span></label>
                                <textarea name="reason" class="form-control" rows="3" placeholder="Describe reason..." required><?= htmlspecialchars($appointment['reason']) ?></textarea>
                            </div>

                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">Save Procedure</button>
                                <a href="procedure.php" class="btn btn-secondary">Back to list</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Search + Autofill Script -->
<script>
document.getElementById('searchPatient').addEventListener('input', function () {
    const query = this.value.trim();
    const results = document.getElementById('patientResults');
    results.innerHTML = '';
    if (query.length < 2) return;

    fetch('search-patient.php?q=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                results.innerHTML = '<li class="list-group-item text-muted">No patients found</li>';
            } else {
                data.forEach(patient => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action';
                    li.textContent = `${patient.name} (${patient.phone})`;
                    li.onclick = () => {
                        document.getElementById('selectedPatientId').value = patient.id;
                        document.getElementById('p_name').value = patient.name;
                        document.getElementById('p_phone').value = patient.phone;
                        document.getElementById('p_id').value = 'MCP' + patient.id.toString().padStart(4, '0');
                        results.innerHTML = '';
                    };
                    results.appendChild(li);
                });
            }
        });
});

// Prefill patient data on page load
document.addEventListener("DOMContentLoaded", function () {
    const patientId = <?= json_encode($appointment['patient_id']) ?>;
    if (patientId) {
        fetch(`get-patient-details.php?id=${patientId}`)
            .then(res => res.json())
            .then(patient => {
                document.getElementById("p_name").value = patient.name || "";
                document.getElementById("p_phone").value = patient.phone || "";
                document.getElementById("p_id").value = 'MCP' + patient.id.toString().padStart(4, '0');
            });
    }
});
</script>

<?php include 'footer.php'; ?>
