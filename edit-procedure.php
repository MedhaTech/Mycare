<?php
include 'header.php';
include 'dbconnection.php';
include 'init.php';

$procedure_id = $_GET['id'] ?? null;
$message = '';

if (!$procedure_id) {
    echo "<script>alert('Invalid procedure ID'); window.location.href='procedure.php';</script>";
    exit();
}

// Department list
$departments = [
    "General Practitioner", "Cardiologist", "Dermatologist", "Gastroenterologist",
    "Neurologist", "Orthopedic", "Pediatrician", "Psychiatrist", "Physician"
];

// Doctors list by department
$doctors = $conn->query("SELECT id, name, department FROM doctors WHERE status = 'Active'");
$doctorList = [];
while ($doc = $doctors->fetch_assoc()) {
    $doctorList[$doc['department']][] = $doc;
}

// Function to fetch updated procedure details
function getProcedure($conn, $id) {
    $stmt = $conn->prepare("SELECT p.*, pa.id as patient_id, pa.name as patient_name, pa.phone as patient_phone, d.department 
                            FROM procedures p
                            JOIN patients pa ON p.patient_id = pa.id
                            JOIN doctors d ON p.doctor_id = d.id
                            WHERE p.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();
    $stmt->close();
    return $data;
}

$procedure = getProcedure($conn, $procedure_id);

if (!$procedure) {
    echo "<script>alert('Procedure not found'); window.location.href='procedure.php';</script>";
    exit();
}

// Handle POST form update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $procedure_date = $_POST['procedure_date'];
    $procedure_time = $_POST['procedure_time'];
    $type = $_POST['type'];
    $duration = $_POST['duration'];
    $reason = $_POST['reason'];
    $status = $_POST['status'];
    $fee = $_POST['fee'];
    $payment_mode = $_POST['payment_mode'];

    $stmt = $conn->prepare("UPDATE procedures SET patient_id=?, doctor_id=?, procedure_date=?, procedure_time=?, type=?, duration=?, reason=?, status=?, fee=?, payment_mode=? WHERE id=?");
    $stmt->bind_param("iisssisssii", $patient_id, $doctor_id, $procedure_date, $procedure_time, $type, $duration, $reason, $status, $fee, $payment_mode, $procedure_id);
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Procedure updated successfully.</div>";
        $procedure = getProcedure($conn, $procedure_id);
    } else {
        $message = "<div class='alert alert-danger'>Update failed: " . $conn->error . "</div>";
    }
    $stmt->close();
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

<?= $message ?>

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
            <form method="POST" id="procedureForm">
                <input type="hidden" name="patient_id" id="selectedPatientId" value="<?= $procedure['patient_id'] ?>">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="font-weight-bold mb-0">Edit Procedure Form</h5>
                            <div class="text-muted font-weight-bold">
                                OP ID: #<?= 'OP' . str_pad($procedure['patient_id'], 4, '0', STR_PAD_LEFT) ?>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Patient Name</label>
                                <input type="text" class="form-control" id="p_name" value="<?= htmlspecialchars($procedure['patient_name']) ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Mobile No</label>
                                <input type="text" class="form-control" id="p_phone" value="<?= htmlspecialchars($procedure['patient_phone']) ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Patient ID</label>
                                <input type="text" class="form-control" id="p_id" value="MCP<?= str_pad($procedure['patient_id'], 4, '0', STR_PAD_LEFT) ?>" readonly>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Department</label>
                                <select id="departmentSelect" class="form-control" required>
                                    <option>Select Department</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= $dept ?>" <?= ($procedure['department'] == $dept) ? 'selected' : '' ?>><?= $dept ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Doctor</label>
                                <select name="doctor_id" id="doctorSelect" class="form-control" required>
                                    <option value="">Select Doctor</option>
                                    <?php
                                    if (isset($procedure['department']) && isset($doctorList[$procedure['department']])) {
                                        foreach ($doctorList[$procedure['department']] as $doc) {
                                            $selected = ($procedure['doctor_id'] == $doc['id']) ? 'selected' : '';
                                            echo "<option value='{$doc['id']}' $selected>" . htmlspecialchars($doc['name']) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Date</label>
                                <input type="date" name="procedure_date" class="form-control" value="<?= $procedure['procedure_date'] ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Time</label>
                                <input type="time" name="procedure_time" class="form-control" value="<?= $procedure['procedure_time'] ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Duration</label>
                                <select name="duration" class="form-control" required>
                                    <?php foreach ([30, 45, 60, 90, 120] as $mins): ?>
                                        <option value="<?= $mins ?>" <?= $procedure['duration'] == $mins ? 'selected' : '' ?>><?= ($mins >= 60 ? floor($mins / 60) . 'h ' : '') . ($mins % 60 ? $mins % 60 . 'm' : '') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Procedure Type</label>
                                <select name="type" class="form-control" required>
                                    <?php
                                    $types = ["Check-Up", "Consultation", "Follow-Up", "Procedure", "Emergency", "Vaccination", "Physical Therapy"];
                                    foreach ($types as $type) {
                                        $sel = $procedure['type'] == $type ? 'selected' : '';
                                        echo "<option value='$type' $sel>$type</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Fee (₹)</label>
                                <input type="number" step="0.01" name="fee" class="form-control" value="<?= $procedure['fee'] ?>" required>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Payment Mode</label>
                                <select name="payment_mode" class="form-control" required>
                                    <?php foreach (["UPI", "Cash", "Net Banking", "Card"] as $mode): ?>
                                        <option value="<?= $mode ?>" <?= $procedure['payment_mode'] == $mode ? 'selected' : '' ?>><?= $mode ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Fee Status</label>
                                <input type="text" class="form-control" value="Paid" readonly>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Procedure Status</label>
                                <select name="status" class="form-control" required>
                                    <?php foreach (["Confirmed", "In Progress", "Completed", "Cancelled"] as $stat): ?>
                                        <option value="<?= $stat ?>" <?= $procedure['status'] == $stat ? 'selected' : '' ?>><?= $stat ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Reason</label>
                                <textarea name="reason" class="form-control" rows="3" required><?= htmlspecialchars($procedure['reason']) ?></textarea>
                            </div>

                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="procedure.php" class="btn btn-secondary">Cancel</a>
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

// Patient search
document.getElementById('searchPatient').addEventListener('input', function () {
    const query = this.value.trim();
    const results = document.getElementById('patientResults');
    results.innerHTML = '';
    if (query.length < 2) return;
    fetch('search-patient.php?q=' + encodeURIComponent(query))
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                results.innerHTML = '<li class="list-group-item text-muted">No patients found</li>';
            } else {
                data.forEach(p => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action';
                    li.textContent = `${p.name} (${p.phone})`;
                    li.onclick = () => {
                        document.getElementById('selectedPatientId').value = p.id;
                        document.getElementById('p_name').value = p.name;
                        document.getElementById('p_phone').value = p.phone;
                        document.getElementById('p_id').value = 'MCP' + p.id.toString().padStart(4, '0');
                        results.innerHTML = '';
                    };
                    results.appendChild(li);
                });
            }
        });
});
</script>

<?php include 'footer.php'; ?>
