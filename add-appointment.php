<?php
include 'header.php';
include 'dbconnection.php';
include 'init.php';

$message = '';

// Handle appointment submission
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

    $stmt = $conn->prepare("INSERT INTO appointments 
        (patient_id, doctor_id, appointment_date, appointment_time, type, duration, reason, status, fee) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssissi", $patient_id, $doctor_id, $appointment_date, $appointment_time, $type, $duration, $reason, $status, $fee);

    if ($stmt->execute()) {
        header("Location: appointments.php?added=1");
        exit();
    } else {
        $message = '<div class="alert alert-danger">❌ Error: ' . $conn->error . '</div>';
    }
    $stmt->close();
}

// Fetch doctors
$doctors = $conn->query("SELECT id, name FROM doctors WHERE status = 'Active'");
?>

<div class="container mt-4">
    <?= $message ?>
    <div class="row">
        <!-- Left: Appointment Form -->
        <div class="col-md-8">
            <h4 class="mb-3"> Schedule Appointment</h4>
            <form method="POST" id="appointmentForm">
                <input type="hidden" name="patient_id" id="selectedPatientId">

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Date</label>
                        <input type="date" name="appointment_date" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Time</label>
                        <input type="time" name="appointment_time" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Appointment Type</label>
                        <select name="type" class="form-control" required>
                            <option value="Check-up">Check-up</option>
                            <option value="Consultation">Consultation</option>
                            <option value="Follow-up">Follow-up</option>
                            <option value="Emergency">Emergency</option>
                            <option value="Review">Review</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Duration (minutes)</label>
                        <input type="number" name="duration" class="form-control" required>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Reason for Visit</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Describe reason..."></textarea>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="Scheduled">Scheduled</option>
                            <option value="Tentative">Tentative</option>
                            <option value="Waitlist">Waitlist</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Consultation Fee (₹)</label>
                        <input type="number" step="0.01" name="fee" class="form-control" required>
                    </div>

                    <!-- Patient Info Autofill -->
                    <div class="col-md-12 mt-3" id="patientDetails" style="display:none;">
                        <h6 class="text-primary"> Patient Details:</h6>
                        <p><strong>Name:</strong> <span id="p_name"></span></p>
                        <p><strong>Phone:</strong> <span id="p_phone"></span></p>
                        <p><strong>Gender:</strong> <span id="p_gender"></span></p>
                        <p><strong>DOB:</strong> <span id="p_dob"></span></p>
                    </div>

                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-success"> Add Appointment</button>
                        <a href="appointments.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right: Search + Doctor -->
        <div class="col-md-4">
            <h5> Search Patient</h5>
            <input type="text" class="form-control mb-2" id="searchPatient" placeholder="Search by name or phone">
            <ul class="list-group" id="patientResults"></ul>
            <a href="add-patient.php" class="btn btn-outline-primary btn-sm mt-3 w-100"> Register New Patient</a>

            <hr class="my-4">

            <h5> Select Doctor</h5>
            <select name="doctor_id" class="form-control" form="appointmentForm" required>
                <option value="">Select Doctor</option>
                <?php while ($d = $doctors->fetch_assoc()): ?>
                    <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>
</div>

<!--  AJAX Search Script -->
<script>
document.getElementById('searchPatient').addEventListener('input', function () {
    const query = this.value.trim();
    const resultList = document.getElementById('patientResults');
    resultList.innerHTML = '';

    if (query.length < 2) return;

    fetch('search-patient.php?q=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                resultList.innerHTML = '<li class="list-group-item text-muted">No matching patients found.</li>';
            } else {
                data.forEach(patient => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action';
                    li.textContent = `${patient.name} (${patient.phone})`;
                    li.addEventListener('click', () => {
                        document.getElementById('selectedPatientId').value = patient.id;
                        document.getElementById('p_name').textContent = patient.name;
                        document.getElementById('p_phone').textContent = patient.phone;
                        document.getElementById('p_gender').textContent = patient.gender || 'N/A';
                        document.getElementById('p_dob').textContent = patient.dob || 'N/A';
                        document.getElementById('patientDetails').style.display = 'block';
                        resultList.innerHTML = '';
                    });
                    resultList.appendChild(li);
                });
            }
        });
});
</script>

<?php include 'footer.php'; ?>
