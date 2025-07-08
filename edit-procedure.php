<?php
session_start();
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $procedure_id = $_GET['id'] ?? null;
    if (!$procedure_id) {
        $_SESSION['toast_error'] = "Invalid procedure ID.";
        header("Location: procedure.php");
        exit();
    }

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
    $stmt->bind_param("iisssissssi", $patient_id, $doctor_id, $procedure_date, $procedure_time, $type, $duration, $reason, $status, $fee, $payment_mode, $procedure_id);

    if ($stmt->execute()) {
        $_SESSION['toast_success'] = "Procedure updated successfully!";
    } else {
        $_SESSION['toast_error'] = "Failed to update procedure.";
    }

    $stmt->close();
    header("Location: procedure.php");
    exit();
}

// Only continue here if not POST
include 'header.php';
include 'init.php';

$procedure_id = $_GET['id'] ?? null;
if (!$procedure_id) {
    echo "<script>alert('Invalid procedure ID'); window.location.href='procedure.php';</script>";
    exit();
}

$departments = [
    "General Practitioner", "Cardiologist", "Dermatologist", "Gastroenterologist",
    "Neurologist", "Orthopedic", "Pediatrician", "Psychiatrist", "Physician"
];

$doctors = $conn->query("SELECT id, name, department FROM doctors WHERE status = 'Active'");
$doctorList = [];
while ($doc = $doctors->fetch_assoc()) {
    $doctorList[$doc['department']][] = $doc;
}

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
?>


<!-- Toast Notification Support -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>

<?php if (isset($_SESSION['toast_success'])): ?>
<script>
    $(function () {
        $.toast({
            heading: 'Success',
            text: '<?= $_SESSION['toast_success'] ?>',
            showHideTransition: 'slide',
            icon: 'success',
            position: 'top-right'
        });
    });
</script>
<?php unset($_SESSION['toast_success']); endif; ?>

<?php if (isset($_SESSION['toast_error'])): ?>
<script>
    $(function () {
        $.toast({
            heading: 'Error',
            text: '<?= $_SESSION['toast_error'] ?>',
            showHideTransition: 'fade',
            icon: 'error',
            position: 'top-right'
        });
    });
</script>
<?php unset($_SESSION['toast_error']); endif; ?>


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

    <div class="row justify-content-center">
        <div class="col-md-10">
            <form method="POST" id="procedureForm">
                <input type="hidden" name="patient_id" id="selectedPatientId" value="<?= $procedure['patient_id'] ?>">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="font-weight-bold mb-0">Edit Procedure Form</h5>
                            <div class="text-muted font-weight-bold">
                                OP ID: #<?= 'OP' . str_pad($procedure['id'], 4, '0', STR_PAD_LEFT) ?>
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Patient Name</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($procedure['patient_name']) ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Mobile No</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($procedure['patient_phone']) ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Patient ID</label>
                                <input type="text" class="form-control" value="PAT<?= str_pad($procedure['patient_id'], 4, '0', STR_PAD_LEFT) ?>" readonly>
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
                                    <option value="">Select Duration</option>
                                    <?php foreach ([30, 45, 60, 90, 120] as $mins): ?>
                                        <option value="<?= $mins ?>" <?= $procedure['duration'] == $mins ? 'selected' : '' ?>>
                                            <?= ($mins >= 60 ? floor($mins / 60) . 'h ' : '') . ($mins % 60 ? $mins % 60 . 'm' : '') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Procedure Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="">Select Type</option>
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
                                <label>Fee (Rs.)</label>
                                <input type="number" step="0.01" name="fee" class="form-control" value="<?= $procedure['fee'] ?>" required>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Payment Mode</label>
                                <select name="payment_mode" class="form-control" required>
                                    <option value="">Select Payment</option>
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
                                    <option value="">Select Status</option>
                                    <?php foreach (["Confirmed", "In Progress", "Completed"] as $stat): ?>
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
</script>

<?php include 'footer.php'; ?>
