<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
include 'dbconnection.php';
include 'init.php';

$preSelectedPatient = null;
$preSelectedDoctorId = null;
$preSelectedDoctorDept = null;
$appointment_id = null;
$display_op_id = null; // This will be shown on frontend

// If not from appointment, generate OP ID before form loads
if (!isset($_GET['appointment_id']) || empty($_GET['appointment_id'])) {
    $getLastOpId = $conn->query("SELECT appointment_id FROM procedures WHERE appointment_id LIKE 'OP%' ORDER BY id DESC LIMIT 1");

    if ($getLastOpId && $getLastOpId->num_rows > 0) {
        $lastOp = $getLastOpId->fetch_assoc();
        $lastNum = (int) str_replace('OP', '', $lastOp['appointment_id']);
        $newNum = $lastNum + 1;
    } else {
        $newNum = 1;
    }

    $appointment_id = 'OP' . str_pad($newNum, 6, '0', STR_PAD_LEFT);
    $display_op_id = $appointment_id;
}

if (isset($_GET['appointment_id']) && !empty($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];
    $display_op_id = $appointment_id;

    $apptQuery = $conn->query("SELECT a.*, p.name as patient_name, p.phone as patient_phone, d.name as doctor_name, d.department 
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        JOIN doctors d ON a.doctor_id = d.id
        WHERE a.appointment_id = '$appointment_id'");

    if ($apptQuery && $apptQuery->num_rows > 0) {
        $apptData = $apptQuery->fetch_assoc();

        $preSelectedPatient = [
            'id' => $apptData['patient_id'],
            'name' => $apptData['patient_name'],
            'phone' => $apptData['patient_phone']
        ];

        $preSelectedDoctorId = $apptData['doctor_id'];
        $preSelectedDoctorDept = $apptData['department'];
        $preSelectedDate = $apptData['appointment_date'];
        $preSelectedTime = $apptData['appointment_time'];
    }
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $procedure_date = $_POST['appointment_date'];
    $procedure_time = $_POST['appointment_time'];
    $type = $_POST['type'];
    $duration = $_POST['duration'];
    $reason = $_POST['reason'];
    $status = $_POST['status'];
    $fee = $_POST['fee'];
    $payment_mode = $_POST['payment_mode'];
    $appointment_id = $_POST['appointment_id'];
    $patient_name = $_POST['patient_name'];

    // If no OP ID came from frontend (fallback)
    if (empty($appointment_id)) {
        $result = $conn->query("SELECT MAX(id) AS max_id FROM procedures");
        $row = $result->fetch_assoc();
        $newId = $row['max_id'] + 1;
        $appointment_id = 'OP' . str_pad($newId, 6, '0', STR_PAD_LEFT);
    }

    $stmt = $conn->prepare("INSERT INTO procedures 
        (patient_id, patient_name, doctor_id, procedure_date, procedure_time, type, duration, reason, status, fee, payment_mode, created_at, appointment_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");

    $stmt->bind_param("isisssisssss", 
        $patient_id, 
        $patient_name, 
        $doctor_id, 
        $procedure_date, 
        $procedure_time, 
        $type, 
        $duration, 
        $reason, 
        $status, 
        $fee, 
        $payment_mode, 
        $appointment_id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Procedure added successfully!";
        header("Location: procedure.php");
        exit();
    } else {
        $message = '<div class="alert alert-danger">❌ Error: ' . $conn->error . '</div>';
    }
    $stmt->close();
}

include 'header.php';

$departments = [
    "General Practitioner", "Cardiologist", "Dermatologist", "Gastroenterologist",
    "Neurologist", "Orthopedic", "Pediatrician", "Psychiatrist", "Physician"
];

$doctors = $conn->query("SELECT id, name, department FROM doctors WHERE status = 'Active'");
$doctorList = [];
while ($doc = $doctors->fetch_assoc()) {
    $doctorList[$doc['department']][] = $doc;
}
?>

<!-- Breadcrumb -->
<div class="container mt-4">
    <div class="row page-title clearfix">
        <div class="col-12 d-flex justify-content-end">
            <div class="col-md-6 text-right">
                <ol class="breadcrumb bg-transparent p-1 mb-1 justify-content-end">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Add Procedure</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Form -->
<div class="container mt-3">
    <?= $message ?>
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
            <form method="POST" id="procedureForm">
                <input type="hidden" name="patient_id" id="selectedPatientId" value="<?= $preSelectedPatient['id'] ?? '' ?>">
                <input type="hidden" name="patient_name" id="hidden_patient_name" value="<?= $preSelectedPatient['name'] ?? '' ?>">
                <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($display_op_id ?? '') ?>" id="generated_op_id">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="font-weight-bold mb-0">Add Procedure Form</h5>
                            <div class="text-muted font-weight-bold">
                                OP ID: #<?= htmlspecialchars($appointment_id ?? 'Will be generated') ?>

                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Patient Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="p_name" value="<?= $preSelectedPatient['name'] ?? '' ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Patient Mobile No<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="p_phone" value="<?= $preSelectedPatient['phone'] ?? '' ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Patient ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="p_id" value="<?= isset($preSelectedPatient['id']) ? 'MCP' . str_pad($preSelectedPatient['id'], 4, '0', STR_PAD_LEFT) : '' ?>" readonly>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Department<span class="text-danger">*</span></label>
                                <select id="departmentSelect" class="form-control" required>
                                    <option>Select Department</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= $dept ?>" <?= ($preSelectedDoctorDept == $dept) ? 'selected' : '' ?>><?= $dept ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Doctor<span class="text-danger">*</span></label>
                                <select name="doctor_id" id="doctorSelect" class="form-control" required>
                                    <option value="">Select Doctor</option>
                                    <?php
                                    if (isset($preSelectedDoctorDept) && isset($doctorList[$preSelectedDoctorDept])) {
                                        foreach ($doctorList[$preSelectedDoctorDept] as $doc) {
                                            $selected = ($preSelectedDoctorId == $doc['id']) ? 'selected' : '';
                                            echo "<option value='{$doc['id']}' $selected>{$doc['name']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Date<span class="text-danger">*</span></label>
                                <input type="date" name="appointment_date" class="form-control" required value="<?= $preSelectedDate ?? '' ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Time<span class="text-danger">*</span></label>
                                <input type="time" name="appointment_time" class="form-control" required value="<?= $preSelectedTime ?? '' ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Duration<span class="text-danger">*</span></label>
                                <select name="duration" class="form-control" required>
                                    <option value="">Select Duration</option>
                                    <option value="30">30m</option>
                                    <option value="45">45m</option>
                                    <option value="60">1hr</option>
                                    <option value="90">1h 30m</option>
                                    <option value="120">2h</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Procedure Type<span class="text-danger">*</span></label>
                                <select name="type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="Check-Up">Check-Up</option>
                                    <option value="Consultation">Consultation</option>
                                    <option value="Follow-Up">Follow-Up</option>
                                    <option value="Procedure">Procedure</option>
                                    <option value="Emergency">Emergency</option>
                                    <option value="Vaccination">Vaccination</option>
                                    <option value="Physical Therapy">Physical Therapy</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Fee (₹)<span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="fee" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Mode of Payment<span class="text-danger">*</span></label>
                                <select name="payment_mode" class="form-control" required>
                                    <option value="">Select Payment </option>
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
                                <label>Procedure Status<span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="">Select Status</option>
                                    <option value="Confirmed">Confirmed</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Reason for Procedure<span class="text-danger"> * </span></label>
                                <textarea name="reason" class="form-control" rows="3" placeholder="Describe reason..."></textarea>
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

<script>
const doctorMap = <?= json_encode($doctorList) ?>;

document.getElementById('departmentSelect').addEventListener('change', function () {
    const dept = this.value;
    const docSelect = document.getElementById('doctorSelect');
    docSelect.innerHTML = '<option value="">Select Doctor</option>';

    if (doctorMap[dept]) {
        doctorMap[dept].forEach(doc => {
            const opt = document.createElement('option');
            opt.value = doc.id;
            opt.textContent = doc.name;
            docSelect.appendChild(opt);
        });
    }
});

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
                        document.getElementById('hidden_patient_name').value = patient.name;
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
</script>

<?php include 'footer.php'; ?>
