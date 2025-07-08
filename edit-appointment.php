<?php
session_start();
include 'dbconnection.php';
include 'init.php';

$appointment_id = $_GET['id'] ?? null;
$message = '';

if (!$appointment_id || !is_numeric($appointment_id)) {
    echo "<script>alert('Invalid appointment ID'); window.location.href='appointments.php';</script>";
    exit();
}

// Fetch patient and doctor lists
$patients = $conn->query("SELECT id, name FROM patients WHERE status='Active'");
$doctors = $conn->query("SELECT id, name, department FROM doctors WHERE status='Active'");

// Fetch appointment data
$stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$appointment = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$appointment) {
    echo "<script>alert('Appointment not found'); window.location.href='appointments.php';</script>";
    exit();
}

// Get department of the selected doctor
$doctor_department = '';
if (!empty($appointment['doctor_id'])) {
    $dep_stmt = $conn->prepare("SELECT department FROM doctors WHERE id = ?");
    $dep_stmt->bind_param("i", $appointment['doctor_id']);
    $dep_stmt->execute();
    $dep_result = $dep_stmt->get_result();
    if ($dep_row = $dep_result->fetch_assoc()) {
        $doctor_department = $dep_row['department'];
    }
    $dep_stmt->close();
}

// Handle form submission
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
        $_SESSION['success'] = "Appointment updated successfully!";
        header("Location: appointments.php");
        exit();
    } else {
        $_SESSION['error'] = "Update failed: " . $conn->error;
        header("Location: appointments.php");
        exit();
    }

    $update->close();
}

include 'header.php';
?>
<div class="container mt-4">
    <div class="row page-title clearfix">
        <div class="page-title-left">
            <h6 class="page-title-heading mr-0 mr-r-5">Edit Appointment</h6>
            <p class="page-title-description mr-0 d-none d-md-inline-block">Fill the form to edit an appointment.</p>
        </div>
        <div class="page-title-right d-none d-sm-inline-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit Appointment</li>
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
                    <p class="text-muted">Search and select a patient for this appointment</p>
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
                                <select id="departmentSelect" class="form-control" required>
                                    <option value="" disabled>Select</option>
                                    <?php
                                    $departments = [
                                        'General Practitioner', 'Cardiologist', 'Dermatologist',
                                        'Gastroenterologist', 'Neurologist', 'Orthopedic',
                                        'Pediatrician', 'Psychiatrist', 'Physician'
                                    ];
                                    foreach ($departments as $dept) {
                                        $selected = ($dept === $doctor_department) ? 'selected' : '';
                                        echo "<option value=\"$dept\" $selected>$dept</option>";
                                    }
                                    ?>
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
                                    <option value="" disabled>Select</option>
                                    <?php
                                    foreach ([30, 45, 60, 90, 120] as $d) {
                                        $label = $d < 60 ? "{$d}m" : ($d == 60 ? "1hr" : ($d == 90 ? "1h 30m" : "2h"));
                                        $selected = $appointment['duration'] == $d ? 'selected' : '';
                                        echo "<option value=\"$d\" $selected>$label</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Appointment Type<span style="color: red;">*</span></label>
                                <select name="type" class="form-control" required>
                                    <option value="" disabled>Select</option>
                                    <?php
                                    $types = ['Check-Up', 'Consultation', 'Follow-Up', 'Procedure', 'Emergency', 'Vaccination', 'Physical Therapy'];
                                    foreach ($types as $t) {
                                        $selected = $appointment['type'] === $t ? 'selected' : '';
                                        echo "<option value=\"$t\" $selected>$t</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Fee (Rs.)<span style="color: red;">*</span></label>
                                <input type="number" step="0.01" name="fee" class="form-control" value="<?= $appointment['fee'] ?>" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Mode of Payment<span style="color: red;">*</span></label>
                                <select name="payment_mode" class="form-control">
                                    <option value="" disabled>Select</option>
                                    <option value="UPI">UPI</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Net Banking">Net Banking</option>
                                    <option value="Card">Card</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 input-has-value">
                                <label>Fee Status</label>
                                <input type="text" class="form-control" value="Paid" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Appointment Status<span style="color: red;">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="" disabled>Select</option>
                                    <?php
                                    foreach (['Confirmed', 'In Progress', 'Completed'] as $s) {
                                        $selected = $appointment['status'] === $s ? 'selected' : '';
                                        echo "<option value=\"$s\" $selected>$s</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Reason for Appointment<span style="color: red;">*</span></label>
                                <textarea name="reason" class="form-control" rows="3" required><?= htmlspecialchars($appointment['reason']) ?></textarea>
                            </div>

                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">Save Appointment</button>
                                <a href="appointments.php" class="btn btn-secondary">Back to list</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
                        document.getElementById('p_id').value = 'PAT' + patient.id.toString().padStart(4, '0');
                        results.innerHTML = '';
                    };
                    results.appendChild(li);
                });
            }
        });
});

document.addEventListener("DOMContentLoaded", function () {
    const patientId = <?= json_encode($appointment['patient_id']) ?>;
    if (patientId) {
        fetch(`get-patient-details.php?id=${patientId}`)
            .then(res => res.json())
            .then(patient => {
                document.getElementById("p_name").value = patient.name || "";
                document.getElementById("p_phone").value = patient.phone || "";
                document.getElementById("p_id").value = 'PAT' + patient.id.toString().padStart(4, '0');
            });
    }
});
</script>

<?php include 'footer.php'; ?>
